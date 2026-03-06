<?php
/**
 * Template Name: SEO Settings - Youtube Channels
 *
 * This template runs a custom query for Youtube Channels
 * and displays them using the archive-youtube_channel.php layout.
 */

// Replace the main query with our custom query so that archive-youtube_channel.php works flawlessly
global $wp_query;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if ($paged == 1) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}

$args = array(
    'post_type'      => 'youtube_channel',
    'post_status'    => 'publish',
    'paged'          => $paged,
);

$per_page = isset($_GET['posts_per_page']) ? intval($_GET['posts_per_page']) : 25;
$args['posts_per_page'] = $per_page;

$meta_query = array('relation' => 'AND');

// Price filter
if (isset($_GET['price_from']) || isset($_GET['price_to'])) {
    $price_from = isset($_GET['price_from']) && $_GET['price_from'] !== '' ? intval($_GET['price_from']) : null;
    $price_to = isset($_GET['price_to']) && $_GET['price_to'] !== '' ? intval($_GET['price_to']) : null;

    if ($price_from !== null || $price_to !== null) {
        if ($price_from !== null && $price_to !== null) {
            $meta_query[] = array(
                'key'     => 'price',
                'value'   => array($price_from, $price_to),
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN'
            );
        } elseif ($price_from !== null) {
            $meta_query[] = array(
                'key'     => 'price',
                'value'   => $price_from,
                'type'    => 'NUMERIC',
                'compare' => '>='
            );
        } elseif ($price_to !== null) {
            $meta_query[] = array(
                'key'     => 'price',
                'value'   => $price_to,
                'type'    => 'NUMERIC',
                'compare' => '<='
            );
        }
    }
}

// Subscribers filter
if (isset($_GET['subscribers_from']) || isset($_GET['subscribers_to'])) {
    $subscribers_from = isset($_GET['subscribers_from']) && $_GET['subscribers_from'] !== '' ? intval($_GET['subscribers_from']) : null;
    $subscribers_to = isset($_GET['subscribers_to']) && $_GET['subscribers_to'] !== '' ? intval($_GET['subscribers_to']) : null;

    if ($subscribers_from !== null || $subscribers_to !== null) {
        if ($subscribers_from !== null && $subscribers_to !== null) {
            $meta_query[] = array(
                'key'     => 'subscribers',
                'value'   => array($subscribers_from, $subscribers_to),
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN'
            );
        } elseif ($subscribers_from !== null) {
            $meta_query[] = array(
                'key'     => 'subscribers',
                'value'   => $subscribers_from,
                'type'    => 'NUMERIC',
                'compare' => '>='
            );
        } elseif ($subscribers_to !== null) {
            $meta_query[] = array(
                'key'     => 'subscribers',
                'value'   => $subscribers_to,
                'type'    => 'NUMERIC',
                'compare' => '<='
            );
        }
    }
}

if (count($meta_query) > 1) {
    $args['meta_query'] = $meta_query;
}

if (!empty($_GET['search'])) {
    $args['s'] = sanitize_text_field($_GET['search']);
}

$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'subscribers';
$order = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'DESC';

// Handle sorting and numeric filtering
if (!function_exists('youtubestore_page_template_numeric_clauses')) {
    function youtubestore_page_template_numeric_clauses($clauses, $query) {
        global $wpdb;
        $order = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'DESC';
        $clauses['orderby'] = "CAST(REPLACE(REPLACE(REPLACE(REPLACE({$wpdb->postmeta}.meta_value, '.', ''), ',', ''), ' ', ''), CHAR(9), '') AS UNSIGNED) " . ($order === 'ASC' ? 'ASC' : 'DESC');
        remove_filter('posts_clauses', 'youtubestore_page_template_numeric_clauses', 20);
        return $clauses;
    }
}

if ($orderby === 'subscribers' || $orderby === 'price' || empty($orderby)) {
    $meta_key = ($orderby === 'price') ? 'price' : 'subscribers';
    $args['meta_key'] = $meta_key;
    $args['orderby']  = 'meta_value';
    $args['order']    = $order;
    add_filter('posts_clauses', 'youtubestore_page_template_numeric_clauses', 20, 2);
} elseif ($orderby === 'title') {
    $args['orderby'] = 'title';
    $args['order']   = $order;
} elseif ($orderby === 'date') {
    $args['orderby'] = 'date';
    $args['order']   = $order;
}

// Perform Query
$wp_query = new WP_Query($args);

// Load the template
include(locate_template('archive-youtube_channel.php'));
?>
