<?php
/**
 * CSV Files Import Class
 * Import data from CSV files (posts, pages, SEO)
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_CSV_Import
{
    private $csv_dir;

    public function __construct()
    {
        echo "DEBUG: Loaded class from: " . __FILE__ . "\n";
        if (!defined('YOUTUBESTORE_DIR')) {
            define('YOUTUBESTORE_DIR', get_template_directory());
        }
        $this->csv_dir = YOUTUBESTORE_DIR . '/laravel-database';
    }

    /**
     * Parse CSV file
     */
    private function parse_csv_file($file_path, $override_headers = null)
    {
        echo "DEBUG: Checking file: $file_path\n";
        if (!file_exists($file_path)) {
            echo "DEBUG: File not found: $file_path\n";
            return false;
        }

        // Create a temp file with normalized line endings
        $content = file_get_contents($file_path);
        echo "DEBUG: Parsing " . basename($file_path) . " (Length: " . strlen($content) . ")\n";
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\r", "\n", $content);

        $temp_file = tempnam(sys_get_temp_dir(), 'csv_import_');
        file_put_contents($temp_file, $content);

        echo "DEBUG: Temp file created: $temp_file (Length: " . filesize($temp_file) . ")\n";

        $rows = array();
        $headers = array();

        if (($handle = fopen($temp_file, "r")) !== FALSE) {
            // Read header row (always read to skip it)
            $file_headers = fgetcsv($handle);
            if ($file_headers === false) {
                echo "DEBUG: Failed to read headers from $temp_file\n";
                fclose($handle);
                unlink($temp_file);
                return array();
            }
            echo "DEBUG: Read headers: " . count($file_headers) . " columns\n";

            // Clean headers (remove quotes and spaces)
            if ($override_headers) {
                $headers = $override_headers;
            } else {
                // Clean headers (remove quotes and spaces)
                $headers = array_map(function ($h) {
                    return trim($h, '"\' ');
                }, $file_headers);
            }

            // Read data rows
            $first = true;
            while (($data = fgetcsv($handle)) !== false) {
                if ($first) {
                    echo "DEBUG: Headers count: " . count($headers) . ", Data count: " . count($data) . "\n";
                    $first = false;
                }

                if (count($data) === count($headers)) {
                    $row = array_combine($headers, $data);
                    // Clean values
                    foreach ($row as $key => $value) {
                        $row[$key] = trim($value, '"\' ');
                        if ($value === 'NULL' || $value === 'null' || $value === '') {
                            $row[$key] = null;
                        }
                    }
                    $rows[] = $row;
                } else {
                    echo "DEBUG: Header mismatch! Expected " . count($headers) . ", Got " . count($data) . "\n";
                    echo "DEBUG: Headers: " . implode(',', $headers) . "\n";
                    echo "DEBUG: Data: " . implode(',', $data) . "\n";
                }
            }

            fclose($handle);
            unlink($temp_file);
        }

        return array('headers' => $headers, 'rows' => $rows);
    }

    /**
     * Get page IDs from SEO CSV
     */
    private function get_page_ids_from_seo()
    {
        $csv_file = $this->csv_dir . '/seos.csv';
        if (!file_exists($csv_file)) {
            return array();
        }

        $parsed = $this->parse_csv_file($csv_file);
        if (empty($parsed['rows'])) {
            return array();
        }

        $page_ids = array();
        foreach ($parsed['rows'] as $data) {
            // Check for both escaped and unescaped versions
            // CSV may have: "App\Models\Page" (single backslash) or "App\\Models\\Page" (double backslash)
            $seo_type = isset($data['seoable_type']) ? $data['seoable_type'] : '';
            $seoable_id = isset($data['seoable_id']) ? $data['seoable_id'] : '';

            // Normalize the type - remove quotes and handle backslashes
            $seo_type = trim($seo_type, '"\' ');

            $is_page = (
                $seo_type === 'App\\Models\\Page' ||
                $seo_type === 'App\Models\Page' ||
                $seo_type === 'App\\\\Models\\\\Page' ||
                (strpos($seo_type, 'Page') !== false && strpos($seo_type, 'App') !== false)
            );

            if ($is_page && !empty($seoable_id)) {
                $page_ids[] = strval(trim($seoable_id, '"\' ')); // Convert to string for consistent comparison
            }
        }

        return $page_ids;
    }

    /**
     * Import pages from separate pages.csv file
     */
    public function import_pages_from_csv()
    {
        $csv_file = $this->csv_dir . '/pages.csv';
        if (!file_exists($csv_file)) {
            return array('success' => true, 'count' => 0, 'message' => 'pages.csv not found - skipping pages import');
        }

        $parsed = $this->parse_csv_file($csv_file);
        if (empty($parsed['rows'])) {
            return array('success' => true, 'count' => 0, 'message' => 'No data found in pages.csv');
        }

        $pages_map = array();
        $imported_pages = 0;
        $total_rows = count($parsed['rows']);

        foreach ($parsed['rows'] as $data) {
            if (empty($data['name']) || empty($data['slug'])) {
                continue;
            }

            // Only import Vietnamese content
            if (isset($data['language']) && $data['language'] !== 'vi') {
                continue;
            }

            // Check status
            $is_published = false;
            if (isset($data['status'])) {
                $status = $data['status'];
                $is_published = ($status === 'published' || $status === '1' || $status === 1 || $status === '1');
            } else {
                $is_published = true;
            }

            if (!$is_published) {
                continue;
            }

            // Check if page already exists
            $existing = get_page_by_path($data['slug'], OBJECT, 'page');
            $data_id_str = strval($data['id']);

            if ($existing) {
                $pages_map[$data_id_str] = $existing->ID;
                continue;
            }

            // Create WordPress page
            $content = $data['content'] ?? '';

            $post_arr = array(
                'post_title' => $data['name'],
                'post_content' => $content,
                'post_name' => $data['slug'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_date' => $data['created_at'] ?? current_time('mysql'),
            );

            $post_id = wp_insert_post($post_arr);

            if (!is_wp_error($post_id)) {
                // Process content for images
                $processed_content = $this->import_content_images($post_id, $content);
                if ($processed_content !== $content) {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_content' => $processed_content
                    ));
                }

                $pages_map[$data_id_str] = $post_id;
                $imported_pages++;

                // Import featured image
                echo "DEBUG: Image data for existing post: " . var_export($data['image'], true) . "\n";
                if (!empty($data['image'])) {
                    echo "DEBUG: Calling import_featured_image for existing post " . $post_id . "\n";
                    $this->import_featured_image($post_id, $data['image']);
                }
            }
        }

        return array(
            'success' => true,
            'count' => $imported_pages,
            'total' => $total_rows,
            'message' => sprintf('Imported %d pages from pages.csv (Total rows: %d)', $imported_pages, $total_rows),
            'pages_map' => $pages_map
        );
    }

    /**
     * Import posts and pages from CSV
     */
    public function import_posts_from_csv()
    {
        echo "DEBUG: Starting import_posts_from_csv\n";
        $csv_file = $this->csv_dir . '/posts.csv';
        if (!file_exists($csv_file)) {
            return new WP_Error('file_not_found', 'posts.csv not found');
        }

        // Define correct headers based on data structure (9 columns in posts.csv)
        // CSV Headers: "id","language","name","image","slug","content","status","created_at","updated_at"
        // We map 'name' to 'title' for internal consistency
        $headers = [
            'id',
            'language',
            'name',
            'image',
            'slug',
            'content',
            'status',
            'created_at',
            'updated_at'
        ];

        $parsed = $this->parse_csv_file($csv_file, $headers);
        if (empty($parsed['rows'])) {
            return array('success' => false, 'message' => 'No data found in posts.csv');
        }

        // Get page IDs from SEO CSV to determine which posts are actually pages
        $page_ids = $this->get_page_ids_from_seo();
        // Convert to strings for comparison (CSV IDs are strings)
        $page_ids = array_map('strval', $page_ids);

        $posts_map = array();
        $pages_map = array();
        $imported_posts = 0;
        $imported_pages = 0;
        $total_rows = count($parsed['rows']);

        foreach ($parsed['rows'] as $data) {
            echo "DEBUG: Processing row ID: " . $data['id'] . "\n";
            if (empty($data['name']) || empty($data['slug'])) {
                continue;
            }

            // Only import Vietnamese content
            if (isset($data['language']) && $data['language'] !== 'vi') {
                continue;
            }

            // Check status - can be 'published', '1', or 1
            $is_published = false;
            if (isset($data['status'])) {
                $status = $data['status'];
                $is_published = ($status === 'published' || $status === '1' || $status === 1 || $status === '1');
            } else {
                $is_published = true; // Default to published if no status
            }

            if (!$is_published) {
                continue;
            }

            // Determine post type - check if ID is in page_ids from SEO CSV
            $post_type = 'post'; // Default to post
            $post_id_str = strval($data['id']);
            if (in_array($post_id_str, $page_ids)) {
                $post_type = 'page';
            }

            // Check if post/page already exists
            $existing = get_page_by_path($data['slug'], OBJECT, $post_type);
            $data_id_str = strval($data['id']);

            if ($existing) {
                // Update existing post
                $post_id = $existing->ID;

                // Process content for images
                $processed_content = $this->import_content_images($post_id, $content);
                if ($processed_content !== $content) {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_content' => $processed_content
                    ));
                }

                // Import featured image
                if (!empty($data['image'])) {
                    $this->import_featured_image($post_id, $data['image']);
                }

                if ($post_type === 'page') {
                    $pages_map[$data_id_str] = $existing->ID;
                } else {
                    $posts_map[$data_id_str] = $existing->ID;
                }
                continue;
            }

            // Prepare content
            $content = $data['content'] ?? '';

            // Create WordPress post/page
            $post_arr = array(
                'post_title' => $data['name'],
                'post_content' => $content,
                'post_name' => $data['slug'],
                'post_status' => 'publish',
                'post_type' => $post_type,
                'post_date' => $data['created_at'] ?? current_time('mysql'),
            );

            $post_id = wp_insert_post($post_arr);

            if (!is_wp_error($post_id)) {
                // Process content for images
                $processed_content = $this->import_content_images($post_id, $content);
                if ($processed_content !== $content) {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_content' => $processed_content
                    ));
                }

                $data_id_str = strval($data['id']);
                if ($post_type === 'page') {
                    $pages_map[$data_id_str] = $post_id;
                    $imported_pages++;
                } else {
                    $posts_map[$data_id_str] = $post_id;
                    $imported_posts++;
                }

                // Import featured image
                if (!empty($data['image'])) {
                    $this->import_featured_image($post_id, $data['image']);
                }
            }
        }

        return array(
            'success' => true,
            'posts_count' => $imported_posts,
            'pages_count' => $imported_pages,
            'total' => $total_rows,
            'message' => sprintf('Imported %d posts and %d pages from CSV (Total rows: %d)', $imported_posts, $imported_pages, $total_rows),
            'posts_map' => $posts_map,
            'pages_map' => $pages_map
        );
    }

    /**
     * Import SEO from CSV
     */
    public function import_seo_from_csv($posts_map = array(), $pages_map = array())
    {
        $csv_file = $this->csv_dir . '/seos.csv';
        if (!file_exists($csv_file)) {
            return new WP_Error('file_not_found', 'seos.csv not found');
        }

        $parsed = $this->parse_csv_file($csv_file);
        if (empty($parsed['rows'])) {
            return array('success' => false, 'message' => 'No data found in seos.csv');
        }

        $imported = 0;
        $all_map = array_merge($posts_map, $pages_map);

        foreach ($parsed['rows'] as $data) {
            // Only import SEO for posts and pages
            $seo_type = $data['seoable_type'];
            $is_post = ($seo_type === 'App\\Models\\Post' || $seo_type === 'App\Models\Post');
            $is_page = ($seo_type === 'App\\Models\\Page' || $seo_type === 'App\Models\Page');

            if (!$is_post && !$is_page) {
                continue;
            }

            // Find WordPress post/page ID
            $wp_id = null;
            $seoable_id_str = strval($data['seoable_id']);

            if ($is_post && isset($posts_map[$seoable_id_str])) {
                $wp_id = $posts_map[$seoable_id_str];
            } elseif ($is_page && isset($pages_map[$seoable_id_str])) {
                $wp_id = $pages_map[$seoable_id_str];
            }

            if (!$wp_id) {
                continue;
            }

            // Import SEO data
            $this->save_seo_meta($wp_id, $data);
            $imported++;
        }

        return array(
            'success' => true,
            'count' => $imported,
            'message' => sprintf('Imported %d SEO records from CSV', $imported)
        );
    }

    /**
     * Save SEO metadata
     */
    private function save_seo_meta($post_id, $seo_data)
    {
        // Yoast SEO
        if (defined('WPSEO_VERSION')) {
            if (!empty($seo_data['title'])) {
                update_post_meta($post_id, '_yoast_wpseo_title', $seo_data['title']);
            }
            if (!empty($seo_data['description'])) {
                update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_data['description']);
            }
            if (!empty($seo_data['canonical'])) {
                update_post_meta($post_id, '_yoast_wpseo_canonical', $seo_data['canonical']);
            }
            if (!empty($seo_data['robots'])) {
                update_post_meta($post_id, '_yoast_wpseo_meta-robots-noindex', 0);
                update_post_meta($post_id, '_yoast_wpseo_meta-robots-nofollow', 0);
            }
        }

        // Rank Math
        if (defined('RANK_MATH_VERSION')) {
            if (!empty($seo_data['title'])) {
                update_post_meta($post_id, 'rank_math_title', $seo_data['title']);
            }
            if (!empty($seo_data['description'])) {
                update_post_meta($post_id, 'rank_math_description', $seo_data['description']);
            }
            if (!empty($seo_data['canonical'])) {
                update_post_meta($post_id, 'rank_math_canonical_url', $seo_data['canonical']);
            }
        }

        // Generic meta (fallback)
        if (!empty($seo_data['title'])) {
            update_post_meta($post_id, '_seo_title', $seo_data['title']);
        }
        if (!empty($seo_data['description'])) {
            update_post_meta($post_id, '_seo_description', $seo_data['description']);
        }
        if (!empty($seo_data['canonical'])) {
            update_post_meta($post_id, '_seo_canonical', $seo_data['canonical']);
        }
    }

    /**
     * Import featured image from URL or local path
     */
    private function import_featured_image($post_id, $image_path)
    {
        if (empty($image_path)) {
            return false;
        }

        // Clean up path
        $image_path_clean = $image_path;
        if (strpos($image_path, '/images/') === 0) {
            $image_path_clean = str_replace('/images/', '', $image_path);
        } else {
            // Remove leading slash if present
            $image_path_clean = ltrim($image_path, '/');
        }

        // Check if it's a local file relative to assets/images/
        // We assume /images/ in CSV maps to assets/images/ in theme
        // If CSV path is /images/upload/post/..., we want assets/images/upload/post/...
        $local_path = get_template_directory() . '/assets/images/' . $image_path_clean;

        echo "Import Debug: Checking local path: " . $local_path . "\n";

        // Also check if the path already includes the directory
        if (!file_exists($local_path) && file_exists(get_template_directory() . '/' . $image_path)) {
            $local_path = get_template_directory() . '/' . $image_path;
            echo "Import Debug: Found at alternate path: " . $local_path . "\n";
        }

        if (file_exists($local_path)) {
            echo "Import Debug: Local file exists. Attempting upload.\n";
            return $this->handle_local_image_upload($post_id, $local_path);
        } else {
            echo "Import Debug: Local file NOT found: " . $local_path . "\n";
        }

        // Fallback to URL import if not found locally
        // Handle relative URLs
        if (strpos($image_path, 'http') !== 0) {
            // If it's a relative path and not found locally, we can't do much unless we have a base URL
            return false;
        }

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $tmp = download_url($image_path);

        if (is_wp_error($tmp)) {
            return false;
        }

        $file_array = array(
            'name' => basename($image_path),
            'tmp_name' => $tmp
        );

        $id = media_handle_sideload($file_array, $post_id);

        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
            return false;
        }

        set_post_thumbnail($post_id, $id);
        return true;
    }

    /**
     * Handle local image upload
     */
    private function handle_local_image_upload($post_id, $file_path)
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $filename = basename($file_path);
        $upload_file = wp_upload_bits($filename, null, file_get_contents($file_path));

        if (!$upload_file['error']) {
            echo "Import Debug: Upload successful: " . $upload_file['file'] . "\n";
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => $post_id,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
            if (!is_wp_error($attachment_id)) {
                echo "Import Debug: Attachment created ID: " . $attachment_id . "\n";
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                wp_update_attachment_metadata($attachment_id, $attach_data);

                set_post_thumbnail($post_id, $attachment_id);
                return $attachment_id;
            } else {
                echo "Import Debug: Attachment creation failed: " . $attachment_id->get_error_message() . "\n";
            }
        } else {
            echo "Import Debug: Upload failed: " . $upload_file['error'] . "\n";
        }
        return false;
    }



    /**
     * Parse content and import images
     */
    private function import_content_images($post_id, $content)
    {
        if (empty($content)) {
            return $content;
        }

        // Find all images in content
        // Matches <img src="..."> tags
        if (preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches)) {
            $image_urls = $matches[1];

            foreach ($image_urls as $image_url) {
                // Check if it's a local file we can import
                // Assuming path structure in content matches what we have in assets
                // Example in content: /storage/upload/post/123/image.jpg
                // We map this to: assets/images/upload/post/123/image.jpg

                $local_relative_path = $image_url;

                // Clean up path
                $local_relative_path = str_replace('/storage/', '', $local_relative_path);
                $local_relative_path = str_replace('/images/', '', $local_relative_path);
                $local_relative_path = ltrim($local_relative_path, '/');

                $local_full_path = get_template_directory() . '/assets/images/' . $local_relative_path;

                if (!file_exists($local_full_path)) {
                    // Try alternative path structure
                    // Maybe just filename?
                    continue;
                }

                // Upload image to WordPress
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $filename = basename($local_full_path);
                $upload_file = wp_upload_bits($filename, null, file_get_contents($local_full_path));

                if (!$upload_file['error']) {
                    $wp_filetype = wp_check_filetype($filename, null);
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_parent' => $post_id,
                        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);

                    if (!is_wp_error($attachment_id)) {
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                        wp_update_attachment_metadata($attachment_id, $attach_data);

                        // Replace URL in content with new WordPress URL
                        $new_image_url = wp_get_attachment_url($attachment_id);
                        $content = str_replace($image_url, $new_image_url, $content);
                    }
                }
            }
        }

        return $content;
    }

    /**
     * Import menu pages based on Laravel menu structure
     */
    public function import_menu_pages()
    {
        $menu_pages = array(
            array(
                'title' => 'Giới Thiệu Chung',
                'slug' => 'gioi-thieu-chung',
                'template' => 'page-gioi-thieu-chung.php'
            ),
            array(
                'title' => 'Danh Sách Kênh',
                'slug' => 'mua-kenh-youtube',
                'template' => 'page.php' // Or specific template if exists
            ),
            array(
                'title' => 'Chuyển nhượng kênh Youtube',
                'slug' => 'chuyen-nhuong-kenh-youtube',
                'template' => 'page.php'
            ),
            array(
                'title' => 'Tin Tức',
                'slug' => 'tin-tuc',
                'template' => 'page-tin-tuc.php'
            ),
            array(
                'title' => 'Liên Hệ',
                'slug' => 'lien-he',
                'template' => 'page-contact.php'
            ),
        );

        $pages_map = array();
        $imported = 0;

        foreach ($menu_pages as $page_data) {
            // Check if page already exists
            $existing = get_page_by_path($page_data['slug'], OBJECT, 'page');

            if ($existing) {
                $pages_map[$page_data['slug']] = $existing->ID;
                continue;
            }

            // Create page
            $page_arr = array(
                'post_title' => $page_data['title'],
                'post_name' => $page_data['slug'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => '',
            );

            $page_id = wp_insert_post($page_arr);

            if (!is_wp_error($page_id)) {
                // Set page template if specified
                if (!empty($page_data['template'])) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }

                $pages_map[$page_data['slug']] = $page_id;
                $imported++;
            }
        }

        // Create WordPress menu
        $this->create_wordpress_menu($pages_map);

        return array(
            'success' => true,
            'count' => $imported,
            'message' => sprintf('Imported %d menu pages and created WordPress menu', $imported),
            'pages_map' => $pages_map
        );
    }

    /**
     * Create WordPress menu from pages
     */
    private function create_wordpress_menu($pages_map)
    {
        // Check if menu already exists
        $menu_name = 'Main Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
        } else {
            $menu_id = $menu_exists->term_id;
        }

        // Clear existing menu items
        $menu_items = wp_get_nav_menu_items($menu_id);
        if ($menu_items) {
            foreach ($menu_items as $item) {
                wp_delete_post($item->ID, true);
            }
        }

        // Add Home page
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Trang chủ',
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));

        // Add menu pages
        $menu_order = 1;
        foreach ($pages_map as $slug => $page_id) {
            $page = get_post($page_id);
            if ($page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $page->post_title,
                    'menu-item-object-id' => $page_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-position' => $menu_order++
                ));
            }
        }

        // Assign menu to primary location
        $locations = get_theme_mod('nav_menu_locations');
        if (!$locations) {
            $locations = array();
        }
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    /**
     * Main import function
     */
    public function import_from_csv_files()
    {
        $results = array();

        // Import menu pages first
        $menu_pages_result = $this->import_menu_pages();
        $menu_pages_map = isset($menu_pages_result['pages_map']) ? $menu_pages_result['pages_map'] : array();

        // Import pages from pages.csv (if exists)
        $pages_result = $this->import_pages_from_csv();
        $pages_map_from_file = isset($pages_result['pages_map']) ? $pages_result['pages_map'] : array();

        // Import posts and pages from posts.csv
        echo "DEBUG: About to call import_posts_from_csv\n";
        $posts_result = $this->import_posts_from_csv();
        if (is_wp_error($posts_result)) {
            $results['posts'] = array('success' => false, 'message' => $posts_result->get_error_message());
        } else {
            $results['posts'] = $posts_result;
        }

        // Merge pages from all sources
        $posts_map = isset($results['posts']['posts_map']) ? $results['posts']['posts_map'] : array();
        $pages_map_from_posts = isset($results['posts']['pages_map']) ? $results['posts']['pages_map'] : array();
        $pages_map = array_merge($menu_pages_map, $pages_map_from_file, $pages_map_from_posts);

        // Update results with combined pages count
        if (isset($results['posts'])) {
            $results['posts']['pages_count'] = count($pages_map);
            if (count($pages_map_from_file) > 0) {
                $results['posts']['message'] .= ' + ' . count($pages_map_from_file) . ' pages from pages.csv';
            }
            if (count($menu_pages_map) > 0) {
                $results['posts']['message'] .= ' + ' . count($menu_pages_map) . ' menu pages';
            }
        }

        // Add menu pages result
        $results['menu_pages'] = $menu_pages_result;

        // Import SEO
        $seo_result = $this->import_seo_from_csv($posts_map, $pages_map);
        if (is_wp_error($seo_result)) {
            $results['seo'] = array('success' => false, 'message' => $seo_result->get_error_message());
        } else {
            $results['seo'] = $seo_result;
        }

        return $results;
    }
}
