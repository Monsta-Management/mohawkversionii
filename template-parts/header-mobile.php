<?php
$logo          = get_field( 'site_logo', 'option' );
$tb_text       = get_field( 'top_bar_text', 'option' );
$tb_bg_color   = get_field( 'top_bar_background_color', 'option' );
$tb_text_color = get_field( 'top_bar_text_color', 'option' );

$cls = ( empty( $tb_text ) ) ?: 'has-top-bar';
?>

<header id="mobile-header" class="<?= $cls; ?> app-2">
    <?php if ( !empty($tb_text)) : ?>
        <div class="top-bar-wrap" style="background-color: <?= $tb_bg_color; ?>; color: <?= $tb_text_color; ?>; font-size: 0.9em; text-align: center; padding: 0.5em;">
            <div class="container"><?= nl2br($tb_text); ?></div>
        </div>
    <?php endif ?>
    <div class="container-fluid">
        <div class="row no-gutters">
            <div class="col-sm-6 col_left">
                <div id="logo">
                    <a href="<?=site_url();?>">
                        <?php if ( $logo ) { ?>
                            <img width="250" height="42" src="<?=$logo['url'];?>" class="custom-logo" alt="<?=get_bloginfo('name');?>">
                        <?php } else { ?>
                            <?php the_custom_logo(); ?>
                        <?php } ?>
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col_right app-ham">
                <div class="cta-menu">
                    <?php
                        $cart_count = WC()->cart->cart_contents_count ? WC()->cart->cart_contents_count : '0';
                        echo  '<a class="cart-customlocation cart-link" href="#minicart" data-toggle="modal" data-target="#minicart">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49.98 47.89">
                                <style>.cls-1 {fill: currentColor; stroke-width: 0px;}</style>
                                <g>
                                    <path class="cls-1" d="M10.79,7.38h.72c12.26,0,24.52.01,36.78-.02,1.21,0,1.97.89,1.6,2.16-1.38,4.65-2.69,9.31-4.02,13.97-.85,2.96-3.48,4.85-6.76,4.85-8.04,0-16.08,0-24.12,0h-.75c.17.8.26,1.56.48,2.27.53,1.65,2.13,2.75,3.92,2.75,1.71,0,3.41,0,5.12,0,5.86,0,11.71,0,17.57,0,.96,0,1.44.42,1.43,1.22-.01.63-.38,1.07-1.01,1.15-.21.03-.42.02-.63.02-7.26,0-14.53,0-21.79,0-3.84,0-6.46-2.27-7.1-6.06-1.21-7.14-2.44-14.27-3.68-21.4-.57-3.27-3.21-5.68-6.53-6.01-.31-.03-.63-.05-.95-.08C.36,2.15,0,1.75,0,1.08,0,.44.43-.02,1.11,0c.8.03,1.62.07,2.41.25,3.73.85,6.2,3.07,7.17,6.83.02.08.06.17.1.29ZM47.32,9.66H11.02c.94,5.5,1.87,10.95,2.81,16.45.24,0,.44,0,.65,0,8.14,0,16.29,0,24.43,0,.25,0,.49,0,.74-.03,2.1-.16,3.7-1.48,4.24-3.52.94-3.51,1.88-7.02,2.81-10.54.21-.77.4-1.55.61-2.37Z"/>
                                    <path class="cls-1" d="M35.21,37c3.09,0,5.44,2.37,5.42,5.46-.01,2.99-2.48,5.44-5.46,5.42-2.88-.02-5.31-2.54-5.29-5.48.03-3.03,2.37-5.41,5.32-5.4ZM35.18,45.5c1.79.01,3.22-1.35,3.23-3.06,0-1.78-1.37-3.19-3.15-3.2-1.74-.02-3.1,1.36-3.13,3.15-.03,1.69,1.35,3.1,3.05,3.11Z"/>
                                    <path class="cls-1" d="M19.41,37c2.97,0,5.29,2.36,5.3,5.4.01,2.98-2.37,5.46-5.27,5.48-3,.02-5.47-2.46-5.46-5.47.02-3.06,2.36-5.4,5.43-5.41ZM19.35,45.5c1.69.02,3.09-1.31,3.12-2.98.03-1.8-1.29-3.26-2.99-3.29-1.83-.03-3.24,1.32-3.27,3.13-.03,1.74,1.34,3.11,3.14,3.14Z"/>
                                </g>
                            </svg>
                            <span class="count">'.$cart_count.'</span></a>';
                    ?>
                </div>
                <ul class="mobile-header-nav">
                    <li class="menu_button">
                        <a href="#">
                            <span class="hamburger-box">
				                <span class="hamburger-inner"></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<nav id="mobile-navigation" class="u-pr app-2">
    <button type="button" class="close u-par" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="menu-main-menu-container">
        <div class="search-widget" data-total="<?= do_shortcode('[product_count]');?>"> 
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
        <div class="menu-wrap">
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
        <ul id="menu-main-menu-1" class="menu">
            <?php
                $taxonomy     = 'product_cat';
                $orderby      = 'name';  
                $show_count   = 0;
                $pad_counts   = 0;
                $hierarchical = 1;
                $title        = '';
                $empty        = 0;

                // Get the acf field value for the category slug, or default to 'trophy-specialists' if not set.
                $trophy_specialists = get_term_by( 'slug', get_field( 'category_slug', 'option' ) ?: 'trophy-specialists', 'product_cat' );
                
                $parent_id = 0; // default to root level.
                if ( $trophy_specialists && ! is_wp_error( $trophy_specialists ) ) {
                    $parent_id = $trophy_specialists->term_id;
                }
                
                $args = [
                    'taxonomy'     => $taxonomy,
                    'show_count'   => $show_count,
                    'pad_counts'   => $pad_counts,
                    'hierarchical' => $hierarchical,
                    'title_li'     => $title,
                    'hide_empty'   => $empty,
                    'parent'       => $trophy_specialists->term_id,
                    'orderby'      => 'name',
                    'order'        => 'ASC'    
                ];
                
                $sub_cats = get_categories( $args );
                
                // sort alphabetically ignoring case.
                usort( $sub_cats, function( $a, $b ) {
                    return strcasecmp( $a->name, $b->name );
                });
                
                if ( $sub_cats ) {
                    foreach ( $sub_cats as $sub_category ) {
                        if ( $sub_category->name != 'Ungrouped' ) {
                            $term_link = get_term_link( $sub_category->slug, 'product_cat' );
                            
                            if ( ! is_wp_error( $term_link ) ) {
                                echo '<div class="menu-item menu-item-type-custom menu-item-object-custom collapsible">
                                    <a class="view-category" href="' . get_term_link( $sub_category->slug, 'product_cat' ) . '" style="line-height: 2 !important;position: absolute;left: 0;z-index: 9;">
                                        <svg width="20" height="20" viewBox="0 0 12 12" enable-background="new 0 0 12 12" id="Слой_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <circle cx="6" cy="6" fill="#ffffff" r="1.5"></circle> <path d="M6,2C4,2,2,3,0,6c2,3,4,4,6,4s4-1,6-4C10,3,8,2,6,2z M6,8.5C4.621582,8.5,3.5,7.3789063,3.5,6 S4.621582,3.5,6,3.5S8.5,4.6210938,8.5,6S7.378418,8.5,6,8.5z" fill="#ffffff"></path> </g> </g></svg>
                                    </a>
                                    <a class="category-name" href="' . get_term_link( $sub_category->term_id, 'product_cat' ) . '">' . $sub_category->name . '</a>
                                    <span></span>
                                  </div>';
                            }
                
                            $args2 = [
                                'taxonomy'   => $taxonomy,
                                'parent'     => $sub_category->term_id,
                                'hide_empty' => $empty,
                                'orderby'    => 'name',
                                'order'      => 'ASC'    
                            ];
                            
                            $subsub_cats = get_categories( $args2 );
                
                            // sort sub-sub categories too.
                            usort( $subsub_cats, function( $a, $b ) {
                                return strcasecmp( $a->name, $b->name );
                            });
                
                            if ( $subsub_cats ) {
                                echo '<div class="content">';
                                    foreach ( $subsub_cats as $subsub_category ) {                                                               
                                        $subsub_link = get_term_link( $subsub_category->slug, 'product_cat' );
                                        
                                        if ( ! is_wp_error( $subsub_link ) ) {
                                            echo '<div class="sub-content">
                                                    <a href="'. esc_url( $subsub_link ) .'">'. esc_html( $subsub_category->name ) .'</a>
                                                  </div>';
                                        }
                                    }
                                echo '</div>';
                            }
                        }
                    }
                }
            ?>
        </ul>
    </div>
</nav>