<?php

class The_Menu_Walker extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth=0, $args=[], $id=0 ) {
		$target_attr = !empty( $item->target ) ? 'target="'.$item->target.'"' : false;

		if ( $item->title == '[TELEPHONE]' ) {
			$telephone = get_field( 'site_telephone', 'option' );
			$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
			$output .= '<a href="tel:'.str_replace( ' ', '', $telephone ).'"><i class="fas fa-phone-alt"></i> ';
			$output .= $telephone;
			$output .= '</a>';
		} elseif ( $item->title == '[ACCOUNT]' ) {
			if ( is_user_logged_in() ) {
				$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
				$output .= '<a href="/my-account/">MY ACCOUNT</a>';
			} else {
				$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
				$output .= '<a href="/my-account/">LOGIN</a>';
			}
		} elseif ( $item->title == '[CART]' ) {
			$cart_count = WC()->cart->cart_contents_count ? WC()->cart->cart_contents_count : '0';
			$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
			$output .= '<a class="cart-customlocation cart-link" href="#minicart" data-toggle="modal" data-target="#minicart"><i class="fas fa-shopping-cart"></i> ('.$cart_count.')</a>';
		} elseif ( strtolower( $item->title ) == 'free trial' ) {
			$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
			$output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a>';
		} else {
			if ( $item->title != 'CATEGORIES' ) {
				$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
		
				if ( $item->url && $item->url != '#' ) {
					$output .= '<a '.$target_attr.' href="' . $item->url . '">';
				} else {
					$output .= '<a '.$target_attr.' href="' . $item->url . '">';
				}

				$output .= $item->title;

				if ( $item->url && $item->url != '#' ) {
					$output .= '</a>';
				} else {
					$output .= '</a>';
				}
			}
		}
	}

	function end_el( &$output, $item, $depth=0, $args=[], $id=0 ) {
	    static $disable_hover_submenu = null;

        if ( $disable_hover_submenu === null ) {
        	$disable_hover_submenu = get_field( 'disable_hover_submenu', 'option' );
        }
	    
		$custom_menu_arr = ['name badges'];

		if ( $item->title != 'CATEGORIES' ) {
			if ( isset( $_GET['test'] ) && $item->classes == 'custom_nav' ) {
				$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."'>";
				$output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a>';
			}

			$output .= '</li>';
		}

		if ( $item->title === 'CATEGORIES' ) {
			$parent_slug = get_field( 'category_slug', 'option' ) ?: 'trophy-specialists';
			$parent_term = get_term_by( 'slug', $parent_slug, 'product_cat' );
		
			if ( ! $parent_term ) {
				return; // Parent category not found, skip.
			}
		
			// Get immediate child categories.
			$child_categories = get_terms( [
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => $parent_term->term_id,
			] );
		
			if ( is_wp_error( $child_categories ) || empty( $child_categories ) ) {
				return; // Nothing to show.
			}
		
			// Get custom ACF order (IDs).
			$acf_order = get_field( 'custom_category_order', 'option' );
			$order_map = ! empty( $acf_order ) && is_array( $acf_order ) ? array_flip( $acf_order ) : [];
		
			// Get current product category IDs for highlighting.
			$current_product_cats = [];
			if ( is_product() && ! empty( $post->ID ) ) {
				$terms = get_the_terms( $post->ID, 'product_cat' );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$current_product_cats[] = $term->term_id;
					}
				}
			}
		
			// Filter out uncategorized / ungrouped.
			$monsta_cats = [];
			foreach ( $child_categories as $child ) {
				if ( in_array( $child->slug, [ 'uncategorized', 'ungrouped' ], true ) ) {
					continue;
				}
		
				$monsta_cats[] = [
					'id'     => $child->term_id,
					'name'   => $child->name,
					'url'    => esc_url( get_term_link( $child ) ),
					'active' => ( in_array( $child->term_id, $current_product_cats, true ) ),
				];
			}
		
			// Sort by ACF custom order, then fallback alphabetical.
			if ( ! empty( $order_map ) ) {
				usort( $monsta_cats, function( $a, $b ) use ( $order_map ) {
					$posA = $order_map[ $a['id'] ] ?? null;
					$posB = $order_map[ $b['id'] ] ?? null;
		
					if ( $posA !== null && $posB !== null ) return $posA - $posB;
					if ( $posA !== null ) return -1;
					if ( $posB !== null ) return 1;
		
					return strcmp( $a['name'], $b['name'] );
				});
			} else {
				usort( $monsta_cats, function( $a, $b ) {
					return strcmp( $a['name'], $b['name'] );
				});
			}
		
			// Output menu items.
			foreach ( $monsta_cats as $cat ) {
				$classes = 'menu-item menu-item-type-tax menu-item-object-product_cat';
				if ( $cat['active'] ) {
					$classes .= ' current_page_item';
				}
				
				if ( $disable_hover_submenu ) {
                	$classes .= ' has-click-submenu';
                }
		
				$output .= '<li class="' . esc_attr( $classes ) . '" data-cat="' . esc_attr( $cat['id'] ) . '">';
				$output .= '<a href="' . $cat['url'] . '">' . esc_html( $cat['name'] ) . '</a>';

				if ( $disable_hover_submenu ) {
                	$output .= '<span class="subcat-caret">
                		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                			<path d="M297.4 438.6C309.9 451.1 330.2 451.1 342.7 438.6L502.7 278.6C515.2 266.1 515.2 245.8 502.7 233.3C490.2 220.8 469.9 220.8 457.4 233.3L320 370.7L182.6 233.4C170.1 220.9 149.8 220.9 137.3 233.4C124.8 245.9 124.8 266.2 137.3 278.7L297.3 438.7z"/>
                		</svg>
                	</span>';
                }

				$output .= '</li>';
			}
		}

		if ( isset( $_GET['test'] ) && $item->classes == 'custom_nav' ) {
			$output .= "<li class='" .  implode( " ", $item->classes ) . "' data-cat='".$item->ID."' data-test>";
			$output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a></li>';
		}
	}

	/**
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	public function end_lvl( &$output, $depth=0, $args=[]) {
		$output .= '</ul>';
	}
}
