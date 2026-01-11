<?php
/**
 * Theme Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Constants
define('YOUTUBESTORE_VERSION', '1.0.0');
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
    wp_enqueue_style('youtubestore-style', get_stylesheet_uri()); // Main style.css
    wp_enqueue_style('youtubestore-app', YOUTUBESTORE_URI . '/assets/css/home/app.min.css', array(), YOUTUBESTORE_VERSION);

    // Enqueue migrated scripts
    // Use WP jQuery instead of bundled one to avoid redundancy, unless strict requirement.
    wp_enqueue_script('jquery');

    wp_enqueue_script('youtubestore-sweetalert', YOUTUBESTORE_URI . '/assets/js/home/sweetalert.min.js', array(), YOUTUBESTORE_VERSION, true);
    wp_enqueue_script('youtubestore-app', YOUTUBESTORE_URI . '/assets/js/home/app.min.js', array('jquery'), YOUTUBESTORE_VERSION, true);

    // We can keep main.js for our custom Facade/Filter logic, or merge. 
    // For now, keep it as it handles the specific WP AJAX logic we wrote.
    wp_enqueue_script('youtubestore-main', YOUTUBESTORE_URI . '/assets/js/main.js', array('jquery', 'youtubestore-app'), YOUTUBESTORE_VERSION, true);

    wp_localize_script('youtubestore-main', 'youtubestore_vars', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'youtubestore_scripts');

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
require_once YOUTUBESTORE_DIR . '/inc/import/import-admin-page.php';
require_once YOUTUBESTORE_DIR . '/inc/import/channel-import-admin-page.php';

// Table of Contents
require_once YOUTUBESTORE_DIR . '/inc/table-of-contents.php';

// Optimization & Security
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head', 'wp_generator');


// Modify Main Query for Archive
function youtubestore_modify_archive_query($query)
{
    if ($query->is_active_query() && !is_admin() && is_post_type_archive('youtube_channel')) {
        $query->set('posts_per_page', 12);
    }
}
add_action('pre_get_posts', 'youtubestore_modify_archive_query');

// SEO Schema
function youtubestore_schema()
{
    if (is_singular('youtube_channel')) {
        $post_id = get_the_ID();
        $price = function_exists('get_field') ? get_field('price') : get_post_meta($post_id, 'price', true);
        $status = function_exists('get_field') ? get_field('status') : get_post_meta($post_id, 'status', true);
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'image' => get_the_post_thumbnail_url($post_id, 'full'),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $price ? $price : 0,
                'priceCurrency' => 'VND',
                'availability' => ($status === 'sold') ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
            )
        );
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'youtubestore_schema');
