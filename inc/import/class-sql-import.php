<?php
/**
 * SQL Files Import Class
 * Import data from SQL dump files
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_SQL_Import
{
    private $sql_dir;

    public function __construct()
    {
        if (!defined('YOUTUBESTORE_DIR')) {
            define('YOUTUBESTORE_DIR', get_template_directory());
        }
        $this->sql_dir = YOUTUBESTORE_DIR . '/laravel-database';
    }

    /**
     * Parse SQL file and extract INSERT statements
     */
    private function parse_sql_file($file_path, $table_name)
    {
        if (!file_exists($file_path)) {
            return array();
        }

        $content = file_get_contents($file_path);
        $data = array();

        // Extract INSERT statements for the table - improved pattern to handle multiline
        $pattern = '/INSERT\s+INTO\s+[`\']?' . preg_quote($table_name, '/') . '[`\']?\s*\([^)]+\)\s*VALUES\s*((?:\([^)]+\)(?:,\s*)?)+);/is';
        
        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[1] as $values_string) {
                // Parse values - handle both single and multiple rows
                $rows = $this->parse_insert_values($values_string);
                $data = array_merge($data, $rows);
            }
        }

        return $data;
    }


    /**
     * Get column names from INSERT statement
     */
    private function get_columns($sql_content, $table_name)
    {
        $pattern = '/INSERT\s+INTO\s+[`\']?' . preg_quote($table_name, '/') . '[`\']?\s*\(([^)]+)\)/i';
        if (preg_match($pattern, $sql_content, $matches)) {
            $columns_string = $matches[1];
            $columns = array_map('trim', explode(',', $columns_string));
            $columns = array_map(function($col) {
                return trim($col, "`'\"");
            }, $columns);
            return $columns;
        }
        return array();
    }

    /**
     * Import categories from SQL
     */
    public function import_categories_from_sql()
    {
        $sql_file = $this->sql_dir . '/categories.sql';
        if (!file_exists($sql_file)) {
            return new WP_Error('file_not_found', 'categories.sql not found');
        }

        $content = file_get_contents($sql_file);
        $columns = $this->get_columns($content, 'categories');
        $rows = $this->parse_sql_file($sql_file, 'categories');

        if (empty($columns) || empty($rows)) {
            return array('success' => false, 'message' => 'No data found in categories.sql');
        }

        $categories_map = array();
        $imported = 0;

        foreach ($rows as $row) {
            $data = array_combine($columns, $row);
            
            if (empty($data['name']) || empty($data['slug'])) {
                continue;
            }

            // Only import Vietnamese categories
            if (isset($data['language']) && $data['language'] !== 'vi') {
                continue;
            }

            $term_slug = sanitize_title($data['slug']);
            $term = get_term_by('slug', $term_slug, 'category');

            if (!$term) {
                $term_data = wp_insert_term(
                    $data['name'],
                    'category',
                    array(
                        'slug' => $term_slug,
                        'description' => $data['content'] ?? ''
                    )
                );

                if (!is_wp_error($term_data)) {
                    $categories_map[$data['id']] = $term_data['term_id'];
                    $imported++;
                }
            } else {
                $categories_map[$data['id']] = $term->term_id;
            }

            // Handle parent
            if (!empty($data['category_id']) && isset($categories_map[$data['category_id']])) {
                wp_update_term($categories_map[$data['id']], 'category', array(
                    'parent' => $categories_map[$data['category_id']]
                ));
            }
        }

        return array(
            'success' => true,
            'count' => $imported,
            'message' => sprintf('Imported %d categories from SQL', $imported)
        );
    }

    /**
     * Import posts from SQL
     */
    public function import_posts_from_sql()
    {
        $sql_file = $this->sql_dir . '/posts.sql';
        if (!file_exists($sql_file)) {
            return new WP_Error('file_not_found', 'posts.sql not found');
        }

        // Use WordPress database to execute SQL directly
        global $wpdb;
        
        // Read SQL file and extract INSERT statements
        $content = file_get_contents($sql_file);
        
        // Get columns from first INSERT statement
        $columns = $this->get_columns($content, 'posts');
        if (empty($columns)) {
            return new WP_Error('parse_error', 'Cannot parse columns from posts.sql');
        }

        // Extract all INSERT statements
        $pattern = '/INSERT\s+INTO\s+[`\']?posts[`\']?\s*\([^)]+\)\s*VALUES\s*([^;]+);/is';
        preg_match_all($pattern, $content, $matches);
        
        if (empty($matches[1])) {
            return array('success' => false, 'message' => 'No INSERT statements found in posts.sql');
        }

        $posts_map = array();
        $imported = 0;
        $total_rows = 0;

        // Process each INSERT statement
        foreach ($matches[1] as $values_string) {
            // Parse rows from VALUES string
            $rows = $this->parse_insert_values($values_string);
            
            foreach ($rows as $row) {
                $total_rows++;
                
                if (count($row) !== count($columns)) {
                    continue; // Skip malformed rows
                }
                
                $data = array_combine($columns, $row);
                
                if (empty($data['name']) || empty($data['slug'])) {
                    continue;
                }

                // Only import Vietnamese and published posts
                // Status can be 'published', '1', or 1 (number)
                $is_published = false;
                if (isset($data['status'])) {
                    $status = $data['status'];
                    $is_published = ($status === 'published' || $status === '1' || $status === 1);
                }
                
                if ((isset($data['language']) && $data['language'] !== 'vi') || !$is_published) {
                    continue;
                }

                $existing_post = get_page_by_path($data['slug'], OBJECT, 'post');
                
                if ($existing_post) {
                    $posts_map[$data['id']] = $existing_post->ID;
                    continue;
                }

                $post_arr = array(
                    'post_title' => $data['name'],
                    'post_content' => $data['content'] ?? '',
                    'post_name' => $data['slug'],
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'post_date' => $data['created_at'] ?? current_time('mysql'),
                );

                $post_id = wp_insert_post($post_arr);

                if (!is_wp_error($post_id)) {
                    $posts_map[$data['id']] = $post_id;
                    $imported++;

                    // Import featured image if exists (async to avoid timeout)
                    if (!empty($data['image'])) {
                        // Schedule for later or skip if too many
                        if ($imported <= 50) { // Limit to avoid timeout
                            $this->import_featured_image($post_id, $data['image']);
                        }
                    }
                }
            }
        }

        return array(
            'success' => true,
            'count' => $imported,
            'total' => $total_rows,
            'message' => sprintf('Imported %d new posts from SQL (Total rows: %d)', $imported, $total_rows),
            'posts_map' => $posts_map
        );
    }

    /**
     * Parse VALUES from INSERT statement - improved version
     */
    private function parse_insert_values($values_string)
    {
        $rows = array();
        $current_row = array();
        $current_value = '';
        $in_quotes = false;
        $quote_char = '';
        $paren_level = 0;
        $escaped = false;

        $len = strlen($values_string);
        
        for ($i = 0; $i < $len; $i++) {
            $char = $values_string[$i];
            
            if ($escaped) {
                $current_value .= $char;
                $escaped = false;
                continue;
            }
            
            if ($char === '\\') {
                $escaped = true;
                $current_value .= $char;
                continue;
            }

            if (!$in_quotes) {
                if ($char === '(') {
                    $paren_level++;
                    if ($paren_level === 1) {
                        $current_row = array();
                        $current_value = '';
                    } else {
                        $current_value .= $char;
                    }
                } elseif ($char === ')') {
                    $paren_level--;
                    if ($paren_level === 0) {
                        if ($current_value !== '') {
                            $current_row[] = $this->clean_value($current_value);
                        }
                        if (!empty($current_row)) {
                            $rows[] = $current_row;
                        }
                        $current_row = array();
                        $current_value = '';
                    } else {
                        $current_value .= $char;
                    }
                } elseif ($char === ',' && $paren_level === 1) {
                    $current_row[] = $this->clean_value($current_value);
                    $current_value = '';
                } elseif ($char === '"' || $char === "'") {
                    $in_quotes = true;
                    $quote_char = $char;
                    $current_value .= $char;
                } elseif ($char !== ' ' || trim($current_value) !== '') {
                    $current_value .= $char;
                }
            } else {
                $current_value .= $char;
                if ($char === $quote_char && !$escaped) {
                    $in_quotes = false;
                    $quote_char = '';
                }
            }
        }

        return $rows;
    }

    /**
     * Clean value - remove quotes and handle NULL
     */
    private function clean_value($value)
    {
        $value = trim($value);
        
        if ($value === 'NULL' || $value === 'null') {
            return null;
        }
        
        // Remove surrounding quotes
        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
            $value = substr($value, 1, -1);
        }
        
        // Unescape
        $value = str_replace(array("\\'", '\\"', '\\\\'), array("'", '"', '\\'), $value);
        
        return $value;
    }


    /**
     * Import SEO from SQL
     */
    public function import_seo_from_sql($posts_map = array())
    {
        $sql_file = $this->sql_dir . '/seos.sql';
        if (!file_exists($sql_file)) {
            return new WP_Error('file_not_found', 'seos.sql not found');
        }

        $content = file_get_contents($sql_file);
        $columns = $this->get_columns($content, 'seos');
        $rows = $this->parse_sql_file($sql_file, 'seos');

        if (empty($columns) || empty($rows)) {
            return array('success' => false, 'message' => 'No data found in seos.sql');
        }

        $imported = 0;

        foreach ($rows as $row) {
            $data = array_combine($columns, $row);
            
            // Only import SEO for posts
            if ($data['seoable_type'] !== 'App\\Models\\Post') {
                continue;
            }

            $laravel_post_id = $data['seoable_id'];
            
            if (!isset($posts_map[$laravel_post_id])) {
                continue;
            }

            $wp_post_id = $posts_map[$laravel_post_id];

            // Import SEO meta
            if (defined('WPSEO_VERSION')) {
                if (!empty($data['title'])) {
                    update_post_meta($wp_post_id, '_yoast_wpseo_title', $data['title']);
                }
                if (!empty($data['description'])) {
                    update_post_meta($wp_post_id, '_yoast_wpseo_metadesc', $data['description']);
                }
                if (!empty($data['canonical'])) {
                    update_post_meta($wp_post_id, '_yoast_wpseo_canonical', home_url('/' . $data['canonical']));
                }
            }

            if (defined('RANK_MATH_VERSION')) {
                if (!empty($data['title'])) {
                    update_post_meta($wp_post_id, 'rank_math_title', $data['title']);
                }
                if (!empty($data['description'])) {
                    update_post_meta($wp_post_id, 'rank_math_description', $data['description']);
                }
                if (!empty($data['canonical'])) {
                    update_post_meta($wp_post_id, 'rank_math_canonical_url', home_url('/' . $data['canonical']));
                }
            }

            // Store as custom meta
            update_post_meta($wp_post_id, '_imported_seo_title', $data['title']);
            update_post_meta($wp_post_id, '_imported_seo_description', $data['description']);
            update_post_meta($wp_post_id, '_imported_seo_canonical', $data['canonical']);
            
            $imported++;
        }

        return array(
            'success' => true,
            'count' => $imported,
            'message' => sprintf('Imported SEO data for %d posts from SQL', $imported)
        );
    }

    /**
     * Import featured image
     */
    private function import_featured_image($post_id, $image_url)
    {
        if (empty($image_url)) {
            return false;
        }

        if (strpos($image_url, 'http') !== 0) {
            $image_url = 'https://youtubestore.vn/' . ltrim($image_url, '/');
        }

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $tmp = download_url($image_url);
        
        if (is_wp_error($tmp)) {
            return false;
        }

        $file_array = array(
            'name' => basename($image_url),
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
     * Create menu
     */
    public function create_menu()
    {
        $menu_name = 'Main Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
        } else {
            $menu_id = $menu_exists->term_id;
        }

        $menu_items = array(
            array('title' => 'Giới Thiệu Chung', 'url' => home_url('/gioi-thieu-chung')),
            array('title' => 'Danh Sách Kênh', 'url' => home_url('/mua-kenh-youtube')),
            array('title' => 'Chuyển nhượng kênh Youtube', 'url' => home_url('/chuyen-nhuong-kenh-youtube')),
            array('title' => 'Tin Tức', 'url' => home_url('/tin-tuc')),
            array('title' => 'Liên Hệ', 'url' => home_url('/contact')),
        );

        $existing_items = wp_get_nav_menu_items($menu_id);
        if ($existing_items) {
            foreach ($existing_items as $item) {
                wp_delete_post($item->ID, true);
            }
        }

        foreach ($menu_items as $item) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish'
            ));
        }

        $locations = get_theme_mod('nav_menu_locations');
        if (!$locations) {
            $locations = array();
        }
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);

        return array(
            'success' => true,
            'message' => 'Menu created successfully'
        );
    }

    /**
     * Run full import from SQL files
     */
    public function import_from_sql_files()
    {
        $results = array();

        // Import categories
        $categories_result = $this->import_categories_from_sql();
        if (is_wp_error($categories_result)) {
            $results['categories'] = array('success' => false, 'message' => $categories_result->get_error_message());
        } else {
            $results['categories'] = $categories_result;
        }

        // Import posts
        $posts_result = $this->import_posts_from_sql();
        if (is_wp_error($posts_result)) {
            $results['posts'] = array('success' => false, 'message' => $posts_result->get_error_message());
        } else {
            $results['posts'] = $posts_result;
        }

        // Import SEO
        if (isset($posts_result['posts_map'])) {
            $seo_result = $this->import_seo_from_sql($posts_result['posts_map']);
            if (is_wp_error($seo_result)) {
                $results['seo'] = array('success' => false, 'message' => $seo_result->get_error_message());
            } else {
                $results['seo'] = $seo_result;
            }
        }

        // Create menu
        $menu_result = $this->create_menu();
        $results['menu'] = $menu_result;

        return $results;
    }
}
