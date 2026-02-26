<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php while ( have_posts() ) : ?>

	<?php the_post(); ?>

	<div class="row_product_image_details">
		<div class="container">
			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		</div>
	</div>
	
	<div class="row_product_desc">
		<div class="container">
			<h1><?php the_title(); ?></h1>

			<?php the_content(); ?>
			<?php wc_get_template_part( 'content', 'specs-table' ); ?>
			<?php wc_get_template_part( 'content', 'engraving' ); ?>

			<?php
    			$addsText = get_field( 'product_additional_details', 'option' );
    
    			if ( $addsText ) { 
    				?>
    					<div class="additional-details f-16 f-400 _d-none">
    						<p><?=$addsText;?></p>
    					</div>
    			    <?php 
    			} 
			?>
		</div>
	</div>
	
    <div class="related-product">
        <div class="container">
            <?php wc_get_template_part( 'content', 'related' ); ?>
        </div>
	</div>

<?php endwhile; ?>

<?php
get_footer( 'shop' );
