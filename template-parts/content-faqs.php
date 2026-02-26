<?php
global $post;
$post_slug = $post->post_name;

// get content from option
$content = get_field( 'page_'.$post_slug, 'option' );
$content = empty( $content ) ? get_the_content() : $content;

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

        <div class="accordion faq-items" id="accordionFaqs">
            <?php
                // check if the repeater field has rows of data
                if( have_rows( 'page_'.$post_slug , 'option' ) ) :
                    // loop through the rows of data
                    $i = 0;
    
                    while ( have_rows( 'page_'.$post_slug, 'option' ) ) : the_row();
                        $i++;
    
                        $the_heading = get_sub_field( 'page_heading_'.$post_slug );
                        $the_content = nl2br( get_sub_field( 'page_content_'.$post_slug ) );
                        
                        if($the_heading){
                            ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-<?=$i;?>">
                                        <button class="accordion-button <?=$i==1 ? '' : 'collapsed';?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?=$i;?>" aria-expanded="<?=$i==1 ? 'true' : 'false';?>" aria-controls="collapse-<?=$i;?>">
                                            <?=$the_heading;?>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?=$i;?>" class="accordion-collapse collapse <?=$i==1 ? 'show' : '';?>" aria-labelledby="heading-<?=$i;?>" data-bs-parent="#accordionFaqs">
                                        <div class="accordion-body">
                                            <?=$the_content;?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    endwhile;
                else:
                    the_content();
                endif;
            ?>
        </div>
    </div>
</div>
