<?php
$latitude = get_field( 'map_latitude', 'option' );
$longitude = get_field( 'map_longitude', 'option' );
$apikey = get_field( 'map_apikey', 'option' );
$contact_form_id = get_field( 'cf7_id', 'option' );
$contact_form_id = ( ! empty( $contact_form_id ) ) ? $contact_form_id : '332';
?>

<?php get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>
        <div class="wp-block-group row_contact row_contact_alt">
            <div class="container">
                <div class="row row_contact_container">
                    <div class="col-md-4 col-contact-address">
                        <?php
                            get_template_part( 'template-parts/content', 'generic' );
                        ?>
                    </div>
                    <div class="col-md-8 col-contact-form">
                        <div class="row_contact-inner">
                            <?php echo do_shortcode( '[contact-form-7 id="' . $contact_form_id.'" title="Contact Form"]' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ( $latitude && $longitude ) { ?>
            <div class="wp-block-group row_contact_map">
                <div id="map-canvas" data-latitude="<?=$latitude;?>" data-longitude="<?=$longitude;?>" data-marker="<?=get_template_directory_uri();?>/images/map-marker.png" data-title="<?= get_bloginfo( 'name' ); ?>"></div>
            </div>
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&key=<?=$apikey;?>"></script>
        <?php } ?>
    <?php endwhile; ?>

<?php
get_footer();
