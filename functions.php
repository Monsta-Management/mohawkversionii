<?php
/**
 * Mohawk V2 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mohawk_V2
 */

if ( ! defined( 'MOHAWK_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'MOHAWK_VERSION', '2.4.1' );
}

/**
 * Insert additional header code.
 */
function secondary_header() {
	if ( function_exists( 'get_field' ) ) {
		$schema = get_field( 'header_code' );
		if ( ! empty( $schema ) ) {
			echo $schema;
		}
	}
}
add_action( 'wp_head', 'secondary_header', 1000 );


/**
 * Insert tracking codes into the <head>.
 */
function global_requirements_header() {
	if ( function_exists( 'get_field' ) ) {
		$head_tracking_tags = get_field( 'head_tracking_tags', 'option' );
		if ( ! empty( $head_tracking_tags ) ) {
			echo $head_tracking_tags;
		}
	}
}
add_action( 'wp_head', 'global_requirements_header', 999 );

/**
 * Insert tracking codes at the start of <body>.
 */
function global_requirements_body_start() {
	if ( function_exists( 'get_field' ) ) {
		$body_start_tracking_tags = get_field( 'body_start_tracking_tags', 'option' );
		if ( ! empty( $body_start_tracking_tags ) ) {
			echo $body_start_tracking_tags;
		}
	}
}
add_action( 'wp_body_open', 'global_requirements_body_start', 0 );

/**
 * Insert tracking codes before </body>.
 */
function global_requirements_body_end() {
	if ( function_exists( 'get_field' ) ) {
		$body_end_tracking_tags = get_field( 'body_end_tracking_tags', 'option' );
		if ( ! empty( $body_end_tracking_tags ) ) {
			echo $body_end_tracking_tags;
		}
	}
}
add_action( 'wp_footer', 'global_requirements_body_end', 999 );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mohawkversionii_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Mohawk V2, use a find and replace
		* to change 'mohawkversionii' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'mohawkversionii', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => __( 'Primary', 'mohawkversionii' ),
			'appearance-2-top-menu' => __( 'Top Menu', 'mohawkversionii' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'mohawkversionii_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'mohawkversionii_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mohawkversionii_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mohawkversionii_content_width', 640 );
}
add_action( 'after_setup_theme', 'mohawkversionii_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mohawkversionii_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mohawkversionii' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mohawkversionii' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mohawkversionii_widgets_init' );

/**
 * Admin enqueue scripts and styles.
 */
function custom_dashicons() {
	wp_enqueue_style( 'custom_dashicons-styles', get_template_directory_uri() . '/includes/glyphter-font/css/monsta-head.css' );
}
add_action( 'admin_enqueue_scripts', 'custom_dashicons' );

/**
 * Enqueue scripts and styles.
 */
function mohawkversionii_scripts() {
	wp_enqueue_style( 'mohawkversionii-wc-layout', get_site_url() . '/wp-content/plugins/woocommerce/assets/css/woocommerce-layout.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-wc-smallscreen', get_site_url() . '/wp-content/plugins/woocommerce/assets/css/woocommerce-smallscreen.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-wc-general', get_site_url() . '/wp-content/plugins/woocommerce/assets/css/woocommerce.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-bootstrap', get_template_directory_uri() . '/inc/bootstrap/css/bootstrap.min.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-monstamanagement', get_site_url() . '/wp-content/plugins/monstamanagement/_inc/trophymonsta.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-woo-variation-gallery', get_site_url() . '/wp-content/plugins/monstamanagement/css/variation-image/frontend.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-woo-variation-gallery-theme-support', get_site_url() . '/wp-content/plugins/monstamanagement/css/variation-image/theme-support.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-fontawesome', get_template_directory_uri() . '/inc/fontawesome/css/all.min.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-slick', get_template_directory_uri() . '/lib/slick/slick.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-swiper', get_template_directory_uri() . '/lib/swiper/swiper-bundle.min.css', array(), MOHAWK_VERSION );
	wp_enqueue_style( 'mohawkversionii-lightbox2', get_template_directory_uri() . '/lib/lightbox2/lightbox.min.css', array(), MOHAWK_VERSION );

	if ( is_product() ) {
		wp_enqueue_style( 'mohawkversionii-photoswipe', get_template_directory_uri() . '/lib/photoswipe/css/photoswipe.css', array(), MOHAWK_VERSION );
	}
	
	// Load minified stylesheet for performance (style.min.css), with style.css as RTL fallback.
	if ( ! is_child_theme() ) {
		wp_enqueue_style(
			'mohawkversionii-style',
			get_template_directory_uri() . '/style.min.css',
			array(),
			MOHAWK_VERSION
		);
		wp_style_add_data( 'mohawkversionii-style', 'rtl', 'replace' );
	}

	wp_enqueue_script( 'mohawkversionii-bootstrap', get_template_directory_uri() . '/inc/bootstrap/js/bootstrap.bundle.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-navigation', get_template_directory_uri() . '/js/navigation.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-slick', get_template_directory_uri() . '/lib/slick/slick.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-swiper', get_template_directory_uri() . '/lib/swiper/swiper-bundle.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-lightbox2', get_template_directory_uri() . '/lib/lightbox2/lightbox.min.js', array('jquery'), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-tm-custom-hide-category', get_site_url() . '/wp-content/plugins/monstamanagement/js/tm-custom-hide-category.js', array(), MOHAWK_VERSION, true );
	
	if ( is_product() ) {
		wp_enqueue_script( 'mohawkversionii-photoswipe', get_template_directory_uri() . '/lib/photoswipe/js/photoswipe.umd.min.js', array(), MOHAWK_VERSION, true );
		wp_enqueue_script( 'mohawkversionii-photoswipe-lightbox', get_template_directory_uri() . '/lib/photoswipe/js/photoswipe-lightbox.umd.min.js', array(), MOHAWK_VERSION, true );
	}
	
	wp_enqueue_script( 'mohawkversionii-accesories', get_template_directory_uri() . '/js/mohawk_accesories.min.js', array('jquery'), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-main', get_template_directory_uri() . '/js/scripts.min.js', array('jquery'), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-custom', get_template_directory_uri() . '/js/custom-scripts.min.js', array('jquery'), MOHAWK_VERSION, true );

	wp_localize_script( 
		'mohawkversionii-main',
		'mohawkSubmenuSettings',
		[
			'disableHoverSubmenu' => get_field('disable_hover_submenu', 'option') ? true : false,
		]
	);

	// Pass infinite scroll config to JS.
	if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
		global $wp_query;
		$current_term = '';
		if ( is_tax() || is_product_category() ) {
			$queried = get_queried_object();
			$current_term = ! empty( $queried->slug ) ? $queried->slug : '';
		}

		wp_localize_script( 'mohawkversionii-custom', 'mohawkInfinite', array(
			'ajaxurl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'mohawk_infinite_nonce' ),
			'max_pages'    => intval( $wp_query->max_num_pages ),
			'current_page' => max( 1, get_query_var( 'paged' ) ),
			'category'     => $current_term,
			'orderby'      => isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'menu_order',
			'per_page'     => 24,
		) );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mohawkversionii_scripts' );

/**
 * Mohawk theme update checker.
 */
require_once get_template_directory() . '/inc/updater.php';

/**
 * Mohawk import implementation.
 */
require get_template_directory() . '/inc/mohawk-import.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom menu walker.
 */
require get_template_directory() . '/inc/menu-walker.php';

/**
 * Custom modals, centre etc.
 */
require get_template_directory() . '/inc/accessories.php';

/**
 * Custom functions and its shortcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Mohawk infinite scroll — WC-aligned sort clauses.
 *
 * Joins wp_wc_product_meta_lookup the same way WC's catalog query does, so
 * AJAX and SSR produce identical orderings. Stable tiebreaker on product_id
 * eliminates duplicates at tied prices/totals (the 2026-06-08 dup-products-
 * across-pagination bug).
 *
 * One-shot filter — auto-removes itself after firing so it can't leak into
 * other queries on the same request.
 */
function mohawk_install_wc_sort_clauses( $metric, $direction = 'ASC' ) {
	$direction     = strtoupper( $direction ) === 'DESC' ? 'DESC' : 'ASC';
	$valid_metrics = array( 'min_price', 'max_price', 'total_sales', 'average_rating' );
	if ( ! in_array( $metric, $valid_metrics, true ) ) {
		return;
	}
	$filter = function( $clauses ) use ( $metric, $direction, &$filter ) {
		global $wpdb;
		$alias = 'mhwk_pml';
		if ( strpos( $clauses['join'], 'wc_product_meta_lookup' ) === false ) {
			$clauses['join'] .= " LEFT JOIN {$wpdb->prefix}wc_product_meta_lookup AS {$alias} ON {$wpdb->posts}.ID = {$alias}.product_id ";
			$order_table = $alias;
		} else {
			// WC already joined — use its canonical alias.
			$order_table = 'wc_product_meta_lookup';
		}
		// Tiebreaker direction must match the primary sort direction to match
		// WC's catalog query exactly (WC uses `product_id DESC` for DESC sorts
		// and `product_id ASC` for ASC sorts). A direction mismatch reverses
		// tied rows between SSR and AJAX, reintroducing the duplicate-products-
		// at-tied-price-boundaries bug.
		$tie_direction = $direction; // ASC pairs with ASC; DESC pairs with DESC
		if ( $metric === 'average_rating' ) {
			$clauses['orderby'] = "{$order_table}.average_rating {$direction}, {$order_table}.rating_count DESC, {$order_table}.product_id {$tie_direction}";
		} else {
			$clauses['orderby'] = "{$order_table}.{$metric} {$direction}, {$order_table}.product_id {$tie_direction}";
		}
		remove_filter( 'posts_clauses', $filter, 10 );
		return $clauses;
	};
	add_filter( 'posts_clauses', $filter, 10, 1 );
}

/**
 * Return the WC catalog per_page setting (columns × rows). Defaults to 12.
 * Used to align AJAX paging boundaries with SSR catalog paging.
 */
function mohawk_get_catalog_per_page() {
	$cols = (int) get_option( 'woocommerce_catalog_columns', 4 );
	$rows = (int) get_option( 'woocommerce_catalog_rows', 3 );
	$pp   = $cols * $rows;
	return $pp > 0 ? $pp : 12;
}

/**
 * AJAX handler for infinite scroll product loading.
 * Returns only the product card HTML fragments + total pages metadata.
 */
function mohawk_infinite_scroll_handler() {
	check_ajax_referer( 'mohawk_infinite_nonce', 'nonce' );

	$paged    = isset( $_GET['paged'] )    ? absint( $_GET['paged'] )    : 1;
	// Force per_page to match WC catalog (SSR) so AJAX page boundaries align
	// with SSR page boundaries. Ignore the JS-supplied per_page — historic
	// value was hard-coded to 24 in localize_script while WC catalog renders
	// 12 per page, producing both gaps and duplicates in the appended scroll.
	$per_page = mohawk_get_catalog_per_page();
	$category = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';
	$orderby  = isset( $_GET['orderby'] )  ? sanitize_text_field( $_GET['orderby'] )  : '';

	// ------------------------------------------------------------------
	// Search context detection.
	//
	// The current JS infinite-scroll script doesn't pass the search query
	// (existing fields: paged, per_page, category, orderby). Fall back to
	// parsing the referring URL — when a user scrolls on the search results
	// page, each AJAX call has that page as its referer, carrying ?s=...
	// in the URL. This lets us honour search without changing the front-end.
	//
	// Future improvement: have the JS also pass $_GET['s'] explicitly; this
	// handler already reads it as the primary source for that case.
	// ------------------------------------------------------------------
	$search   = isset( $_GET['s'] )        ? sanitize_text_field( $_GET['s'] )        : '';
	$type_aws = isset( $_GET['type_aws'] ) ? sanitize_text_field( $_GET['type_aws'] ) : '';

	if ( empty( $search ) ) {
		$referer = wp_get_referer();
		if ( $referer ) {
			$referer_q = parse_url( $referer, PHP_URL_QUERY );
			if ( $referer_q ) {
				parse_str( $referer_q, $rp );
				if ( ! empty( $rp['s'] ) ) {
					$search   = sanitize_text_field( $rp['s'] );
					$type_aws = isset( $rp['type_aws'] ) ? sanitize_text_field( $rp['type_aws'] ) : $type_aws;
				}
			}
		}
	}

	// ------------------------------------------------------------------
	// SEARCH path — run a product-search WP_Query when ?s= is detected.
	// The cached-ranking path below is wrong for search because it doesn't
	// filter by the search query; subsequent infinite-scroll pages would
	// return the full catalogue's ranking-ordered tail.
	// ------------------------------------------------------------------
	if ( ! empty( $search ) ) {
		// Preserve AWS plugin context — if it was in the original URL, simulate
		// it on the AJAX request so AWS's pre_get_posts hooks fire identically.
		if ( ! empty( $type_aws ) && empty( $_GET['type_aws'] ) ) {
			$_GET['type_aws'] = $type_aws;
		}

		$query_args = array(
			's'              => $search,
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		);

		// Use WC-aligned sort clauses (JOIN wc_product_meta_lookup) instead of
		// meta_value_num + postmeta. Adds a stable product_id tiebreaker so
		// products tied on price/sales/rating land in a deterministic order
		// across paginated WP_Query invocations.
		switch ( strtolower( $orderby ) ) {
			case 'price':
				mohawk_install_wc_sort_clauses( 'min_price', 'ASC' );
				break;
			case 'price-desc':
				mohawk_install_wc_sort_clauses( 'max_price', 'DESC' );
				break;
			case 'date':
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
				break;
			case 'popularity':
				mohawk_install_wc_sort_clauses( 'total_sales', 'DESC' );
				break;
			case 'rating':
				mohawk_install_wc_sort_clauses( 'average_rating', 'DESC' );
				break;
			// default (menu_order / '') — use relevance ordering from search
		}

		$query     = new WP_Query( $query_args );
		$max_pages = (int) $query->max_num_pages;

		ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product-card' );
			}
			wp_reset_postdata();
		}
		$html = ob_get_clean();

		wp_send_json_success( array(
			'html'      => $html,
			'max_pages' => $max_pages,
			'page'      => $paged,
		) );
		return;
	}

	// ------------------------------------------------------------------
	// SORT path — same as before: if user picked a real sort, run a
	// proper WC catalog WP_Query instead of the cached "ranking" path.
	// ------------------------------------------------------------------
	$orderby_lc = strtolower( $orderby );

	if ( $orderby_lc && $orderby_lc !== 'menu_order' ) {
		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		);

		if ( ! empty( $category ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $category,
				),
			);
		}

		// WC-aligned sort (see search path above for rationale).
		switch ( $orderby_lc ) {
			case 'price':
				mohawk_install_wc_sort_clauses( 'min_price', 'ASC' );
				break;
			case 'price-desc':
				mohawk_install_wc_sort_clauses( 'max_price', 'DESC' );
				break;
			case 'date':
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
				break;
			case 'popularity':
				mohawk_install_wc_sort_clauses( 'total_sales', 'DESC' );
				break;
			case 'rating':
				mohawk_install_wc_sort_clauses( 'average_rating', 'DESC' );
				break;
		}

		$query     = new WP_Query( $query_args );
		$max_pages = (int) $query->max_num_pages;

		ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product-card' );
			}
			wp_reset_postdata();
		}
		$html = ob_get_clean();

		wp_send_json_success( array(
			'html'      => $html,
			'max_pages' => $max_pages,
			'page'      => $paged,
		) );
		return;
	}

	// ------------------------------------------------------------------
	// DEFAULT path: cached "ranking" sorted product IDs.
	// (Original handler logic — preserved unchanged.)
	// ------------------------------------------------------------------

	// Get fully cached sorted product IDs from mohawkversion/inc/template-functions.php
	$all_product_ids = get_cached_sorted_product_ids(); // returns array of post IDs sorted by ranking

	// Get pre-cached category mapping (product_id => array of category_ids). If not cached, generate once and cache for future requests.
	$category_map = get_transient('mohawk_product_category_map');
	if ( false === $category_map ) {
		$category_map = [];
		foreach ( $all_product_ids as $pid ) {
			$terms = wp_get_post_terms( $pid, 'product_cat', [ 'fields' => 'ids' ] );
			$category_map[ $pid ] = $terms ?: [];
		}

		set_transient( 'mohawk_product_category_map', $category_map, HOUR_IN_SECONDS ); // cache it for 1 hour.
	}

	if ( ! empty( $category ) ) {
		$term = get_term_by( 'slug', $category, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$term_id = (int) $term->term_id;
			$all_product_ids = array_filter( $all_product_ids, function( $pid ) use ( $category_map, $term_id ) {
				return in_array( $term_id, $category_map[ $pid ] );
			});
		}
	}

	$total_products = count( $all_product_ids );
	$max_pages      = ceil( $total_products / $per_page );
	$offset         = ( $paged - 1 ) * $per_page;
	$page_ids       = array_slice( $all_product_ids, $offset, $per_page );

	if ( empty( $page_ids ) ) {
		wp_send_json_success( [
			'html'      => '',
			'max_pages' => $max_pages,
			'page'      => $paged,
		] );
	}

	$query = new WP_Query( [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'post__in'       => $page_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => $per_page,
		'no_found_rows'  => true,
	]);

	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product-card' );
		}
		wp_reset_postdata();
	}
	$html = ob_get_clean();

	wp_send_json_success( [
		'html'      => $html,
		'max_pages' => $max_pages,
		'page'      => $paged,
	] );
}
add_action( 'wp_ajax_mohawk_infinite_scroll', 'mohawk_infinite_scroll_handler' );
add_action( 'wp_ajax_nopriv_mohawk_infinite_scroll', 'mohawk_infinite_scroll_handler' );
