<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Mohawk_V2
 */
 
get_header();
?>
	<main id="main-container" class="wp-block-group row_content">
		<div class="container">
		    <div class="text-center mb-5 mt-5">
		         <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'mohawkversionii' ); ?></h1>
		    </div>
			<div class="page-content text-center mt-5">
				<p>
                    <?php esc_html_e( 'You may want to head back to the', 'mohawkversionii' ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home Page', 'mohawkversionii' ); ?></a>,
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Shop', 'mohawkversionii' ); ?></a>,
                    <a href="<?php echo esc_url( home_url( '/contact-us' ) ); ?>"><?php esc_html_e( 'Contact Us', 'mohawkversionii' ); ?></a>
                    <?php esc_html_e( 'or learn more on our', 'mohawkversionii' ); ?>
                    <a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>"><?php esc_html_e( 'About Us', 'mohawkversionii' ); ?></a>
                    <?php esc_html_e( 'page.', 'mohawkversionii' ); ?>
                </p>
			</div><!-- .page-content -->
		</div>
	</main><!-- #main -->
<?php
get_footer();

