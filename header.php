<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                                        <a href="<?php echo home_url('/tin-tuc'); ?>" class="navigation__link">Tin Tức</a>
                                    </li>
                                    <li class="navigation__item">
                                        <a href="<?php echo home_url('/lien-he'); ?>" class="navigation__link">Liên Hệ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="navigation__flex">
                            <div class="navigation__content">
                                <div class="navigation__image">
                                    <img id="menu-image" class="img-fluid"
                                        src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/menu-about.png"
                                        alt="Images">
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

    <!-- Language Picker -->
    <div class="language-picker">
        <button class="language-picker__button" onclick="document.getElementById('language-content').classList.toggle('show');" aria-expanded="false">
            <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/flag_vi.png" alt="Tiếng Việt">
            <em>Tiếng Việt</em>
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div id="language-content" class="dropdown-content">
            <ul class="language-picker__list">
                <li>
                    <a href="<?php echo home_url('/'); ?>" class="active">
                        <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/flag_vi.png" alt="Tiếng Việt">
                        <span>Tiếng Việt</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo home_url('/en'); ?>">
                        <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/flag_en.png" alt="English">
                        <span>Tiếng Anh</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <header id="header" class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="header__logo-box d-flex align-items-center">
                        <a href="<?php echo home_url('/'); ?>" title="logo">
                            <img class="img-fluid"
                                src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/logo-white.png"
                                alt="<?php bloginfo('name'); ?>">
                        </a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="header__nav">
                      
                    </div>
                </div>
            </div>
        </div>
    </header>