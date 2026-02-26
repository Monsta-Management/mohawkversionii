<div class="engraving-desc mt-5">
	<?php
		$post_slug = 'product';
        if( have_rows( 'page_'.$post_slug , 'option' ) ) :
                while ( have_rows( 'page_'.$post_slug, 'option' ) ) : the_row();
                    // Display a sub field value.
                    $the_heading = get_sub_field( 'page_heading_' . $post_slug );
                    $the_content = get_sub_field( 'page_content_' . $post_slug );

                    // Display a sub field value.
                    if ( $the_heading ) {
                        echo '<h3>';
                            echo $the_heading;
                        echo '</h3>';
                    }

                    if ( $the_content ) {
                        echo '<p>';
                            echo $the_content;
                        echo '</p>';
                    }
                endwhile;
        endif;
    ?>	
</div>
