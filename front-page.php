<?php
/**
 * The front page template file
 */

get_header();
?>

<div class="grid-hero" id="grid-hero">
    <div id="grid-hero-banner-container" style="width: 100%; position: relative; display: block;">
        <picture>
            <source media="(max-width: 768px)" srcset="<?php echo esc_url(YOUTUBESTORE_URI . '/assets/images/714x500-2.jpg'); ?>">
            <img id="grid-hero-banner-img" src="<?php echo esc_url(YOUTUBESTORE_URI . '/assets/images/1912x800-5.webp'); ?>"
                alt="Youtube Store" fetchpriority="high" loading="eager" decoding="async" width="1912" height="800"
                style="width: 100%; min-height: 500px; object-fit: cover; object-position: center; display: block;">
        </picture>
    </div>
</div>

<style>
    /* Critical CSS to prevent layout shift before app.min.css loads */
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .grid-hero {
        position: relative;
        z-index: 1;
        width: 100%;
    }

    .container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    @media (max-width: 768px) {
        #grid-hero-banner-img {
            min-height: unset !important;
            height: auto !important;
            width: 100%;
        }
    }

    @media (min-width: 576px) {
        .container {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .container {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .container {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }
</style>

<div class="grid-call">
    <div class="container">
        <div class="box-service-home branding">
            <div class="box-content row">
                <div class="col-12 col-md-12 " style="text-align: center; padding-bottom:20px;">
                    <div class="youtube-facade" data-id="oITErRInqZM"
                        style="position: relative; padding-bottom: 56.25%; height: 0; background: #000; border-radius: 8px; overflow: hidden; cursor: pointer;">
                        <img src="https://img.youtube.com/vi/oITErRInqZM/hqdefault.jpg" alt="Video Youtubestore"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                            width="1280" height="720" loading="lazy" decoding="async">
                        <div class="play-button-overlay"
                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background: rgba(220, 53, 69, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <div
                                style="width: 0; height: 0; border-left: 25px solid #fff; border-top: 15px solid transparent; border-bottom: 15px solid transparent; margin-left: 5px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="grid-service servicee">
    <div class="container">
        <div class="box-call">
            <h3 class="title">Tại sao nên sở hữu một kênh youtube</h3>
            <div class="sapo" style="max-width:740px">
                <p style="text-align: left">
                    ► Youtube tạo nên thương hiệu cho Sản Phẩm, Doanh Nghiệp của bạn.<br>
                    ► Youtube đem lại doanh thu với tính năng kiếm tiền trên Youtube.<br>
                    ► Đưa Sản Phẩm, Doanh Nghiệp của bạn đến với khách hàng mà không tốn phí.<br>
                    ► Giúp khách hàng đánh giá, nhìn nhận đúng và rõ hơn về Sản Phẩm, Doanh Nghiệp.
                </p>
                <div class="btn-normal">
                    <a href="<?php echo home_url('/mua-kenh-youtube'); ?>" title="Danh Sách Kênh">
                        <span class="btn-normal__title">DANH SÁCH KÊNH</span>
                        <svg class="icon">
                            <use xlink:href="#icon-arrow"></use>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="grid-action">
    <div class="container">
        <div class="row">
            <div class="col-md-1 col-lx-2"></div>
            <div class="col-12 col-md-10 col-lx-8">
                <div class="box-action row">
                    <div class="box-action--content col-12 col-md-6">
                        <h3 class="title">BẠN CẦN SỰ TƯ VẤN?</h3>
                        <div class="sapo">Đăng kí ngay và nhận những ưu đãi mới nhất nhé!</div>
                    </div>
                    <div class="box-action--form col-12 col-md-6 d-flex align-items-center">
                        <form class="form-group js-form">
                            <input type="text" class="form-control" name="email" placeholder="Email của bạn" required>
                            <button type="submit" class="btn btn-form">
                                <svg class="icon">
                                    <use xlink:href="#icon-send"></use>
                                </svg>
                                <span>Gửi</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-lx-2"></div>
        </div>
    </div>
</div>

<div id="custom-welcome-modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(30, 30, 30, 0.55); z-index: 10000; align-items: center; justify-content: center;">
    <div
        style="background: #fff; width: 90%; max-width: 500px; padding: 30px 20px; border-radius: 5px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="font-size: 24px; color: rgba(0,0,0,0.65); margin-bottom: 25px; margin-top: 0; font-weight: 600;">Hãy
            lựa chọn nhu cầu của bạn?</h3>
        <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <button onclick="window.location.href='<?php echo esc_url(home_url('/mua-kenh-youtube')); ?>'"
                style="padding: 10px 20px; border-radius: 4px; background-color: #CF4042; color: #fff; font-size: 18px; border: none; cursor: pointer; text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3); font-weight: 600;">Tôi
                muốn mua kênh</button>
            <button onclick="window.location.href='<?php echo esc_url(home_url('/chuyen-nhuong-kenh-youtube')); ?>'"
                style="padding: 10px 20px; border-radius: 4px; background-color: #CF4042; color: #fff; font-size: 18px; border: none; cursor: pointer; text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3); font-weight: 600;">Tôi
                muốn bán kênh</button>
        </div>
        <div style="margin-top: 25px;">
            <button onclick="document.getElementById('custom-welcome-modal').style.display='none'"
                style="background: none; border: none; color: #888; cursor: pointer; text-decoration: underline; font-size: 16px;">Đóng</button>
        </div>
    </div>
</div>

<?php
get_footer();
