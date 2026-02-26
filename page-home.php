<?php get_header(); ?>

    <?php include get_theme_file_path('template-parts/header-features.php'); ?>
    
	<div id="main-container">
        <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content', 'page' );
            endwhile;
        ?>
	</div>
<?php
get_footer();
