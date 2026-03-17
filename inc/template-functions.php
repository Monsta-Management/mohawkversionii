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
 * Mohawk V2 - Auto Import ACF JSON Field Group.
 *
 * Efficient and safe function to import ACF field groups once per theme version.
 */
if ( ! function_exists( 'mohawkversionii_acf_import_category_settings' ) ) {
    function mohawkversionii_acf_import_category_settings() {
        // Make sure ACF functions exist.
        if ( ! function_exists( 'acf_add_local_field_group' ) || ! function_exists( 'acf_get_field_group' ) ) {
            return;
        }
    
        // Theme version for import tracking.
        $theme_version    = wp_get_theme()->get( 'Version' );
        $imported_version = get_option( 'mohawk_acf_imported_version', '' );
    
        // Field group key.
        $field_group_key = 'group_64e308fd35fe8';
    
        // Strict check: exit if field group exists or already imported for this theme version.
        if ( acf_get_field_group( $field_group_key ) || $imported_version === $theme_version ) {
            return;
        }
    
        // Define the Category Settings field group.
        $category_settings_group = [
            'key' => $field_group_key,
            'title' => 'Category Settings',
            'fields' => [
                [
                    'key' => 'field_64e30926491af',
                    'label' => 'Category Slug',
                    'name' => 'category_slug',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => 'monsta-categories',
                    'placeholder' => 'Enter the category slug here e.g: trophy-specialists or monsta-categories',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'grr-options',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => false,
            'display_title' => '',
        ];
    
        // Import the field group.
        acf_add_local_field_group( $category_settings_group );
    
        // Mark as imported for this theme version.
        update_option( 'mohawk_acf_imported_version', $theme_version );
    }
}
add_action( 'after_setup_theme', 'mohawkversionii_acf_import_category_settings', 5 );

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

//============= RANKING ORDER ========================//
function set_product_ids_by_term_ranking() {
    if ( ! session_id() ) {
        session_start();
    }
    
    session_unset();
    session_destroy();
    
    if ( ! isset( $_SESSION['sess_rank_product_ids'] ) ) {
        $cached_data = get_transient( 'term_ranking_product_data' );
    
        if ( $cached_data ) {
            $_SESSION['sess_rank_product_ids'] = $cached_data['product_ids'];
            $_SESSION['sess_rank_product_ids_level'] = $cached_data['product_ranks'];
            return;
        }
    
        // Query the database if no cached data found
        global $wpdb;
        $product_ids = [];
        $product_ranks = [];
    
        $ranked_products = $wpdb->get_results( "
            SELECT p.ID, tm.meta_value AS `rank`
            FROM {$wpdb->prefix}posts AS p
            INNER JOIN {$wpdb->prefix}term_relationships AS tr ON p.ID = tr.object_id
            INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->prefix}termmeta AS tm ON tm.term_id = tt.term_id
            WHERE p.post_type = 'product' 
              AND p.post_status = 'publish' 
              AND tm.meta_key LIKE '%ranking%'
            ORDER BY CAST(tm.meta_value AS UNSIGNED) ASC
        " );
    
        foreach ( $ranked_products as $product ) {
            $product_ids[] = $product->ID;
            $product_ranks[$product->ID] = ( int ) $product->rank;
        }
    
        $cached_data = [
            'product_ids'  => $product_ids,
            'product_ranks' => $product_ranks,
        ];
        
        set_transient( 'term_ranking_product_data', $cached_data, 24 * HOUR_IN_SECONDS );
    
        $_SESSION['sess_rank_product_ids'] = $product_ids;
        $_SESSION['sess_rank_product_ids_level'] = $product_ranks;
    }
}

function sort_woocommerce_products_by_term_ranking( $query ) {
    if ( $query->is_main_query() && ( is_shop() || is_product_category() ) ) {
        set_product_ids_by_term_ranking();

        // Fetch sorted product IDs and ranks from session
        $product_ids = $_SESSION['sess_rank_product_ids'] ?? [];
        $product_ranks = $_SESSION['sess_rank_product_ids_level'] ?? [];

        if ( ! empty( $product_ids ) ) {
            // Preload necessary post meta for sorting
            $meta_cache = [];
            foreach ( $product_ids as $product_id ) {
                $meta_cache[ $product_id ] = [
                    'info_new' => get_post_meta( $product_id, '_trophymonsta_info_new', true ) === 'Yes' ? 1 : 0,
                    'video'    => get_post_meta( $product_id, '_trophymonsta_valids3url', true ) ? 1 : 0,
                    'image'    => get_post_meta( $product_id, '_trophymonsta_image', true ) ? 1 : 0,
                ];
            }

            // Sort the products based on ranks and meta values
            usort( $product_ids, function( $a, $b ) use ( $product_ranks, $meta_cache ) {
                // Compare term ranks (lower rank value comes first)
                $rank_a = $product_ranks[ $a ] ?? PHP_INT_MAX;
                $rank_b = $product_ranks[ $b ] ?? PHP_INT_MAX;
                if ( $rank_a !== $rank_b ) {
                    return $rank_a - $rank_b; // Lower rank value has higher priority
                }

                // Compare `_trophymonsta_info_new` (1 for Yes, 0 for No)
                $info_new_a = $meta_cache[ $a ]['info_new'];
                $info_new_b = $meta_cache[ $b ]['info_new'];
                if ( $info_new_a !== $info_new_b ) {
                    return $info_new_b - $info_new_a;
                }

                // Compare `_trophymonsta_video` and `_trophymonsta_image` (sum of priorities).
                $priority_a = $meta_cache[ $a ]['video'] + $meta_cache[ $a ]['image'];
                $priority_b = $meta_cache[ $b ]['video'] + $meta_cache[ $b ]['image'];
                return $priority_b - $priority_a; // Higher priority goes first
            });

            // Update query with sorted product IDs.
            $query->set( 'post__in', $product_ids );
            $query->set( 'orderby', 'post__in' );
        }
    }
}
add_action( 'pre_get_posts', 'sort_woocommerce_products_by_term_ranking', 99 );

// Invalidate cache when product or term ranking data changes
function clear_product_cache_on_update() {
    delete_transient( 'term_ranking_product_data' );
}
add_action( 'save_post_product', 'clear_product_cache_on_update' );
add_action( 'edited_term', 'clear_product_cache_on_update' );

// Function to retrieve the rank of a product
function get_product_rank( $product_id ) {
    $product_ranks = $_SESSION['sess_rank_product_ids_level'] ?? [];
    return $product_ranks[ $product_id ] ?? false;
}
//============= END OF :: RANKING ORDER ========================//
