<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mohawk_V2
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class();?>>
    <?php uproar_post_thumbnail(); ?>
    <?php the_content(); ?>
</div>
