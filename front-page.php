<?php
/**
 * The front page template file
 */

get_header();
?>

<div style="position:fixed; top:-100%">
    <h1>Youtubestore.vn Đơn Vị Mua Bán Chuyển Nhượng Kênh Youtube Uy Tín</h1>
    <h2>Giới thiệu chung</h2>
    <h2>Danh sách kênh</h2>
    <h2>Chuyển nhượng kênh</h2>
    <h2>Tin tức</h2>
    <h2>Liên hệ</h2>
</div>

<div class="grid-hero" id="grid-hero">
    <div id="grid-hero-banner" style="background-image: url('<?php echo YOUTUBESTORE_URI; ?>/assets/images/1912x800-5.jpg'); background-size: cover; background-position: center; min-height: 500px;">
    </div>
</div>

<style>
    
    @media (max-width: 768px) {
        #grid-hero-banner {
            min-height: unset !important;
            height: auto !important;
            width: 100%;
            background: var(--bg-hero) no-repeat;
            background-size: 100%;
        }
    }
</style>

<div class="grid-call">
    <div class="container">
        <div class="box-service-home branding">
            <div class="box-content row">
                <div class="col-12 col-md-12 " style="text-align: center; padding-bottom:20px;">
                    <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0;">
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" width="100%"
                            src="https://www.youtube.com/embed/oITErRInqZM?autoplay=1&mute=0"
                            title="YouTube video player" frameborder="0" allowfullscreen allow='autoplay'></iframe>
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

<style>
    .swal-overlay {
        background-color: rgba(30, 30, 30, 0.55);
    }

    .swal-button {
        padding: 7px 19px;
        border-radius: 2px;
        background-color: #CF4042;
        font-size: 20px;
        border: 1px solid #CF4042;
        text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
    }

    .swal-modal {
        min-width: 50% !important;
        max-width: 100% !important;
    }

    .swal-footer {
        text-align: center;
    }

    .swal-text {
        font-size: 22px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof swal !== 'undefined') {
            swal("Hãy lựa chọn nhu cầu của bạn?", {
                buttons: {
                    catch: {
                        text: "Tôi muốn mua kênh",
                        value: "catch",
                    },
                    defeat: {
                        text: "Tôi muốn bán kênh",
                        value: "defeat",
                    }
                },
            })
                .then((value) => {
                    switch (value) {
                        case "defeat":
                            window.location.href = "<?php echo home_url('/chuyen-nhuong-kenh-youtube'); ?>"
                            break;

                        case "catch":
                            window.location.href = "<?php echo home_url('/mua-kenh-youtube'); ?>"
                            break;
                    }
                });
        }
    });
</script>

<?php
get_footer();
