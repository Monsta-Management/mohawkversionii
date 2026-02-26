<?php
global $post;
$post_slug = $post->post_name;
$page_status = get_field( "disable_page_" . $post_slug, 'options' );
$clsHidden = ( ! $page_status ) ?: 'hidden';
?>

<div id="post-<?php the_ID(); ?>" <?php post_class();?>>
    <?php if($page_status) : ?>
        <div class="text-center">
            <h1 class="">404 PAGE</h1>
            <div class="page-content text-center">
                <p>THAT PAGE DOESN'T EXIST OR IS UNAVAILABLE.</p>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center <?= $clsHidden; ?>">
        <h1 class=""><?php the_title();?></h1>
        <?php
        // check if the repeater field has rows of data
        if ( have_rows( 'page_'.$post_slug , 'option' ) ) :
            echo '<div class="page-content text-center">';

                // loop through the rows of data
                while ( have_rows( 'page_'.$post_slug, 'option' ) ) : the_row();
                    // display a sub field value
                    $the_heading = get_sub_field( 'page_heading_'.$post_slug );
                    $the_content = get_sub_field( 'page_content_'.$post_slug );

                    // display a sub field value
                    if ( $the_heading ) {
                        echo '<h3>';
                            echo $the_heading;
                        echo '</h3>';
                    }

                    if ( $the_content ) {
                        if ( strpos( $the_content, ':' ) !== false ) {
                            $_the_content = explode(':', $the_content);

                            if ( count( $_the_content ) >= 2 ) {
                                if ( $_the_content[0] == 'email' ) {
                                    $_email = str_replace(' ', '', $_the_content[1] );
                                    $the_content = "<a href='mailto:".$_email."' class='u-email'>".$_email."</a>";
                                } elseif( $_the_content[0] == 'phone' ) {
                                    $_phone = str_replace(' ', '', $_the_content[1]);
                                    $the_content = "<a href='tel:".$_phone."' class='u-phone'>".$_the_content[1]."</a>";
                                }
                            }
                        }
                        echo '<p>';
                            echo nl2br($the_content);
                        echo '</p>';
                    }
                    
                endwhile;
            echo '</div>';
        else:
            the_content();
        endif;
    ?>
    </div>
</div>
