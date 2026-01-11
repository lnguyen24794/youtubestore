<?php
/**
 * Sidebar Template - Card Design
 * Used in page.php, single.php, archive.php
 */
?>

<style>
.sidebar-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.sidebar-card h4 {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #dc3545;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sidebar-breadcrumb {
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.sidebar-breadcrumb a {
    color: #dc3545;
    text-decoration: none;
}

.sidebar-breadcrumb a:hover {
    text-decoration: underline;
}

.sidebar-search {
    position: relative;
    margin-bottom: 25px;
}

.sidebar-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    opacity: 0.6;
}

.sidebar-search input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.sidebar-search input:focus {
    outline: none;
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.suggested-posts-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.suggested-post-card {
    display: flex;
    gap: 15px;
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.suggested-post-card:hover {
    background: #fff;
    border-color: #dc3545;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateX(5px);
}

.suggested-post-card:last-child {
    margin-bottom: 0;
}

.suggested-post-thumb {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    border-radius: 6px;
    overflow: hidden;
    background: #e9ecef;
}

.suggested-post-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.suggested-post-content {
    flex: 1;
    min-width: 0;
}

.suggested-post-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.suggested-post-excerpt {
    font-size: 12px;
    color: #666;
    line-height: 1.5;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<div class="col-md-4 tour-list-item">
    <div class="sidebar-card">
        <div class="sidebar-breadcrumb">
            <?php
            if (is_page() || is_single()) {
                echo '<a href="' . home_url('/') . '">HOME</a> / ';
                if (is_page()) {
                    echo '<span>' . get_the_title() . '</span>';
                } elseif (is_single()) {
                    echo '<span>' . get_the_title() . '</span>';
                }
            } elseif (is_archive()) {
                echo '<a href="' . home_url('/') . '">HOME</a> / ';
                if (is_category()) {
                    echo '<span>' . single_cat_title('', false) . '</span>';
                } elseif (is_tag()) {
                    echo '<span>' . single_tag_title('', false) . '</span>';
                } elseif (is_author()) {
                    echo '<span>' . get_the_author() . '</span>';
                } elseif (is_date()) {
                    echo '<span>' . get_the_date('F Y') . '</span>';
                } else {
                    echo '<span>' . get_the_archive_title() . '</span>';
                }
            }
            ?>
        </div>
    </div>

    <div class="sidebar-card">
        <h4><?php _e('Tìm kiếm', 'youtubestore'); ?></h4>
        <div class="sidebar-search">
            <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/icon-search.png" alt="icon-search" class="sidebar-search-icon" />
            <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
                <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="search" placeholder="<?php _e('Search now', 'youtubestore'); ?>" />
            </form>
        </div>
    </div>

    <div class="sidebar-card">
        <h4><?php _e('Gợi ý cho bạn', 'youtubestore'); ?></h4>
        <?php
        $exclude_ids = array();
        if (is_single()) {
            $exclude_ids[] = get_the_ID();
        }
        
        $suggested_posts = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'post__not_in' => $exclude_ids,
            'ignore_sticky_posts' => 1,
        ));

        if ($suggested_posts->have_posts()):
            ?>
            <ul class="suggested-posts-list">
                <?php
                while ($suggested_posts->have_posts()):
                    $suggested_posts->the_post();
                    ?>
                    <li>
                        <a href="<?php the_permalink(); ?>" class="suggested-post-card">
                            <div class="suggested-post-thumb">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('thumbnail', ['class' => 'suggested-post-thumb-img']); ?>
                                <?php else: ?>
                                    <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/no-image.png" class="suggested-post-thumb-img" alt="<?php the_title(); ?>" />
                                <?php endif; ?>
                            </div>
                            <div class="suggested-post-content">
                                <h6 class="suggested-post-title">
                                    <?php the_title(); ?>
                                </h6>
                                <p class="suggested-post-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </p>
                            </div>
                        </a>
                    </li>
                    <?php
                endwhile;
                ?>
            </ul>
            <?php
            wp_reset_postdata();
        else:
            ?>
            <p style="color: #999; font-size: 14px; margin: 0;"><?php _e('Chưa có bài viết nào', 'youtubestore'); ?></p>
            <?php
        endif;
        ?>
    </div>
</div>
