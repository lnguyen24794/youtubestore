<?php
/**
 * Admin Page for Normalizing Numeric Data
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_Normalize_Data_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_normalize_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_youtubestore_normalize_data', array($this, 'ajax_normalize_data'));
    }

    public function add_normalize_page()
    {
        add_submenu_page(
            'tools.php',
            'Normalize Channel Data',
            'Normalize Channel Data',
            'manage_options',
            'youtubestore-normalize-data',
            array($this, 'render_normalize_page')
        );
    }

    public function enqueue_scripts($hook)
    {
        if ('tools_page_youtubestore-normalize-data' !== $hook) {
            return;
        }
        wp_enqueue_script('jquery');
    }

    public function render_normalize_page()
    {
        ?>
        <div class="wrap">
            <h1>Normalize Channel Data</h1>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>📊 Normalize Subscribers & Price Data</h2>
                <p>This tool will convert all subscribers and price values from string format to numeric format.</p>
                <p><strong>What it does:</strong></p>
                <ul>
                    <li>Converts subscribers values (e.g., "18.000", "2.800.000") to integers (18000, 2800000)</li>
                    <li>Converts price values (e.g., "10.000.000", "222000000") to integers</li>
                    <li>Updates both ACF fields and post meta</li>
                    <li>This will fix sorting issues on the archive page</li>
                </ul>
                <p><strong>Note:</strong> This process may take a few minutes if you have many channels.</p>
                <p>
                    <button type="button" id="normalize-data-btn" class="button button-primary button-large">Normalize All Channel Data</button>
                </p>
                <div id="normalize-results" style="margin-top: 20px;"></div>
            </div>
        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#normalize-data-btn').on('click', function() {
                var $button = $(this);
                var $results = $('#normalize-results');
                
                if (!confirm('Bạn có chắc chắn muốn normalize tất cả dữ liệu? Quá trình này có thể mất vài phút.')) {
                    return;
                }

                $button.prop('disabled', true).text('Đang xử lý...');
                $results.html('<div class="notice notice-info"><p><strong>Đang xử lý...</strong> Vui lòng đợi, quá trình này có thể mất vài phút.</p></div>');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'youtubestore_normalize_data',
                        nonce: '<?php echo wp_create_nonce('youtubestore_normalize_data'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = '<div class="notice notice-success"><p><strong>✅ Normalize hoàn tất!</strong></p>';
                            html += '<ul>';
                            html += '<li><strong>Total channels processed:</strong> ' + (response.data.total || 0) + '</li>';
                            html += '<li><strong>Subscribers updated:</strong> ' + (response.data.subscribers_updated || 0) + '</li>';
                            html += '<li><strong>Price updated:</strong> ' + (response.data.price_updated || 0) + '</li>';
                            html += '</ul>';
                            html += '</div>';
                            $results.html(html);
                        } else {
                            $results.html('<div class="notice notice-error"><p><strong>❌ Normalize thất bại:</strong> ' + (response.data || 'Unknown error') + '</p></div>');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var errorMsg = textStatus + ': ' + errorThrown;
                        if (jqXHR.responseText) {
                            errorMsg += '<br>' + jqXHR.responseText;
                        }
                        $results.html('<div class="notice notice-error"><p><strong>❌ Normalize thất bại:</strong> ' + errorMsg + '</p></div>');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('Normalize All Channel Data');
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function ajax_normalize_data()
    {
        check_ajax_referer('youtubestore_normalize_data', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        // Increase execution time
        @set_time_limit(300);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '256M');

        $args = array(
            'post_type' => 'youtube_channel',
            'posts_per_page' => -1,
            'post_status' => 'any',
        );

        $query = new WP_Query($args);
        $total = 0;
        $subscribers_updated = 0;
        $price_updated = 0;

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $total++;

                // Normalize subscribers
                $subscribers = get_post_meta($post_id, 'subscribers', true);
                $subscribers_was_updated = false;
                
                if (!empty($subscribers)) {
                    // Check if it's already a number
                    if (!is_numeric($subscribers)) {
                        // Remove dots, commas, spaces, and extract number
                        $subscribers_clean = preg_replace('/[^\d]/', '', strval($subscribers));
                        $subscribers_num = intval($subscribers_clean);
                        
                        update_post_meta($post_id, 'subscribers', $subscribers_num);
                        if (function_exists('update_field')) {
                            update_field('subscribers', $subscribers_num, $post_id);
                        }
                        $subscribers_updated++;
                        $subscribers_was_updated = true;
                    } elseif (is_float($subscribers)) {
                        // Convert float to int
                        $subscribers_num = intval($subscribers);
                        update_post_meta($post_id, 'subscribers', $subscribers_num);
                        if (function_exists('update_field')) {
                            update_field('subscribers', $subscribers_num, $post_id);
                        }
                        $subscribers_updated++;
                        $subscribers_was_updated = true;
                    }
                } else {
                    // Set to 0 if empty
                    update_post_meta($post_id, 'subscribers', 0);
                    if (function_exists('update_field')) {
                        update_field('subscribers', 0, $post_id);
                    }
                }

                // Normalize price
                $price = get_post_meta($post_id, 'price', true);
                
                if (!empty($price)) {
                    // Check if it's already a number
                    if (!is_numeric($price)) {
                        // Remove dots, commas, spaces, and extract number
                        $price_clean = preg_replace('/[^\d]/', '', strval($price));
                        $price_num = intval($price_clean);
                        
                        update_post_meta($post_id, 'price', $price_num);
                        if (function_exists('update_field')) {
                            update_field('price', $price_num, $post_id);
                        }
                        $price_updated++;
                    } elseif (is_float($price)) {
                        // Convert float to int
                        $price_num = intval($price);
                        update_post_meta($post_id, 'price', $price_num);
                        if (function_exists('update_field')) {
                            update_field('price', $price_num, $post_id);
                        }
                        $price_updated++;
                    }
                } else {
                    // Set to 0 if empty
                    update_post_meta($post_id, 'price', 0);
                    if (function_exists('update_field')) {
                        update_field('price', 0, $post_id);
                    }
                }
            }
            wp_reset_postdata();
        }

        wp_send_json_success(array(
            'total' => $total,
            'subscribers_updated' => $subscribers_updated,
            'price_updated' => $price_updated,
        ));
    }
}

if (is_admin()) {
    new YoutubeStore_Normalize_Data_Admin();
}
