<?php
/**
 * Theme Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Constants
define('YOUTUBESTORE_VERSION', '1.1.2');
define('YOUTUBESTORE_DIR', get_template_directory());
define('YOUTUBESTORE_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function youtubestore_setup()
{
    // Load theme text domain first
    load_theme_textdomain('youtubestore', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Switch default core markup to output valid HTML5.
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
}
add_action('after_setup_theme', 'youtubestore_setup');

/**
 * Enqueue Scripts & Styles
 */
function youtubestore_scripts()
{
    // Enqueue migrated styles
    // Note: We keep style.css for Theme Declaration but use app.min.css for visual
    wp_enqueue_style('youtubestore-style', get_stylesheet_uri(), array(), YOUTUBESTORE_VERSION); // Main style.css
    wp_enqueue_style('youtubestore-app', YOUTUBESTORE_URI . '/assets/css/home/app.min.css', array(), YOUTUBESTORE_VERSION);

    // Optimized theme styles (moved from inline styles)
    wp_enqueue_style('youtubestore-optimized', YOUTUBESTORE_URI . '/assets/css/theme-optimized.css', array('youtubestore-app'), YOUTUBESTORE_VERSION);

    // Enqueue migrated scripts with defer for better performance
    // Use WP jQuery instead of bundled one to avoid redundancy, unless strict requirement.
    wp_enqueue_script('jquery');

    wp_enqueue_script('youtubestore-app', YOUTUBESTORE_URI . '/assets/js/home/app.min.js', array('jquery'), YOUTUBESTORE_VERSION, true);

    // We can keep main.js for our custom Facade/Filter logic, or merge. 
    // For now, keep it as it handles the specific WP AJAX logic we wrote.
    wp_enqueue_script('youtubestore-main', YOUTUBESTORE_URI . '/assets/js/main.js', array('jquery', 'youtubestore-app'), YOUTUBESTORE_VERSION, true);

    wp_localize_script('youtubestore-main', 'youtubestore_vars', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));

    // Archive channels script (moved from inline)
    if (is_post_type_archive('youtube_channel')) {
        wp_enqueue_script('youtubestore-archive-channels', YOUTUBESTORE_URI . '/assets/js/archive-channels.js', array('jquery'), YOUTUBESTORE_VERSION, true);
    }

    // Front page: lightweight deferred script (no jQuery) to reduce main-thread time
    if (is_front_page()) {
        wp_enqueue_script('youtubestore-front-page', YOUTUBESTORE_URI . '/assets/js/front-page.js', array(), YOUTUBESTORE_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'youtubestore_scripts');

/**
 * Add defer/async attributes to scripts for better performance
 */
function youtubestore_defer_scripts($tag, $handle, $src)
{
    // Do not defer scripts in admin area
    if (is_admin()) {
        return $tag;
    }

    // Scripts to defer
    $defer_scripts = array(
        'jquery',
        'jquery-core',
        'jquery-migrate',
        'youtubestore-app',
        'youtubestore-main',
        'youtubestore-archive-channels',
        'youtubestore-front-page'
    );

    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'youtubestore_defer_scripts', 10, 3);

/**
 * Remove Unnecessary Core CSS
 */
function youtubestore_remove_core_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('classic-theme-styles');

    // Disable dashicons for non-logged in users
    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
        wp_deregister_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', 'youtubestore_remove_core_css', 100);

/**
 * Non-render-blocking CSS on front page: load theme-optimized.css with media="print" + onload
 * to reduce render-blocking time (PageSpeed: "Remove render-blocking resources").
 * Critical above-the-fold styles are in app.min.css and inline critical CSS in front-page.php.
 */
function youtubestore_nonblocking_styles_front_page($html, $handle, $href, $media)
{
    if (!is_front_page() || $handle !== 'youtubestore-optimized') {
        return $html;
    }
    return sprintf(
        '<link rel="stylesheet" id="%s-css" href="%s" media="print" onload="this.media=\'all\';this.onload=null;">' . "\n" . '<noscript><link rel="stylesheet" href="%s"></noscript>',
        esc_attr($handle),
        esc_url($href),
        esc_url($href)
    );
}
add_filter('style_loader_tag', 'youtubestore_nonblocking_styles_front_page', 10, 4);

/**
 * Optimized out: Async Load CSS was removed because it causes Flash of Unstyled Content (FOUC)
 * and high Cumulative Layout Shift (CLS) scores since we do not have critical CSS inlined.
 */

/**
 * Add Content Security Policy Header
 */
function youtubestore_add_csp_header()
{
    if (!is_admin()) {
        header("Content-Security-Policy: upgrade-insecure-requests;");
    }
}
add_action('send_headers', 'youtubestore_add_csp_header');



/**
 * Require Files
 */
require_once YOUTUBESTORE_DIR . '/inc/post-types.php';
require_once YOUTUBESTORE_DIR . '/inc/acf-fields.php'; // Load ACF definitions
// require_once YOUTUBESTORE_DIR . '/inc/taxonomies.php'; 
require_once YOUTUBESTORE_DIR . '/inc/admin/settings-page.php';
require_once YOUTUBESTORE_DIR . '/inc/sync/class-google-sheet-sync.php';
require_once YOUTUBESTORE_DIR . '/inc/ajax-filters.php';

// Import functionality
require_once YOUTUBESTORE_DIR . '/inc/admin/normalize-data-page.php';
require_once YOUTUBESTORE_DIR . '/inc/import/import-admin-page.php';
require_once YOUTUBESTORE_DIR . '/inc/import/channel-import-admin-page.php';

// Table of Contents
require_once YOUTUBESTORE_DIR . '/inc/table-of-contents.php';

// Optimization & Security
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head', 'wp_generator');

/**
 * Disable WordPress Emojis
 */
function youtubestore_disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'youtubestore_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'youtubestore_disable_emojis_remove_dns_prefetch', 10, 2);
}
add_action('init', 'youtubestore_disable_emojis');

function youtubestore_disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

function youtubestore_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}

/**
 * Disable WP Embeds
 */
function youtubestore_disable_embeds_code_init()
{
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    add_filter('embed_oembed_discover', '__return_false');
    remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
}
add_action('init', 'youtubestore_disable_embeds_code_init', 9999);

/**
 * Remove Query Strings from static resources
 */
function youtubestore_remove_script_version($src)
{
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    if (strpos($src, '&ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'youtubestore_remove_script_version', 9999);
add_filter('script_loader_src', 'youtubestore_remove_script_version', 9999);

/**
 * Remove jQuery Migrate
 */
function youtubestore_remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'youtubestore_remove_jquery_migrate');


// Modify Main Query for Archive
function youtubestore_modify_archive_query($query)
{
    if ($query->is_main_query() && !is_admin() && is_post_type_archive('youtube_channel')) {
        // Set posts per page
        $per_page = isset($_GET['posts_per_page']) ? intval($_GET['posts_per_page']) : 25;
        $query->set('posts_per_page', $per_page);

        // Handle filtering
        $meta_query = array('relation' => 'AND');

        // Price filter
        if (isset($_GET['price_from']) || isset($_GET['price_to'])) {
            $price_from = isset($_GET['price_from']) ? intval($_GET['price_from']) : null;
            $price_to = isset($_GET['price_to']) ? intval($_GET['price_to']) : null;

            if ($price_from !== null || $price_to !== null) {
                if ($price_from !== null && $price_to !== null) {
                    $meta_query[] = array(
                        'key' => 'price',
                        'value' => array($price_from, $price_to),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN'
                    );
                } elseif ($price_from !== null) {
                    $meta_query[] = array(
                        'key' => 'price',
                        'value' => $price_from,
                        'type' => 'NUMERIC',
                        'compare' => '>='
                    );
                } elseif ($price_to !== null) {
                    $meta_query[] = array(
                        'key' => 'price',
                        'value' => $price_to,
                        'type' => 'NUMERIC',
                        'compare' => '<='
                    );
                }
            }
        }

        // Subscribers filter
        if (isset($_GET['subscribers_from']) || isset($_GET['subscribers_to'])) {
            $subscribers_from = isset($_GET['subscribers_from']) ? intval($_GET['subscribers_from']) : null;
            $subscribers_to = isset($_GET['subscribers_to']) ? intval($_GET['subscribers_to']) : null;

            if ($subscribers_from !== null || $subscribers_to !== null) {
                if ($subscribers_from !== null && $subscribers_to !== null) {
                    $meta_query[] = array(
                        'key' => 'subscribers',
                        'value' => array($subscribers_from, $subscribers_to),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN'
                    );
                } elseif ($subscribers_from !== null) {
                    $meta_query[] = array(
                        'key' => 'subscribers',
                        'value' => $subscribers_from,
                        'type' => 'NUMERIC',
                        'compare' => '>='
                    );
                } elseif ($subscribers_to !== null) {
                    $meta_query[] = array(
                        'key' => 'subscribers',
                        'value' => $subscribers_to,
                        'type' => 'NUMERIC',
                        'compare' => '<='
                    );
                }
            }
        }

        // Search filter
        if (!empty($_GET['search'])) {
            $query->set('s', sanitize_text_field($_GET['search']));
        }

        if (count($meta_query) > 1) {
            $query->set('meta_query', $meta_query);
        }

        // Handle sorting
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'subscribers';
        $order = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'ASC';

        // Store orderby in query for use in posts_clauses filter
        $query->set('youtubestore_orderby', $orderby);
        $query->set('youtubestore_order', $order);

        // Default: sort by subscribers DESC
        if ($orderby === 'subscribers' || $orderby === 'price') {
            $meta_key = ($orderby === 'subscribers') ? 'subscribers' : 'price';
            $query->set('meta_key', $meta_key);
            $query->set('orderby', 'meta_value');
            $query->set('order', $order);
            // Add filter to convert string to number
            add_filter('posts_clauses', 'youtubestore_orderby_numeric_clauses', 20, 2);
        } elseif ($orderby === 'title') {
            $query->set('orderby', 'title');
            $query->set('order', $order);
        } elseif ($orderby === 'date') {
            $query->set('orderby', 'date');
            $query->set('order', $order);
        } else {
            // Default: subscribers DESC
            $query->set('meta_key', 'subscribers');
            $query->set('orderby', 'meta_value');
            $query->set('order', 'DESC');
            add_filter('posts_clauses', 'youtubestore_orderby_numeric_clauses', 20, 2);
        }
    }
}
add_action('pre_get_posts', 'youtubestore_modify_archive_query');

/**
 * Custom orderby clauses - convert string to number for numeric sorting
 */
function youtubestore_orderby_numeric_clauses($clauses, $query)
{
    global $wpdb;

    // Only apply to main query on archive page
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('youtube_channel')) {
        $orderby = $query->get('youtubestore_orderby');
        $order = $query->get('youtubestore_order') ? $query->get('youtubestore_order') : 'DESC';

        if (in_array($orderby, array('subscribers', 'price'))) {
            // Replace the orderby clause to cast string to number
            // Remove dots, commas, spaces, and any non-numeric characters, then cast to UNSIGNED
            $clauses['orderby'] = "CAST(REPLACE(REPLACE(REPLACE(REPLACE({$wpdb->postmeta}.meta_value, '.', ''), ',', ''), ' ', ''), CHAR(9), '') AS UNSIGNED) " . $order;

            // Remove filter after use to prevent conflicts
            remove_filter('posts_clauses', 'youtubestore_orderby_numeric_clauses', 20);
        }
    }

    return $clauses;
}

/**
 * Normalize numeric meta values (subscribers, price) from string to integer
 * This function can be called once to fix existing data
 */
function youtubestore_normalize_numeric_meta_values()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $args = array(
        'post_type' => 'youtube_channel',
        'posts_per_page' => -1,
        'post_status' => 'any',
    );

    $query = new WP_Query($args);
    $updated = 0;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Normalize subscribers
            $subscribers = get_post_meta($post_id, 'subscribers', true);
            if (!empty($subscribers) && !is_numeric($subscribers)) {
                $subscribers_clean = preg_replace('/[^\d]/', '', strval($subscribers));
                $subscribers_num = intval($subscribers_clean);
                update_post_meta($post_id, 'subscribers', $subscribers_num);
                if (function_exists('update_field')) {
                    update_field('subscribers', $subscribers_num, $post_id);
                }
                $updated++;
            } elseif (empty($subscribers)) {
                update_post_meta($post_id, 'subscribers', 0);
                if (function_exists('update_field')) {
                    update_field('subscribers', 0, $post_id);
                }
            }

            // Normalize price
            $price = get_post_meta($post_id, 'price', true);
            if (!empty($price) && !is_numeric($price)) {
                $price_clean = preg_replace('/[^\d]/', '', strval($price));
                $price_num = intval($price_clean);
                update_post_meta($post_id, 'price', $price_num);
                if (function_exists('update_field')) {
                    update_field('price', $price_num, $post_id);
                }
                $updated++;
            } elseif (empty($price)) {
                update_post_meta($post_id, 'price', 0);
                if (function_exists('update_field')) {
                    update_field('price', 0, $post_id);
                }
            }
        }
        wp_reset_postdata();
    }

    return $updated;
}

// Uncomment the line below and visit any admin page once to normalize existing data
// add_action('admin_init', function() { youtubestore_normalize_numeric_meta_values(); });

// SEO Schema
function youtubestore_schema()
{
    if (is_singular('youtube_channel')) {
        $post_id = get_the_ID();
        $price_raw = function_exists('get_field') ? get_field('price') : get_post_meta($post_id, 'price', true);
        $status = function_exists('get_field') ? get_field('status') : get_post_meta($post_id, 'status', true);

        // Ensure price is a number
        $price = 0;
        if (!empty($price_raw)) {
            $price_clean = preg_replace('/[^\d]/', '', strval($price_raw));
            $price = intval($price_clean);
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'image' => get_the_post_thumbnail_url($post_id, 'full'),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => 'VND',
                'availability' => ($status === 'sold') ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
            )
        );
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'youtubestore_schema');

/**
 * Override SEO Meta Title and Description for YouTube Channel Archive
 */
function youtubestore_override_archive_seo()
{
    if (!is_post_type_archive('youtube_channel')) {
        return;
    }

    $seo_page_id = 0;
    $seo_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-youtube-channels.php',
        'number' => 1
    ));
    if (!empty($seo_pages)) {
        $seo_page_id = $seo_pages[0]->ID;
    }

    if (!$seo_page_id) {
        return;
    }

    $meta_title = '';
    $meta_desc = '';

    // 1. SiteSEO
    $ss_page_title = get_post_meta($seo_page_id, '_siteseo_titles_title', true);
    $ss_page_desc = get_post_meta($seo_page_id, '_siteseo_titles_desc', true);

    if (!empty($ss_page_title) || !empty($ss_page_desc)) {
        if (function_exists('siteseo_parse_variables')) {
            $meta_title = siteseo_parse_variables($ss_page_title, get_post($seo_page_id));
            $meta_desc = siteseo_parse_variables($ss_page_desc, get_post($seo_page_id));
        } else {
            // SiteSEO fallback replacements if function not available
            $replacements = array(
                '%%sitetitle%%' => get_bloginfo('name'),
                '%%tagline%%' => get_bloginfo('description'),
                '%%title%%' => get_the_title($seo_page_id),
                '%%sep%%' => '-',
            );
            $meta_title = str_replace(array_keys($replacements), array_values($replacements), $ss_page_title);
            $meta_desc = str_replace(array_keys($replacements), array_values($replacements), $ss_page_desc);
        }
    }
    // 2. Yoast SEO
    elseif (class_exists('WPSEO_Options')) {
        $meta_title = get_post_meta($seo_page_id, '_yoast_wpseo_title', true) ?: WPSEO_Options::get('title-ptarchive-youtube_channel', '');
        $meta_desc = get_post_meta($seo_page_id, '_yoast_wpseo_metadesc', true) ?: WPSEO_Options::get('metadesc-ptarchive-youtube_channel', '');
        if (function_exists('wpseo_replace_vars')) {
            $meta_title = wpseo_replace_vars($meta_title, get_post($seo_page_id));
            $meta_desc = wpseo_replace_vars($meta_desc, get_post($seo_page_id));
        }
    }
    // 3. Rank Math
    elseif (class_exists('RankMath')) {
        $meta_title = get_post_meta($seo_page_id, 'rank_math_title', true);
        $meta_desc = get_post_meta($seo_page_id, 'rank_math_description', true);
        if (function_exists('rank_math_replace_variables')) {
            $meta_title = rank_math_replace_variables($meta_title, get_post($seo_page_id));
            $meta_desc = rank_math_replace_variables($meta_desc, get_post($seo_page_id));
        }
    }

    // Apply Overrides
    if (!empty($meta_title)) {
        // Yoast
        add_filter('wpseo_title', function ($title) use ($meta_title) {
            return $meta_title;
        });
        // RankMath
        add_filter('rank_math/frontend/title', function ($title) use ($meta_title) {
            return $meta_title;
        });
        // SiteSEO / WordPress core
        add_filter('pre_get_document_title', function ($title) use ($meta_title) {
            return $meta_title;
        }, 99);
        add_filter('document_title_parts', function ($parts) use ($meta_title) {
            $parts['title'] = $meta_title;
            return $parts;
        }, 99);
    }

    if (!empty($meta_desc)) {
        $meta_desc_clean = trim(wp_strip_all_tags($meta_desc));

        // Yoast
        add_filter('wpseo_metadesc', function ($desc) use ($meta_desc_clean) {
            return $meta_desc_clean;
        });
        // RankMath
        add_filter('rank_math/frontend/description', function ($desc) use ($meta_desc_clean) {
            return $meta_desc_clean;
        });

        // SiteSEO - Remove their action, add our own
        if (class_exists('\SiteSEO\TitlesMetas')) {
            remove_action('wp_head', '\SiteSEO\TitlesMetas::add_meta_description', 1);
        }
        add_action('wp_head', function () use ($meta_desc_clean) {
            echo '<meta name="description" content="' . esc_attr($meta_desc_clean) . '" />' . "\n";
        }, 1);
    }
}
add_action('wp', 'youtubestore_override_archive_seo');
