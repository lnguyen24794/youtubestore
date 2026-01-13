<?php
/**
 * Single YouTube Channel Template
 * Clean layout with sidebar
 */

get_header();
?>

<style>
.single-channel-page {
    margin-top: 100px;
    padding: 20px 0;
}

.channel-detail-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: #fff;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.channel-detail-header h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 15px 0;
    color: #fff;
}

.channel-header-meta {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.monetized,
.status-badge.available {
    background: #28a745;
    color: #fff;
}

.status-badge.not-monetized {
    background: #ffc107;
    color: #000;
}

.status-badge.sold {
    background: #6c757d;
    color: #fff;
}

.channel-video-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.channel-video-wrapper {
    position: relative;
    padding-top: 56.25%;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.channel-video-wrapper iframe,
.channel-video-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.youtube-facade {
    position: relative;
    cursor: pointer;
}

.play-button-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: rgba(220, 53, 69, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.play-button-overlay::after {
    content: '';
    width: 0;
    height: 0;
    border-left: 25px solid #fff;
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    margin-left: 5px;
}

.youtube-facade:hover .play-button-overlay {
    background: rgba(220, 53, 69, 1);
    transform: translate(-50%, -50%) scale(1.1);
}

.channel-info-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.channel-info-table {
    width: 100%;
    border-collapse: collapse;
}

.channel-info-table tr {
    border-bottom: 1px solid #f0f0f0;
}

.channel-info-table tr:last-child {
    border-bottom: none;
}

.channel-info-table td {
    padding: 15px 0;
    vertical-align: middle;
}

.channel-info-label {
    font-weight: 600;
    color: #666;
    width: 200px;
    font-size: 14px;
}

.channel-info-value {
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

.channel-info-value.price {
    font-size: 24px;
    color: #dc3545;
    font-weight: 700;
}

.channel-actions-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
}

.channel-action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-channel-action {
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-view-channel {
    background: #dc3545;
    color: #fff;
}

.btn-view-channel:hover {
    background: #c82333;
    color: #fff;
}

.btn-copy-link {
    background: #28a745;
    color: #fff;
}

.btn-copy-link:hover {
    background: #218838;
    color: #fff;
}

.btn-order-channel {
    background: #ffc107;
    color: #000;
    font-size: 18px;
    padding: 18px 40px;
}

.btn-order-channel:hover {
    background: #e0a800;
    color: #000;
}

.channel-content-section {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.channel-content-section h2 {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #dc3545;
}

.related-channels-section {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.related-channels-section h2 {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #dc3545;
}

.related-channels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.related-channel-card {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.related-channel-card:hover {
    background: #fff;
    border-color: #dc3545;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.related-channel-thumb {
    position: relative;
    padding-top: 56.25%;
    background: #e9ecef;
    overflow: hidden;
}

.related-channel-thumb img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-channel-body {
    padding: 15px;
}

.related-channel-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 10px 0;
    line-height: 1.4;
}

.related-channel-price {
    font-size: 18px;
    font-weight: 700;
    color: #dc3545;
}

@media (max-width: 768px) {
    .channel-action-buttons {
        flex-direction: column;
    }
    
    .btn-channel-action {
        width: 100%;
    }
    
    .related-channels-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-wrapper new-detail" id="editor">
    <section class="tour" id="grid-hero">
        <div class="container">
            <div class="row">
                <div class="col-md-8 tour-content">
                    <?php while (have_posts()): the_post(); ?>
                        
                        <?php
                        $video_url = function_exists('get_field') ? get_field('video_url') : get_post_meta(get_the_ID(), 'video_url', true);
                        
                        // Get subscribers and ensure it's a number
                        $subscribers_raw = function_exists('get_field') ? get_field('subscribers') : get_post_meta(get_the_ID(), 'subscribers', true);
                        $subscribers = 0;
                        if (!empty($subscribers_raw)) {
                            $subscribers_clean = preg_replace('/[^\d]/', '', strval($subscribers_raw));
                            $subscribers = intval($subscribers_clean);
                        }
                        
                        // Get price and ensure it's a number
                        $price_raw = function_exists('get_field') ? get_field('price') : get_post_meta(get_the_ID(), 'price', true);
                        $price = 0;
                        if (!empty($price_raw)) {
                            $price_clean = preg_replace('/[^\d]/', '', strval($price_raw));
                            $price = intval($price_clean);
                        }
                        
                        $monetization = function_exists('get_field') ? get_field('monetization') : get_post_meta(get_the_ID(), 'monetization', true);
                        $status = function_exists('get_field') ? get_field('status') : get_post_meta(get_the_ID(), 'status', true);
                        
                        // Extract video ID from URL
                        $video_id = '';
                        if ($video_url) {
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                                $video_id = $matches[1];
                            } elseif (preg_match('/youtube\.com\/channel\/([^\/]+)/', $video_url, $matches)) {
                                $channel_id = $matches[1];
                            } elseif (preg_match('/youtube\.com\/@([^\/]+)/', $video_url, $matches)) {
                                $channel_handle = $matches[1];
                            }
                        }
                        
                        // Status text and class
                        $status_text = 'Chưa bật kiếm tiền';
                        $status_class = 'not-monetized';
                        if ($monetization === 'yes' || $monetization === 'Đã bật kiếm tiền') {
                            $status_text = 'Đã bật kiếm tiền';
                            $status_class = 'monetized';
                        }
                        if ($status === 'sold' || $status === 'Đã bán') {
                            $status_text = 'Đã bán';
                            $status_class = 'sold';
                        }
                        
                        // Get categories
                        $categories = get_the_terms(get_the_ID(), 'category');
                        $category_name = 'N/A';
                        if ($categories && !is_wp_error($categories)) {
                            $category_name = $categories[0]->name;
                        }
                        
                        // Get order page URL
                        $order_page = null;
                        $possible_slugs = array(
                            'quy-trinh-giao-dich-kenh-youtube',
                            'quy-trinh-giao-dich-kenh-youtube-day-ban-nhe'
                        );
                        
                        foreach ($possible_slugs as $slug) {
                            $order_page = get_page_by_path($slug);
                            if ($order_page) {
                                break;
                            }
                        }
                        
                        $order_url = $order_page ? get_permalink($order_page->ID) : home_url('/quy-trinh-giao-dich-kenh-youtube');
                        ?>
                        
                        <!-- Channel Header -->
                        <div class="channel-detail-header">
                            <h1><?php the_title(); ?></h1>
                            <div class="channel-header-meta">
                                <span class="status-badge <?php echo esc_attr($status_class); ?>">
                                    <?php echo esc_html($status_text); ?>
                                </span>
                                <?php if ($category_name !== 'N/A'): ?>
                                    <span style="font-size: 14px; opacity: 0.9;">Chủ đề: <?php echo esc_html($category_name); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Video Section -->
                        <?php if ($video_url): ?>
                            <div class="channel-video-section">
                                <div class="channel-video-wrapper">
                                    <?php if ($video_id): ?>
                                        <div class="youtube-facade" data-id="<?php echo esc_attr($video_id); ?>">
                                            <img src="https://img.youtube.com/vi/<?php echo esc_attr($video_id); ?>/maxresdefault.jpg" alt="Video Thumbnail" loading="lazy">
                                            <div class="play-button-overlay"></div>
                                        </div>
                                    <?php else: ?>
                                        <iframe src="<?php echo esc_url($video_url); ?>" frameborder="0" allowfullscreen></iframe>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Channel Info Table -->
                        <div class="channel-info-section">
                            <table class="channel-info-table">
                                <tr>
                                    <td class="channel-info-label">Lượng subscribers:</td>
                                    <td class="channel-info-value"><?php echo esc_html(number_format($subscribers, 0, ',', '.')); ?></td>
                                </tr>
                                <tr>
                                    <td class="channel-info-label">Giá bán:</td>
                                    <td class="channel-info-value price"><?php echo esc_html(number_format($price, 0, ',', '.')); ?> VND</td>
                                </tr>
                                <tr>
                                    <td class="channel-info-label">Tình trạng kiếm tiền:</td>
                                    <td class="channel-info-value"><?php echo esc_html($status_text); ?></td>
                                </tr>
                                <tr>
                                    <td class="channel-info-label">Chủ đề:</td>
                                    <td class="channel-info-value"><?php echo esc_html($category_name); ?></td>
                                </tr>
                                <?php if ($video_url): ?>
                                <tr>
                                    <td class="channel-info-label">Link kênh:</td>
                                    <td class="channel-info-value">
                                        <a href="<?php echo esc_url($video_url); ?>" target="_blank" style="color: #dc3545; text-decoration: none; word-break: break-all;">
                                            <?php echo esc_html($video_url); ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="channel-actions-section">
                            <div class="channel-action-buttons">
                                <?php if ($video_url): ?>
                                    <a href="<?php echo esc_url($video_url); ?>" target="_blank" class="btn-channel-action btn-view-channel">
                                        Xem kênh YouTube
                                    </a>
                                    <button type="button" class="btn-channel-action btn-copy-link" data-url="<?php echo esc_attr($video_url); ?>">
                                        Sao chép link
                                    </button>
                                <?php endif; ?>
                                <a href="<?php echo esc_url($order_url); ?>" target="_blank" class="btn-channel-action btn-order-channel">
                                    Đặt mua kênh này
                                </a>
                            </div>
                        </div>
                        
                        <!-- Content Section -->
                        <?php if (get_the_content()): ?>
                            <div class="channel-content-section">
                                <h2>Thông tin chi tiết</h2>
                                <div class="tour-subtitle-wrapper wrapper-content">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Related Channels -->
                        <?php
                        $related_args = array(
                            'post_type' => 'youtube_channel',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand'
                        );
                        
                        // Try to get related by category
                        if ($categories && !is_wp_error($categories)) {
                            $related_args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'category',
                                    'field' => 'term_id',
                                    'terms' => $categories[0]->term_id,
                                )
                            );
                        }
                        
                        $related = new WP_Query($related_args);
                        if ($related->have_posts()):
                            ?>
                            <div class="related-channels-section">
                                <h2>Kênh tương tự</h2>
                                <div class="related-channels-grid">
                                    <?php
                                    while ($related->have_posts()):
                                        $related->the_post();
                                        $related_price_raw = function_exists('get_field') ? get_field('price') : get_post_meta(get_the_ID(), 'price', true);
                                        $related_price = 0;
                                        if (!empty($related_price_raw)) {
                                            $related_price_clean = preg_replace('/[^\d]/', '', strval($related_price_raw));
                                            $related_price = intval($related_price_clean);
                                        }
                                        ?>
                                        <a href="<?php the_permalink(); ?>" class="related-channel-card">
                                            <div class="related-channel-thumb">
                                                <?php if (has_post_thumbnail()): ?>
                                                    <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                                                <?php else: ?>
                                                    <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/no-image.png" alt="<?php the_title(); ?>" />
                                                <?php endif; ?>
                                            </div>
                                            <div class="related-channel-body">
                                                <h3 class="related-channel-title"><?php the_title(); ?></h3>
                                                <div class="related-channel-price">
                                                    <?php echo number_format($related_price, 0, ',', '.'); ?> VND
                                                </div>
                                            </div>
                                        </a>
                                        <?php
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>
                        
                    <?php endwhile; ?>
                </div>

                <?php get_template_part('template-parts/sidebar'); ?>
            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/consultation'); ?>
</div>

<script>
jQuery(document).ready(function($) {
    // YouTube video facade click handler
    $('.youtube-facade').on('click', function() {
        var videoId = $(this).data('id');
        if (videoId) {
            var iframe = $('<iframe>', {
                src: 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0',
                frameborder: '0',
                allow: 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
                allowfullscreen: true
            });
            $(this).html(iframe);
        }
    });
    
    // Copy link functionality
    $('.btn-copy-link').on('click', function() {
        var url = $(this).data('url');
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(url).select();
        document.execCommand('copy');
        $temp.remove();
        
        var $btn = $(this);
        var originalText = $btn.text();
        $btn.text('Đã sao chép!');
        setTimeout(function() {
            $btn.text(originalText);
        }, 2000);
    });
});
</script>

<?php
get_footer();
