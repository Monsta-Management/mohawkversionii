<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Mohawk_V2
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function mohawkversionii_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'mohawkversionii_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function mohawkversionii_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'mohawkversionii_pingback_header' );

/**
 * Disallow access to engraving page when empty cart.
 */
function redirect_to_cart_if_empty() {
	if ( WC()->cart->is_empty() && ( is_page( 'monsta-engravings-settings' ) || is_page( 'monsta-engravings-details' ) || is_page( 'monsta-engravings-review' ) ) ) {
		wp_safe_redirect( wc_get_cart_url() );
		exit;
	}
}
add_action( 'template_redirect', 'redirect_to_cart_if_empty' );

/**
 * Reset engraving when updated cart.
 */
function reset_engraving_completion_on_cart_update() {
	$_SESSION['engraving_completion'] = '0';
}
add_action( 'woocommerce_cart_updated', 'reset_engraving_completion_on_cart_update' );

/**
 * Implement theme's base color.
 */
function mohawkversionii_base_colors() {
	$primary_color = get_field( 'site_primary_color', 'option' );
	$secondary_color = get_field( 'site_secondary_color', 'option' );
	$tertiary_color = get_field( 'style_tertiary_color', 'option' );
	?>
	<style>
		:root {
			--primary-color: <?php echo $primary_color; ?>;
			--secondary-color: <?php echo $secondary_color; ?>;
			--tertiary-color: <?php echo $tertiary_color; ?>;
		}
	</style>
	<?php
}
add_action( 'wp_footer', 'mohawkversionii_base_colors' );

/**
 * Dynamically populate the Custom Category Order dropdown with immediate children
 * of the parent category defined in Category Slug.
 */
add_filter( 'acf/load_field/key=field_64f000000001a', function( $field ) {
	// Get parent category slug from options field.
	$parent_slug = get_field( 'category_slug', 'option' ) ?: 'trophy-specialists';
	$parent_term = get_term_by( 'slug', $parent_slug, 'product_cat' );

	if ( ! $parent_term ) {
		return $field; // fallback: show nothing if parent not found.
	}

	// Fetch immediate children only.
	$children = get_terms( [
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => $parent_term->term_id,
	] );

	// Build choices array (term_id => name).
	$choices = [];
	foreach ( $children as $child ) {
		$choices[ $child->term_id ] = $child->name;
	}

	$field['choices'] = $choices;

	return $field;
});

/**
 * Category Settings (ACF)
 *
 * Provides admin control for WooCommerce category display:
 * - Select parent category via slug
 * - Reorder its immediate children via multi-select
 *
 * Dropdown is dynamically populated (no sub-categories).
 * Used globally for consistent category ordering (shortcode, menus, UI).
 *
 * Optimized: version-based import + no additional query overhead.
 */
if ( ! function_exists( 'mohawkversionii_acf_import_category_settings' ) ) {
	function mohawkversionii_acf_import_category_settings() {
		// Ensure ACF exists.
		if ( ! function_exists( 'acf_add_local_field_group' ) || ! function_exists( 'acf_get_field_group' ) ) {
			return;
		}

		$field_group_key = 'group_64e308fd35fe8';

		// Fetch theme version once.
		$theme_version = wp_get_theme( 'mohawkversionii' )->get( 'Version' );

		// Fetch last imported version once.
		$imported_version = get_option( 'mohawk_acf_imported_version', '' );

		// If field group exists AND version is up-to-date, exit early.
		if ( acf_get_field_group( $field_group_key ) && $imported_version === $theme_version ) {
			return;
		}

		// Define Category Settings field group.
		$category_settings_group = [
			'key'					=> $field_group_key,
			'title'					=> 'Category Settings',
			'fields'				=> [
				[
					'key'           => 'field_64e30926491af',
					'label'         => 'Category Slug',
					'name'          => 'category_slug',
					'type'          => 'text',
					'default_value' => 'monsta-categories',
					'placeholder'   => 'Enter the category slug here e.g: trophy-specialists or monsta-categories',
					'wrapper'       => ['width' => '', 'class' => '', 'id' => ''],
				],
				[
					'key'           => 'field_64f000000001a', // unique key for this field.
					'label'         => 'Custom Category Order',
					'name'          => 'custom_category_order',
					'type'          => 'taxonomy',
					'taxonomy'      => 'product_cat',
					'field_type'    => 'multi_select', // multi select dropdown.
					'allow_null'    => 1,
					'return_format' => 'id', // return term IDs for easy sorting.
					'add_term'      => 0,
					'multiple'      => 1,
					'wrapper'       => ['width' => '', 'class' => '', 'id' => ''],
					'instructions'  => 'Select categories in the order you want them to appear in the featured categories shortcode.',
				],
				[
					'key'           => 'field_disable_hover_submenu',
					'label'         => 'Disable Hover Submenu',
					'name'          => 'disable_hover_submenu',
					'type'          => 'true_false',
					'ui'            => 1,
					'ui_on_text'    => 'Toggled Submenu',
					'ui_off_text'   => 'Hover',
					'default_value' => 0,
					'instructions'  => 'When enabled, submenu will not open on hover. A caret icon will appear and users must click it to reveal submenu items.',
					'wrapper'       => ['width' => '', 'class' => '', 'id' => ''],
				],
			],
			'location' => [
				[
					[
						'param'     => 'options_page',
						'operator'  => '==',
						'value'     => 'grr-options',
					],
				],
			],
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		];

		// Register field group.
		acf_add_local_field_group( $category_settings_group );

		// Update version only if field group was imported.
		update_option( 'mohawk_acf_imported_version', $theme_version );
	}
}
add_action( 'acf/init', 'mohawkversionii_acf_import_category_settings' );

/**
 * Adds the product mark logo as background for .marked products.
 * Exclusive to mohawkversionii parent + child theme only.
 */
function mohawkversionii_mark_logo() {
	static $mark_logo_url = null;

	// Only retrieve ACF field once.
	if ( $mark_logo_url === null ) {
		$acf = get_field('site_product_mark_logo', 'option');
		$mark_logo_url = $acf && isset($acf['url']) ? $acf['url'] : '';
	}

	if ( ! $mark_logo_url ) {
		return;
	}

	$custom_css = "
		.row-products .product-item-wrap .product-inner-img > a::before {
			background: url('" . esc_url($mark_logo_url) . "') no-repeat center center;
			background-size: 100% auto;
			content: '';
			display: block;
			position: absolute;
		}";

	// Attach to the active stylesheet handle.
	if ( wp_style_is( 'mohawkversionii-child-style', 'enqueued' ) ) {
		wp_add_inline_style( 'mohawkversionii-child-style', $custom_css ); // If child theme is active.
	} elseif ( wp_style_is( 'mohawkversionii-style', 'enqueued' ) ) {
		wp_add_inline_style( 'mohawkversionii-style', $custom_css ); // If parent theme only.
	}
}
add_action( 'wp_enqueue_scripts', 'mohawkversionii_mark_logo', 30 );

/**
 * Custom sort by name.
 */
function sort_by_name( $a, $b ) {
	return $a->name > $b->name;
}

/**
 * Custom product pagination.
 */
function custom_pagination( $numpages = '', $pagerange = '', $paged = '', $wp_query ) {
	if (empty( $pagerange ) ) {
		$pagerange = 2;
	}

	global $paged;

	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( $numpages == '' ) {
		$numpages = $wp_query->max_num_pages;

		if ( ! $numpages ) {
			$numpages = 1;
		}
	}

	/**
	 * We construct the pagination arguments to enter into our paginate_links
	 * function.
	 */

	$big = 999999999; // need an unlikely integer

	$pagination_args = [
		'base'         => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'       => 'page/%#%',
		'total'        => $wp_query->max_num_pages,
		'current'      => max( 1, get_query_var( 'paged' ) ),
		'show_all'     => False,
		'end_size'     => 1,
		'mid_size'     => $pagerange,
		'prev_next'    => True,
		'prev_text'    => __( '<i class="fas fa-chevron-left"></i>' ) ,
		'next_text'    => __( '<i class="fas fa-chevron-right"></i>' ) ,
		'type'         => 'plain',
		'add_args'     => false,
		'add_fragment' => '',
	];

	$paginate_links = paginate_links( $pagination_args );

	if ( $paginate_links ) {
		echo "<div class='pagination-c u-dib'><nav class='pagination'>";
		echo $paginate_links;
		echo "</nav></div>";
	}
}

/**
 * Rename checkout billing.
 */
function rename_company_label_checkout( $fields ) {
	$fields['company']['label'] = 'Company/Club/Business Name';

	return $fields;
}
add_filter( 'woocommerce_default_address_fields' , 'rename_company_label_checkout', 9999 );

/**
 * Reposition billing field first.
 */
function company_first_billing( $checkout_fields ) {
	$checkout_fields[ 'shipping' ][ 'shipping_company' ][ 'priority' ] = 5;
	$checkout_fields[ 'billing' ][ 'billing_company' ][ 'priority' ] = 5;
	$checkout_fields[ 'billing' ][ 'billing_phone' ][ 'priority' ] = 21;
	$checkout_fields[ 'billing' ][ 'billing_email' ][ 'priority' ] = 22;
	return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'company_first_billing' );

/**
 * Custom billing placeholder.
 */
function override_placeholder_fields( $fields ) {
	$fields['billing']['billing_company']['placeholder'] = 'Company/Club/Business Name';
	$fields['shipping']['shipping_company']['placeholder'] = 'Company/Club/Business Name';

	$fields['billing']['billing_first_name']['placeholder'] = 'First name';
	$fields['shipping']['shipping_first_name']['placeholder'] = 'First name';

	$fields['shipping']['shipping_last_name']['placeholder'] = 'Last name';
	$fields['billing']['billing_last_name']['placeholder'] = 'Last name';


	$fields['billing']['billing_email']['placeholder'] = 'Email address ';
	$fields['billing']['billing_phone']['placeholder'] = 'Phone ';

	$fields['billing']['billing_city']['placeholder'] = 'Suburb ';
	$fields['shipping']['shipping_city']['placeholder'] = 'Suburb ';

	$fields['billing']['billing_postcode']['placeholder'] = 'Postcode ';
	$fields['shipping']['shipping_postcode']['placeholder'] = 'Postcode ';

	return $fields;
}
add_filter('woocommerce_checkout_fields', 'override_placeholder_fields');

/**
 * Remove auto-populate on shippping.
 */
function disable_autopopulate_billing_shipping( $fields ) {
	$fields['billing']['billing_company']['autocomplete'] = 'off';
	$fields['shipping']['shipping_company']['autocomplete'] = 'off';

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'disable_autopopulate_billing_shipping' );

/**
 * Enable block checkout.
 */
function mohawk_enable_gutenberg_on_checkout( $current_status, $post_type ) {
	if( $post_type === 'page' && is_checkout() ) return true;
	return $current_status;
}
add_filter( 'use_block_editor_for_post_type', 'mohawk_enable_gutenberg_on_checkout', 10, 2 );

/**
 * Change number of products that are displayed per page (shop page)
 */
function new_loop_shop_per_page( $cols ) {
  $cols = 24;
  return $cols;
}
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

/**
 * Redirect checkout add cart.
 */
function bbloomer_redirect_checkout_add_cart() {
	if ( ! empty( $_GET['redirect'] ) ) {
		return wc_get_checkout_url();
	}
}
add_filter( 'woocommerce_add_to_cart_redirect', 'bbloomer_redirect_checkout_add_cart' );

/**
 * Sorting.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woo_custom_catalog_ordering', 'woocommerce_catalog_ordering', 30 );

/*
 * Add custom fields to user / checkout
 */
function my_custom_checkout_field( $checkout ) {
	echo '<div id="bv_custom_checkout_field"><h3>Job reference</h3>';

	/* Weight */
	woocommerce_form_field( 'user_job_reference', array(
		'type'          => 'text',
		'class'         => array('my-class form-row-wide'),
		'label'         => __('Job reference'),
		'placeholder'   => __('Job reference'),
	), get_user_meta(  get_current_user_id(),'user_job_reference' , true  ) );

	echo '</div>';
}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

/*
 * Update field
 */
function my_custom_checkout_field_update_order_meta( $order_id ) {
	if ( ! empty( $_POST['user_job_reference'] ) ) {
		update_user_meta( get_current_user_id(), 'user_job_reference', sanitize_text_field( $_POST['user_job_reference'], '' ) );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

//============= PRODUCT RANKING/SORTING START ========================//
function get_cached_sorted_product_ids() {
	$cache_key = 'trophymonsta_sorted_product_ids';
	$product_ids = get_transient( $cache_key );

	if ( $product_ids !== false ) {
		return $product_ids;
	}

	global $wpdb;

	$product_ids = $wpdb->get_col("
		SELECT p.ID
		FROM {$wpdb->prefix}posts p
		
			/* =========================
			STAR RANKING (ACTIVE ONLY)
		========================= */
		LEFT JOIN (
			SELECT 
			post_id,
			
			MAX(
				CASE
					WHEN meta_key = '_trophymonsta_star_ranking'
					THEN CAST(meta_value AS UNSIGNED)
					ELSE 0
				END
			) AS star_rank,
			
			MAX(
				CASE
					WHEN meta_key = '_trophymonsta_start_date'
					THEN meta_value
				END
			) AS start_date,
			
			MAX(
				CASE
					WHEN meta_key = '_trophymonsta_end_date'
					THEN meta_value
				END
			) AS end_date
			
			FROM {$wpdb->prefix}postmeta
			
			WHERE meta_key IN (
				'_trophymonsta_star_ranking',
				'_trophymonsta_start_date',
				'_trophymonsta_end_date'
			)
			
			GROUP BY post_id
		
		) star ON star.post_id = p.ID
		
		/* =========================
			SUPPLIER RANK (1 row per product)
		========================= */
		LEFT JOIN (
			SELECT 
			tr.object_id,
			MIN(CAST(tm.meta_value AS UNSIGNED)) AS supplier_rank
			FROM {$wpdb->prefix}term_relationships tr
			INNER JOIN {$wpdb->prefix}term_taxonomy tt 
			ON tt.term_taxonomy_id = tr.term_taxonomy_id
			AND tt.taxonomy = 'product_supplier'
			INNER JOIN {$wpdb->prefix}termmeta tm 
			ON tm.term_id = tt.term_id
			AND tm.meta_key = 'ranking'
			GROUP BY tr.object_id
		) supplier ON supplier.object_id = p.ID
		
		/* =========================
			POST META (AGGREGATED - FIXES DUPLICATES)
		========================= */
		LEFT JOIN (
			SELECT 
			post_id,
			
			MAX(CASE WHEN meta_key = '_trophymonsta_valids3url' THEN 1 ELSE 0 END) AS has_s3_video,
			MAX(CASE WHEN meta_key = '_trophymonsta_valids3image' THEN 1 ELSE 0 END) AS has_s3_image,
			MAX(CASE WHEN meta_key = '_trophymonsta_image' THEN 1 ELSE 0 END) AS has_local_image,
			
			MAX(CASE WHEN meta_key = '_trophymonsta_info_new' AND meta_value = 'Yes' THEN 1 ELSE 0 END) AS is_new
			
			FROM {$wpdb->prefix}postmeta
			WHERE meta_key IN (
				'_trophymonsta_valids3url',
				'_trophymonsta_valids3image',
				'_trophymonsta_image',
				'_trophymonsta_info_new'
			)
			GROUP BY post_id
		) meta ON meta.post_id = p.ID
		
		/* =========================
			BASE FILTER
		========================= */
		WHERE p.post_type = 'product'
		AND p.post_status = 'publish'
		
		/* =========================
			FINAL ORDERING
		========================= */
		ORDER BY

				/* =========================
				1. ACTIVE STAR RANKING
				========================= */
				CASE
					WHEN
					star.star_rank > 0
					AND (
					star.start_date IS NULL
					OR star.start_date = ''
					OR star.start_date <= NOW()
					)
					AND (
					star.end_date IS NULL
					OR star.end_date = ''
					OR star.end_date >= NOW()
					)
					THEN 0
					ELSE 1
				END ASC,
			
				/* Higher star rank first */
				CASE
					WHEN
					star.star_rank > 0
					AND (
					star.start_date IS NULL
					OR star.start_date = ''
					OR star.start_date <= NOW()
					)
					AND (
					star.end_date IS NULL
					OR star.end_date = ''
					OR star.end_date >= NOW()
					)
					THEN star.star_rank
					ELSE 999
				END ASC,
			
				/* =========================
				2. SUPPLIER RANK
				========================= */
				COALESCE(supplier.supplier_rank, 999) ASC,
			
				/* =========================
				3. MEDIA PRIORITY
				========================= */
				CASE 
				WHEN meta.has_s3_video = 1 THEN 0
				WHEN meta.has_s3_image = 1 THEN 1
				ELSE 2
				END ASC,
			
				/* =========================
				4. LOCAL IMAGE
				========================= */
				CASE 
				WHEN meta.has_local_image = 1 THEN 0
				ELSE 1
				END ASC,
			
				/* =========================
				5. NEW PRODUCTS
				========================= */
				CASE 
				WHEN meta.is_new = 1 THEN 0
				ELSE 1
				END ASC,
			
				/* =========================
				6. STABLE FALLBACK
				========================= */
				p.ID ASC
		");

	// Cache for 12 hours for performance.
	set_transient( $cache_key, $product_ids, 12 * HOUR_IN_SECONDS );

	return $product_ids;
}

// Hook WooCommerce shop/category pages.
function sort_woocommerce_products_by_rank_cached( $query ) {
	if ( $query->is_main_query() && ( is_shop() || is_product_category() ) ) {
		$product_ids = get_cached_sorted_product_ids();

		if ( ! empty( $product_ids ) ) {
			$query->set( 'post__in', $product_ids );
			$query->set( 'orderby', 'post__in' );
		}
	}
}
add_action( 'pre_get_posts', 'sort_woocommerce_products_by_rank_cached', 99 );

// Clear cache on product update/meta changes.
function clear_sorted_product_cache( $post_id ) {
	if ( get_post_type( $post_id ) === 'product' ) {
		delete_transient( 'trophymonsta_sorted_product_ids' );
	}
}
add_action( 'save_post_product', 'clear_sorted_product_cache' );

function clear_sorted_product_cache_on_termmeta( $meta_id, $term_id, $meta_key ) {
	if ( $meta_key === 'ranking' ) {
		delete_transient( 'trophymonsta_sorted_product_ids' );
	}
}
add_action( 'updated_termmeta', 'clear_sorted_product_cache_on_termmeta', 10, 3 );
add_action( 'added_termmeta',   'clear_sorted_product_cache_on_termmeta', 10, 3 );
add_action( 'deleted_termmeta', 'clear_sorted_product_cache_on_termmeta', 10, 3 );

function clear_sorted_product_cache_on_meta_update( $meta_id = null, $post_id = null, $meta_key = null ) {
	if ( ! $post_id || ! $meta_key ) { // bail out early if missing required data.
		return;
	}

	$relevant_keys = [
		'_trophymonsta_info_new',
		'_trophymonsta_valids3url',
		'_trophymonsta_valids3image',
	];

	if ( get_post_type( $post_id ) === 'product' && in_array( $meta_key, $relevant_keys, true ) ) {
		delete_transient( 'trophymonsta_sorted_product_ids' );
	}
}
add_action( 'updated_postmeta', 'clear_sorted_product_cache_on_meta_update', 10, 3 );
add_action( 'added_postmeta',   'clear_sorted_product_cache_on_meta_update', 10, 3 );
add_action( 'deleted_postmeta', 'clear_sorted_product_cache_on_meta_update', 10, 3 );
//============= PRODUCT RANKING/SORTING END ========================//

/**
 * Get the rank of a single product based on cached sorted IDs
 *
 * @param int $product_id
 * @return int|null Rank number (1-based), or null if not found
 */
function get_product_rank( $product_id ) {
	static $rank_map = null;

	if ( $rank_map === null ) {
		$sorted_ids = get_cached_sorted_product_ids();
		$rank_map   = array_flip( $sorted_ids );
	}

	if ( isset( $rank_map[ $product_id ] ) ) {
		return $rank_map[ $product_id ] + 1; // convert to 1-based rank.
	}

	return null;
}
