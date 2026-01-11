<?php
/* Template Name: Branding Design & POSM */
get_header();
?>

<div class="page-wrapper page-logo">
    <?php /* Block hero */ ?>
    <div class="grid-hero" id="grid-hero">
        <div class="container">
            <div class="grid-hero-bg">
                <div class="row">
                    <div class="col-11 col-md-8 col-lg-7 col-xl-6">
                        <div class="grid-hero__wrapp">
                            <div class="grid-hero__content">
                                <div class="box-hero">
                                    <h2 class="title" data-cms="<?php echo get_locale(); ?>-brand-index-9">LOGO DESIGN
                                        &amp; <br> BRAND IDENTITY</h2>
                                    <h4 class="sub" data-cms="<?php echo get_locale(); ?>-brand-index-10">BRANDING
                                        INCREASES YOUR VALUE</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block Logo block 1 */ ?>
    <div class="grid-logo-flex">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="grid-logo-flex__img">
                        <img class="img-fluid"
                            src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-grid-logo-flex.png"
                            alt="image logo" data-cms="<?php echo get_locale(); ?>-brand-index-16">
                    </div>
                </div>
                <div class="col-12 col-md-12 offset-lg-1 col-lg-5">
                    <div class="grid-logo-flex__content">
                        <div class="grid-head">
                            <h2 class="title" data-cms="<?php echo get_locale(); ?>-brand-index-20">BRANDING INCREASES
                                <br> YOUR VALUE</h2>
                        </div>
                        <ul class="support-list">
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <div class="text" data-cms="<?php echo get_locale(); ?>-brand-index-25">The number one
                                    thing to help you service in this serverely competitive world is a stand out brand
                                </div>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <div class="text" data-cms="<?php echo get_locale(); ?>-brand-index-29">The number one
                                    thing that your client remember about you is a stand out brand</div>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <div class="text" data-cms="<?php echo get_locale(); ?>-brand-index-33">The number one
                                    thing that your competitor couldn't copy from is a stand out brand</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block Logo block 2 */ ?>
    <div class="grid-logo-flex grid-logo-flex-2">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-5 col-lg-5">
                    <div class="grid-logo-flex__content">
                        <ul class="support-list">
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <div class="text" data-cms="<?php echo get_locale(); ?>-brand-index-43">YoutubeStore
                                    will raise your business to be even more professional with a stood out branding.
                                </div>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <div class="text" data-cms="<?php echo get_locale(); ?>-brand-index-47">We only deliver
                                    you high quality product because our designs have received many recognitions in the
                                    designing industry</div>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <div class="text" data-cms="<?php echo get_locale(); ?>-brand-index-51">We always think
                                    differently, that is why our products are unique and stood out among the rest</div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-6 offset-lg-1">
                    <div class="grid-logo-flex__content">
                        <ul class="grid-logo-flex__list">
                            <li data-cms="<?php echo get_locale(); ?>-brand-index-55">Unique and creative branding idea
                            </li>
                            <li data-cms="<?php echo get_locale(); ?>-brand-index-56">Unlimited revision</li>
                            <li data-cms="<?php echo get_locale(); ?>-brand-index-57">Young and professional designers
                                team</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block partner */ ?>
    <div class="grid-partner">
        <div class="container">
            <div class="grid-head">
                <h2 class="title" data-cms="<?php echo get_locale(); ?>-brand-index-61">These are the AWARDS that we
                    have received in many professional logo design contests</h2>
            </div>
            <div class="grid-content">
                <div class="row">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="col-6 col-md-3">
                            <div class="box-partner">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/partner-img-<?php echo $i; ?>.png"
                                    alt="partner <?php echo $i; ?>"
                                    data-cms="<?php echo get_locale(); ?>-brand-index-<?php echo 66 + ($i - 1) * 3; ?>">
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="grid-head">
                <div class="sapo" data-cms="<?php echo get_locale(); ?>-brand-index-77">We are proud of the quality and
                    the accomplishments of our designers. We believe that the logo design by YoutubeStore will aid your
                    first step to win the competitive market places</div>
            </div>
        </div>
    </div>

    <?php /* Block solution */ ?>
    <div class="grid-solution--logo">
        <div class="container">
            <div class="grid-head">
                <h2 class="title" data-cms="<?php echo get_locale(); ?>-brand-index-81">We will provide you <br> with
                    the solutions</h2>
            </div>
            <div class="grid-content">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="box-item-solution">
                            <div class="box-image">
                                <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-solution-logo-1.png"
                                    alt="images" data-cms="<?php echo get_locale(); ?>-brand-index-87">
                            </div>
                            <div class="box-item-solution__title">
                                <h3 class="box-title" data-cms="<?php echo get_locale(); ?>-brand-index-89">POSM Design
                                </h3>
                            </div>
                            <div class="box-content active">
                                <div class="box-sapo animated" data-cms="<?php echo get_locale(); ?>-brand-index-91">
                                    Logo, Business card, Brochure, Envelop, Bag, Office supplies and more.</div>
                                <a class="box-btn" onclick="solutionLink(this)" href="javascript:void(0)">
                                    <svg class="icon">
                                        <use xlink:href="#icon-expand-more"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="box-item-solution">
                            <div class="box-image">
                                <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-solution-logo-2.png"
                                    alt="images" data-cms="<?php echo get_locale(); ?>-brand-index-98">
                            </div>
                            <div class="box-item-solution__title">
                                <h3 class="box-title" data-cms="<?php echo get_locale(); ?>-brand-index-100">Design
                                    content and advertisment for your business campaigns</h3>
                            </div>
                            <div class="box-content">
                                <div class="box-sapo animated" data-cms="<?php echo get_locale(); ?>-brand-index-102">
                                    Logo, Business card, Brochure, Envelop, Bag, Office supplies and more.</div>
                                <a class="box-btn" onclick="solutionLink(this)" href="javascript:void(0)">
                                    <svg class="icon">
                                        <use xlink:href="#icon-expand-more"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="box-item-solution">
                            <div class="box-image">
                                <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-solution-logo-3.png"
                                    alt="images" data-cms="<?php echo get_locale(); ?>-brand-index-109">
                            </div>
                            <div class="box-item-solution__title">
                                <h3 class="box-title" data-cms="<?php echo get_locale(); ?>-brand-index-111">Designing
                                    business document - Promotional items</h3>
                            </div>
                            <div class="box-content">
                                <div class="box-sapo animated" data-cms="<?php echo get_locale(); ?>-brand-index-113">
                                    Logo, Business card, Brochure, Envelop, Bag, Office supplies and more.</div>
                                <a class="box-btn" onclick="solutionLink(this)" href="javascript:void(0)">
                                    <svg class="icon">
                                        <use xlink:href="#icon-expand-more"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="box-item-solution">
                            <div class="box-image">
                                <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-solution-logo-4.png"
                                    alt="images" data-cms="<?php echo get_locale(); ?>-brand-index-120">
                            </div>
                            <div class="box-item-solution__title">
                                <h3 class="box-title" data-cms="<?php echo get_locale(); ?>-brand-index-122">Packaging
                                    System Design, Stamp, Label</h3>
                            </div>
                            <div class="box-content">
                                <div class="box-sapo animated" data-cms="<?php echo get_locale(); ?>-brand-index-124">
                                    Logo, Business card, Brochure, Envelop, Bag, Office supplies and more.</div>
                                <a class="box-btn" onclick="solutionLink(this)" href="javascript:void(0)">
                                    <svg class="icon">
                                        <use xlink:href="#icon-expand-more"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block call */ ?>
    <div class="grid-call--logo">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="grid-content">
                        <div class="grid-call--logo__text">
                            <h4 data-cms="<?php echo get_locale(); ?>-brand-index-134">IF YOUR BUSINESS DID RUN VERY
                                WELL DURING THESE YEARS</h4>
                            <p data-cms="<?php echo get_locale(); ?>-brand-index-135">Let's take a step back and
                                renovate starting with a stunning and professional logo. Great visual will surely
                                attract more customers</p>
                        </div>
                        <ul class="grid-call--logo__list d-flex align-items-center">
                            <li>
                                <h3 class="title" data-cms="<?php echo get_locale(); ?>-brand-index-138">IT WILL TAKE
                                    JUST A <br> SECOND TO CALL US:</h3>
                            </li>
                            <li>
                                <div class="btn-normal">
                                    <a href="tel:<?php echo get_theme_mod('contact_phone', '09X.XXX.XXX'); ?>"
                                        title="<?php echo get_theme_mod('contact_phone', '09X.XXX.XXX'); ?>">
                                        <span class="btn-normal__title"
                                            data-cms="<?php echo get_locale(); ?>-brand-index-142">
                                            <?php echo get_theme_mod('contact_phone', '09X.XXX.XXX'); ?>
                                        </span>
                                        <svg class="icon">
                                            <use xlink:href="#icon-arrow"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="grid-head">
                        <h2 class="title" data-cms="<?php echo get_locale(); ?>-brand-index-146">AND USE THE REST OF
                            YOUR TIME TO FOCUS ON BUILDING A SUCESSFUL BUSINESS.</h2>
                    </div>
                </div>
                <div class="col-12 col-md-12 offset-lg-1 col-lg-4">
                    <div class="grid-call--logo__image">
                        <img class="img-fluid"
                            src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-call--logo.png" alt="image call"
                            data-cms="<?php echo get_locale(); ?>-brand-index-149">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>

<?php get_footer(); ?>