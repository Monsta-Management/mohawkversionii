<?php global $product; ?>

<div class="summary-wrap product-summary-wrap" data-currency="<?php echo  get_woocommerce_currency_symbol(); ?>">
	<div class="summary-cover">
		<h1><?php the_title(); ?></h1>
		<?php if($product->get_sku()){ ?>
			<h4 class="f-14 f-400">Product SKU: <strong><?=$product->get_sku();?></strong></h4>
		<?php } ?>
	</div>
	<div class="summary-orig">
		<?php
		do_action( 'woocommerce_single_product_summary' );
		?>
	</div>
	<div class="variant-options hide">
		<?php

			$size_labels = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', '6XL', '7XL', '8XL'];
			$color_items = product_colors_or_sizes($product, 'color');
			$size_items = product_colors_or_sizes($product, 'size');
			$image_items = product_image_vartiant($product);
			$image_items_by_key = product_image_variants_by_key($product);
			$variation_type = !empty($color_items) ? 'color' : 'size';
			$color_codes = [
				'bronze' => 'color-1',
				'silver' => 'color-2',
				'gold' => 'color-3',
			];
		?>

		<?php if( $variation_type == 'color' ){ ?>
        	<table>
        		<tr class="tr-colors">
        			<td class="label"><label>Colours</label></td>
        			<td class="value">
        				<div class="product-variant-list">
        					<div class="product-variant-items-new" count="<?=count($color_items);?>">
        						<?php foreach ($color_items as $item) { ?>
        							<?php $color_data = explode('|' ,$item) ?>
        							<?php $variant_key = $color_data[2] ?>
        							<div data-variant="<?=strtolower($color_data[2]);?>" class="option-item u-option-item-product" data-image-url="<?= $image_items_by_key[$variant_key]; ?>" data-color="<?=$color_codes[strtolower($color_data[1])];?>">
        							    <?php
        							        $current_color = $color_data[0];
        							        if ( $current_color == 'BR' ) {
        							            echo 'B';
        							        } else {
        							            echo $color_data[0];
        							        }
        							    ?>
        							</div>
        						<?php } ?>
        					</div>
        				</div>
        			</td>
        		</tr>
        	</table>
        <?php } else { ?>
			<table>
				<tr class="tr-sizes">
					<td class="label"><label>Sizes</label></td>
					<td class="value">
						<div class="product-variant-list">
							<div class="product-variant-items">
								<?php
								$s = 0;
								usort($size_items, function ($item1, $item2) {
									if ($item1 == $item2) return 0;
									return $item1 < $item2 ? -1 : 1;
								});

								foreach ($size_items as $k => $size_item) { 
									if (!empty($size_labels[$k])) { 
										$size_data = explode('|', $size_item);
										?><div data-variant="<?=strtolower($size_data[2]);?>" class="size-item u-size-item-product" data-image-url="<?= $image_items[$k]; ?>"><?=$size_labels[$k];?></div><?php  
										$s++;  
									}
								}
								?>
							</div>
						</div>
					</td>
				</tr>
			</table>
				

		<?php } ?>
		
	</div>
</div>
