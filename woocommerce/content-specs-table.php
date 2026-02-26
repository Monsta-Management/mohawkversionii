<?php
global $product; 

$engraving_note = 'Text & *Logo included * See Checkout for Logo file type required';
$brands = get_the_terms( $product->ID , 'trophymonsta_brand' ); 

if ( ! empty( $brands ) ) {
	foreach ( $brands as $brand ) {
		if ( $brand->name == 'FRESSKO' ) {
			$engraving_note = 'Engraving for this product is an additional cost';
		}
	}
}
?>

<div class="specs-tables mt-4">
	<div class="row specs-row">
		<div class="col-md-6 col-sku">
			<div class="spec-item-wrap">
				<strong>SKU:</strong>
				<em class="pull-right"><?=$product->get_sku();?></em>
			</div>
		</div>
		<div class="col-md-6 col-logo">
			<div class="spec-item-wrap">
				<strong><?=$engraving_note;?></strong>
			</div>
		</div>
	</div>
</div>