<?php

global $product;

if ( ! $product ) return false;

// Cache ACF option lookups — these return the same value for every product card
// but were previously queried 24 times per page (once per card).
static $bulk_pricing_from = null;
if ( $bulk_pricing_from === null ) {
    $bulk_pricing_from = get_field( 'bulk_pricing_from', 'option' );
}

// Get image.
$thumb_url = false;
$attachment_ids = $product->get_gallery_image_ids();
$image_items = product_image_vartiant( $product );

// Featured image.
if ( has_post_thumbnail( $product->id ) ) {
    $thumb_id = get_post_thumbnail_id( $product->id );
    $thumb_url = reset( wp_get_attachment_image_src( $thumb_id, 'large' ) );
}

// Gallery images.
if ( empty( $thumb_url ) ) {
    foreach( $attachment_ids as $attachment_id ) {
        $thumb_url = wp_get_attachment_url( $attachment_id );
    }
}

// Variant image.
if ( empty( $thumb_url ) && ! empty( $image_items ) ) {
    $thumb_url = reset( $image_items );
}

// Placeholder if empty.
if ( empty( $thumb_url ) ) {
    $thumb_url = get_stylesheet_directory_uri() . '/images/placeholder.png';
}

$size_labels = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', '6XL', '7XL', '8XL'];
// Performance fix: call product_colors_or_sizes() once per type instead of 3 times.
$color_items = product_colors_or_sizes( $product, 'color' );
$size_items = product_colors_or_sizes( $product, 'size' );
$variation_type = ! empty( $color_items ) ? 'color' : 'size';
$color_codes = [
    'bronze' => 'color-1',
    'silver' => 'color-2',
    'gold'   => 'color-3',
];

$rank = get_product_rank( $product->id );
$rank = empty( $rank ) ? 'no-rank-provided' : $rank;

// Get the product supplier.
$supplier = get_the_terms( $product->id, 'trophymonsta_brand', true );
$supplier = empty( $supplier ) ? 'unknown' : $supplier[0]->name;

// Add 'NEW' badge to newly added products.
$badge = '';
$custompostmeta = get_post_meta( $product->id, '_trophymonsta_text_field', true );
if ( $custompostmeta == 'trophymonsta' ) {
	$infocommunique = get_post_meta( $product->id, '_trophymonsta_info_new', true );
    if ( $infocommunique == 'Yes' ) $badge = '<span class="prod-new item">NEW</span>';
}

// Bulk pricing 'from' price reference.
$bulk_price = '';

$regular_price = $product->get_price();

// Mapping of tiers to discount percentages based on provided correct prices.
$discount_rates = [
    'tier1' => 0.00, // No discount
    'tier2' => 0.08, // 8% discount
    'tier3' => 0.12, // 12% discount
    'tier4' => 0.15, // 15% discount (adjusted to match expected price)
    'tier5' => 0.20, // 20% discount (adjusted to match expected price)
    'tier6' => 0.25, // 25% discount (adjusted to match expected price)
];

// Determine the discount rate based on the selected tier.
$discount_rate = $discount_rates[$bulk_pricing_from] ?? 0.00;

// Calculate the bulk price.
$bulk_price = $regular_price - ($regular_price * $discount_rate);
$bulk_price = round($bulk_price, 2);

// S3 video/image display START.
$trophymonsta_video = get_post_meta( $product->get_id(), '_trophymonsta_video', true );
$trophymonsta_image = get_post_meta( $product->get_id(), '_trophymonsta_image', true );
?>

<div class="col-sm-2 product-item-wrap" data-new="<?=$infocommunique?>" supplier="<?=$supplier?>" data-rank="<?=$rank;?>">
    <div class="product-item product-card">

        <div class="product-inner">
            <div class="product-inner-img">
                <?php echo $badge; ?>
                <a href="<?=get_permalink();?>">
                    <?php if ( $trophymonsta_image ) { ?>
                        <img src="<?php echo $trophymonsta_image ?>" alt="<?php echo get_the_title(); ?>" loading="lazy">
                    <?php } else { ?>
                        <img src="<?=$thumb_url;?>" alt="<?=basename( $thumb_url );?>" loading="lazy">
                    <?php } ?>

                    <?php if ( $trophymonsta_video ) { ?>
                        <div class="hover-spin s3">
                            <video autoplay loop muted preload="none">
                                <source src="<?php echo $trophymonsta_video; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    <?php } ?>
                </a>
            </div>
            <div class="product-inner-link">
                <div class="row">
                    <div class="col-sm-6">
                        <a class="btn btn-success btn-sm btn-block " href="<?=get_permalink();?>">VIEW PRODUCT</a>
                    </div>
                </div>
            </div>

            <?php if ( $variation_type == 'color' ) { ?>
                <div class="product-variant-list u-product-variant-list">
                    <ul class="product-variant-items u-product-variant-items">
                        <?php foreach ( $color_items as $item ) { ?>
                            <?php $color_data = explode( '|' ,$item ) ?>
                            <li class="option-box u-color-box u-color-box-dimension" data-color="<?=$color_codes[strtolower( $color_data[1] )];?>">
                                <?php
							        $current_color = $color_data[0];
							        if ( $current_color == 'BR' ) {
							            echo 'B';
							        } else {
							            echo $color_data[0];
							        }
							    ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } else { ?>
                <div class="product-variant-list u-product-variant-list">
                    <ul class="product-variant-items u-product-variant-items">
                        <?php
                            $s = 0;
                            foreach ( $size_items as $size_item ) { 
                                if ( ! empty( $size_labels[$s] ) ) { 
                                    ?><li class="size-item u-size-item u-size-item-dimension"><?=$size_labels[$s];?></li><?php  
                                    $s++;  
                                }
                            }
                        ?>
                    </ul>
                </div>
            <?php } ?>

            <div class="product-title">
                <a href="<?=$product->get_permalink();?>" title="<?php the_title(); ?>">
                    <?php the_title(); ?>
                </a>
            </div>
            
            <div class="product-price u-product-price u-pull-right">
                <?php if ( $bulk_pricing_from == 'range' ) {
                    $lowest_price = $regular_price - ( $regular_price * 0.27 );
                    $highest_price = $regular_price;
                    ?>
                    <span>from</span> <?=wc_price( $lowest_price );?> <span class="amount">-</span> <?=wc_price( $highest_price );?>
                <?php } else { ?>
                    <span>from</span> <?=wc_price( $bulk_price );?>
                <?php } ?>
            </div>

            <?php if ( $variation_type == 'color' ) { ?>
                <ul class="option-list u-option-list">
                    <?php foreach ( $color_items as $item ) { ?>
                        <?php $color_data = explode( '|' ,$item ) ?>
                        <li class="option-item u-option-item">
                            <div class="option-box u-color-box u-color-box-dimension" data-color="<?=$color_codes[strtolower( $color_data[1] )];?>">
                                <?php
            				        $current_color = $color_data[0];
            				        if ( $current_color == 'BR' ) {
            				            echo 'B';
            				        } else {
            				            echo $color_data[0];
            				        }
            				    ?>
                            </div>&nbsp;<?=$color_data[2];?> 
                            <div class="product-price u-product-price u-pull-right">
                                <span>from</span> <?=wc_price( $bulk_price );?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <ul class="option-list u-option-list">
                    <?php
                        $s = 0;
                        foreach ( $size_items as $size_item ) { 
                            $size_data = explode( '|', $size_item );
                            $size_name = $size_data[0];
                            $size_price = $size_data[1];
                            if ( ! empty( $size_labels[$s] ) ) { 
                                ?><li class="option-item u-option-item"><div class="size-item u-size-item u-size-item-dimension"><?=$size_labels[$s];?></div>&nbsp;<?=$size_name;?> <div class="product-price u-product-price u-pull-right"><small>from</small> <?=$size_price;?></div></li><?php  
                                $s++;  
                            }
                        }
                    ?>
                </ul>
            <?php } ?>
        </div>
    </div>
</div>
