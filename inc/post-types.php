<?php
/**
 * Register Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit;
}

function youtubestore_register_cpt()
{
    $labels = array(
        'name' => _x('YouTube Channels', 'Post Type General Name', 'youtubestore'),
        'singular_name' => _x('YouTube Channel', 'Post Type Singular Name', 'youtubestore'),
        'menu_name' => __('YouTube Channels', 'youtubestore'),
        'name_admin_bar' => __('YouTube Channel', 'youtubestore'),
        'archives' => __('Channel Archives', 'youtubestore'),
        'attributes' => __('Channel Attributes', 'youtubestore'),
        'parent_item_colon' => __('Parent Channel:', 'youtubestore'),
        'all_items' => __('All Channels', 'youtubestore'),
        'add_new_item' => __('Add New Channel', 'youtubestore'),
        'add_new' => __('Add New', 'youtubestore'),
        'new_item' => __('New Channel', 'youtubestore'),
        'edit_item' => __('Edit Channel', 'youtubestore'),
        'update_item' => __('Update Channel', 'youtubestore'),
        'view_item' => __('View Channel', 'youtubestore'),
        'view_items' => __('View Channels', 'youtubestore'),
        'search_items' => __('Search Channel', 'youtubestore'),
        'not_found' => __('Not found', 'youtubestore'),
        'not_found_in_trash' => __('Not found in Trash', 'youtubestore'),
        'featured_image' => __('Channel Screenshot', 'youtubestore'),
        'set_featured_image' => __('Set channel screenshot', 'youtubestore'),
        'remove_featured_image' => __('Remove channel screenshot', 'youtubestore'),
        'use_featured_image' => __('Use as channel screenshot', 'youtubestore'),
        'insert_into_item' => __('Insert into channel', 'youtubestore'),
        'uploaded_to_this_item' => __('Uploaded to this channel', 'youtubestore'),
        'items_list' => __('Channels list', 'youtubestore'),
        'items_list_navigation' => __('Channels list navigation', 'youtubestore'),
        'filter_items_list' => __('Filter channels list', 'youtubestore'),
    );
    $cpt_desc = __('YouTube Channels for sale', 'youtubestore');

    $siteseo_options = get_option('siteseo_titles_option_name');
    if (is_array($siteseo_options) && !empty($siteseo_options['titles_pt_archive_youtube_channel_desc'])) {
        $cpt_desc = $siteseo_options['titles_pt_archive_youtube_channel_desc'];
        if (function_exists('siteseo_parse_variables')) {
            $cpt_desc = siteseo_parse_variables($cpt_desc);
        } else {
            $replacements = array(
                '%%sitetitle%%' => get_bloginfo('name'),
                '%%tagline%%' => get_bloginfo('description'),
                '%%cpt_plural%%' => 'Danh sách Giới Thiệu Youtube',
                '%%sep%%' => '-',
            );
            $cpt_desc = str_replace(array_keys($replacements), array_values($replacements), $cpt_desc);
        }
    } elseif (class_exists('WPSEO_Options')) {
        $yoast_desc = WPSEO_Options::get('metadesc-ptarchive-youtube_channel', '');
        if (!empty($yoast_desc)) {
            $cpt_desc = function_exists('wpseo_replace_vars') ? wpseo_replace_vars($yoast_desc, null) : $yoast_desc;
        }
    } elseif (class_exists('RankMath')) {
        $rm_options = get_option('rank-math-options-titles');
        if (is_array($rm_options) && !empty($rm_options['pt_youtube_channel_archive_description'])) {
            $cpt_desc = $rm_options['pt_youtube_channel_archive_description'];
            if (function_exists('rank_math_replace_variables')) {
                $cpt_desc = rank_math_replace_variables($cpt_desc);
            }
        }
    }

    $args = array(
        'label' => __('YouTube Channel', 'youtubestore'),
        'description' => $cpt_desc,
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies' => array('category'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-video-alt3',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'mua-kenh-youtube', 'with_front' => false),
    );
    register_post_type('youtube_channel', $args);
}
add_action('init', 'youtubestore_register_cpt', 0);
