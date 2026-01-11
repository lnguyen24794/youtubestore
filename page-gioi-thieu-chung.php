<?php
/* Template Name: Giới Thiệu Chung */
get_header();
?>

<div class="page-wrapper page-gioi-thieu-chung">
    <?php /* Block hero */ ?>
    <div class="grid-hero" id="grid-hero">
        <div class="container">
            <div class="grid-hero-bg">
                <div class="row">
                    <div class="col-11 col-md-8 col-lg-6 col-lx-6">
                        <div class="grid-hero__wrapp">
                            <div class="grid-hero__content">
                                <div class="box-hero">
                                    <h4 class="sub">GIỚI THIỆU CHUNG</h4>
                                    <h2 class="title">THƯƠNG HIỆU NÂNG TẦM GIÁ TRỊ<br>CHÌA KHÓA THÀNH CÔNG</h2>
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
                            <span class="hero"><span class="hero-text">M</span></span>
                            <span>GIẢI PHÁP MARKETING CHUYÊN NGHIỆP<br>ĐƯA DOANH NGHIỆP ĐẾN TẦM CAO</span>
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
                                            alt="about <?php echo $i; ?>">
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
                <h2 class="title">XIN CHÀO ĐÃ ĐẾN VỚI YOUTUBESTORE.VN</h2>
            </div>
            <div class="grid-content">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                        <div class="grid-about-me__item">
                            <div class="grid-about-me__img">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/about-me-img-1.png"
                                    alt="about me image 1">
                            </div>
                            <div class="grid-about-me__content">
                                <h3 class="grid-about-me__title"><span>Tầm nhìn</span></h3>
                                <div class="grid-about-me__sapo">
                                    Với hơn 10 năm kinh nghiệm cùng với thế mạnh là đội ngũ nhân viên trẻ và nhiệt huyết, chuyên môn cao. Chúng tôi luôn cố gắng hoàn thiện trong tư duy và đổi mới trong sáng tạo, không ngừng phát triển sản phẩm dịch vụ để được sự tín nhiệm của khách hàng dễ và khó tính nhất.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                        <div class="grid-about-me__item grid-about-me__item-2">
                            <div class="grid-about-me__img">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/about-me-img-2.png"
                                    alt="about me image 2">
                            </div>
                            <div class="grid-about-me__content">
                                <h3 class="grid-about-me__title"><span>Sứ mệnh</span></h3>
                                <div class="grid-about-me__sapo">
                                    Chúng tôi mang lại những giải pháp phù hợp với doanh nghiệp, theo xu hướng thay đổi mỗi ngày của thương mại điện tử. Sứ mệnh của YOUTUBESTORE là không ngừng mang đến cho những khách hàng những sản phẩm chất lượng. Để đưa các doanh nghiệp Việt Nam vươn xa hơn trên Thế Giới.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 col-lx-4">
                        <div class="grid-about-me__item grid-about-me__item-3">
                            <div class="grid-about-me__img">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/about-me-img-3.png"
                                    alt="about me image 3">
                            </div>
                            <div class="grid-about-me__content">
                                <h3 class="grid-about-me__title"><span>Giá trị cốt lõi</span></h3>
                                <div class="grid-about-me__sapo">
                                    Với phương châm uy tín & bảo mật tạo nên thương hiệu của YOUTUBESTORE Đó cũng là hướng đi cho toàn bộ hoạt động của công ty, tôn trọng các ý kiến cá nhân, đề cao tinh thần làm việc nhóm giữa các thành viên, tạo nên môi trường làm việc thực sự chuyên nghiệp.
                                </div>
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
                <h2 class="title">CÙNG XEM KHÁCH HÀNG TIÊU BIỂU CỦA YOUTUBESTORE.VN ĐÃ TỪNG HỢP TÁC NHÉ</h2>
            </div>
            <div class="grid-content">
                <div class="row">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="col-6 col-md-3">
                            <div class="box-partner">
                                <img class="img-fluid"
                                    src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/partner-img-<?php echo $i; ?>.png"
                                    alt="partner <?php echo $i; ?>">
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
                        <span>NHỮNG DỊCH VỤ HỖ TRỢ DOANH NGHIỆP</span>
                        <span>ĐẾN TỪ YOUTUBESTORE</span>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8 col-lx-8">
                    <div class="grid-support__content">
                        <ul class="support-list">
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span>Tư vấn giải pháp Youtube Marketing</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span>Thiết kế Website, APPS, Phần mềm (Software) + vận hành Website</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span>Thiết kế Logo/ Bộ nhận diện thương hiệu</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span>Domain/ Hosting/ Server/ Email</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span>Digital Marketing/ SEO/ Google Adword,<br>Ads Facebook, Instagram</span>
                            </li>
                            <li>
                                <svg class="icon">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                <span>Video Viral, Video Marketing</span>
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
                        <span class="hero"><span class="hero-text">6</span></span>
                        <span class="text">LÍ DO NÊN CHỌN<br>YOUTUBESTORE</span>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6 col-lx-6">
                    <div class="grid-reason__image">
                        <img class="img-fluid" src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/reason-image.png"
                            alt="image reason">
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
                            <span>Hỗ trợ nhanh chóng</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span>Lộ trình rõ ràng</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span>Chính sách bảo mật chặt chẽ</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span>Giao diện độc quyền</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span>Sắp xếp thời gian hiệu quả</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="grid-reason__list-item">
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                            <span>Đội ngũ nhân viên tâm huyết</span>
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

