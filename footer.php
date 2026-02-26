<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mohawk_V2
 */

//include get_theme_file_path('/template-parts/footer-partners-testimonials.php'); 

global $post;
$post_slug = $post->post_name;

$copyright = get_field( 'footer_copyright', 'option'  );
$payments = get_field( 'footer_payments', 'option' );
$column_heading_1 = get_field( 'footer_links_header_1', 'option' );
$column_heading_2 = get_field( 'footer_links_header_2', 'option' );
$column_heading_3 = get_field( 'footer_links_header_3', 'option' );
$social_heading = get_field( 'footer_social_header', 'option' );

$page_status = get_field( "disable_page_" . $post_slug, 'options' );
$clsHidden = ( !$page_status ) ?: 'hidden';
?>	

	<footer id="colophon" class="site-footer">
	    <div class="wave">
	        <div class="layer-1">
	            <svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1923.74 287.38">
                  <defs>
                    <style>
                      .cls-wave-bg {
                        fill: currentColor;
                        stroke-width: 0px;
                      }
                    </style>
                  </defs>
                  <g id="Layer_1-2" data-name="Layer 1">
                    <g>
                      <path class="cls-1" d="M1920.02,264.76c0-3.46,0-4.86,0-4.86,0-.57,0-1.68,0-1.68,0,0-.02-10.74,0-17.36.03-8.03.1-14.59.15-18.89-.2-8.03-.19-14.43-.15-18.9.04-4.34.16-10.65.07-15.73,0-.15,0-.43-.01-.81-.01-.74-.04-2.59-.06-5.42-.02-3.16,0-6.06,0-7.63v-46.25s0-2.21,0-6.67c-50.97,22.08-282.82,119.12-515.51,106.71-101.95-5.44-199.34-30.36-199.34-30.36-76.83-19.74-135.51-44.6-174.59-62.14C520.04-94.4,112.53,28.73.02,71.66v.19c0,.6,0,1.2,0,1.8,0,1.03,0,2.05,0,3.06v19.98s0,.43,0,.54c0,.51,0,.95,0,1.07,0,1.01,0,3.15,0,3.15,0,1.44,0,2.57,0,5.83,0,3.77,0,7.53,0,11.3v19.98C.02,149.11,0,159.66.02,170.2c.02,8.42,0,16.84,0,25.26,0,4.89,0,9.79,0,14.68,0,5.82.02,7.87,0,11.87,0,1.65-.02,3.23-.02,5.74,0,2.41.01,3.57.02,6.45,0,2.31,0,4.19,0,5.41v24.89c640,.08,1280,.17,1920,.26Z"/>
                      <path class="cls-1" d="M908.34,250.05c155.6,5.86,274.05,8.18,327.57,7.83,41.8-.27,92.96-1.08,92.96-1.08,24.84-.4,45.03-.8,57.68-1.07-30.68-108.66-130.47-183.71-241.99-182.79-107.93.88-204.1,72.74-236.22,177.11Z"/>
                      <path class="cls-1" d="M1455.32,254.7c66.92.09,100.13.89,184.51,1.38,5.54.03,11.46.06,16,.08.53-3.16.93-6.82.99-10.88.67-47.52-45.92-117.2-117.21-117.21-71.89,0-120.55,70.83-117.21,117.21.22,3.07.68,6.22,1.43,9.44,13.96-.03,25.05-.02,31.49-.01Z"/>
                      <path class="cls-1" d="M1442.21,254.69h0s4.61,0,13.11,0c-.04.34-.11.82-.13.82-.12-.01,1.21-13.59,1.21-14.75,0-49.3-39.96-89.26-89.26-89.26s-89.26,39.96-89.26,89.26c0,5.79.56,11.46,1.61,16.94.91-.02,162.71-3.02,162.71-3.02Z"/>
                      <circle class="cls-1" cx="1439.02" cy="100.16" r="29"/>
                    </g>
                </svg>
            </div>
	        <div class="layer-2">
	            <svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1929.29 340.35">
                  <defs>
                    <style>
                         .cls-wave-line {
                            fill: none;
                            stroke: currentColor;
                            stroke-dasharray: 0 0 9 18;
                            stroke-linecap: round;
                            stroke-linejoin: round;
                            stroke-width: 10px;
                        }
                    </style>
                  </defs>
                  <g id="Layer_1-2" data-name="Layer 1">
                    <path class="cls-wave-line" d="M1922.55,5c-73.41,25.94-143.41,55.94-206.41,105.94-25,21-48,49-45,83,2,30,25,51,50,67,56,37,115,53,178,70,6,1,13,2,19.15,4.41"/>
                    <path class="cls-wave-line" d="M5,238.07c17.14-7.13,32.14-16.13,49.14-23.13,3-1,5-1,8-2,106-43,211-77,326-95,15-2,28-4,43-6,48-6,95-9,143-11,58-1,116-1,175,6h10c28,3,54,6,82,10,1,0,1,2,2,2,20,3,39,6,59,8,16,2,30,7,46,10,22,4,41,10,62,16,6,1,13,2,19,4,1,1,3,2,4,2,20,4,37,10,56,17,85,30,168,58,257,80,53,14,104,24,157,28,6,1,11,3,17,4,24,2,47,4,71,5,33,1,66,1,99-1,12-1,23-2,35-3,3,0,7-2,10-2,66-7,129-23,189.15-49.56"/>
                  </g>
                </svg>
	        </div>
	    </div>
	    <div class="footer-wrap">
    		<div class="footer-menu">
    			<div class="container">
    				<div class="row">
    					<div class="col">
    
    						<?php if ( $column_heading_1 ) { ?>
    							<h3><?php echo $column_heading_1; ?></h3>
    						<?php } ?>

    						<ul>
                                <?php
                                	if ( have_rows( 'footer_links_items_1' , 'option' ) ):
                                		while ( have_rows( 'footer_links_items_1', 'option' ) ) : the_row();
                                			$the_title = get_sub_field( 'footer_links_item_title' );
                                			$the_link = get_sub_field( 'footer_links_item_link' );
                                			if ( $the_title ) { ?>
                                			    <li><a href="<?php echo $the_link; ?>"><?php echo $the_title;?></a></li><?php
                                			}
                                		endwhile;
                                	endif;
                                ?>
    						</ul>
    
    					</div>
    					<div class="col">
    						<?php if ( $column_heading_2 ) { ?>
    							<h3><?php echo $column_heading_2; ?></h3>
    						<?php } ?>
    
    						<ul>
                                <?php
                                if ( have_rows( 'footer_links_items_2' , 'option' ) ):
                                	while ( have_rows( 'footer_links_items_2', 'option' ) ) : the_row();
                                		$the_title = get_sub_field( 'footer_links_item_title' );
                                		$the_link = get_sub_field( 'footer_links_item_link' );
                                		if ( $the_title ) { ?>
                                		    <li><a href="<?php echo $the_link; ?>"><?php echo $the_title;?></a></li><?php
                                		}
                                	endwhile;
                                endif;
                                ?>
    						</ul>
    					</div>
    					<div class="col">
    						<?php if ( $column_heading_3 ) { ?>
    							<h3><?php echo $column_heading_3; ?></h3>
    						<?php } ?>
    
    						<ul>
    							<?php
        							if ( have_rows( 'footer_links_items_3' , 'option' ) ):
        								while ( have_rows( 'footer_links_items_3', 'option' ) ) : the_row();
        									$the_title = get_sub_field( 'footer_links_item_title' );
        									$the_link = get_sub_field( 'footer_links_item_link' );
        									if ( $the_title ){?>
        									    <li><a href="<?php echo $the_link; ?>"><?php echo $the_title; ?></a></li><?php
        									}
        								endwhile;
        							endif;
    							?>
    						</ul>
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="site-info">
    			<div class="container">
    				<div class="row">
    				    <div class="col-sm-6 col-social-wrap">
    						<?php if ( $social_heading ) { ?>
    							<h3><?php echo $social_heading; ?></h3>
    						<?php } ?>
    						
    						<ul>
    							<?php
        							if( have_rows( 'footer_social_items' , 'option' ) ):
        								while ( have_rows( 'footer_social_items', 'option' ) ) : the_row();
        									$the_title = get_sub_field( 'footer_social_item_title' );
        									$the_link = get_sub_field( 'footer_social_item_link' );
        									if ( $the_title ) { ?>
        									    <li><a href="<?php echo $the_link; ?>" target="_blank"><i class="fab fa-<?php echo strtolower($the_title);?> fa-2x"></i></a></li><?php
        									}
        								endwhile;
        							endif;
    							?>
    						</ul>
    					</div>
    					<div class="col-sm-6 text-right">
    						<p data-test="<?php echo do_shortcode( '[theme_year]' ); ?>">
    							<?php
    								if ( $copyright ) { 
        								$year = do_shortcode( '[theme_year]' );
        								$copyright = str_replace( '[theme_year]', $year, $copyright );
        								echo $copyright;
    								}
    							?>
    							<a href="/privacy" class="<?php echo $clsHidden; ?>"> | Privacy Policy</a> <a href="/terms" class="<?php echo $clsHidden; ?>"> | Terms & Conditions</a>
    						</p>
    						<p>Created and powered by <a href="https://www.monstamanagement.com/" target="_blank">MonstaManagement</a></p>
    					</div>
    				</div>
    			</div>
    		</div>
		</div>
	</footer>
	<?php echo do_shortcode( '[custom-mini-cart]' );?>
	<?php //echo do_shortcode( '[uproar_page_navigation]' ); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
