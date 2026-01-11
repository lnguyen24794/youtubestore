<?php
/* Template Name: Chính Sách Thanh Toán */
get_header();
?>

<div class="page-wrapper new-detail" id="editor">
    <?php /* Block hero */ ?>
    <section class="tour" id="grid-hero">
        <div class="container">
            <div class="row">
                <div class="col-md-8 tour-content">
                    <?php while (have_posts()):
                        the_post(); ?>
                        <h1>
                            <?php the_title(); ?>
                        </h1>

                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('full', ['class' => 'tour-image']); ?>
                        <?php endif; ?>

                        <div class="tour-subtitle-wrapper wrapper-content">
                            <?php the_content(); ?>
                        </div>

                        <div class="tour-contact-wrapper">
                            <div class="btn-normal" style="margin-top: 10px;">
                                <a style="background: #4F4F4F; border-radius: 24px;"
                                    href="<?php echo home_url('/lien-he'); ?>">
                                    <span style="color: white !important; visibility: visible;" class="btn-normal__title">
                                        Liên Hệ
                                    </span>
                                    <svg style="color: white !important" class="icon">
                                        <use xlink:href="#icon-arrow"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>

<?php get_footer(); ?>
