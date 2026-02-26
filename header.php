<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mohawk_Version_Two
 */

$logo = get_field( 'site_logo', 'option' );
$logo_size = get_field( 'site_logo_size', 'option' );
$logo_size = ( $logo_size == 'tall' ) ? 'header-lg' : 'header-sm';
$telephone = get_field( 'site_telephone', 'option' );
$submenu_color = get_field( 'site_submenu_color', 'option' ) ? get_field( 'site_submenu_color', 'option' ) : 'black';
$mainmenu_color = get_field( 'site_mainmenu_color', 'option' ) ? get_field( 'site_mainmenu_color', 'option' ) : 'black';
$site_favicon = get_field( 'site_favicon', 'option' );

// TOP BAR MESSAGE.
$tb_text = get_field( 'top_bar_text', 'option' );
$tb_bg_color = get_field( 'top_bar_background_color', 'option' );
$tb_text_color = get_field( 'top_bar_text_color', 'option' );
$tb_class = $tb_text ? 'has-topbar' : ''
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
    <?php if ( $site_favicon ){ ?>
        <link rel="icon" type="image/x-icon" href="<?php echo esc_url( $site_favicon ); ?>">
    <?php } ?>
</head>
<body <?php body_class( $tb_class ); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">

        <header class="header-desktop <?php echo $logo_size; ?> submenu-<?php echo $submenu_color; ?> mainmenu-<?php echo $mainmenu_color; ?> ">
            <div class="header-inner u-pr">
                <?php if ( !empty( $tb_text ) ) : ?>
                <div class="top-bar-wrap" style="background-color: <?php echo $tb_bg_color; ?>; color: <?php echo $tb_text_color; ?>; font-size: 0.9em; text-align: center; padding: 0.5em;">
                    <div class="container"><?php echo nl2br( $tb_text ); ?></div>
                </div>
                <?php endif ?>
                <div class="row-header hide">
                    <div class="container">
                        <div class="row no-gutters row-header-inner align-items-center">
                            <div class="col-sm-4 d-flex align-items-center justify-content-start">
                                <ul class="cta-menu menu">
                                    <?php
                                        $cart_count = WC()->cart->cart_contents_count ? WC()->cart->cart_contents_count : '0';
                                        $output = "<li class='cart'>";
                                        $output .= '<a class="cart-customlocation cart-link" href="#minicart" data-toggle="modal" data-target="#minicart">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49.98 47.89">
                                                <style>.cls-1 {fill: currentColor; stroke-width: 0px;}</style>
                                                <g>
                                                    <path class="cls-1" d="M10.79,7.38h.72c12.26,0,24.52.01,36.78-.02,1.21,0,1.97.89,1.6,2.16-1.38,4.65-2.69,9.31-4.02,13.97-.85,2.96-3.48,4.85-6.76,4.85-8.04,0-16.08,0-24.12,0h-.75c.17.8.26,1.56.48,2.27.53,1.65,2.13,2.75,3.92,2.75,1.71,0,3.41,0,5.12,0,5.86,0,11.71,0,17.57,0,.96,0,1.44.42,1.43,1.22-.01.63-.38,1.07-1.01,1.15-.21.03-.42.02-.63.02-7.26,0-14.53,0-21.79,0-3.84,0-6.46-2.27-7.1-6.06-1.21-7.14-2.44-14.27-3.68-21.4-.57-3.27-3.21-5.68-6.53-6.01-.31-.03-.63-.05-.95-.08C.36,2.15,0,1.75,0,1.08,0,.44.43-.02,1.11,0c.8.03,1.62.07,2.41.25,3.73.85,6.2,3.07,7.17,6.83.02.08.06.17.1.29ZM47.32,9.66H11.02c.94,5.5,1.87,10.95,2.81,16.45.24,0,.44,0,.65,0,8.14,0,16.29,0,24.43,0,.25,0,.49,0,.74-.03,2.1-.16,3.7-1.48,4.24-3.52.94-3.51,1.88-7.02,2.81-10.54.21-.77.4-1.55.61-2.37Z"/>
                                                    <path class="cls-1" d="M35.21,37c3.09,0,5.44,2.37,5.42,5.46-.01,2.99-2.48,5.44-5.46,5.42-2.88-.02-5.31-2.54-5.29-5.48.03-3.03,2.37-5.41,5.32-5.4ZM35.18,45.5c1.79.01,3.22-1.35,3.23-3.06,0-1.78-1.37-3.19-3.15-3.2-1.74-.02-3.1,1.36-3.13,3.15-.03,1.69,1.35,3.1,3.05,3.11Z"/>
                                                    <path class="cls-1" d="M19.41,37c2.97,0,5.29,2.36,5.3,5.4.01,2.98-2.37,5.46-5.27,5.48-3,.02-5.47-2.46-5.46-5.47.02-3.06,2.36-5.4,5.43-5.41ZM19.35,45.5c1.69.02,3.09-1.31,3.12-2.98.03-1.8-1.29-3.26-2.99-3.29-1.83-.03-3.24,1.32-3.27,3.13-.03,1.74,1.34,3.11,3.14,3.14Z"/>
                                                </g>
                                            </svg>
                                            <span class="count">'.$cart_count.'</span> SHOP</a>';
                                    
                                        if ( $telephone ) :
                                            $output .= "<li class='phone'>";
                                            $output .= '<a href="tel:+'.str_replace(' ', '', $telephone).'">
                                                <svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44.87 44.88">
                                                  <defs>
                                                    <style>
                                                      .cls-1 {
                                                        fill: currentColor;
                                                        stroke-width: 0px;
                                                      }
                                                    </style>
                                                  </defs>
                                                  <g id="Layer_1-2" data-name="Layer 1">
                                                    <g>
                                                      <path class="cls-1" d="M31.54,44.88c-1.95-.06-4.35-.75-6.67-1.77-7.41-3.27-13.45-8.23-18.26-14.73-2.5-3.38-4.54-6.99-5.79-11.03C.31,15.72-.01,14.05,0,12.32c.02-1.96.67-3.66,2.05-5.06,1.07-1.09,2.15-2.18,3.25-3.25,2.03-1.97,4.43-1.98,6.49-.06.33.31.66.62.97.94,1.45,1.49,2.9,2.97,4.34,4.48,1.83,1.92,1.84,4.29.02,6.22-.9.95-1.83,1.87-2.78,2.75-.35.32-.34.57-.15.94.84,1.71,2,3.19,3.24,4.61,1.96,2.25,4.13,4.26,6.66,5.85.54.34,1.12.64,1.7.92.13.06.4-.03.51-.14.97-.94,1.91-1.92,2.89-2.85,1.11-1.06,2.42-1.57,3.98-1.24.84.18,1.56.6,2.17,1.2,1.79,1.78,3.59,3.56,5.37,5.37,1.94,1.97,2,4.38.12,6.42-1.14,1.24-2.35,2.43-3.58,3.59-1.42,1.34-3.16,1.87-5.7,1.85ZM31.8,42.39c1.75.02,2.92-.28,3.84-1.21,1.1-1.11,2.19-2.23,3.24-3.39,1.05-1.15.99-2.03-.12-3.15-1.05-1.05-2.09-2.1-3.14-3.14-.76-.75-1.49-1.52-2.3-2.21-.72-.61-1.45-.56-2.2.02-.2.16-.39.34-.57.52-1.02,1.01-2.02,2.03-3.05,3.03-.72.71-1.44.8-2.34.35-.57-.29-1.15-.59-1.7-.92-2.92-1.74-5.42-3.97-7.67-6.51-1.64-1.85-3.15-3.79-4.14-6.09-.51-1.17-.38-1.83.54-2.73,1.03-1.02,2.08-2.02,3.09-3.06.91-.93.91-1.89.01-2.81-1.72-1.76-3.46-3.51-5.22-5.23-1.1-1.07-2-1.03-3.09.06-.88.87-1.72,1.79-2.64,2.62-1.39,1.26-1.91,2.8-1.79,4.63.12,1.96.73,3.78,1.47,5.58,1.7,4.14,4.17,7.8,7.11,11.15,4.32,4.92,9.46,8.72,15.55,11.18,1.8.73,3.66,1.24,5.13,1.3Z"/>
                                                      <path class="cls-1" d="M44.87,19.91c0,.67-.45,1.22-1.06,1.3-.74.09-1.31-.34-1.46-1.16-.31-1.79-.82-3.52-1.61-5.15-3.24-6.73-8.48-10.91-15.85-12.37-.94-.19-1.42-.77-1.23-1.55.18-.73.76-1.09,1.54-.96,2.99.51,5.8,1.51,8.36,3.13,6.09,3.82,9.88,9.24,11.24,16.33.02.09.03.19.05.29,0,.05,0,.1.01.14Z"/>
                                                      <path class="cls-1" d="M35.57,21.61c-.7,0-1.17-.42-1.32-1.22-.38-2.02-1.2-3.83-2.52-5.41-1.91-2.3-4.33-3.76-7.27-4.35-.52-.1-.97-.28-1.16-.82-.35-.95.42-1.85,1.43-1.68,3.31.56,6.09,2.11,8.33,4.6,1.81,2,3,4.33,3.59,6.96.07.32.1.68.05,1-.09.56-.58.93-1.13.93Z"/>
                                                    </g>
                                                  </g>
                                                </svg>
                                            ';
                                            $output .= $telephone;
                                            $output .= '</a>';
                                        endif;
                                        
                                        echo $output;
                                    ?>
                                </ul>
                            </div>
                            <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                <div id="logo" class="logo logo_white">
                                    <a href="<?php echo esc_url( site_url() );?>">
                                        <?php if ( $logo ) { ?>
                                            <img width="250" height="42" src="<?php echo esc_url( $logo['url'] );?>" class="custom-logo" alt="<?php echo get_bloginfo( 'name' ); ?>">
                                        <?php } else { ?>
                                            <?php the_custom_logo(); ?>
                                        <?php } ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right d-flex align-items-center justify-content-end">
                                <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="btn btn-secondary btn-lg text-uppercase">Shop Now</a>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="row-menu">
                    <div class="top-menu-wrap">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-sm-8 text-left">
                                    <?php
                                        wp_nav_menu(
                                            [
                                                'theme_location' => 'appearance-2-top-menu',
                                                'menu_class'     => 'top-menu menu',
                                                'container'      => false, 
                                            ]
                                        );
                                    ?>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="search-widget" data-total="<?php echo do_shortcode( '[product_count]' ); ?>">
                                        <?php aws_get_search_form( true ); ?>
                                        <span class="icon">
                                            <svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.02 39.97">
                                              <defs>
                                                <style>
                                                  .cls-1 {
                                                    fill: currentColor;
                                                    stroke-width: 0px;
                                                  }
                                                </style>
                                              </defs>
                                              <g id="Layer_1-2" data-name="Layer 1">
                                                <g id="Wct2Sh.tif">
                                                  <path class="cls-1" d="M27.13,30.67c-7.94,5.55-17.86,3.71-23.26-2.89C-1.54,21.15-1.25,11.65,4.57,5.39,10.32-.81,19.86-1.77,26.81,3.07c6.89,4.79,10.05,15.36,3.92,24.04.22.16.49.32.7.54,2.59,2.58,5.19,5.16,7.76,7.76.86.87,1.04,2.01.55,3.04-.48,1.01-1.52,1.69-2.62,1.48-.65-.12-1.35-.47-1.82-.93-2.61-2.53-5.16-5.12-7.73-7.7-.18-.18-.3-.42-.45-.64ZM28.66,16.94c-.03-6.45-5.27-11.64-11.72-11.62-6.46.03-11.62,5.25-11.6,11.72.02,6.41,5.25,11.61,11.67,11.6,6.43-.01,11.68-5.29,11.65-11.7Z"/>
                                                </g>
                                              </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div id="menu-carousel">
                            <?php 
                                wp_nav_menu( 
                                    [
                                        'theme_location' => 'menu-1',
                                        'walker'         => new The_Menu_Walker()    
                                    ]    
                                );
                            ?>
                        </div>
                    </div>
                </div>
                
                <?php get_template_part( 'template-parts/subcategories', 'order' ); ?>
                
            </div>
        </header>
        
        <div class="header-clone <?php echo $logo_size; ?>"></div>
        
        <?php include get_theme_file_path('template-parts/header-mobile.php'); ?>
        
        <?php
            if( is_front_page() && file_exists( get_theme_file_path( 'template-parts/content-banner-slider.php' ) ) ) {
                include get_theme_file_path( 'template-parts/content-banner-slider.php' );
            }
        ?>
        