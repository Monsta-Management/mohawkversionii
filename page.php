<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mohawk_V2
 */

get_header(); ?>
	<div class="wp-block-group row_content" id="main-container">
        <div class="container">
            <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'generic' );
                endwhile;
            ?>
        </div>
	</div>
<?php
get_footer();
