<?php
class The_Menu_Walker extends Walker_Nav_Menu {
    
	function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
        
        $target_attr = !empty($item->target) ? 'target="'.$item->target.'"' : false;

        if($item->title == '[TELEPHONE]'){

            $telephone = get_field('site_telephone', 'option');
            $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
            $output .= '<a href="tel:'.str_replace(' ', '', $telephone).'"><i class="fas fa-phone-alt"></i> ';
            $output .= $telephone;
            $output .= '</a>';

        }elseif($item->title == '[ACCOUNT]'){

            if ( is_user_logged_in() ) {
                $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
                $output .= '<a href="/my-account/">MY ACCOUNT</a>';
            } else {
                $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
                $output .= '<a href="/my-account/">LOGIN</a>';
            }

        }elseif($item->title == '[CART]'){
            
            $cart_count = WC()->cart->cart_contents_count ? WC()->cart->cart_contents_count : '0';
            $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
            $output .= '<a class="cart-customlocation cart-link" href="#minicart" data-toggle="modal" data-target="#minicart"><i class="fas fa-shopping-cart"></i> ('.$cart_count.')</a>';

        }elseif(strtolower($item->title) == 'free trial'){

            $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
            $output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a>';

        }else{

            if($item->title != 'CATEGORIES'){

                $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
        
                if ($item->url && $item->url != '#') {
                    $output .= '<a '.$target_attr.' href="' . $item->url . '">';
                } else {
                    $output .= '<a '.$target_attr.' href="' . $item->url . '">';
                }
        
                $output .= $item->title;
        
                if ($item->url && $item->url != '#') {
                    $output .= '</a>';
                } else {
                    $output .= '</a>';
                }
                
            }

        }
        


	}
	function end_el(&$output, $item, $depth=0, $args=[], $id=0) {
        $custom_menu_arr = ['name badges'];


        if($item->title != 'CATEGORIES'){

            if(isset($_GET['test']) && $item->classes == 'custom_nav') {
                $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."'>";
                $output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a>';
    
            }
           
            $output .= '</li>';
          
        }

        if($item->title == 'CATEGORIES'){

            global $post;
            global $wp_query;
            
            $monsta_parent_array = get_term_by( 'slug', 'trophy-specialists', 'product_cat' );
            $monsta_parent = !empty($monsta_parent_array->term_id) ? $monsta_parent_array->term_id : false;
            $args = array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent'   => $monsta_parent
            );
            $product_cat = get_terms( $args );
            $product_cat_id = !empty($wp_query->get_queried_object()->term_id) ? $wp_query->get_queried_object()->term_id : 1;
            
            $ungrouped_array = get_term_by( 'slug', 'ungrouped', 'product_cat' );
            $ungrouped = $ungrouped_array->term_id;
            $category = get_queried_object();

            // sort ascending top nav menu
            $columns = array_column($product_cat, 'name');
            array_multisort($columns, SORT_ASC, $product_cat);
    
            // get the active category on carousel for product page
            if(is_product()){
                $product_terms = get_the_terms( $post->ID, 'product_cat' );
                $current_product_cat_ids = array();
                foreach ($product_terms as $product_term) {
                    $current_product_cat_ids[] = $product_term->term_id;
                }
            }
            foreach ($product_cat as $parent_product_cat) {
                $thumbnail_id = get_woocommerce_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
                $image = wp_get_attachment_url( $thumbnail_id );

                if( $parent_product_cat->term_id != $ungrouped && $parent_product_cat->name != 'Uncategorized' ||  $item->classes == 'custom_nav' ) {
                        $checked_cat = $parent_product_cat->term_id == $category->term_id && !empty($parent_product_cat->term_id) && !empty($category->term_id);
                        $checked_parent = $parent_product_cat->term_id == $category->parent && !empty($parent_product_cat->term_id) && !empty($category->parent);
                        $checked_product = (is_product() && in_array($parent_product_cat->term_id, $current_product_cat_ids)) && !empty($parent_product_cat->term_id) && !empty($current_product_cat_ids);
    
                    $output .= '
                        <li class="menu-item menu-item-type-custom menu-item-object-custom '.( ( $checked_cat || $checked_parent || $checked_product ) ? 'current_page_item' : '').'" data-cat="'.$parent_product_cat->term_id.'">
                            <a href="'.get_term_link($parent_product_cat->term_id).'">'.$parent_product_cat->name.'</a>
                        </li>';
                        
                        
                }
            }

            if(isset($_GET['test']) ) {
               print_r($item->classes); 
               echo "<br/> ===== " . $item->title;
    
            }
            if($item->classes == 'custom_nav') {
                $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."' data-test>";
                $output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a></li>';
            }
        }
        if(isset($_GET['test']) && $item->classes == 'custom_nav') {
            $output .= "<li class='" .  implode(" ", $item->classes) . "' data-cat='".$item->ID."' data-test>";
            $output .= '<a '.$target_attr.' class="btn-trial" href="' . $item->url . '">'.$item->title.'</a></li>';

        }
	}

    /**
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	public function end_lvl( &$output, $depth=0, $args=[])
	{
        /* global $post;
        global $wp_query;
        $monsta_parent_array = get_term_by( 'slug', 'trophy-specialists', 'product_cat' );
        $monsta_parent = !empty($monsta_parent_array->term_id) ? $monsta_parent_array->term_id : false;
        $args = array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'parent'   => $monsta_parent
        );
        $product_cat = get_terms( $args );
        $product_cat_id = !empty($wp_query->get_queried_object()->term_id) ? $wp_query->get_queried_object()->term_id : 1;
        
        $ungrouped_array = get_term_by( 'slug', 'ungrouped', 'product_cat' );
        $ungrouped = $ungrouped_array->term_id;
        $category = get_queried_object();

        // get the active category on carousel for product page
        if(is_product()){
            $product_terms = get_the_terms( $post->ID, 'product_cat' );
            $current_product_cat_ids = array();
            foreach ($product_terms as $product_term) {
                $current_product_cat_ids[] = $product_term->term_id;
            }
        }

        foreach ($product_cat as $parent_product_cat) {
            $thumbnail_id = get_woocommerce_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
            $image = wp_get_attachment_url( $thumbnail_id );
            if( $parent_product_cat->term_id != $ungrouped && $parent_product_cat->name != 'Uncategorized' ) {
                    $checked_cat = $parent_product_cat->term_id == $category->term_id && !empty($parent_product_cat->term_id) && !empty($category->term_id);
                    $checked_parent = $parent_product_cat->term_id == $category->parent && !empty($parent_product_cat->term_id) && !empty($category->parent);
                    $checked_product = (is_product() && in_array($parent_product_cat->term_id, $current_product_cat_ids)) && !empty($parent_product_cat->term_id) && !empty($current_product_cat_ids);

                $output .= '
                    <li class="menu-item menu-item-type-custom menu-item-object-custom '.( ( $checked_cat || $checked_parent || $checked_product ) ? 'current_page_item' : '').'" data-cat="'.$parent_product_cat->term_id.'">
                        <a href="'.get_term_link($parent_product_cat->term_id).'">'.$parent_product_cat->name.'</a>
                    </li>';
            }
        } */
        
		$output .= '</ul>';
	}
}
