<?php
/**
 * Laravel Database Import Class
 * Import data from Laravel database (categories, posts, SEO) to WordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_Laravel_Import
{
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_prefix;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Set database connection details
     */
    public function set_database($host, $name, $user, $pass, $prefix = '')
    {
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_user = $user;
        $this->db_pass = $pass;
        $this->db_prefix = $prefix;
    }

    /**
     * Connect to Laravel database
     */
    private function get_laravel_db()
    {
        try {
            $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4";
            $pdo = new PDO($dsn, $this->db_user, $this->db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            return new WP_Error('db_connection', 'Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Import categories
     */
    public function import_categories()
    {
        $pdo = $this->get_laravel_db();
        if (is_wp_error($pdo)) {
            return $pdo;
        }

        $categories_map = array(); // Store mapping: laravel_id => wp_term_id

        try {
            // Get all categories from Laravel
            $stmt = $pdo->query("SELECT * FROM categories WHERE language = 'vi' ORDER BY master_category_id, category_id, `order`");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $cat) {
                $term_slug = sanitize_title($cat['slug']);
                
                // Check if term exists
                $term = get_term_by('slug', $term_slug, 'category');
                
                if (!$term) {
                    // Create new category
                    $term_data = wp_insert_term(
                        $cat['name'],
                        'category',
                        array(
                            'slug' => $term_slug,
                            'description' => $cat['content'] ?? ''
                        )
                    );

                    if (!is_wp_error($term_data)) {
                        $term_id = $term_data['term_id'];
                        $categories_map[$cat['id']] = $term_id;
                    } else {
                        continue;
                    }
                } else {
                    $categories_map[$cat['id']] = $term->term_id;
                }

                // Handle parent category
                if (!empty($cat['category_id']) && isset($categories_map[$cat['category_id']])) {
                    wp_update_term($categories_map[$cat['id']], 'category', array(
                        'parent' => $categories_map[$cat['category_id']]
                    ));
                }
            }

            return array(
                'success' => true,
                'count' => count($categories),
                'message' => sprintf('Imported %d categories', count($categories))
            );
        } catch (PDOException $e) {
            return new WP_Error('import_error', 'Failed to import categories: ' . $e->getMessage());
        }
    }

    /**
     * Import posts
     */
    public function import_posts($categories_map = array())
    {
        $pdo = $this->get_laravel_db();
        if (is_wp_error($pdo)) {
            return $pdo;
        }

        $posts_map = array(); // Store mapping: laravel_id => wp_post_id
        $imported_count = 0;

        try {
            // Get all posts from Laravel
            $stmt = $pdo->query("SELECT * FROM posts WHERE language = 'vi' AND status = 'published' ORDER BY id");
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as $post_data) {
                // Check if post already exists by slug
                $existing_post = get_page_by_path($post_data['slug'], OBJECT, 'post');
                
                if ($existing_post) {
                    $posts_map[$post_data['id']] = $existing_post->ID;
                    continue;
                }

                // Prepare post content
                $content = $post_data['content'] ?? '';
                
                // Create WordPress post
                $post_arr = array(
                    'post_title' => $post_data['name'],
                    'post_content' => $content,
                    'post_name' => $post_data['slug'],
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'post_date' => $post_data['created_at'] ?? current_time('mysql'),
                    'post_date_gmt' => get_gmt_from_date($post_data['created_at'] ?? current_time('mysql')),
                );

                $post_id = wp_insert_post($post_arr);

                if (is_wp_error($post_id)) {
                    continue;
                }

                $posts_map[$post_data['id']] = $post_id;
                $imported_count++;

                // Set featured image if exists
                if (!empty($post_data['image'])) {
                    $this->import_featured_image($post_id, $post_data['image']);
                }

                // Set category if exists
                // Note: You may need to map Laravel category_id to WordPress term_id
                // This depends on your Laravel database structure
            }

            return array(
                'success' => true,
                'count' => $imported_count,
                'total' => count($posts),
                'message' => sprintf('Imported %d new posts (Total: %d)', $imported_count, count($posts)),
                'posts_map' => $posts_map
            );
        } catch (PDOException $e) {
            return new WP_Error('import_error', 'Failed to import posts: ' . $e->getMessage());
        }
    }

    /**
     * Import SEO data
     */
    public function import_seo($posts_map = array())
    {
        $pdo = $this->get_laravel_db();
        if (is_wp_error($pdo)) {
            return $pdo;
        }

        $imported_count = 0;

        try {
            // Get all SEO data from Laravel
            $stmt = $pdo->query("SELECT * FROM seos WHERE seoable_type = 'App\\\\Models\\\\Post'");
            $seos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($seos as $seo_data) {
                $laravel_post_id = $seo_data['seoable_id'];
                
                // Find corresponding WordPress post
                if (!isset($posts_map[$laravel_post_id])) {
                    continue;
                }

                $wp_post_id = $posts_map[$laravel_post_id];

                // Import SEO using Yoast SEO or Rank Math
                if (function_exists('update_post_meta')) {
                    // Yoast SEO
                    if (defined('WPSEO_VERSION')) {
                        if (!empty($seo_data['title'])) {
                            update_post_meta($wp_post_id, '_yoast_wpseo_title', $seo_data['title']);
                        }
                        if (!empty($seo_data['description'])) {
                            update_post_meta($wp_post_id, '_yoast_wpseo_metadesc', $seo_data['description']);
                        }
                        if (!empty($seo_data['canonical'])) {
                            update_post_meta($wp_post_id, '_yoast_wpseo_canonical', home_url('/' . $seo_data['canonical']));
                        }
                        if (!empty($seo_data['robots'])) {
                            update_post_meta($wp_post_id, '_yoast_wpseo_meta-robots-noindex', strpos($seo_data['robots'], 'noindex') !== false ? 1 : 0);
                            update_post_meta($wp_post_id, '_yoast_wpseo_meta-robots-nofollow', strpos($seo_data['robots'], 'nofollow') !== false ? 1 : 0);
                        }
                    }
                    
                    // Rank Math
                    if (defined('RANK_MATH_VERSION')) {
                        if (!empty($seo_data['title'])) {
                            update_post_meta($wp_post_id, 'rank_math_title', $seo_data['title']);
                        }
                        if (!empty($seo_data['description'])) {
                            update_post_meta($wp_post_id, 'rank_math_description', $seo_data['description']);
                        }
                        if (!empty($seo_data['canonical'])) {
                            update_post_meta($wp_post_id, 'rank_math_canonical_url', home_url('/' . $seo_data['canonical']));
                        }
                    }

                    // Store as custom meta for fallback
                    update_post_meta($wp_post_id, '_imported_seo_title', $seo_data['title']);
                    update_post_meta($wp_post_id, '_imported_seo_description', $seo_data['description']);
                    update_post_meta($wp_post_id, '_imported_seo_canonical', $seo_data['canonical']);
                    
                    $imported_count++;
                }
            }

            return array(
                'success' => true,
                'count' => $imported_count,
                'message' => sprintf('Imported SEO data for %d posts', $imported_count)
            );
        } catch (PDOException $e) {
            return new WP_Error('import_error', 'Failed to import SEO: ' . $e->getMessage());
        }
    }

    /**
     * Import featured image from URL
     */
    private function import_featured_image($post_id, $image_url)
    {
        if (empty($image_url)) {
            return false;
        }

        // If URL is relative, construct full URL
        if (strpos($image_url, 'http') !== 0) {
            // You may need to adjust this based on your Laravel setup
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
     * Create WordPress menu from Laravel data
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

        // Menu items based on website structure
        $menu_items = array(
            array(
                'title' => 'Giới Thiệu Chung',
                'url' => home_url('/gioi-thieu-chung'),
            ),
            array(
                'title' => 'Danh Sách Kênh',
                'url' => home_url('/mua-kenh-youtube'),
            ),
            array(
                'title' => 'Chuyển nhượng kênh Youtube',
                'url' => home_url('/chuyen-nhuong-kenh-youtube'),
            ),
            array(
                'title' => 'Tin Tức',
                'url' => home_url('/tin-tuc'),
            ),
            array(
                'title' => 'Liên Hệ',
                'url' => home_url('/contact'),
            ),
        );

        // Clear existing menu items
        $existing_items = wp_get_nav_menu_items($menu_id);
        if ($existing_items) {
            foreach ($existing_items as $item) {
                wp_delete_post($item->ID, true);
            }
        }

        // Add menu items
        foreach ($menu_items as $item) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish'
            ));
        }

        // Assign menu to location
        $locations = get_theme_mod('nav_menu_locations');
        if (!$locations) {
            $locations = array();
        }
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);

        return array(
            'success' => true,
            'menu_id' => $menu_id,
            'message' => 'Menu created successfully'
        );
    }

    /**
     * Run full import
     */
    public function run_full_import()
    {
        $results = array();

        // Import categories
        $categories_result = $this->import_categories();
        $results['categories'] = $categories_result;

        // Import posts
        $posts_result = $this->import_posts();
        $results['posts'] = $posts_result;

        // Import SEO
        if (isset($posts_result['posts_map'])) {
            $seo_result = $this->import_seo($posts_result['posts_map']);
            $results['seo'] = $seo_result;
        }

        // Create menu
        $menu_result = $this->create_menu();
        $results['menu'] = $menu_result;

        return $results;
    }
}
