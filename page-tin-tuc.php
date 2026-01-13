<?php
/* Template Name: Tin Tức */
get_header();
?>

<div class="page-wrapper page-tin-tuc">
    <?php /* Block hero */ ?>
    <div class="grid-hero" id="grid-hero" style="height: unset;">
        <div class="container">
            <div class="grid-hero-bg">
                <div class="row">
                    <div class="col-11 col-md-8 col-lg-6 col-lx-6">
                        <div class="">
                            <div class="grid-hero__content">
                                <div class="box-hero">
                                    <h2 class="title">TIN TỨC</h2>
                                    <h4 class="sub">YoutubeStore - Uy tín - Giá rẻ - Chất lượng</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block news list */ ?>
    <div class="grid-news">
        <div class="container">
            <div class="row">
                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 12,
                    'paged' => $paged,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $news_query = new WP_Query($args);
                
                if ($news_query->have_posts()) :
                    while ($news_query->have_posts()) : $news_query->the_post();
                        $categories = get_the_category();
                        $category_name = !empty($categories) ? $categories[0]->name : 'Chia sẻ kiến thức';
                        $excerpt = get_the_excerpt();
                        if (empty($excerpt)) {
                            $excerpt = wp_trim_words(get_the_content(), 20, '...');
                        }
                        ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <article class="news-card">
                                <a href="<?php the_permalink(); ?>" class="news-card__link">
                                    <div class="news-card__image-wrapper">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="news-card__image">
                                                <?php the_post_thumbnail('medium_large', array('class' => 'img-fluid', 'loading' => 'lazy')); ?>
                                            </div>
                                        <?php else : ?>
                                            <div class="news-card__image news-card__image--skeleton">
                                                <svg class="skeleton-image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 225" preserveAspectRatio="xMidYMid slice">
                                                    <rect width="400" height="225" fill="#e0e0e0"/>
                                                    <rect x="150" y="90" width="100" height="45" fill="#bdbdbd" rx="4"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="news-card__content">
                                        <span class="news-card__category"><?php echo esc_html($category_name); ?></span>
                                        <h3 class="news-card__title"><?php the_title(); ?></h3>
                                        <?php if (!empty($excerpt)) : ?>
                                            <p class="news-card__excerpt"><?php echo esc_html($excerpt); ?></p>
                                        <?php endif; ?>
                                        <div class="news-card__meta">
                                            <span class="news-card__date">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <?php echo get_the_date('d/m/Y'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        </div>
                    <?php
                    endwhile;
                    ?>
                    <div class="col-12">
                        <div class="pagination-wrapper">
                            <?php
                            $big = 999999999;
                            $pagination = paginate_links(array(
                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format' => '?paged=%#%',
                                'current' => max(1, $paged),
                                'total' => $news_query->max_num_pages,
                                'prev_text' => '← Lùi',
                                'next_text' => 'Tiếp →',
                                'type' => 'list',
                                'end_size' => 2,
                                'mid_size' => 2,
                            ));
                            
                            if ($pagination) {
                                echo '<ul class="pagination">' . $pagination . '</ul>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="col-12">
                        <p>Chưa có bài viết nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>

<?php get_footer(); ?>

