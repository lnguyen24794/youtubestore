<?php
/**
 * Class for Google Sheet Sync Logic
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_Google_Sheet_Sync
{

    public function __construct()
    {
        // Prepare hooks for ajax sync
        add_action('wp_ajax_youtubestore_sync', array($this, 'handle_sync_ajax'));
    }

    public function handle_sync_ajax()
    {
        // Security check
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $sheet_id = get_option('youtubestore_sheet_id');
        $tab_name = get_option('youtubestore_tab_name'); // Assuming this maps to 'gid' for CSV export or similar

        if (empty($sheet_id)) {
            wp_send_json_error('Missing Spreadsheet ID');
        }

        $result = $this->run_sync($sheet_id, $tab_name);

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        } else {
            wp_send_json_success(sprintf('Sync complete! Processed %d items.', $result));
        }
    }

    public function run_sync($sheet_id, $tab_name)
    {
        // 1. Fetch Data (CSV)
        $csv_url = "https://docs.google.com/spreadsheets/d/{$sheet_id}/export?format=csv";
        if (!empty($tab_name)) {
            $csv_url .= "&gid={$tab_name}";
        }

        $response = wp_remote_get($csv_url);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        if (empty($body)) {
            return new WP_Error('empty_data', 'Got empty response from Google Sheet');
        }

        // Parse CSV
        $rows = array_map('str_getcsv', explode("\n", $body));
        $header = array_shift($rows); // First row is header

        if (!$header) {
            return new WP_Error('invalid_csv', 'CSV format error or empty');
        }

        // Normalize header to lowercase/slugs for easier mapping
        $header = array_map(function ($h) {
            return sanitize_title($h);
        }, $header);

        // Count processed
        $count_processed = 0;
        $count_created = 0;
        $count_updated = 0;

        // Loop rows
        foreach ($rows as $row) {
            if (empty($row) || count($row) !== count($header)) {
                continue;
            }

            // Combine header with row data
            $item = array_combine($header, $row);

            // Required ID
            if (empty($item['id'])) {
                continue;
            }

            // Sync Item
            $this->sync_item($item);
            $count_processed++;
        }

        // Clear Cache if needed (stub)
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        return $count_processed;
    }

    private function sync_item($item)
    {
        $channel_id = sanitize_text_field($item['id']);

        // Check if exists
        $args = array(
            'post_type' => 'youtube_channel',
            'meta_key' => 'channel_id',
            'meta_value' => $channel_id,
            'posts_per_page' => 1,
            'fields' => 'ids'
        );
        $query = new WP_Query($args);
        $post_id = 0;

        if ($query->have_posts()) {
            $post_id = $query->posts[0];
        }

        $post_data = array(
            'post_title' => sanitize_text_field($item['channel_name'] ?? 'No Name'),
            'post_status' => 'publish',
            'post_type' => 'youtube_channel',
        );

        if ($post_id) {
            // Update
            $post_data['ID'] = $post_id;
            wp_update_post($post_data);
        } else {
            // Insert
            $post_id = wp_insert_post($post_data);
            update_field('channel_id', $channel_id, $post_id);
        }

        // Update Meta Fields (using ACF update_field if available, else update_post_meta)
        // Fields map: Price, Subscribers, Monetization, Video URL, Status
        $this->update_meta($post_id, 'price', $item['price'] ?? 0);
        $this->update_meta($post_id, 'subscribers', $item['subscribers'] ?? '');
        $this->update_meta($post_id, 'monetization', strtolower($item['monetization'] ?? 'no'));
        $this->update_meta($post_id, 'video_url', $item['video_url'] ?? '');
        $this->update_meta($post_id, 'status', strtolower($item['status'] ?? 'available'));

        // Handle Category
        if (!empty($item['category'])) {
            wp_set_object_terms($post_id, $item['category'], 'category', true);
        }

        // Handle Image
        if (!empty($item['image_url'])) { // Assuming column is image_url based on assumption, checking mapping
            // Requirement says "Video URL" -> Parse to get thumbnail? Or is there a thumbnail column?
            // PRD 3.2 says: "Video URL" -> Dev extract Video ID -> get thumbnail.
            // OR map directly if there is an image column. Mapping table implies video_url is the source.
            $this->handle_thumbnail_from_video($post_id, $item['video_url'] ?? '');
        }
    }

    private function update_meta($post_id, $key, $value)
    {
        if (function_exists('update_field')) {
            update_field($key, $value, $post_id);
        } else {
            update_post_meta($post_id, $key, $value);
        }
    }

    private function handle_thumbnail_from_video($post_id, $video_url)
    {
        if (empty($video_url))
            return;

        // Extract ID
        $video_id = $this->get_youtube_id($video_url);
        if (!$video_id)
            return;

        $image_url_maxres = "https://img.youtube.com/vi/$video_id/maxresdefault.jpg";
        $image_url_hq = "https://img.youtube.com/vi/$video_id/hqdefault.jpg";

        // Check current thumb
        $current_thumb_id = get_post_thumbnail_id($post_id);
        if ($current_thumb_id) {
            // Optimization: only update if changed? simpler to skip for now or force update?
            // For now, let's assume if it has one, we skip to save time, unless forced (not required yet)
            return;
        }

        // Download and attach
        $this->attach_image_url($post_id, $image_url_maxres, $video_id);
    }

    private function get_youtube_id($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
        return $my_array_of_vars['v'] ?? null;
        // Can add more robust regex if needed
    }

    private function attach_image_url($post_id, $url, $name)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $desc = "Thumbnail for " . $name;
        $file_array = array();
        $tmp = download_url($url);

        if (is_wp_error($tmp)) {
            return; // Failed to download
        }

        $file_array['name'] = $name . '.jpg';
        $file_array['tmp_name'] = $tmp;

        $id = media_handle_sideload($file_array, $post_id, $desc);

        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
        } else {
            set_post_thumbnail($post_id, $id);
        }
    }
}

new YoutubeStore_Google_Sheet_Sync();
