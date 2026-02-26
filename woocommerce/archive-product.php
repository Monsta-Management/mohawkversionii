<?php
global $wp_query;

$enable_infinite_result = get_field('enable_infinite_result', 'option');
$enable_infinite_result = !empty($enable_infinite_result) ? reset($enable_infinite_result) : false;

$enable_fix_sidebar_menu = get_field( 'enable_fix_sidebar_menu', 'option' );

$totalproducts = wc_get_loop_prop( 'total' ) ? wc_get_loop_prop( 'total' ) : $wp_query->post_count;
$limit = 24;

// Lightweight infinite scroll fallback: when JS fetches with ?infinite_result=1,
// return ONLY the product card HTML (no header/footer/sidebar), then exit.
// This is the fallback for when wp_localize_script (mohawkInfinite) isn't available
// (e.g. due to server-side caching). The preferred path is the mohawk_infinite_scroll
// AJAX endpoint in functions.php.
if ( ! empty( $_GET['infinite_result'] ) ) {
    echo '<div class="row row-products">';
    while ( have_posts() ) {
        the_post();
        wc_get_template_part( 'content', 'product-card' );
    }
    echo '</div>';
    exit;
}

if ( isset($_GET['orderby']) && $_GET['orderby'] === 'price-high-to-low' ) {
    $current_term = '';
    if ( is_tax() || is_product_category() ) {
        $current_term = get_queried_object()->slug;
    }

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = array(
        'posts_per_page' => 24,
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_key'       => '_price',
        'meta_query'     => array(
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        ),
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $current_term,
            ),
        ),
        'fields'         => 'ids',
        'paged'          => $paged,
    );

    $wp_query = new WP_Query( $args );
}

?>

<?php get_header(); ?>
	<div id="main-container">
        <div class="row_product_result">
            <div class="container">
			    <div class="archive-header">
			        <div class="col-breadcrumbs">
						<div class="breadcrumbs-wrap">
							<?php  woocommerce_breadcrumb(array('delimiter' => ' <i class="fas fa-chevron-right f-12"></i> ',)); ?>
						</div>
					</div>
					<div class="col-title">
						<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
							<h1 class="woocommerce-products-header__title page-title text-center f-bold"><?php woocommerce_page_title(); ?></h1>
						<?php endif; ?>
					</div>
				    <div class="row row-title">
				        <div class="col-md-10 col-products-total">
    						<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
    							<h3 class="text-center"><?=$totalproducts; ?> products found.</h3>
    						<?php endif; ?>
    					</div>
    					<div class="col-md-2 col-filter">
    						<div class="filter-wrap pull-right">
    							<?php if ( have_posts() ) { ?>
    								<div class="row">
    									<div class="col-md-12 text-right">
    										<div class="sort-wrap u-dib">
    											<?php do_action( 'woo_custom_catalog_ordering' ); ?>
    										</div>
    									</div>
    								</div>
    							<?php } ?>
    						</div>
    					</div>
    				</div>
    			</div>
				
				<div class="archive-content text-center">
					<?php
					do_action( 'woocommerce_archive_description' );
					?>
				</div>
				
				<?php if ( $enable_fix_sidebar_menu ) { ?>
				    <div id="with-sidebar" class="inner-page-wrap clearfix" style="opacity:0;">
				        <div id="loader-dom"></div>
				        <div class="sidebar">
                            <?php get_template_part('template-parts/category-sidebar'); ?>
                        </div>
                        <div class="content">
                            <?php if ( have_posts() ) { ?>
            					<div class="infinite-wrap<?= empty($enable_infinite_result) ? '-disabled' : false;?> <?php if ( isset($_GET['orderby']) && $_GET['orderby'] === 'price-high-to-low' ) { echo 'high-low'; }?>">
            						<div class="row row-products">
            							<?php
                                            while ( have_posts() ) {
                                                the_post();
                                                wc_get_template_part( 'content', 'product-card' );
                                            }
                                        ?>
            						</div>
            						<div class="infinite-loader text-center">
            						    <span class="loading-container d-none hide"><img src="<?=get_template_directory_uri() . '/images/loading.gif';?>" alt=""></span>
            						    Page loading with more products below..
            						</div>
            					</div>
            
                                <?php if ( isset($_GET['orderby']) && $_GET['orderby'] === 'price-high-to-low' ) { ?>
                					<div class="custom-pagination <?= !empty($enable_infinite_result) ? 'd-none hide' : false;?>">
                					    <?php
                					         // Display pagination.
                                            $big = 999999999;
                                            echo paginate_links( array(
                                                'base'      => str_replace( $big, '%#%', esc_url( add_query_arg( 'paged', '%#%', get_pagenum_link( $big ) ) ) ),
                                                'format'    => '?paged=%#%',
                                                'current'   => max( 1, $paged ),
                                                'total'     => $wp_query->max_num_pages,
                                                'prev_text' => __('<i class="fas fa-chevron-left"></i>'),
		                                        'next_text' => __('<i class="fas fa-chevron-right"></i>'),
                                            ) );
                					    ?>
                					</div>
                				<?php } else { ?>
                				    <div class="pagination-wrap text-right <?= !empty($enable_infinite_result) ? 'd-none hide' : false;?>">
                						<?php 
                    						 if ( function_exists( 'custom_pagination' ) ) {
                    							custom_pagination( $limit, 999999999, false, $wp_query );
                    						}
                						?>
                					</div>
                				<?php } ?>
            					
            				<?php }else{ ?>
            					<h3 class="text-center">No result found.</h3>
            				<?php } ?>
                        </div>
				    </div>
				<?php } else { ?>
				    <?php if ( have_posts() ) { ?>
    					<div class="infinite-wrap<?= empty($enable_infinite_result) ? '-disabled' : false;?>">
    						<div class="row row-products">
    							<?php
    							while ( have_posts() ) :
    								the_post();
    								wc_get_template_part( 'content', 'product-card' );
    							endwhile;
    							?>
    						</div>
    						<div class="infinite-loader text-center">
    						    <span class="loading-container d-none hide"><img src="<?=get_template_directory_uri() . '/images/loading.gif';?>" alt=""></span>
    						    Page loading with more products below..
    						</div>
    					</div>
    
    					<?php if ( isset($_GET['orderby']) && $_GET['orderby'] === 'price-high-to-low' ) { ?>
        					<div class="custom-pagination <?= !empty($enable_infinite_result) ? 'd-none hide' : false;?>">
        					    <?php
                                    $big = 999999999;
                                    echo paginate_links( array(
                                        'base'      => str_replace( $big, '%#%', esc_url( add_query_arg( 'paged', '%#%', get_pagenum_link( $big ) ) ) ),
                                        'format'    => '?paged=%#%',
                                        'current'   => max( 1, $paged ),
                                        'total'     => $wp_query->max_num_pages,
                                        'prev_text' => __('<i class="fas fa-chevron-left"></i>'),
                                        'next_text' => __('<i class="fas fa-chevron-right"></i>'),
                                    ) );
        					    ?>
        					</div>
        				<?php } else { ?>
        				    <div class="pagination-wrap text-right <?= !empty($enable_infinite_result) ? 'd-none hide' : false;?>">
        						<?php 
            						 if ( function_exists( 'custom_pagination' ) ) {
            							custom_pagination( $limit, 999999999, false, $wp_query );
            						}
        						?>
        					</div>
        				<?php } ?>
    					
    				<?php }else{ ?>
    					<h3 class="text-center">No result found.</h3>
    				<?php } ?>
				<?php } ?>

            </div>
        </div>
	</div>
<?php
get_footer();
