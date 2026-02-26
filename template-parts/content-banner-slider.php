<?php
$monsta_slides = get_field( 'monsta_slides', 'option' );

if( $monsta_slides && !empty( $monsta_slides ) ) :
    ?>
        <div id="site-slider" class="swiper">
            <div class="swiper-wrapper">
                <?php foreach( $monsta_slides as $slide ) : ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_attr( $slide[ 'monsta_slide_image'] ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" />
                        <div class="banner-text">
                            <h1><?php echo esc_html( $slide['monsta_slide_title'] ); ?></h1>
                            <p><?php echo esc_html( $slide['monsta_slide_content'] ); ?></p>
                            <?php if ( !empty( $slide['monsta_slide_button_link']['url'] ) ) : ?>
                                <a href="<?php echo esc_url( $slide['monsta_slide_button_link']['url'] ); ?>" class="button">
                                    <?php echo esc_html( $slide['monsta_slide_button_text'] ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
endif;
