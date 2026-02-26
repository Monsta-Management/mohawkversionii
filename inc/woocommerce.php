<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Mohawk_V2
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function mohawkversionii_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 150,
			'single_image_width'    => 300,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	
	remove_theme_support( 'wc-product-gallery-zoom' );
	remove_theme_support( 'wc-product-gallery-lightbox' );
	remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'mohawkversionii_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function mohawkversionii_woocommerce_scripts() {
	wp_enqueue_style( 'mohawkversionii-woocommerce-style', get_template_directory_uri() . '/woocommerce.css', array(), MOHAWK_VERSION );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'mohawkversionii-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'mohawkversionii_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function mohawkversionii_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'mohawkversionii_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function mohawkversionii_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'mohawkversionii_woocommerce_related_products_args' );

/**
 * Woocommerce filter.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woo_custom_catalog_ordering', 'woocommerce_catalog_ordering', 30 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'mohawkversionii_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function mohawkversionii_woocommerce_wrapper_before() {
		?>
			<main id="primary" class="site-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'mohawkversionii_woocommerce_wrapper_before' );

if ( ! function_exists( 'mohawkversionii_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function mohawkversionii_woocommerce_wrapper_after() {
		?>
			</main><!-- #main -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'mohawkversionii_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'mohawkversionii_woocommerce_header_cart' ) ) {
			mohawkversionii_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'mohawkversionii_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function mohawkversionii_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		mohawkversionii_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'mohawkversionii_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'mohawkversionii_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function mohawkversionii_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'mohawkversionii' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'mohawkversionii' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'mohawkversionii_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function mohawkversionii_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php mohawkversionii_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}


/**
 * Create image url attribute to size options.
 */
function product_image_vartiant( $product ) {
    // check if has variations
    $variations = method_exists($product, 'get_available_variations') ? $product->get_available_variations() : false;

    if ( ! empty( $variations ) ) {

        $image_variation = [];
        foreach ( $variations as $variation ) {
            $image_variation[] = $variation['image']['url'];
        }
    }
    return $image_variation;
}

/**
 * Create options for colors or size.
 */
function product_colors_or_sizes( $product, $type = 'size' ) {
    // Check if has variations.
    $variations = method_exists( $product, 'get_available_variations' ) ? $product->get_available_variations() : false;
    $variation_type = 'size';

    // Check product if medal.
    $terms = get_the_terms( $product->id, 'product_cat' );
    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            if ( $term->name == 'Medals' ) {
                $variation_type = 'colors';
                break;
            }
        }
    }
    
    $color_items = [];
    $size_items = [];

    // Sort variations by price for size options.
    $the_variations = $variations;

    if ( $variation_type != 'colors' ) {
        $the_variations = [];

        if ( ! empty( $variations ) ) {
            $extra_key = range( 'A', 'Z' );
            $num = 0;

            foreach ( $variations as $variation ) {
                $var_key = $variation['display_price'] . $extra_key[$num];
                $the_variations[$var_key] = $variation;
                $num++;
            }

            ksort($the_variations);
        }
    }

    if ( ! empty( $the_variations ) ) {
        $color_wildcards = [
            'svp' => 'Silver',
            'bvp' => 'Bronze',
            'gvp' => 'Gold',
        ];

        $color_inits = [
            'G' => 'Gold',
            'S' => 'Silver',
            'B' => 'Bronze',
            'BR' => 'Bronze',
            'Y' => 'Yellow',
        ];
        
        $size_labels = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', '6XL', '7XL', '8XL'];

        foreach ( $the_variations as $variation ) {

            $attrs = @$variation['attributes'];
            $size_attr = $attrs['attribute_pa_monstasize'];

            // Check the size first.
            if ( $variation_type == 'colors' ) {

                $size_attr_items = explode( '-', $size_attr );
                $colorKey = '';
                $colorMatch = false;

                // Check the SKU against the wildcard patterns.
                foreach ( $color_wildcards as $pattern => $color ) {
                    if ( stripos( $size_attr, $pattern ) !== false ) {
                        $colorName = $color;
                        $colorKey = array_search( $colorName, $color_inits );
                        $colorMatch = true;
                        break;
                    }
                }

                // If no match found, fallback to checking the last letter of each segment.
                if ( ! $colorMatch ) {
                    foreach ( $size_attr_items as $size_attr_item ) {
                        foreach ( $color_inits as $key => $val ) {
                            $size_attr_item_last_letter = strtoupper( substr( $size_attr_item, -1 * strlen( $key ) ) );
                            
                            if ( ! empty( $color_inits[$size_attr_item_last_letter] ) && $size_attr_item_last_letter == $key ) {
                                $colorKey = $key;
                                $colorMatch = true;
                                break;
                            }
                        }
                    }
                }

                if ( ! empty( $colorKey ) ) {
                    $colorName = $color_inits[$colorKey];
                    $color_items[$size_attr] = $colorKey . '|' . $colorName . '|' . $size_attr . '|' . wc_price( $variation['display_price'] );
                }
            }

            // If size or colors is empty.
            if ( empty( $color_items ) || $variation_type == 'size' ) {
                $size = explode( '-', $size_attr );
                if ( ! empty( $size[count( $size ) - 1] ) ) {
                    $size_val = $size[count( $size ) - 1];
                    $size_val = strpos( $size_val, 'mm' ) !== false ? $size_val : $size_attr;
                    $size_items[$size_val] = $size_val . '|' . wc_price( $variation['display_price'] ) . '|' . $size_attr;
                    $variation_type = 'size';
                }
            }
        }

        $color_items = array_unique( $color_items );
        $size_items = array_unique( $size_items );
        usort( $color_items, 'medal_color_sorting' );
        ksort( $size_items );
    }

    if ( $type == 'color' ) {
        return $color_items;
    } else {
        return $size_items;
    }
}

/**
 * Get product image variants by key.
 */
function product_image_variants_by_key( $product ) {
    $variations = method_exists( $product, 'get_available_variations' ) ? $product->get_available_variations() : false;

    if ( ! empty( $variations ) ) {
        $image_variation = [];
        $num = 0;
        
        foreach ( $variations as $variation ) {
            $num++;
            $variant_key = $variation['attributes']['attribute_pa_monstasize'] ?? $num;
            $image_variation[$variant_key] = $variation['image']['url'];
        }
    }
    
    return $image_variation;
}
