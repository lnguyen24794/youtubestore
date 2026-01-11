<?php
/**
 * Archive Template - Table Layout
 * For posts, categories, tags, etc.
 */

get_header();
?>

<style>
.archive-page {
    margin-top: 100px;
    padding: 20px 0;
}

.archive-header {
    margin-bottom: 30px;
}

.archive-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.archive-description {
    font-size: 16px;
    color: #666;
    line-height: 1.6;
}

.posts-table-wrapper {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin: 30px 0;
}

.posts-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.posts-table thead {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: #fff;
}

.posts-table th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    user-select: none;
    position: relative;
}

.posts-table th:hover {
    background: rgba(255,255,255,0.1);
}

.posts-table th .sort-indicator {
    margin-left: 8px;
    font-size: 12px;
    opacity: 0.8;
}

.posts-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
}

.posts-table tbody tr:hover {
    background-color: #f8f9fa;
}

.posts-table tbody tr:last-child {
    border-bottom: none;
}

.posts-table td {
    padding: 18px 20px;
    vertical-align: middle;
    font-size: 14px;
    color: #333;
}

.post-title-cell {
    font-weight: 600;
    font-size: 16px;
}

.post-title-cell a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-title-cell a:hover {
    color: #dc3545;
}

.post-excerpt-cell {
    color: #666;
    line-height: 1.6;
    max-width: 400px;
}

.post-date-cell {
    color: #999;
    font-size: 13px;
    white-space: nowrap;
}

.post-category-cell {
    font-size: 13px;
}

.category-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #f0f0f0;
    color: #333;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-badge:hover {
    background: #dc3545;
    color: #fff;
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
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li {
    margin: 0;
    display: inline-block !important;
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

@media (max-width: 768px) {
    .posts-table-wrapper {
        overflow-x: auto;
    }
    
    .posts-table {
        min-width: 800px;
    }
}
</style>

<div class="container archive-page">
    <div class="row">
        <div class="col-md-8">
            <!-- Archive Header -->
            <div class="archive-header">
                <h1 class="archive-title">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        echo 'Bài viết của: ' . get_the_author();
                    } elseif (is_date()) {
                        if (is_day()) {
                            echo 'Ngày: ' . get_the_date();
                        } elseif (is_month()) {
                            echo 'Tháng: ' . get_the_date('F Y');
                        } elseif (is_year()) {
                            echo 'Năm: ' . get_the_date('Y');
                        }
                    } else {
                        echo 'Tin Tức';
                    }
                    ?>
                </h1>
                <?php if (is_category() || is_tag()): ?>
                    <div class="archive-description">
                        <?php echo term_description(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Posts Table -->
            <div class="posts-table-wrapper">
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>
                                Tiêu đề
                                <span class="sort-indicator" data-sort="title">↕</span>
                            </th>
                            <th>
                                Mô tả
                                <span class="sort-indicator" data-sort="excerpt">↕</span>
                            </th>
                            <th>
                                Danh mục
                                <span class="sort-indicator" data-sort="category">↕</span>
                            </th>
                            <th>
                                Ngày đăng
                                <span class="sort-indicator" data-sort="date">↕</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                
                                $categories = get_the_category();
                                $category_name = 'N/A';
                                $category_link = '#';
                                if ($categories && !is_wp_error($categories)) {
                                    $category_name = $categories[0]->name;
                                    $category_link = get_category_link($categories[0]->term_id);
                                }
                                ?>
                                <tr data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>"
                                    data-date="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"
                                    data-category="<?php echo esc_attr(strtolower($category_name)); ?>">
                                    
                                    <td class="post-title-cell">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </td>
                                    
                                    <td class="post-excerpt-cell">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                    </td>
                                    
                                    <td class="post-category-cell">
                                        <a href="<?php echo esc_url($category_link); ?>" class="category-badge">
                                            <?php echo esc_html($category_name); ?>
                                        </a>
                                    </td>
                                    
                                    <td class="post-date-cell">
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="no-results">
                                    <h3>Không tìm thấy bài viết nào</h3>
                                    <p>Vui lòng thử lại với điều kiện tìm kiếm khác</p>
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
        </div>

        <?php get_template_part('template-parts/sidebar'); ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var currentSort = { field: null, direction: 'asc' };
    
    // Sort functionality
    $('.posts-table th').on('click', function() {
        var sortField = $(this).find('.sort-indicator').data('sort');
        if (!sortField) return;
        
        if (currentSort.field === sortField) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.field = sortField;
            currentSort.direction = 'asc';
        }
        
        // Update sort indicators
        $('.posts-table th .sort-indicator').text('↕');
        var indicator = currentSort.direction === 'asc' ? '↑' : '↓';
        $(this).find('.sort-indicator').text(indicator);
        
        // Sort rows
        var $rows = $('.posts-table tbody tr:visible').toArray();
        
        $rows.sort(function(a, b) {
            var $a = $(a);
            var $b = $(b);
            var valA, valB;
            
            switch(sortField) {
                case 'title':
                    valA = $a.data('title');
                    valB = $b.data('title');
                    break;
                case 'date':
                    valA = $a.data('date');
                    valB = $b.data('date');
                    break;
                case 'category':
                    valA = $a.data('category');
                    valB = $b.data('category');
                    break;
                default:
                    return 0;
            }
            
            if (valA < valB) return currentSort.direction === 'asc' ? -1 : 1;
            if (valA > valB) return currentSort.direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Re-append sorted rows
        var $tbody = $('.posts-table tbody');
        $tbody.empty();
        $.each($rows, function(index, row) {
            $tbody.append(row);
        });
    });
});
</script>

<?php
get_footer();
