<?php
/**
 * YouTube Channel Import from CSV/Excel
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_Channel_Import
{
    /**
     * Parse CSV file
     */
    public function parse_csv($file_path)
    {
        $channels = array();
        $handle = fopen($file_path, 'r');
        
        if ($handle === false) {
            return new WP_Error('file_error', 'Cannot open file');
        }

        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return new WP_Error('file_error', 'Cannot read CSV headers');
        }

        // Normalize headers (remove BOM, trim spaces)
        $headers = array_map(function($header) {
            return trim(str_replace("\xEF\xBB\xBF", '', $header));
        }, $headers);

        // Map CSV columns to our fields
        $column_map = array(
            'Lượng subscribers' => 'subscribers',
            'subscribers' => 'subscribers',
            'Link Kênh' => 'video_url',
            'video_url' => 'video_url',
            'link' => 'video_url',
            'Chủ Đề' => 'topic',
            'topic' => 'topic',
            'chủ đề' => 'topic',
            'Giá bán (VND)' => 'price',
            'price' => 'price',
            'giá' => 'price',
            'Tình trạng kênh' => 'monetization',
            'monetization' => 'monetization',
            'tình trạng' => 'monetization',
            'Mua hàng' => 'status',
            'status' => 'status',
        );

        $row_num = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $row_num++;
            $channel = array();

            foreach ($headers as $index => $header) {
                $field = isset($column_map[$header]) ? $column_map[$header] : null;
                if ($field && isset($row[$index])) {
                    $channel[$field] = trim($row[$index]);
                }
            }

            // Skip empty rows
            if (empty($channel['video_url'])) {
                continue;
            }

            $channels[] = $channel;
        }

        fclose($handle);
        return $channels;
    }

    /**
     * Parse Excel file (XLSX)
     */
    public function parse_excel($file_path)
    {
        // Check if PhpSpreadsheet is available
        if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            // Try to use simple CSV conversion or return error
            return new WP_Error('excel_error', 'Excel parsing requires PhpSpreadsheet library. Please convert to CSV first.');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return new WP_Error('excel_error', 'Excel file is empty');
            }

            // First row is headers
            $headers = array_map(function($header) {
                return trim(str_replace("\xEF\xBB\xBF", '', $header));
            }, array_shift($rows));

            $column_map = array(
                'Lượng subscribers' => 'subscribers',
                'subscribers' => 'subscribers',
                'Link Kênh' => 'video_url',
                'video_url' => 'video_url',
                'link' => 'video_url',
                'Chủ Đề' => 'topic',
                'topic' => 'topic',
                'chủ đề' => 'topic',
                'Giá bán (VND)' => 'price',
                'price' => 'price',
                'giá' => 'price',
                'Tình trạng kênh' => 'monetization',
                'monetization' => 'monetization',
                'tình trạng' => 'monetization',
                'Mua hàng' => 'status',
                'status' => 'status',
            );

            $channels = array();
            foreach ($rows as $row) {
                $channel = array();

                foreach ($headers as $index => $header) {
                    $field = isset($column_map[$header]) ? $column_map[$header] : null;
                    if ($field && isset($row[$index])) {
                        $channel[$field] = trim($row[$index]);
                    }
                }

                if (empty($channel['video_url'])) {
                    continue;
                }

                $channels[] = $channel;
            }

            return $channels;
        } catch (Exception $e) {
            return new WP_Error('excel_error', 'Failed to parse Excel file: ' . $e->getMessage());
        }
    }

    /**
     * Extract channel name from YouTube URL
     */
    private function extract_channel_name($url)
    {
        if (empty($url)) {
            return '';
        }

        // Handle different YouTube URL formats
        if (preg_match('/youtube\.com\/channel\/([^\/\?]+)/', $url, $matches)) {
            return 'Channel ' . substr($matches[1], 0, 8);
        } elseif (preg_match('/youtube\.com\/@([^\/\?]+)/', $url, $matches)) {
            return '@' . $matches[1];
        } elseif (preg_match('/youtube\.com\/c\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/youtube\.com\/user\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        return 'YouTube Channel';
    }

    /**
     * Normalize subscriber count
     */
    private function normalize_subscribers($subscribers)
    {
        if (empty($subscribers)) {
            return '0';
        }

        // Remove dots and commas
        $subscribers = str_replace(array('.', ','), '', $subscribers);
        
        // Extract number
        preg_match('/(\d+)/', $subscribers, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return '0';
    }

    /**
     * Normalize price
     */
    private function normalize_price($price)
    {
        if (empty($price)) {
            return 0;
        }

        // Remove dots, commas, and currency symbols
        $price = preg_replace('/[^\d]/', '', $price);
        
        return intval($price);
    }

    /**
     * Normalize monetization status
     */
    private function normalize_monetization($status)
    {
        if (empty($status)) {
            return 'no';
        }

        $status_lower = strtolower($status);
        if (strpos($status_lower, 'đã bật') !== false || 
            strpos($status_lower, 'bật kiếm tiền') !== false ||
            strpos($status_lower, 'yes') !== false ||
            strpos($status_lower, 'enabled') !== false) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * Normalize channel status
     */
    private function normalize_status($status)
    {
        if (empty($status)) {
            return 'available';
        }

        $status_lower = strtolower($status);
        if (strpos($status_lower, 'sold') !== false || 
            strpos($status_lower, 'đã bán') !== false ||
            strpos($status_lower, 'hết') !== false) {
            return 'sold';
        }

        return 'available';
    }

    /**
     * Import channels from parsed data
     */
    public function import_channels($channels, $options = array())
    {
        if (is_wp_error($channels)) {
            return $channels;
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = array();

        $default_options = array(
            'skip_duplicates' => true,
            'update_existing' => true,
        );
        $options = wp_parse_args($options, $default_options);

        foreach ($channels as $index => $channel_data) {
            try {
                // Extract and normalize data
                $video_url = sanitize_text_field($channel_data['video_url'] ?? '');
                $subscribers = $this->normalize_subscribers($channel_data['subscribers'] ?? '0');
                $price = $this->normalize_price($channel_data['price'] ?? '0');
                $topic = sanitize_text_field($channel_data['topic'] ?? '');
                $monetization = $this->normalize_monetization($channel_data['monetization'] ?? '');
                $status = $this->normalize_status($channel_data['status'] ?? 'available');

                if (empty($video_url)) {
                    $skipped++;
                    continue;
                }

                // Generate title
                $title = $this->extract_channel_name($video_url);
                if (!empty($topic)) {
                    $title = $topic . ' - ' . $title;
                }

                // Check if channel already exists by URL
                $existing_post = $this->find_channel_by_url($video_url);

                if ($existing_post) {
                    if (!$options['update_existing']) {
                        $skipped++;
                        continue;
                    }
                    $post_id = $existing_post->ID;
                    $updated++;
                } else {
                    // Create new post
                    $post_data = array(
                        'post_title' => $title,
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_type' => 'youtube_channel',
                    );

                    $post_id = wp_insert_post($post_data);
                    if (is_wp_error($post_id)) {
                        $errors[] = 'Row ' . ($index + 2) . ': ' . $post_id->get_error_message();
                        continue;
                    }
                    $imported++;
                }

                // Update ACF fields - check field names from acf-fields.php
                // ACF field names: channel_id, price, subscribers, monetization_status, status, video_url
                if (function_exists('update_field')) {
                    // Use ACF field names
                    update_field('video_url', $video_url, $post_id);
                    update_field('subscribers', $subscribers, $post_id);
                    update_field('price', $price, $post_id);
                    update_field('monetization_status', $monetization, $post_id);
                    update_field('status', $status, $post_id);
                    
                    // Also update post meta as fallback
                    update_post_meta($post_id, 'video_url', $video_url);
                    update_post_meta($post_id, 'subscribers', $subscribers);
                    update_post_meta($post_id, 'price', $price);
                    update_post_meta($post_id, 'monetization_status', $monetization);
                    update_post_meta($post_id, 'status', $status);
                } else {
                    // Fallback to post meta only
                    update_post_meta($post_id, 'video_url', $video_url);
                    update_post_meta($post_id, 'subscribers', $subscribers);
                    update_post_meta($post_id, 'price', $price);
                    update_post_meta($post_id, 'monetization_status', $monetization);
                    update_post_meta($post_id, 'status', $status);
                }

                // Set category if topic is provided
                if (!empty($topic)) {
                    $term = get_term_by('name', $topic, 'category');
                    if (!$term) {
                        $term_data = wp_insert_term($topic, 'category');
                        if (!is_wp_error($term_data)) {
                            $term_id = $term_data['term_id'];
                        } else {
                            $term_id = null;
                        }
                    } else {
                        $term_id = $term->term_id;
                    }

                    if ($term_id) {
                        wp_set_post_terms($post_id, array($term_id), 'category');
                    }
                }

            } catch (Exception $e) {
                $errors[] = 'Row ' . ($index + 2) . ': ' . $e->getMessage();
                $skipped++;
            }
        }

        return array(
            'success' => true,
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
            'total' => count($channels)
        );
    }

    /**
     * Find existing channel by URL
     */
    private function find_channel_by_url($url)
    {
        // Try to find by ACF field first
        if (function_exists('get_field')) {
            $args = array(
                'post_type' => 'youtube_channel',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'video_url',
                        'value' => $url,
                        'compare' => '='
                    ),
                    array(
                        'key' => '_video_url', // ACF stores field name with underscore prefix
                        'value' => $url,
                        'compare' => '='
                    )
                )
            );
        } else {
            $args = array(
                'post_type' => 'youtube_channel',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => 'video_url',
                        'value' => $url,
                        'compare' => '='
                    )
                )
            );
        }

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            return $query->posts[0];
        }

        return null;
    }
}
