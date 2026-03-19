<div class="toggle_cats"><?php echo esc_html__( 'SHOW CATEGORIES', 'your-textdomain' ); ?></div>
<div class="cat_list">
	<?php
		// Performance fix: fetch ALL descendant terms in a single query instead of
		// running a separate get_terms() for each parent inside a loop (N+1 pattern).

		global $wp_query;
		
		// Get parent category.
		$parent_slug = get_field( 'category_slug', 'option' ) ?: 'trophy-specialists';
		$parent_term = get_term_by( 'slug', $parent_slug, 'product_cat' );
		$monsta_parent = ! empty( $parent_term->term_id ) ? (int) $parent_term->term_id : 0;
		
		if ( ! $monsta_parent ) {
			return;
		}
		
		// Single query: fetch all descendants.
		$all_terms = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'child_of'   => $monsta_parent,
		] );
		
		if ( is_wp_error( $all_terms ) || empty( $all_terms ) ) {
			return;
		}
		
		// Group terms.
		$parents  = [];
		$children = [];
		
		foreach ( $all_terms as $term ) {
			if ( (int) $term->parent === $monsta_parent ) {
				$parents[] = $term;
			} else {
				$children[ $term->parent ][] = $term;
			}
		}
		
		// ACF ORDER (only for parents).
		$acf_order = get_field( 'custom_category_order', 'option' );
		$order_map = ! empty( $acf_order ) && is_array( $acf_order ) ? array_flip( $acf_order ) : [];
		
		// Sort parents.
		usort( $parents, function( $a, $b ) use ( $order_map ) {
			$posA = $order_map[ $a->term_id ] ?? null;
			$posB = $order_map[ $b->term_id ] ?? null;
		
			if ( $posA !== null && $posB !== null ) return $posA - $posB;
			if ( $posA !== null ) return -1;
			if ( $posB !== null ) return 1;
		
			return strcasecmp( $a->name, $b->name );
		});
		
		// Current category.
		$current_cat_id = ! empty( $wp_query->get_queried_object()->term_id )
			? (int) $wp_query->get_queried_object()->term_id
			: 0;
		
		foreach ( $parents as $parent_product_cat ) {
		
			if ( in_array( $parent_product_cat->slug, [ 'ungrouped', 'uncategorized' ], true ) ) {
				continue;
			}
		
			$child_product_cats = $children[ $parent_product_cat->term_id ] ?? [];
		
			// Sort children alphabetically (fast + consistent).
			if ( ! empty( $child_product_cats ) ) {
				usort( $child_product_cats, function( $a, $b ) {
					return strcasecmp( $a->name, $b->name );
				});
			}
		
			// Active state.
			$is_active = ( $parent_product_cat->term_id === $current_cat_id );
		
			if ( ! $is_active ) {
				foreach ( $child_product_cats as $child ) {
					if ( $child->term_id === $current_cat_id ) {
						$is_active = true;
						break;
					}
				}
			}
		
			$parent_link = get_term_link( $parent_product_cat );
			if ( is_wp_error( $parent_link ) ) {
				continue;
			}
			?>
		
			<ul class="<?php echo $is_active ? 'active' : ''; ?>">
				<li>
					<a href="<?php echo esc_url( $parent_link ); ?>">
						<?php echo esc_html( $parent_product_cat->name ); ?>
						<i class="fas fa-chevron-down"></i>
					</a>
		
					<?php if ( ! empty( $child_product_cats ) ) : ?>
						<ul class="children">
							<?php foreach ( $child_product_cats as $child_product_cat ) :
								$child_link = get_term_link( $child_product_cat );
								if ( is_wp_error( $child_link ) ) continue;
								?>
								<li class="<?php echo ( $child_product_cat->term_id === $current_cat_id ) ? 'active' : ''; ?>">
									<a href="<?php echo esc_url( $child_link ); ?>">
										<?php echo esc_html( $child_product_cat->name ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			</ul>
		
			<?php
		}
	?>
</div>
