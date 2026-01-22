<?php
/**
 * The archive template for Youtube Channel post type
 * Table Row Layout - Clean and Easy to Read
 */

get_header();
?>

<style>
.channel-archive-page {
    margin-top: 100px;
    padding: 20px 0;
}

.page-header-section {
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.page-commitment {
    color: #dc3545;
    font-weight: 600;
    font-size: 16px;
}

.top-filter-section {
    background: #f8f9fa;
    padding: 25px;
    margin: 30px 0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 25px 0;
    flex-wrap: wrap;
    gap: 20px;
}

.display-options, .search-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.display-options select, .search-box input {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.search-box input {
    min-width: 300px;
}

/* Table Styles */
.channels-table-wrapper {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin: 30px 0;
}

.channels-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.channels-table thead {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: #fff;
}

.channels-table th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    user-select: none;
    position: relative;
    vertical-align: top;
}

.channels-table th:hover {
    background: rgba(255,255,255,0.1);
}

.channels-table th .sort-indicator {
    margin-left: 8px;
    font-size: 12px;
    opacity: 0.8;
}

.channels-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
}

.channels-table tbody tr:hover {
    background-color: #f8f9fa;
}

.channels-table tbody tr:last-child {
    border-bottom: none;
}

.channels-table td {
    padding: 18px 20px;
    vertical-align: middle;
    font-size: 14px;
    color: #333;
}

.channels-table .subscribers-value {
    font-weight: 600;
    color: #333;
}

.channels-table .category-value {
    font-weight: 500;
    color: #555;
}

.channels-table .price-value {
    font-weight: 700;
    color: #dc3545;
    font-size: 16px;
}

.channels-table .status-value {
    font-size: 13px;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.monetized {
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

.channel-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-channel {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    white-space: nowrap;
}

.btn-view {
    background: #dc3545;
    color: #fff;
}

.btn-view:hover {
    background: #c82333;
    color: #fff;
}

.btn-copy {
    background: #28a745;
    color: #fff;
}

.btn-copy:hover {
    background: #218838;
    color: #fff;
}

.btn-order {
    background: #ffc107;
    color: #000;
}

.btn-order:hover {
    background: #e0a800;
    color: #000;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.no-results h3 {
    color: #666;
    font-size: 20px;
    margin-bottom: 10px;
}

.no-results p {
    color: #999;
    font-size: 14px;
}

/* Pagination Styles */
.pagination-wrapper {
    margin: 40px 0;
    display: flex;
    flex-direction: row;
    justify-content: center;
}

.pagination {
    display: flex;
    flex-direction: row;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
    align-items: center;
}

.pagination li {
    margin: 0;
    display: inline-block;
    flex-shrink: 0;
}

.pagination a,
.pagination span {
    display: block;
    padding: 10px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.pagination a:hover {
    background: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

.pagination .current {
    background: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

.pagination .prev,
.pagination .next {
    font-weight: 600;
}
.page-numbers li {
    display: inline-block !important;
}

@media (max-width: 768px) {
    .channels-table-wrapper {
        overflow-x: auto;
    }
    
    .channels-table {
        min-width: 800px;
    }
    
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box input {
        min-width: 100%;
    }
    
    .channel-actions {
        flex-direction: column;
    }
    
    .btn-channel {
        width: 100%;
    }
}
</style>

<div class="container channel-archive-page">
    <!-- Page Title and Commitment Section -->
    <div class="page-header-section">
        <h1 class="page-title">Mua bán kênh Youtube uy tín - Bảng giá & danh sách kênh Youtube</h1>
        <p class="page-commitment">
            Bảo hành bảo mật kênh vĩnh viễn. 
            Cam kết 100% subscribe thật. 
            Liên hệ Zalo: 0899.707,888 để gửi thêm các kênh khác
        </p>
    </div>

    <!-- Top Filter Section -->
    <section class="top-filter-section">
        <form id="top-filter-form">
            <div class="filter-group">
                <label>Giá (VND):</label>
                <div>
                    <div>
                        <label>Từ</label>
                        <input type="number" name="price_from" value="0" min="0">
                    </div>
                    <div>
                        <label>Đến</label>
                        <input type="number" name="price_to" value="0" min="0">
                    </div>
                </div>
            </div>
            <div class="filter-group">
                <label>Lượng subscribers:</label>
                <div>
                    <div>
                        <label>Từ</label>
                        <input type="number" name="subscribers_from" value="0" min="0">
                    </div>
                    <div>
                        <label>Đến</label>
                        <input type="number" name="subscribers_to" value="0" min="0">
                    </div>
                </div>
            </div>
            <div class="filter-buttons">
                <button type="button" id="reset-filter" class="btn btn-warning">Hủy bỏ</button>
                <button type="submit" class="btn btn-success">Lọc</button>
            </div>
        </form>
    </section>

    <!-- Display Options and Search -->
    <div class="filter-controls">
        <div class="display-options">
            <span>Hiển thị</span>
            <select id="per-page">
                <?php
                $current_per_page = isset($_GET['posts_per_page']) ? intval($_GET['posts_per_page']) : 25;
                ?>
                <option value="10" <?php selected($current_per_page, 10); ?>>10</option>
                <option value="25" <?php selected($current_per_page, 25); ?>>25</option>
                <option value="50" <?php selected($current_per_page, 50); ?>>50</option>
                <option value="100" <?php selected($current_per_page, 100); ?>>100</option>
            </select>
            <span>kênh</span>
        </div>
        <div class="search-controls">
            <label>Tìm kiếm:</label>
            <input type="search" id="channel-search" placeholder="Tìm kiếm kênh, chủ đề...">
        </div>
    </div>

    <!-- Channels Table -->
    <div class="channels-table-wrapper">
        <table class="channels-table">
            <thead>
                <tr>
                    <th>
                        Lượng subscribers
                        <span class="sort-indicator" data-sort="subscribers">↕</span>
                    </th>
                    <th>
                        Link Kênh
                        <span class="sort-indicator" data-sort="link">↕</span>
                    </th>
                    <th>
                        Chủ Đề
                        <span class="sort-indicator" data-sort="category">↕</span>
                    </th>
                    <th>
                        Giá bán (VND)
                        <span class="sort-indicator" data-sort="price">↕</span>
                    </th>
                    <th>
                        Tình trạng kênh
                        <span class="sort-indicator" data-sort="status">↕</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        
                        $subscribers = function_exists('get_field') ? get_field('subscribers') : get_post_meta(get_the_ID(), 'subscribers', true);
                        // Ensure subscribers is a number
                        $subscribers_num = 0;
                        if (!empty($subscribers)) {
                            // Remove any non-numeric characters and convert to integer
                            $subscribers_clean = preg_replace('/[^\d]/', '', strval($subscribers));
                            $subscribers_num = intval($subscribers_clean);
                        }
                        
                        $video_url = function_exists('get_field') ? get_field('video_url') : get_post_meta(get_the_ID(), 'video_url', true);
                        $video_url = $video_url ? $video_url : '#';
                        
                        $price = function_exists('get_field') ? get_field('price') : get_post_meta(get_the_ID(), 'price', true);
                        // Ensure price is a number
                        $price_num = 0;
                        if (!empty($price)) {
                            // Remove any non-numeric characters and convert to integer
                            $price_clean = preg_replace('/[^\d]/', '', strval($price));
                            $price_num = intval($price_clean);
                        }
                        $price_formatted = number_format($price_num, 0, ',', '.');
                        
                        $monetization = function_exists('get_field') ? get_field('monetization') : get_post_meta(get_the_ID(), 'monetization', true);
                        
                        // Determine status text and class from monetization field
                        // Monetization field can be: 'yes', 'no', 'Đã bật kiếm tiền', 'Chưa bật kiếm tiền', 'Tắt kiếm tiền'
                        $status_text = 'Chưa bật kiếm tiền';
                        $status_class = 'not-monetized';
                        
                        if (!empty($monetization)) {
                            $monetization_lower = strtolower(trim($monetization));
                            
                            // Check for "Chưa bật kiếm tiền" FIRST (before checking "bật kiếm tiền")
                            if (strpos($monetization, 'Chưa bật') !== false || 
                                $monetization_lower === 'no' ||
                                $monetization_lower === '0') {
                                $status_text = 'Chưa bật kiếm tiền';
                                $status_class = 'not-monetized';
                            } elseif (strpos($monetization, 'Đã bật') !== false || 
                                     strpos($monetization, 'bật kiếm tiền') !== false ||
                                     $monetization_lower === 'yes' ||
                                     $monetization_lower === '1') {
                                $status_text = 'Đã bật kiếm tiền';
                                $status_class = 'monetized';
                            } elseif (strpos($monetization, 'Tắt') !== false || 
                                     strpos($monetization, 'tắt kiếm tiền') !== false) {
                                $status_text = 'Tắt kiếm tiền';
                                $status_class = 'not-monetized';
                            } else {
                                // Use the value as-is if it's already Vietnamese text
                                $status_text = $monetization;
                            }
                        }
                        
                        // Override with channel status if sold
                        $channel_status = function_exists('get_field') ? get_field('status') : get_post_meta(get_the_ID(), 'status', true);
                        if ($channel_status === 'sold' || $channel_status === 'Đã bán') {
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
                        
                        if (!$order_page) {
                            $pages = get_pages(array(
                                'post_status' => 'publish',
                                'number' => 1,
                            ));
                            
                            foreach ($pages as $page) {
                                if (stripos($page->post_title, 'quy trình giao dịch') !== false) {
                                    $order_page = $page;
                                    break;
                                }
                            }
                        }
                        
                        $order_url = $order_page ? get_permalink($order_page->ID) : home_url('/quy-trinh-giao-dich-kenh-youtube');
                        ?>
                        <tr data-subscribers="<?php echo esc_attr($subscribers_num); ?>"
                            data-price="<?php echo esc_attr($price_num); ?>"
                            data-category="<?php echo esc_attr($category_name); ?>"
                            data-status="<?php echo esc_attr($status_text); ?>">
                            
                            <td class="subscribers-value">
                                <?php echo esc_html(number_format($subscribers_num, 0, ',', '.')); ?>
                            </td>
                            
                            <td class="channel-link-value">
                                <div class="channel-actions">
                                    <a href="<?php echo esc_url($video_url); ?>" target="_blank" class="btn-channel btn-view">Xem kênh</a>
                                    <button type="button" class="btn-channel btn-copy btn-copy-url" data-url="<?php echo esc_attr($video_url); ?>">Sao chép</button>
                                </div>
                            </td>
                            
                            <td class="category-value">
                                <?php echo esc_html($category_name); ?>
                            </td>
                            
                            <td class="price-value">
                                <div class="channel-price-wrapper">
                                    <span><?php echo esc_html($price_formatted); ?></span>
                                    <a href="<?php echo esc_url($order_url); ?>" target="_blank" class="btn-channel btn-order">Đặt mua</a>
                                </div>
                            </td>
                            
                            <td class="status-value">
                                <span class="status-badge <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_text); ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" class="no-results">
                            <h3>Không tìm thấy kênh nào</h3>
                            <p>Vui lòng thử lại với bộ lọc khác</p>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        <?php
        global $wp_query;
        if ($wp_query->max_num_pages > 1) {
            $big = 999999999;
            $pagination = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages,
                'prev_text' => '← Lùi',
                'next_text' => 'Tiếp →',
                'type' => 'list',
                'end_size' => 2,
                'mid_size' => 2,
            ));
            
            if ($pagination) {
                echo '<ul class="pagination">' . $pagination . '</ul>';
            }
        }
        ?>
    </div>

    <!-- Content Section from Page Editor -->
    <?php
    // Get the archive page content - try by ID 64 first (from admin URL)
    $archive_page = get_post(64);
    
    // If not found or not a page, try by slug
    if (!$archive_page || $archive_page->post_type !== 'page') {
        $archive_page = get_page_by_path('mua-kenh-youtube');
    }
    
    // If still not found, try to find page with matching slug/title
    if (!$archive_page) {
        $pages = get_pages(array(
            'post_status' => 'publish',
        ));
        foreach ($pages as $page) {
            if (stripos($page->post_name, 'mua-kenh') !== false || 
                stripos($page->post_title, 'danh sách kênh') !== false ||
                stripos($page->post_title, 'mua bán kênh') !== false) {
                $archive_page = $page;
                break;
            }
        }
    }
    
    if ($archive_page && !empty($archive_page->post_content)):
        ?>
        <section class="archive-content-section">
            <div class="tour-subtitle-wrapper wrapper-content">
                <?php echo apply_filters('the_content', $archive_page->post_content); ?>
            </div>
        </section>
        <?php
    endif;
    ?>
</div>


<?php
get_footer();
