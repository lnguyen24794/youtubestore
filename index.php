<?php get_header(); ?>
<?php
// Inline styles from legacy blade
// .page-wrapper, .grid-hero, .grid-hero__wrapp { height: auto !important; }
// I'll add a style block here for now, or this should go to style.css. 
// Given the legacy code had it in a section pushed to head, I'll print it.
?>
<style>
    .page-wrapper.page-news,
    .page-wrapper.page-news .grid-hero,
    .page-wrapper.page-news .grid-hero__wrapp {
        height: auto !important;
    }
</style>

<div class="page-wrapper page-news">
    <?php /* Block hero */ ?>
    <div class="grid-hero" id="grid-hero">
        <div class="container">
            <div class="grid-hero-bg">
                <div class="row">
                    <div class="col-11 col-md-8 col-lg-6 col-lx-6">
                        <div class="grid-hero__wrapp">
                            <div class="grid-hero__content">
                                <div class="box-hero">
                                    <h2 class="title" data-cms="<?php echo get_locale(); ?>-news-index-9">NEWS</h2>
                                    <h4 class="sub" data-cms="<?php echo get_locale(); ?>-news-index-10">Our thoughts on
                                        creativity, digital, and branding.</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-news">
        <div class="container">
            <div class="masonry">
                <?php if (have_posts()): ?>
                    <?php while (have_posts()):
                        the_post(); ?>
                        <div class="box-news">
                            <div class="box-images">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                                    <?php else: ?>
                                        <img class="img-fluid" src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/no-image.png"
                                            alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="box-content">
                                <h4 class="box-subtitle">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" title="' . esc_attr($categories[0]->name) . '">' . esc_html($categories[0]->name) . '</a>';
                                    }
                                    ?>
                                </h4>
                                <h4 class="box-title" style="font-size:1.3rem">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                </h4>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p><?php _e('Sorry, no posts matched your criteria.', 'youtubestore'); ?></p>
                <?php endif; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'youtubestore'),
                'next_text' => __('Next', 'youtubestore'),
            ));
            ?>
        </div>
    </div>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>
<?php get_footer(); ?>