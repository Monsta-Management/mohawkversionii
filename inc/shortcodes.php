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
** Featured product categories - [featured_categories]
*/
function shortcode_featured_categories() {
    ob_start();
    global $post;
    global $wp_query;

    $monsta_cats = [];
    $monsta_parent_array = get_term_by( 'slug', 'trophy-specialists', 'product_cat' );
    $monsta_parent = ! empty( $monsta_parent_array->term_id ) ? $monsta_parent_array->term_id : false;
    
    $args = [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => $monsta_parent
    ];
    
    $product_cat = get_terms( $args );
    $product_cat_id = ! empty( $wp_query->get_queried_object()->term_id ) ? $wp_query->get_queried_object()->term_id : 1;
    
    $ungrouped_array = get_term_by( 'slug', 'ungrouped', 'product_cat' );
    $ungrouped = $ungrouped_array->term_id;
    
    $category = get_queried_object();

    // Get the active category on carousel for product page.
    if ( is_product() ) {
        $product_terms = get_the_terms( $post->ID, 'product_cat' );
        $current_product_cat_ids = array();
        foreach ( $product_terms as $product_term ) {
            $current_product_cat_ids[] = $product_term->term_id;
        }
    }

    foreach ( $product_cat as $parent_product_cat ) {
        //$thumbnail_id = get_woocommerce_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
        $thumbnail_id = get_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
        $image = wp_get_attachment_url( $thumbnail_id );
        if ( $parent_product_cat->term_id != $ungrouped && $parent_product_cat->name != 'Uncategorized' ) {
            $checked_cat = $parent_product_cat->term_id == $category->term_id && ! empty( $parent_product_cat->term_id ) && ! empty( $category->term_id );
            $checked_parent = $parent_product_cat->term_id == $category->parent && ! empty( $parent_product_cat->term_id ) && !empty( $category->parent );
            $checked_product = ( is_product() && in_array( $parent_product_cat->term_id, $current_product_cat_ids ) ) && ! empty( $parent_product_cat->term_id ) && ! empty( $current_product_cat_ids );

            $monsta_cats[] = [
                'id'   => $parent_product_cat->term_id,
                'name' => $parent_product_cat->name,
                'url'  => get_term_link( $parent_product_cat->term_id ),
                'img'  => $image    
            ];
        }
    }
    
    // Sort ascending featured menus.
    $columns = array_column( $monsta_cats, 'name' );
    array_multisort( $columns, SORT_ASC, $monsta_cats );
    ?>

    <div class="row row-featured_categories row-thumb_categories">
        <?php foreach ( $monsta_cats as $cat ) { ?>
            <?php $img = ( strpos( basename( $cat['img'] ), 'product-no-image' ) !== false ) ? get_template_directory_uri() . '/images/trophy.png' : $cat['img']; ?>
            <div class="col col-item">
                <div class="cat-item text-center" >
                    <a href="#" class="cat-item-cta" data-cat="<?=$cat['id'];?>">
                        <div class="img-wrap u-db u-pr">
                            <img class="u-pa w-100 h-100" src="<?=$img;?>" alt="">
                        </div>
                        <h3 class="mt-1"><?=$cat['name'];?></h3>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row row-featured_categories row-tab_categories">
        <?php foreach ( $monsta_cats as $cat ) { ?>
            <div class="col col-item">
                <div class="cat-item_alt text-center" >
                    <a href="#" class="cat-item-tab" data-cat="<?=$cat['id'];?>">
                        <h3 class="mt-1"><?=$cat['name'];?></h3>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
    
    <div class="row-subcategories">
        <?php get_template_part( 'template-parts/subcategories', 'order' ); ?>
    </div>

    <?php
    // Dead/duplicate taxonomy queries removed — they were repeating the same
    // get_term_by / get_terms calls from the top of this function with no effect.

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
