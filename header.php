<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PZ6T4PS');</script>
    <!-- End Google Tag Manager -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://connect.facebook.net">
    <?php if (is_front_page()): ?>
    <link rel="preconnect" href="https://img.youtube.com" crossorigin>
    <?php endif; ?>

    <?php if (is_front_page()): ?>
        <link rel="preload" as="image" href="<?php echo YOUTUBESTORE_URI; ?>/assets/images/714x500-2.jpg"
            media="(max-width: 768px)">
        <link rel="preload" as="image" href="<?php echo YOUTUBESTORE_URI; ?>/assets/images/1912x800-5.webp"
            media="(min-width: 769px)">
    <?php endif; ?>
    <link rel="preload" href="<?php echo YOUTUBESTORE_URI; ?>/assets/css/home/app.min.css" as="style">
    <?php if (!is_front_page()): ?>
    <link rel="preload" href="<?php echo YOUTUBESTORE_URI; ?>/assets/css/theme-optimized.css" as="style">
    <?php endif; ?>
    <?php wp_head(); ?>
    <style>
        iframe {
            max-width: 100% !important;
        }

        @media only screen and (max-width: 768px) {
            iframe {
                max-height: 50vw !important;
            }
        }

        /* Header Menu Styles */
        .header-menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-menu li {
            margin: 0;
        }

        .header-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: opacity 0.3s ease;
        }

        .header-menu a:hover {
            opacity: 0.8;
        }

        @media only screen and (max-width: 768px) {
            .header-menu {
                display: none;
            }
        }
    </style>
</head>

<body <?php body_class(); ?> id="body-site">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZ6T4PS"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/svg-defs'); ?>

    <div class="navigation">
        <input type="checkbox" class="navigation__checkbox" id="navi-toggle">

        <label id="navigation__button" for="navi-toggle" class="navigation__button">
            <span class="navigation__icon"></span>
        </label>
        <div id="navigation__background" class="navigation__background"></div>
        <h3 id="navigation__title" class="navigation__title">LIÊN HỆ HOTLINE: 0899.707.888</h3>

        <nav class="navigation__nav">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="navigation__flex">
                            <div class="navigation__content">
                                <h4 class="navigation__lable">Menu</h4>
                                <ul class="navigation__list" id="navi-menu">
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/'); ?>" class="navigation__link">Trang chủ</a>
                                    </li>
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/gioi-thieu-chung'); ?>"
                                            class="navigation__link">Giới Thiệu Chung</a>
                                    </li>
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/mua-kenh-youtube'); ?>"
                                            class="navigation__link">Danh Sách Kênh</a>
                                    </li>
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/chuyen-nhuong-kenh-youtube'); ?>"
                                            class="navigation__link">Chuyển nhượng kênh Youtube</a>
                                    </li>
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/tin-tuc'); ?>" class="navigation__link">Tin
                                            Tức</a>
                                    </li>
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/lien-he'); ?>" class="navigation__link">Liên
                                            Hệ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="navigation__flex">
                            <div class="navigation__content">
                                <div class="navigation__image">
                                    <?php youtubestore_theme_img('home/menu-about.png', array('alt' => 'Menu', 'width' => 612, 'height' => 463, 'class' => 'img-fluid', 'id' => 'menu-image', 'style' => 'max-height:80px;width:auto')); ?>
                                </div>
                                <div class="navigation__info">
                                    <ul class="navigation-list">
                                        <li><i class="fa fa-phone" aria-hidden="true"></i> <a
                                                href="tel:0899707888">0899.707.888</a></li>
                                        <li><span>Email:</span> <a
                                                href="mailto:mrkiengmcc@gmail.com">mrkiengmcc@gmail.com</a></li>
                                        <li><i class="fa fa-map-marker" aria-hidden="true"></i> <span>Zalo:
                                                0899707888</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <header id="header" class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="header__logo-box d-flex align-items-center">
                        <a href="<?php echo home_url('/'); ?>" title="logo">
                            <img class="img-fluid"
                                src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/logo-white.webp"
                                alt="<?php bloginfo('name'); ?>" width="80" height="80" fetchpriority="high">
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="header__nav">

                    </div>
                </div>
            </div>
    </header>

    <main id="main" class="site-main" role="main">