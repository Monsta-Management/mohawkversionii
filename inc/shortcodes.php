<?php
/**
 * Get year shortcode.
 */
function shortcode_year( $attr ) {
	return date( "Y" );
} 
add_shortcode( 'theme_year', 'shortcode_year' );

/*
** Homepage content shortcode - [home_content]
*/
function shortcode_home_content() {
	ob_start();
	$page_title = get_the_title();

	// check if the repeater field has rows of data.
	if ( have_rows( 'page_home' , 'option' ) ) :
		echo '<div class="home-content text-center">';
			while ( have_rows('page_home', 'option') ) : the_row();
				$the_heading = get_sub_field( 'page_heading_home' );
				$the_content = get_sub_field( 'page_content_home' );

				if ( $the_heading ) {
					echo '<h3>';
						echo $the_heading;
					echo '</h3>';
				}

				if ( $the_content ) {
					echo '<p>';
						echo nl2br( $the_content );
					echo '</p>';
				}
			endwhile;
		echo '</div>';
	endif;

	return ob_get_clean();
}
add_shortcode( 'home_content', 'shortcode_home_content' );

/*
** Featured products shortcode - [uproar_featured_products]
*/
function shortcode_featured_products() {
	// save ranking data to sessioin.
	set_product_ids_by_term_ranking();

	ob_start();

	$image = get_template_directory_uri() . '/images/temporary/medal.jpg';

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 6,
		'tax_query'      => [
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',    
				],
			],
		);

	// filter products from rank session data
	if ( ! empty( $_SESSION['sess_rank_product_ids'] ) ) {
		$args['post__in'] = $_SESSION['sess_rank_product_ids'];
		$args['orderby'] = 'post__in';
	}
	
	$result = new WP_Query( $args );

	if ( ! $result->have_posts() ) {
		$args = [
			'post_type' => 'product',
			'posts_per_page' => 6    
		];

		// products from ranked categories.
		if ( ! empty( $_SESSION['sess_rank_product_ids'] ) ) {
			$args['post__in'] = $_SESSION['sess_rank_product_ids'];
			$args['orderby'] = 'post__in';
		}
		
		$result = new WP_Query( $args );
	}
	?>
	<?php if ( $result->have_posts() ) { ?>
		<div class="row row-products">
			<?php
				while ( $result->have_posts() ) :
					$result->the_post();
					wc_get_template_part( 'content', 'product-card' );
				endwhile;
			?>
		</div>
	<?php } ?>
	<?php

	return ob_get_clean();
}
add_shortcode( 'uproar_featured_products', 'shortcode_featured_products' );

/*
** Featured product via ACF - [acf_featured_products]
*/
function shortcode_selected_featured_products() {
	ob_start();

	// Get selected product IDs from ACF (Global Options Page).
	$acf_selected_products = get_field( 'featured_products_show', 'option' );

	if ( empty( $acf_selected_products ) ) {
		return '<p>No featured products selected.</p>';
	}

	$args = [
		'post_type'      => 'product',
		'post__in'       => $acf_selected_products,
		'orderby'        => 'post__in',
		'posts_per_page' => count( $acf_selected_products ),
	];

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		echo '<div class="row row-products">';
			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product-card' );
			}
		echo '</div>';
	} else {
		echo '<p>No products found.</p>';
	}

	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'acf_featured_products', 'shortcode_selected_featured_products' );

/**
 * Featured product categories - [featured_categories]
 *
 * Displays child product categories of a parent category
 * in the order defined by ACF "Custom Category Order" field.
 *
 * @return string HTML output
 */
function shortcode_featured_categories() {
	if ( ! function_exists( 'get_field' ) ) {
		return ''; // ACF not active.
	}

	ob_start();

	global $post, $wp_query;

	$monsta_cats = [];

	// Get parent category slug from ACF option.
	$parent_slug = get_field( 'category_slug', 'option' ) ?: 'trophy-specialists';
	$parent_term = get_term_by( 'slug', $parent_slug, 'product_cat' );

	if ( ! $parent_term ) {
		return ''; // Parent category not found.
	}

	// Get immediate child categories of parent.
	$child_categories = get_terms( [
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => $parent_term->term_id,
	] );

	if ( is_wp_error( $child_categories ) || empty( $child_categories ) ) {
		return ''; // No child categories.
	}

	// Get ACF custom category order (IDs).
	$acf_order = get_field( 'custom_category_order', 'option' );
	$order_map = ! empty( $acf_order ) && is_array( $acf_order ) ? array_flip( $acf_order ) : [];

	// Get currently queried category for highlighting.
	$current_category = get_queried_object();
	$current_product_cats = [];

	if ( is_product() && ! empty( $post->ID ) ) {
		$terms = get_the_terms( $post->ID, 'product_cat' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$current_product_cats[] = $term->term_id;
			}
		}
	}

	// Build categories array.
	foreach ( $child_categories as $child ) {
		if ( in_array( $child->slug, [ 'uncategorized', 'ungrouped' ], true ) ) {
			continue; // skip default categories.
		}

		$thumbnail_id = get_term_meta( $child->term_id, 'thumbnail_id', true );
		$image_url = wp_get_attachment_url( $thumbnail_id );

		$monsta_cats[] = [
			'id'    => $child->term_id,
			'name'  => $child->name,
			'slug'  => $child->slug,
			'url'   => esc_url( get_term_link( $child ) ),
			'img'   => $image_url ?: '', 
			'active' => ( is_product() && in_array( $child->term_id, $current_product_cats, true ) )
					   || ( isset( $current_category->term_id ) && $child->term_id === $current_category->term_id ),
		];
	}

	// Sort by ACF custom order, then fallback alphabetical.
	if ( ! empty( $order_map ) ) {
		usort( $monsta_cats, function( $a, $b ) use ( $order_map ) {
			$posA = $order_map[ $a['id'] ] ?? null;
			$posB = $order_map[ $b['id'] ] ?? null;

			if ( $posA !== null && $posB !== null ) {
				return $posA - $posB;
			}
			if ( $posA !== null ) return -1;
			if ( $posB !== null ) return 1;

			return strcmp( $a['name'], $b['name'] );
		});
	} else {
		// No ACF order defined → simple alphabetical.
		usort( $monsta_cats, function( $a, $b ) {
			return strcmp( $a['name'], $b['name'] );
		});
	}

	?>
	<div class="row row-featured_categories row-thumb_categories">
		<?php foreach ( $monsta_cats as $cat ) : 
			$img_url = ! empty( $cat['img'] ) && strpos( basename( $cat['img'] ), 'product-no-image' ) === false
				? esc_url( $cat['img'] )
				: esc_url( get_template_directory_uri() . '/images/trophy.png' );
			?>
			<div class="col col-item">
				<div class="cat-item text-center<?php echo $cat['active'] ? ' is-active' : ''; ?>">
					<a href="<?php echo esc_url( $cat['url'] ); ?>" class="cat-item-cta" data-cat="<?php echo esc_attr( $cat['id'] ); ?>">
						<div class="img-wrap u-db u-pr">
							<img class="u-pa w-100 h-100" src="<?php echo $img_url; ?>" alt="<?php echo esc_attr( $cat['name'] ); ?>">
						</div>
						<h3 class="mt-1"><?php echo esc_html( $cat['name'] ); ?></h3>
					</a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="row row-featured_categories row-tab_categories">
		<?php foreach ( $monsta_cats as $cat ) : ?>
			<div class="col col-item">
				<div class="cat-item_alt text-center<?php echo $cat['active'] ? ' is-active' : ''; ?>">
					<a href="<?php echo esc_url( $cat['url'] ); ?>" class="cat-item-tab" data-cat="<?php echo esc_attr( $cat['id'] ); ?>">
						<h3 class="mt-1"><?php echo esc_html( $cat['name'] ); ?></h3>
					</a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="row-subcategories">
		<?php get_template_part( 'template-parts/subcategories', 'order' ); ?>
	</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'featured_categories', 'shortcode_featured_categories' );

/*
** Shortcode related products - [show_related_product]
*/
function shortcode_related_products() {
	ob_start();
	global $woocommerce, $product;

	$product_cat = wp_get_post_terms(
		get_the_id(),
		'product_cat',
		[
			'fields' => 'ids'    
		]
	);

	// Related products are found from category.
	$cats_array = [];
	$terms = wp_get_post_terms( $product->id, 'product_cat' );

	for ( $i = count( $terms )-1; $i >= 0; $i-- ) {
		if ( $terms[$i]->count > 1 ) {
			$term = $terms[$i];
			break;
		}
	}

	if ( sizeof( $terms ) == 0 ) return false;

   	$cats_array = $product_cat[0];

	$meta_query = array();
	$meta_query[] = $woocommerce->query->visibility_meta_query();
	$meta_query[] = $woocommerce->query->stock_status_meta_query();

	$limit = 6;

	$result = new WP_Query(
		[
			'orderby'       => 'rand',
			'posts_per_page'=> $limit,
			'post_type'     => 'product',
			'meta_query'    => $meta_query,
			'post__not_in'  => array( $product->get_id() ),
			'tax_query'     => [
				[
					'taxonomy'  => 'product_cat',
					'field'     => 'term_id',
					'operator'  => 'IN',
					'terms'     => [$term->term_id]    
				]    
			],    
		]    
	);
	?>
	
	<?php if (  $result->have_posts() ) { ?>
		<div class="related-wrap">
			<div class="related-inner">
				<h2>Related products</h2>
				<div class="related-app2 row-products">
					<?php 
						while( $result->have_posts() ):  $result->the_post();
							wc_get_template_part( 'content', 'product-card' );
						endwhile;
						
						wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php

	return ob_get_clean();
}
add_shortcode( 'show_related_product', 'shortcode_related_products' );

/*
** Shortcode for testimonial slider - [testimonial_slider]
*/
function shortcode_testimonial_slider() {
	ob_start();
	
	$testimonial_slider = get_field( 'testimonial_slider', 'option' );

	if( $testimonial_slider && !empty( $testimonial_slider ) ) : ?>
		<div id="testimonial-slider" class="swiper">
			<div class="swiper-wrapper">
				<?php foreach( $testimonial_slider as $testimonial ) : ?>
					<div class="swiper-slide">
						<div class="swiper-slide__contents">
							<?php $ratings = $testimonial['testimonial_slider_ratings']; ?>
						
							<figcaption>
								<span class="star-rating">
									<span class="star-rating__value <?php echo $ratings; ?>"></span>
								</span>
								
								<?php echo esc_html( $testimonial['testimonial_slider_content'] ); ?>
							</figcaption>
							
							<h5>
								<?php echo esc_html( $testimonial['testimonial_slider_name'] ); ?>
							</h5>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
	<?php endif;

	return ob_get_clean();
}
add_shortcode( 'testimonial_slider', 'shortcode_testimonial_slider' );

/*
** Chortcode woocommerce mini-cart - [custom-mini-cart]
*/
function custom_mini_cart() {
	ob_start();
	?>

	<div class="mini-cart-widget woocommerce">
		<div class="modal right fade" id="minicart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-checkout="<?=site_url();?>/checkout">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">YOUR SHOPPING CART</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

						<div class="woocommerce-message minicart-msg hide">“<span class="msg-product-title"></span>” has been added to your cart.</div>

						<div class="widget_shopping_cart_content">
							<?=woocommerce_mini_cart();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	
	return ob_get_clean();

}
add_shortcode( 'custom-mini-cart', 'custom_mini_cart' );

/*
** Shortcode for search form - [product_count]
*/
/*function product_count_shortcode() {
	$total_count = 0;

	$args = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids'    
	];
	
	$products = new WP_Query( $args );

	if ( $products->have_posts() ) {
		foreach ( $products->posts as $product_id ) {
			// Get the variations for each product.
			$args = array(
				'post_type'   => 'product_variation',
				'post_status' => 'publish',
				'post_parent' => $product_id
			);
			
			$variations = new WP_Query( $args );

			if ( $variations->have_posts() ) {
				$total_count += $variations->found_posts;
			} else {
				$total_count += 1;
			}
		}
	}

	return $total_count;
}
add_shortcode( 'product_count', 'product_count_shortcode' );*/
function update_product_and_variation_count_cache() {
	// Initialize counts.
	$product_count = 0;
	$variation_count = 0;

	// Query for all published products.
	$product_args = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'posts_per_page' => -1,
	];
	$products = new WP_Query($product_args);

	if ($products->have_posts()) {
		$product_count = $products->found_posts;

		// Query for all published product variations.
		$variation_args = [
			'post_type'      => 'product_variation',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => -1,
		];
		$variations = new WP_Query($variation_args);
		$variation_count = $variations->found_posts;
	}

	// Store the counts in WordPress options for quick retrieval.
	update_option('total_product_count', $product_count);
	update_option('total_variation_count', $variation_count);
	update_option('total_combined_count', $product_count + $variation_count);
}

// Hook to update counts whenever a product is added, updated, or deleted.
add_action('save_post_product', 'update_product_and_variation_count_cache');
add_action('save_post_product_variation', 'update_product_and_variation_count_cache');
add_action('delete_post', 'update_product_and_variation_count_cache');

function get_total_product_and_variation_count_shortcode() {
	$total_combined = get_option('total_combined_count', 0);
	return $total_combined;
}
add_shortcode('product_count', 'get_total_product_and_variation_count_shortcode');

function schedule_combined_count_recalculation() {
	if (!wp_next_scheduled('recalculate_combined_count')) {
		wp_schedule_event(time(), 'hourly', 'recalculate_combined_count');
	}
}
add_action('wp', 'schedule_combined_count_recalculation');

function recalculate_combined_count() {
	update_product_and_variation_count_cache();
}
add_action('recalculate_combined_count', 'recalculate_combined_count');
