<?php get_header(); ?>
	<div class="wp-block-group row_content" id="main-container">
        <div class="container">
            <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'faqs' );
                endwhile;
            ?>
        </div>
	</div>
<?php
get_footer();
