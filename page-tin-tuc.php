<?php
/* Template Name: Tin Tức */
get_header();
?>

<div class="page-wrapper page-tin-tuc">
    <?php /* Block hero */ ?>
    <div class="grid-hero" id="grid-hero">
        <div class="container">
            <div class="grid-hero-bg">
                <div class="row">
                    <div class="col-11 col-md-8 col-lg-6 col-lx-6">
                        <div class="grid-hero__wrapp">
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
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <article class="news-item">
                                <a href="<?php the_permalink(); ?>" class="news-item__link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="news-item__image">
                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="news-item__content">
                                        <h4 class="news-item__category"><?php echo esc_html($category_name); ?></h4>
                                        <h3 class="news-item__title"><?php the_title(); ?></h3>
                                    </div>
                                </a>
                            </article>
                        </div>
                    <?php
                    endwhile;
                    ?>
                    <div class="col-12">
                        <div class="pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $news_query->max_num_pages,
                                'prev_text' => '&laquo; Trang trước',
                                'next_text' => 'Trang sau &raquo;',
                            ));
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

