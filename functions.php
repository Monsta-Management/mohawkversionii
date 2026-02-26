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
	define( 'MOHAWK_VERSION', '1.2.6' );
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
    wp_enqueue_style( 'mohawkversionii-bootstrap', get_template_directory_uri() . '/inc/bootstrap/css/bootstrap.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-monstamanagement', get_site_url() . '/wp-content/plugins/monstamanagement/_inc/trophymonsta.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-woo-variation-gallery', get_site_url() . '/wp-content/plugins/monstamanagement/css/variation-image/frontend.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-woo-variation-gallery-theme-support', get_site_url() . '/wp-content/plugins/monstamanagement/css/variation-image/theme-support.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-fontawesome', get_template_directory_uri() . '/inc/fontawesome/css/all.min.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-slick', get_template_directory_uri() . '/lib/slick/slick.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-swiper', get_template_directory_uri() . '/lib/swiper/swiper-bundle.min.css', array(), MOHAWK_VERSION );
    wp_enqueue_style( 'mohawkversionii-lightbox2', get_template_directory_uri() . '/lib/lightbox2/lightbox.min.css', array(), MOHAWK_VERSION );
    
    if ( is_product() ) {
		//wp_enqueue_style( 'mohawkversionii-drift', get_template_directory_uri() . '/lib/drift/drift.min.css', array(), MOHAWK_VERSION );
		wp_enqueue_style( 'mohawkversionii-photoswipe', get_template_directory_uri() . '/lib/photoswipe/css/photoswipe.css', array(), MOHAWK_VERSION );
	}
    
	wp_enqueue_style( 'mohawkversionii-style', get_stylesheet_uri(), array(), MOHAWK_VERSION );
	wp_style_add_data( 'mohawkversionii-style', 'rtl', 'replace' );

    wp_enqueue_script( 'mohawkversionii-bootstrap', get_template_directory_uri() . '/inc/bootstrap/js/bootstrap.bundle.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-navigation', get_template_directory_uri() . '/js/navigation.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-slick', get_template_directory_uri() . '/lib/slick/slick.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-swiper', get_template_directory_uri() . '/lib/swiper/swiper-bundle.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-lightbox2', get_template_directory_uri() . '/lib/lightbox2/lightbox.min.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-tm-custom-hide-category', get_site_url() . '/wp-content/plugins/monstamanagement/js/tm-custom-hide-category.js', array(), MOHAWK_VERSION, true );
	
	if ( is_product() ) {
		//wp_enqueue_script( 'mohawkversionii-drift', get_template_directory_uri() . '/lib/drift/drift.min.js', array(), MOHAWK_VERSION, true );
		wp_enqueue_script( 'mohawkversionii-photoswipe', get_template_directory_uri() . '/lib/photoswipe/js/photoswipe.umd.min.js', array(), MOHAWK_VERSION, true );
		wp_enqueue_script( 'mohawkversionii-photoswipe-lightbox', get_template_directory_uri() . '/lib/photoswipe/js/photoswipe-lightbox.umd.min.js', array(), MOHAWK_VERSION, true );
	}
	
	wp_enqueue_script( 'mohawkversionii-accesories', get_template_directory_uri() . '/js/mohawk_accesories.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-main', get_template_directory_uri() . '/js/scripts.js', array(), MOHAWK_VERSION, true );
	wp_enqueue_script( 'mohawkversionii-custom', get_template_directory_uri() . '/js/custom-scripts.js', array(), MOHAWK_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mohawkversionii_scripts' );

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
 * S3 url validator.
 */
/*function s3_url_validator( $url ) {
    $response = wp_remote_head( $url );
    
    // If it's a valid response (HTTP 200), return true.
    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
        return true;
    }
    
    // If the first check failed, try the lowercase version.
    $lowercase_url = strtolower( $url );
    $response = wp_remote_head( $lowercase_url );
    
    // Return true if the lowercase URL is valid, false otherwise.
    return ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200;
}*/
/*function s3_url_validator( $url ) {
    // Generate a unique cache key for the URL.
    $cache_key = 's3_url_validator_' . md5( $url );

    // Attempt to get a cached result.
    $cached_result = get_transient( $cache_key );

    if ( $cached_result !== false ) {
        return $cached_result;
    }

    // Perform the HTTP HEAD request.
    $response = wp_remote_head( $url );

    // Validate the response.
    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
        set_transient( $cache_key, true, HOUR_IN_SECONDS );
        return true;
    }

    // Try the lowercase URL as a fallback.
    $lowercase_url = strtolower( $url );
    $response = wp_remote_head( $lowercase_url );

    $is_valid = ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200;

    // Cache the result for 1 hour.
    set_transient( $cache_key, $is_valid, HOUR_IN_SECONDS );

    return $is_valid;
}*/

/*
*
* PRODUCT SORTING OVERRIDES START
*
*/
function remove_old_sorting_hook() {
    remove_action( 'pre_get_posts', 'sort_woocommerce_products_by_term_ranking' );
}
add_action( 'after_setup_theme', 'remove_old_sorting_hook', 999 );

// Custom sorting options.
function monsta_customize_product_sorting( $sorting_options ){
    $sorting_options = array(
        'menu_order'        => __( 'FILTER PRODUCTS', 'mohawkversionii' ),
        'popularity'        => __( 'Sort by Popularity', 'mohawkversionii' ),
        'date'              => __( 'Sort by New', 'mohawkversionii' ),
        'price-low-to-high' => __( 'Sort by Price: low to high', 'mohawkversionii' ),
        'price-high-to-low' => __( 'Sort by Price: high to low', 'mohawkversionii' ),
    );

    return $sorting_options;
}
add_filter( 'woocommerce_catalog_orderby', 'monsta_customize_product_sorting' );

function monsta_get_catalog_ordering_args( $args ) {
    // Check if the 'orderby' parameter is set
    if ( isset( $_GET['orderby'] ) ) {
        switch ( $_GET['orderby'] ) {
            case 'popularity':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
                $args['order'] = 'DESC';
                break;
                
            case 'date':
                $args['meta_key'] = '_trophymonsta_info_new';
                $args['orderby'] = 'meta_value';
                $args['order'] = 'DESC';
                break;
                
            case 'price-low-to-high':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
                $args['meta_type'] = 'DECIMAL';
                break;
                
            case 'price-high-to-low':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
                $args['meta_type'] = 'DECIMAL';
                break;
                
            case 'menu_order':
            default:
                $args['meta_key'] = '_trophymonsta_info_new';
                $args['orderby'] = 'meta_value';
                $args['order'] = 'DESC';
                break;
        }
    }

    return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'monsta_get_catalog_ordering_args' );