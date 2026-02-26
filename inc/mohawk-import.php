<?php

/* IMPORTED COLORS
================================================== */
function imported_colors() {


	$primary_color = get_field('site_primary_color', 'option');
	$secondary_color = get_field('site_secondary_color', 'option');
	$site_font = get_field('site_font', 'option');
    
    $site_font_slug = str_replace(' ', '-', $site_font);
    $site_font_slug = strtolower($site_font_slug);
    
    $font_path = get_stylesheet_directory() . '/webfonts/'.$site_font_slug.'/stylesheet.css';
    if(file_exists($font_path)){
        wp_register_style('mohawk-font', get_stylesheet_directory_uri() . '/webfonts/'.$site_font_slug.'/stylesheet.css', array(), filemtime( get_stylesheet_directory() . '/webfonts/'.$site_font_slug.'/stylesheet.css' ), 'all');
        wp_enqueue_style('mohawk-font');
    }

    ?>

	<style>
		:root {
			--primary-color: <?=$primary_color;?>;
			--secondary-color: <?=$secondary_color;?>;
			--site-font: "<?=$site_font;?>";
		}
	</style>

    <?php


}
add_action( 'wp_footer', 'imported_colors' );

add_action( 'admin_enqueue_scripts', 'mohawk_import_script' );
function mohawk_import_script( $hook ) {
	if ('toplevel_page_mohawk-import' !== $hook) {
        return;
    }
    wp_register_script('mohawk-import', get_stylesheet_directory_uri() . '/js/mohawk_import.js', false, filemtime( get_stylesheet_directory().'/js/mohawk_import.js' ), true);
    wp_enqueue_script('mohawk-import');
}

/* 
* IMPORT CSV
*/
function mohawk_import_csv(){
    $process = [];

	// importing appearance
	$process[] = mohawk_import_appearance();

	// importing benefits banner
	$process[] = mohawk_import_benefits_banner();

	// importing partners logo
	$process[] = mohawk_import_partners_logo();
	
	// importing testimonials
	$process[] = mohawk_import_testimonials();
	
	// importing pages
	$process[] = mohawk_import_pages();
	
	// importing faqs
	$process[] = mohawk_import_faqs();

	echo json_encode($process);

	wp_die();
}
add_action("wp_ajax_mohawk_import_csv", "mohawk_import_csv");


function mohawk_import_appearance(){

    $csv_appearance = get_field('csv_appearance ', 'option');
	$include_appearance = get_field('include_appearance', 'option');

    if(!$include_appearance) return false;

    $success_item = '';

	if($csv_appearance){
		
		$url = @$csv_appearance['url'];

		if($url){

			$data = [];
	
			$fileData = fopen($url,'r');
            $i = 0;

			while (($line = fgetcsv($fileData)) !== FALSE) {
                
                $i++;

				if($i == 2){

					$site_logo = @$line[0];
					$site_logo_size = @$line[1];
					$primary_color = @$line[2];
					$secondary_color = @$line[3];
					$top_submenu_text_color = @$line[4];
					$main_menu_text_color = @$line[5];
					$font = @$line[6];
					$tel = @$line[7];

					if($site_logo){
						$result = upload_from_url($site_logo);
                        $attachment_id = $result['attachment_id'];
                        if($attachment_id){
                            update_field('site_logo', $attachment_id, 'option');
                        }
					}
					
					if($site_logo_size){
						update_field( 'site_logo_size', $site_logo_size, 'option' );
					}

					if($primary_color){
						update_field( 'site_primary_color', $primary_color, 'option' );
					}

					if($secondary_color){
						update_field( 'site_secondary_color', $secondary_color, 'option' );
					}

					if($top_submenu_text_color){
						update_field( 'site_submenu_color', $top_submenu_text_color, 'option' );
					}

					if($main_menu_text_color){
						update_field( 'site_mainmenu_color', $main_menu_text_color, 'option' );
					}

					if($font){
						update_field( 'site_font', $font, 'option' );
					}
					if($tel){
						update_field( 'site_telephone', $tel, 'option' );
					}

                    $success_item = 'appearance';

				}

			}
		}

	}

    return $success_item;

}

function mohawk_import_benefits_banner(){

    //$csv = get_field('csv_benefits ', 'option');
	//$include = get_field('include_benefits', 'option');
    $csv = get_field('csv_appearance ', 'option');
	$include = get_field('include_appearance', 'option');

    if(!$include) return false;

    $success_item = '';

	if($csv){
		
		$url = @$csv['url'];

		if($url){

			$data = [];
	
			$fileData = fopen($url,'r');
            $i = 0;

			while (($line = fgetcsv($fileData)) !== FALSE) {
				
				$i++;

				if($i == 2){

					$heading    = @$line[8];

					$img_1      = @$line[9];
					$title_1    = @$line[10];
					$desc_1     = @$line[11];
					$link_1     = @$line[12];
					$target_1   = @$line[13];

					$img_2      = @$line[14];
					$title_2    = @$line[15];
					$desc_2     = @$line[16];
					$link_2     = @$line[17];
					$target_2   = @$line[18];

					$img_3      = @$line[19];
					$title_3    = @$line[20];
					$desc_3     = @$line[21];
					$link_3     = @$line[22];
					$target_3   = @$line[23];

                    $data = $line;

                    // heading
                    if($heading){
                        update_field( 'benefits_heading', $heading, 'option' );
                    }

                    // image 1
					if($img_1){
						$result_1 = upload_from_url($img_1);
                        $attachment_id_1 = $result_1['attachment_id'];
                        if($attachment_id_1){
                            update_field('benefits_img_1', $attachment_id_1, 'option');
                        }
					}

                    // image 2
                    if($img_2){
						$result_2 = upload_from_url($img_2);
                        $attachment_id_2 = $result_2['attachment_id'];
                        if($attachment_id_2){
                            update_field('benefits_img_2', $attachment_id_2, 'option');
                        }
					}

                    // image 3
                    if($img_3){
						$result_3 = upload_from_url($img_3);
                        $attachment_id_3 = $result_3['attachment_id'];
                        if($attachment_id_3){
                            update_field('benefits_img_3', $attachment_id_3, 'option');
                        }
					}

                    // content 1
					if($title_1){
						update_field( 'benefits_title_1', $title_1, 'option' );
					}
					if($desc_1){
						update_field( 'benefits_content_1', $desc_1, 'option' );
					}
					if($link_1){
						update_field( 'benefits_link_1', $link_1, 'option' );
					}
					if($target_1){
						update_field( 'benefits_target_1', $target_1, 'option' );
					}

                    // content 2
					if($title_2){
						update_field( 'benefits_title_2', $title_2, 'option' );
					}
					if($desc_2){
						update_field( 'benefits_content_2', $desc_2, 'option' );
					}
					if($link_2){
						update_field( 'benefits_link_2', $link_2, 'option' );
					}
					if($target_2){
						update_field( 'benefits_target_2', $target_2, 'option' );
					}
					
                    // content 3
					if($title_3){
						update_field( 'benefits_title_3', $title_3, 'option' );
					}
					if($desc_3){
						update_field( 'benefits_content_3', $desc_3, 'option' );
					}
					if($link_3){
						update_field( 'benefits_link_3', $link_3, 'option' );
					}
					if($target_3){
						update_field( 'benefits_target_3', $target_3, 'option' );
					}

                    $success_item = 'benefits';

				}

			}
		}

	}

    return $success_item;

}


function mohawk_import_partners_logo(){

    $csv = get_field('csv_posts', 'option');
	$include = get_field('include_posts', 'option');

    if(!$include) return false;

    $success_item = '';

	if($csv){
		
		$url = @$csv['url'];

		if($url){

			$data = [];
	
			$fileData = fopen($url,'r');
            $i = 0;
			$heading = false;
			$images = [];

			while (($line = fgetcsv($fileData)) !== FALSE) {
				
				$i++;

				if($i > 1){

					$data = $line;

					// heading
					if($data[0] == 'partner_heading'){
						$heading = $data[3];
					}

					// logo
					if($data[0] == 'partner_logo'){
						$img = $data[2];
						if($img){
							$result = upload_from_url($img);
							$images[] = $result['attachment_id'];
						}
					}
					
				}
				
			}

			// heading
			if($heading){
				update_field( 'partners_heading', $heading, 'option' );
			}
			
			// logos
			if($images){
				update_field( 'partners_logo', $images, 'option' );
			}

			$success_item = 'logos';
			
		}

	}

    return $success_item;

}


function mohawk_import_testimonials(){

    $csv = get_field('csv_posts', 'option');
	$include = get_field('include_posts', 'option');

    if(!$include) return false;

    $success_item = '';

	if($csv){
		
		$url = @$csv['url'];

		if($url){

			$data = [];
	
			$fileData = fopen($url,'r');
            $i = 0;
			$images = [];

			while (($data = fgetcsv($fileData)) !== FALSE) {
				
				$i++;

				if($i > 1){

					if($data[0] == 'testimonial'){

						$title = @$data[3];
						$content = @$data[4];
						$name = @$data[5];
						$position = @$data[6];
						$type = @$data[9];
	
						if(!$title || !$content) continue; // skip if no title or content
	
						$the_name = "<strong>{$name}</strong>";
						$the_name .= !empty($position) ? "- {$position}" : '';
						$the_content = "<!-- wp:paragraph --><p>{$content}</p><cite>{$the_name}</cite><!-- /wp:paragraph -->";
						
						if($title && $the_content){

							// Create post object
							$testimonial_post = array(
								'post_title'    => $title,
								'post_content'  => $the_content,
								'post_status'   => 'publish',
								'post_type' 	=> 'testimonial',
								'post_author'   => 1
							);
							
							// Insert the post into the database
							$post_id = wp_insert_post( $testimonial_post );
							
							// Google or Default
							if($type){
								update_field( 'testimonial_type', $type, $post_id );
							}
							
						}
					}


                    $success_item = 'testimonials';

				}

			}
		}

	}

    return $success_item;

}

function mohawk_import_pages(){

    $csv = get_field('csv_posts', 'option');
	$include = get_field('include_posts', 'option');

    if(!$include) return false;

    $success_item = '';

	if($csv){
		
		$url = @$csv['url'];

		if($url){

			$data = [];
	
			$fileData = fopen($url,'r');
            $i = 0;
			$images = [];

			while (($data = fgetcsv($fileData)) !== FALSE) {
				
				$i++;

				if($i > 1){

					if($data[0] == 'page'){

						$slug = $data[1];

						$values = [];

						$title_1 = @$data[3];
						$content_1 = @$data[4];
						$title_2 = @$data[5];
						$content_2 = @$data[6];
						$title_3 = @$data[7];
						$content_3 = @$data[8];

						$existing = get_field( 'page_'.$slug, 'option');
						if ( ! is_array($existing) ) $existing = [];

						$values[] = [
							'page_heading_'.$slug => $title_1,
							'page_content_'.$slug => $content_1,
						];
						if($title_2 || $content_2){
							$values[] = [
								'page_heading_'.$slug => $title_2,
								'page_content_'.$slug => $content_2,
							];
						}
						if($title_3 || $content_3){
							$values[] = [
								'page_heading_'.$slug => $title_3,
								'page_content_'.$slug => $content_3,
							];
						}

						if($values){
							update_field( 'page_'.$slug, $values, 'option' );
						}
					}


                    $success_item = 'pages';

				}

			}
		}

	}

    return $success_item;

}

function mohawk_import_faqs(){

    $csv = get_field('csv_posts', 'option');
	$include = get_field('include_posts', 'option');

    if(!$include) return false;

    $success_item = '';

	if($csv){
		
		$url = @$csv['url'];

		if($url){

			$data = [];
	
			$fileData = fopen($url,'r');
            $i = 0;
			$images = [];
			$values = [];

			while (($data = fgetcsv($fileData)) !== FALSE) {
				
				$i++;

				if($i > 1){

					if($data[0] == 'faq'){

						$slug = 'faqs';


						$title_1 = @$data[3];
						$content_1 = @$data[4];

						$values[] = [
							'page_heading_'.$slug => $title_1,
							'page_content_'.$slug => $content_1
						];

					}
					

				}

			}


			if($values){
				update_field( 'page_'.$slug, $values, 'option' );
				$success_item = 'faqs';
			}
			
		}

	}

    return $success_item;

}



// ACF OPTIONS PAGE
function my_acf_op_init() {
    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

        // Register Importer page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Grr Import'),
            'menu_title'    => __('Grr Import'),
            'menu_slug'     => 'grr-import',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-monsta-head'
        ));

		if(current_user_can('administrator')){

			// Register Importer page.
			$option_page = acf_add_options_page(array(
				'page_title'    => __('Grr Admin'),
				'menu_title'    => __('Grr Admin'),
				'menu_slug'     => 'grr-admin',
				'capability'    => 'edit_posts',
				'redirect'      => false,
				'icon_url'      => 'dashicons-monsta-head'
			));
			
		}

    }
} add_action('acf/init', 'my_acf_op_init');