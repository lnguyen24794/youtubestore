<?php
/**
 * AJAX Filter Handler
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_nopriv_filter_channels', 'youtubestore_filter_channels');
add_action('wp_ajax_filter_channels', 'youtubestore_filter_channels');

function youtubestore_filter_channels()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type' => 'youtube_channel',
        'posts_per_page' => 12,
        'paged' => $paged,
        'status' => 'publish', // Important
        'tax_query' => array('relation' => 'AND'),
        'meta_query' => array('relation' => 'AND'),
    );

    // Search
    if (!empty($_POST['search'])) {
        $args['s'] = sanitize_text_field($_POST['search']);
    }

    // Category
    if (!empty($_POST['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', $_POST['category']),
        );
    }

    // Price
    if (!empty($_POST['price_range']) && $_POST['price_range'] !== 'all') {
        $range = sanitize_text_field($_POST['price_range']);
        $price_args = array(
            'key' => 'price',
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN'
        );

        if (strpos($range, '+') !== false) {
            $min = (int) str_replace('+', '', $range);
            $price_args['value'] = $min;
            $price_args['compare'] = '>=';
        } else {
            $parts = explode('-', $range);
            if (count($parts) == 2) {
                $price_args['value'] = array((int) $parts[0], (int) $parts[1]);
            }
        }
        $args['meta_query'][] = $price_args;
    }

    // Status
    if (!empty($_POST['status'])) {
        $args['meta_query'][] = array(
            'key' => 'status',
            'value' => array_map('sanitize_text_field', $_POST['status']),
            'compare' => 'IN'
        );
    }

    // Monetization
    if (!empty($_POST['monetization']) && $_POST['monetization'] === 'yes') {
        $args['meta_query'][] = array(
            'key' => 'monetization',
            'value' => 'yes',
            'compare' => '='
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Output HTML for card - duplicated from front-page.php (should refactor if possible)
            // For now, strictly reproducing html
            ?>
            <a href="<?php the_permalink(); ?>" class="channel-card">
                <div class="card-thumb">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('medium_large', array('loading' => 'lazy'));
                    } else {
                        echo '<div class="placeholder"></div>';
                    }
                    ?>
                </div>
                <div class="card-body">
                    <h2 class="card-title">
                        <?php the_title(); ?>
                    </h2>
                    <div class="card-meta">
                        <span><span class="dashicons dashicons-groups"></span>
                            <?php
                            $subscribers_raw = get_field('subscribers');
                            $subscribers = 0;
                            if (!empty($subscribers_raw)) {
                                $subscribers_clean = preg_replace('/[^\d]/', '', strval($subscribers_raw));
                                $subscribers = intval($subscribers_clean);
                            }
                            echo esc_html(number_format($subscribers, 0, ',', '.')); 
                            ?> Subs
                        </span>
                        <?php if (get_field('monetization') === 'yes'): ?>
                            <span class="monetized" title="Monetized">$</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-price">
                        <?php
                        $price_raw = get_field('price');
                        $price = 0;
                        if (!empty($price_raw)) {
                            $price_clean = preg_replace('/[^\d]/', '', strval($price_raw));
                            $price = intval($price_clean);
                        }
                        echo number_format($price, 0, ',', '.'); 
                        ?> đ
                    </div>
                </div>
            </a>
            <?php
        }
    } else {
        echo '<p class="no-results">No channels found matching criteria.</p>';
    }

    wp_reset_postdata();
    die();
}
