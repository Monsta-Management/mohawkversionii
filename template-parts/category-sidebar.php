<div class="toggle_cats">SHOW CATEGORIES</div>
<div class="cat_list">
    <?php
    // Performance fix: fetch ALL descendant terms in a single query instead of
    // running a separate get_terms() for each parent inside a loop (N+1 pattern).
    $monsta_parent_array = get_term_by( 'slug', get_field( 'category_slug', 'option' ) ?: 'trophy-specialists', 'product_cat' );
    $monsta_parent = ! empty( $monsta_parent_array->term_id ) ? $monsta_parent_array->term_id : 0;

    // One query: fetch all terms whose top-level ancestor is monsta-categories or trophy-specialists.
    $all_terms = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'child_of'   => $monsta_parent,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );

    // Group terms by parent ID in PHP instead of per-parent DB queries.
    $parents  = [];
    $children = [];
    foreach ( $all_terms as $term ) {
        if ( $term->parent == $monsta_parent ) {
            $parents[] = $term;
        } else {
            $children[ $term->parent ][] = $term;
        }
    }

    $product_cat_id = ! empty( $wp_query->get_queried_object()->term_id )
        ? $wp_query->get_queried_object()->term_id
        : 0;

    foreach ( $parents as $parent_product_cat ) {
        if ( $parent_product_cat->term_id == 16 || $parent_product_cat->name == 'Ungrouped' ) {
            continue;
        }

        $child_product_cats = isset( $children[ $parent_product_cat->term_id ] )
            ? $children[ $parent_product_cat->term_id ]
            : [];

        // Check if parent or any child is the active category.
        $is_active = ( $parent_product_cat->term_id == $product_cat_id );
        if ( ! $is_active ) {
            foreach ( $child_product_cats as $child ) {
                if ( $child->term_id == $product_cat_id ) {
                    $is_active = true;
                    break;
                }
            }
        }
        ?>

        <ul class="<?php echo $is_active ? 'active' : ''; ?>">
            <li>
                <a href="<?php echo get_term_link( $parent_product_cat->term_id ); ?>">
                    <?php echo $parent_product_cat->name; ?>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="children">
                    <?php foreach ( $child_product_cats as $child_product_cat ) { ?>
                        <li class="<?php echo $child_product_cat->term_id == $product_cat_id ? 'active' : ''; ?>">
                            <a href="<?php echo get_term_link( $child_product_cat->term_id ); ?>">
                                <?php echo $child_product_cat->name; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
        <?php
    }
    ?>
</div>
