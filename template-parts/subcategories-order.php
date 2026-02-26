<?php
global $post;
global $wp_query;

$remove_subcategories = get_field( 'remove_subcategories_in_menus', 'option' );
$remove_subcategories = ! empty( $remove_subcategories ) ? reset( $remove_subcategories ) : false;

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
?>

<div class="submenu-wrap u-pa w-100">
	<?php
		foreach ( $product_cat as $parent_product_cat ) {

			if ( $parent_product_cat->term_id != $ungrouped && $parent_product_cat->name != 'Uncategorized' ) {

				$checked_cat = $parent_product_cat->term_id == $category->term_id && !empty( $parent_product_cat->term_id ) && !empty( $category->term_id );
				$checked_parent = $parent_product_cat->term_id == $category->parent && !empty( $parent_product_cat->term_id ) && !empty( $category->parent );
				$checked_product = ( is_product() && in_array( $parent_product_cat->term_id, $current_product_cat_ids ) ) && !empty( $parent_product_cat->term_id ) && !empty( $current_product_cat_ids );
				
				$category_name = $parent_product_cat->name;
				$category_link = get_term_link( $parent_product_cat->term_id );

				$subcats_args = [
				    'taxonomy'   => 'product_cat',
					'hide_empty' => false,
					'parent'     => $parent_product_cat->term_id,
					'orderby'    => 'name',
					'order'      => 'DESC'    
				];

				$subcats_product_cats = get_terms( $subcats_args );

				usort( $subcats_product_cats, 'sort_by_name' );

				$max_list = 0;
				$AZ_range = [];
				
				foreach( range( 'A','Z' ) as $v ) {
					$AZ_range[$v] = [];
				}
				?>
				
				<?php if ( $subcats_product_cats ) { ?>
    				<div class="submenu-inner submenu-item u-pa w-100" data-cat="<?=$parent_product_cat->term_id;?>">
    					<div class="container">
    						<div class="submenu-container">
    						    <div class="row">
    						        <a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo esc_html( $category_name ); ?>" class="btn-category">View All <?php echo esc_html( $category_name ); ?> Products</a>
    						    </div>
    							<div class="row">
    								<?php
        								foreach ( $subcats_product_cats as $subcats_product_cat ) { 
        									if( $parent_product_cat->term_id == $subcats_product_cat->parent ) {
        										$parent_letter = substr( $subcats_product_cat->name, 0, 1 );
        
        										$child_args = [
        										    'taxonomy'   => 'product_cat',
        											'hide_empty' => false,
        											'parent'     => $subcats_product_cat->term_id,
        											'orderby'    => 'name',
        											'order'      => 'DESC'    
        										];
        
        										$child_product_cats = get_terms( $child_args );
        
        										usort( $child_product_cats, 'sort_by_name' );
        
        										$AZ_range_data = ! empty( $AZ_range[$parent_letter] ) ? $AZ_range[$parent_letter] : [];
        
        										if ( !array_key_exists( $subcats_product_cat->name, $AZ_range_data ) ) {
        											$AZ_range[$parent_letter][$subcats_product_cat->name] = [
        											    'name'   => $subcats_product_cat->name,
        												'url'    => get_term_link( $subcats_product_cat->term_id ),
        												'childs' => $child_product_cats    
        											];
        
        											// Get the total of the list.
        											if ( empty( $AZ_range[$parent_letter]['total'] ) ) {
        												$AZ_range[$parent_letter]['total'] = count( $child_product_cats ) + 1;
        											} else {
        												$AZ_range[$parent_letter]['total'] = $AZ_range[$parent_letter]['total'] + count( $child_product_cats ) + 1;
        											}
        
        											if( $max_list < $AZ_range[$parent_letter]['total'] ) {
        												$max_list = $AZ_range[$parent_letter]['total'];
        											}
        										}
        									}
        								}
    								?>
    
    								<?php $col = 0; ?>
    
    								<?php foreach ( $AZ_range as $letter => $data ) { ?>
    									<?php if ( ! empty( $data ) ) { ?>
    									<?php $col++; ?>
    										<div class="col-md-2">
    											<h3><?=$letter;?></h3>
    											<ul>
    												<?php foreach ( $data as $cat_data ) { ?>
    													<?php if ( $cat_data != 'total' && !empty( $cat_data['name'] ) ) {  ?>
    														<li>
    															<a href="<?=$cat_data['url'];?>"><?=$cat_data['name'];?></a>
    															<?php
        															$childs = $cat_data['childs'];
        															
        															if( $childs && $remove_subcategories != 'yes' ) {
        																?><ul><?php
            																foreach ( $childs as $child ) {
            																	?>
            																	    <li><a href="<?=get_term_link( $child->term_id );?>"><i class="fas fa-chevron-right"></i> <?=$child->name;?></a></li>
            																	<?php
            																}
        																?></ul><?php
        															}
    															?>
    														</li>
    													<?php } ?>
    												<?php } ?>
    											</ul>
    										</div>
    									<?php } ?>
    								<?php } ?>
    							</div>
    						</div>
    					</div>
    				</div>
				<?php } ?>
			<?php
			}
		}
	?>
</div>
