<div class="toggle_cats">SHOW CATEGORIES</div>
<div class="cat_list">
    <?php
    function sortingByName( $a, $b ) {
        return $a->name > $b->name;
    }

    $monsta_parent_array = get_term_by( 'slug', 'monsta-categories', 'product_cat' );
    $monsta_parent = $monsta_parent_array->term_id;

    $args = [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => $monsta_parent,
        'orderby'    => 'name',
        'order'      => 'DESC'    
    ];
    
    $product_cat = get_terms( $args );
    
    usort( $product_cat, 'sortingByName' );
    
    $category_test = get_queried_object();
    $product_cat_id = $wp_query->get_queried_object()->term_id;

    foreach ( $product_cat as $parent_product_cat ) {
        // Check if the parent category or any of its children is active.
        $is_active_parent = $parent_product_cat->term_id == $product_cat_id;
        
        $child_args = [
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $parent_product_cat->term_id,
            'orderby'    => 'name',
            'order'      => 'DESC'    
        ];
        
        $child_product_cats = get_terms( $child_args );
        
        usort( $child_product_cats, 'sortingByName' );

        // Check if any child category is active.
        $is_active_child = false;
        
        foreach ( $child_product_cats as $child_product_cat ) {
            if ( $child_product_cat->term_id == $product_cat_id ) {
                $is_active_child = true;
                break;
            }
        }
        
        $is_active = $is_active_parent || $is_active_child;
        ?>
        
        <ul class="<?php echo $is_active ? 'active' : ''; ?>">
            <?php
                if ( $parent_product_cat->term_id != 16 && $parent_product_cat->name != 'Ungrouped' ) {
                    ?>
                    <li>
                        <a href="<?php echo get_term_link( $parent_product_cat->term_id ); ?>">
                            <?php echo $parent_product_cat->name; ?>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="children">
                            <?php
                                foreach ( $child_product_cats as $child_product_cat ) {
                                    ?>
                                    <li class="<?php echo $child_product_cat->term_id == $product_cat_id ? 'active' : ''; ?>">
                                        <a href="<?php echo get_term_link( $child_product_cat->term_id ); ?>">
                                            <?php echo $child_product_cat->name; ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
            ?>
        </ul>
        <?php
    }
    ?>
</div>
