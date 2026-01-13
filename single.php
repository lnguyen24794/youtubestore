<?php get_header(); ?>

<div class="page-wrapper new-detail" id="editor">
    <?php /* Block hero */ ?>
    <section class="tour" id="grid-hero">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-12">
                    <?php while (have_posts()):
                        the_post(); ?>
                        <h1 class="single-post-title">
                            <?php the_title(); ?>
                        </h1>
                        
                        <div class="tour-subtitle-wrapper wrapper-content">
                            <?php the_content(); ?>
                        </div>

                        <div>
                            <!-- Google Ads Placeholder -->
                            <ins class="adsbygoogle adsbygoogle-block"
                                data-ad-client="ca-pub-4885114851785989" data-ad-slot="6830795445" data-ad-format="auto"
                                data-full-width-responsive="true"></ins>
                        </div>

                        <div class="tour-contact-wrapper">
                            <div class="btn-normal">
                                <a href="<?php echo home_url('/lien-he'); ?>">
                                    <span class="btn-normal__title">
                                        Liên Hệ
                                    </span>
                                    <svg class="icon">
                                        <use xlink:href="#icon-arrow"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php get_template_part('template-parts/sidebar'); ?>
            </div>
        </div>
    </section>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>

<?php get_footer(); ?>