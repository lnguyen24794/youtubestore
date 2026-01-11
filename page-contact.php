<?php
/* Template Name: Liên Hệ */
get_header();
?>

<div class="page-wrapper page-contact">
    <?php /* Block contact */ ?>
    <div class="grid-contact" id="grid-hero">
        <div class="container">
            <div class="grid-head">
                <h2 class="title" data-cms="<?php echo get_locale(); ?>-contact-index-4">XIN CHÀO. HÃY ĐỂ CHÚNG TÔI GIÚP ĐỠ CHO BẠN NHÉ!</h2>
                <div class="sapo">
                    <p data-cms="<?php echo get_locale(); ?>-contact-index-6">Điền thông tin vào form sau hoặc gửi email cho chúng tôi!</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="grid-contact__form">
                        <form class="form js-form">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="" data-cms="<?php echo get_locale(); ?>-contact-index-13">Tên công ty</label>
                                    <input required type="text" name="company" class="form-control">
                                </div>
                                <div class="form-group col-12">
                                    <label for="" data-cms="<?php echo get_locale(); ?>-contact-index-16">Tên của bạn</label>
                                    <input required type="text" name="name" class="form-control">
                                </div>
                                <div class="form-group col-12">
                                    <label for="" data-cms="<?php echo get_locale(); ?>-contact-index-19">Số điện thoại</label>
                                    <input required type="number" name="phone" class="form-control">
                                </div>
                                <div class="form-group col-12">
                                    <label for="" data-cms="<?php echo get_locale(); ?>-contact-index-22">Email</label>
                                    <input required type="email" name="email" class="form-control">
                                </div>
                                <div class="form-group col-12">
                                    <label for="" data-cms="<?php echo get_locale(); ?>-contact-index-25">Mô tả về yêu cầu của bạn (nếu có)</label>
                                    <textarea name="content" class="form-control" rows="8"></textarea>
                                </div>
                                <div class="form-group col-12">
                                    <div class="btn-normal">
                                        <button class="btn button-submit" type="submit">
                                            <span class="btn-normal__title"
                                                data-cms="<?php echo get_locale(); ?>-contact-index-30">Gửi Đi</span>
                                            <svg class="icon">
                                                <use xlink:href="#icon-arrow"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="grid-contact__img">
                        <img src="<?php echo YOUTUBESTORE_URI; ?>/assets/images/home/img-contact-form.png"
                            alt="img contact form" data-cms="<?php echo get_locale(); ?>-contact-index-35">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /* Block action */ ?>
    <?php get_template_part('template-parts/consultation'); ?>
</div>

<?php get_footer(); ?>