<?php
$asset_url = get_stylesheet_directory_uri() . '/images/temp';

$heading = get_field( 'benefits_heading', 'option' );
$img_1 = get_field( 'benefits_img_1', 'option' );
$title_1 = get_field( 'benefits_title_1', 'option' );
$content_1 = get_field( 'benefits_content_1', 'option' );
$link_1 = get_field( 'benefits_link_1', 'option' );
$target_1 = get_field( 'benefits_target_1', 'option' );
$img_2 = get_field( 'benefits_img_2', 'option' );
$title_2 = get_field( 'benefits_title_2', 'option' );
$content_2 = get_field( 'benefits_content_2', 'option' );
$link_2 = get_field( 'benefits_link_2', 'option' );
$target_2 = get_field( 'benefits_target_2', 'option' );
$img_3 = get_field( 'benefits_img_3', 'option' );
$title_3 = get_field( 'benefits_title_3', 'option' );
$content_3 = get_field( 'benefits_content_3', 'option' );
$link_3 = get_field( 'benefits_link_3', 'option' );
$target_3 = get_field( 'benefits_target_3', 'option' );
?>

<?php if ( $heading || $content_1 || $content_2 || $content_3 ) { ?>
    <div class="wp-block-group row_features">
        <div class="wp-block-group__inner-container">
            <?php if ( $heading ) { ?>
                <div class="wp-block-columns">
                    <div class="wp-block-column">
                        <h2 class="has-text-align-center"><?=$heading;?></h2>
                    </div>
                </div>
            <?php } ?>
            
            <div class="wp-block-columns container">
                <?php if ( $img_1 || $title_1 ) { ?>
                    <div class="wp-block-column">
                        <div class="wp-block-group feature-item">
                            <?php if ( $link_1 ) { ?>
                                <a href="<?=$link_1;?>" target="<?=$target_1;?>" class="wp-block-group__inner-container">
                            <?php } else { ?>
                                <div class="wp-block-group__inner-container">
                            <?php } ?>
                                <figure class="wp-block-image size-full">
                                    <?php
                                        echo wp_get_attachment_image( $img_1['ID'], 'full', false, [
                                            'class'   => 'wp-image-34',
                                            'width'   => 100,
                                            'height'  => 100,
                                            'loading' => 'lazy',
                                        ] );
                                    ?>
                                </figure>
                                <h3><?=$title_1;?></h3>
                                <p><?=$content_1;?></p>
                            <?php if ( $link_1 ) { ?>
                                </a>
                            <?php } else { ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                    
                <?php if ( $img_2 || $title_2 ) { ?>
                    <div class="wp-block-column">
                        <div class="wp-block-group feature-item">
                            <?php if ( $link_2 ) { ?>
                                <a href="<?=$link_2;?>" target="<?=$target_2;?>" class="wp-block-group__inner-container">
                            <?php } else { ?>
                                <div class="wp-block-group__inner-container">
                            <?php } ?>
                                <figure class="wp-block-image size-full">
                                    <?php
                                        echo wp_get_attachment_image( $img_2['ID'], 'full', false, [
                                            'class'   => 'wp-image-34',
                                            'width'   => 100,
                                            'height'  => 100,
                                            'loading' => 'lazy',
                                        ] );
                                    ?>
                                </figure>
                                <h3><?=$title_2;?></h3>
                                <p><?=$content_2;?></p>
                            <?php if ( $link_2 ) { ?>
                                </a>
                            <?php } else { ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                    
                <?php if ( $img_3 || $title_3 ) { ?>
                    <div class="wp-block-column">
                        <div class="wp-block-group feature-item">
                            <?php if ( $link_3 ) { ?>
                                <a href="<?=$link_3;?>" target="<?=$link_3;?>" class="wp-block-group__inner-container">
                            <?php } else { ?>
                                <div class="wp-block-group__inner-container">
                            <?php } ?>
                                <figure class="wp-block-image size-full">
                                    <?php
                                        echo wp_get_attachment_image( $img_3['ID'], 'full', false, [
                                            'class'   => 'wp-image-34',
                                            'width'   => 100,
                                            'height'  => 100,
                                            'loading' => 'lazy',
                                        ] );
                                    ?>
                                </figure>
                                <h3><?=$title_3;?></h3>
                                <p><?=$content_3;?></p>
                            <?php if ( $link_3 ) { ?>
                                </a>
                            <?php } else { ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
