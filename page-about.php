<?php
/* Template Name: About Us */
get_header();
?>

<div class="page-wrapper page-about">
    <?php /* Block hero */ ?>
    <div class="grid-hero" id="grid-hero">
        <div class="container">
            <div class="grid-hero-bg">
                <div class="row">
                    <div class="col-11 col-md-8 col-lg-6 col-lx-6">
                        <div class="grid-hero__wrapp">
                            <div class="grid-hero__content">
                                <div class="box-hero">
                                    <h4 class="sub" data-cms="<?php echo get_locale(); ?>-about-index-9">ABOUT OUR
                                        COMPANY</h4>
                                    <h2 class="title"><span
                                            data-cms="<?php echo get_locale(); ?>-about-index-11">Branding is <br>
                                            the</span> <span class="hero" style="text-decoration-line: none;"
                                            data-cms="<?php echo get_locale(); ?>-about-index-12">KEY</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block about */ ?>
    <div class="grid-about grid-style--arrowdown">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3 col-lx-3">
                    <div class="grid-about__head">
                        <h3 class="grid-about__title">
                            <span class="hero"><span class="hero-text"
                                    data-cms="<?php echo get_locale(); ?>-about-index-20">A</span></span>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-21">PROFESSIONAL MARKETING SOLUTION
                                LEVERAGE YOUR BUSINIESS TO A WHOLE NEW LEVEL</span>
                        </h3>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-9 col-lx-9">
                    <div class="grid-about__content">
                        <div class="row">
                            <?php
                            $about_images = range(1, 8);
                            foreach ($about_images as $i):
                                ?>
                                <div class="col-6 col-md-3">
                                    <div class="grid-about__item">
                                        <img class="img-fluid"
                                            src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-about-<?php echo $i; ?>.jpg"
                                            alt="about <?php echo $i; ?>"
                                            data-cms="<?php echo get_locale(); ?>-about-index-<?php echo 27 + ($i - 1) * 3; ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block about 2 */ ?>
    <div class="grid-about-me">
        <div class="container">
            <div class="grid-head">
                <h2 class="title" data-cms="<?php echo get_locale(); ?>-about-index-52">We are YoutubeStore</h2>
            </div>
            <div class="grid-content">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                        <div class="grid-about-me__item">
                            <div class="grid-about-me__img">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/about-me-img-1.png"
                                    alt="about me image 1" data-cms="<?php echo get_locale(); ?>-about-index-58">
                            </div>
                            <div class="grid-about-me__content">
                                <h3 class="grid-about-me__title"><span
                                        data-cms="<?php echo get_locale(); ?>-about-index-61">Vision</span></h3>
                                <div class="grid-about-me__sapo" data-cms="<?php echo get_locale(); ?>-about-index-62">
                                    With over 10 years of experience and passionate and young developers with high
                                    proficiency, We always try our best to improve and innovate our already perfect
                                    workflows in order to deliver the best products to even the most difficult clients
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                        <div class="grid-about-me__item grid-about-me__item-2">
                            <div class="grid-about-me__img">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/about-me-img-2.png"
                                    alt="about me image 2" data-cms="<?php echo get_locale(); ?>-about-index-66">
                            </div>
                            <div class="grid-about-me__content">
                                <h3 class="grid-about-me__title"><span
                                        data-cms="<?php echo get_locale(); ?>-about-index-69">Mission</span></h3>
                                <div class="grid-about-me__sapo" data-cms="<?php echo get_locale(); ?>-about-index-70">
                                    Our mission is to never stop delivering the best product to our clients. With the
                                    ever changing in the digital marketing, we promises that you will get the most
                                    suitable solution for your business.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                        <div class="grid-about-me__item grid-about-me__item-3">
                            <div class="grid-about-me__img">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/about-me-img-3.png"
                                    alt="about me image 3" data-cms="<?php echo get_locale(); ?>-about-index-74">
                            </div>
                            <div class="grid-about-me__content">
                                <h3 class="grid-about-me__title"><span
                                        data-cms="<?php echo get_locale(); ?>-about-index-77">Value</span></h3>
                                <div class="grid-about-me__sapo" data-cms="<?php echo get_locale(); ?>-about-index-78">
                                    Data Protection, individual voices and team work are the core of our Company. We
                                    always listen and try our best to create a professional working enviroment for our
                                    members and our clients</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block partner */ ?>
    <div class="grid-partner">
        <div class="container">
            <div class="grid-head">
                <h2 class="title" data-cms="<?php echo get_locale(); ?>-about-index-82">Some friends we’ve made in the
                    process.</h2>
            </div>
            <div class="grid-content">
                <div class="row">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="col-6 col-md-3">
                            <div class="box-partner">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/partner-img-<?php echo $i; ?>.png"
                                    alt="partner <?php echo $i; ?>"
                                    data-cms="<?php echo get_locale(); ?>-about-index-<?php echo 87 + ($i - 1) * 3; ?>">
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block support */ ?>
    <div class="grid-support">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                    <div class="grid-support__title">
                        <span data-cms="<?php echo get_locale(); ?>-about-index-102">BUSINESS</span>
                        <span data-cms="<?php echo get_locale(); ?>-about-index-103">SUPPORTING</span>
                        <span data-cms="<?php echo get_locale(); ?>-about-index-104">SERVICE FROM</span>
                        <span data-cms="<?php echo get_locale(); ?>-about-index-105">YoutubeStore</span>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8 col-lx-8">
                    <div class="grid-support__content">
                        <ul class="support-list">
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span data-cms="<?php echo get_locale(); ?>-about-index-112">Marketing solution
                                    consultation</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span data-cms="<?php echo get_locale(); ?>-about-index-116">Web Design, App, Solfware
                                    and <br> running a website</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span data-cms="<?php echo get_locale(); ?>-about-index-120">Logo Design/POSM</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span data-cms="<?php echo get_locale(); ?>-about-index-124">Domain/ Hosting/ Server/
                                    Email</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span data-cms="<?php echo get_locale(); ?>-about-index-128">Digital Marketing/ SEO/
                                    Google Adword,<br> Ads Facebook, Instagram</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span data-cms="<?php echo get_locale(); ?>-about-index-132">Video Viral, Video
                                    Marketing</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block reason */ ?>
    <div class="grid-reason">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6 col-lx-6 d-flex align-items-end">
                    <div class="grid-reason__title">
                        <span class="hero"><span class="hero-text"
                                data-cms="<?php echo get_locale(); ?>-about-index-139">6</span></span>
                        <span class="text" data-cms="<?php echo get_locale(); ?>-about-index-140">REASONS TO <br> CHOOSE
                            YoutubeStore</span>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6 col-lx-6">
                    <div class="grid-reason__image">
                        <img class="img-fluid" src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/reason-image.png"
                            alt="image reason" data-cms="<?php echo get_locale(); ?>-about-index-143">
                    </div>
                </div>
            </div>
            <div class="grid-reason__list">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-150">Lightnight support</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-155">Clear direction</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-160">Data protection</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-165">Unique design</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-170">Efficency</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span data-cms="<?php echo get_locale(); ?>-about-index-175">Passionate member</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>

<?php get_footer(); ?>