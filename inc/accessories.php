<?php
/*
* Inject Centers
*/

function injectVariants_alt() {
    global $product, $wpdb;
    
    if (is_product()) {
        $accessoriesList = [];
        $accessoriesOptions = [];

        if(method_exists($product,'get_visible_children')){
			foreach($product->get_visible_children() as $variation_id ) {
				$monstavariants = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."woocommerce_attribute_taxonomies WHERE `attribute_name` like 'monsta%' and `attribute_name` not in ('monstasize' , 'monstamaterial', 'monstaprocess', 'monstacolor')");
				
				foreach($monstavariants as $k => $variation){
					$variation_name = $variation->attribute_name;
					$pamonstaattr = get_post_meta( $variation_id, 'pa_'.$variation_name, false );
					$attrList[] = 'pa_'.$variation_name;

                    // add accessories list
					$accessoriesList[$variation_name] = $variation->attribute_label;

                    if (count($pamonstaattr)) {
                        foreach ($pamonstaattr as $slug) {
                            if (empty($accessoriesOptions[$variation_name][$slug])) {
                                $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                $tax_id = $term->term_taxonomy_id;
                                $term_meta = get_term_meta($tax_id);

                                // add accessories options
                                $accessoriesOptions[$variation_name][$slug] = $term_meta;
                            }
                        }
                    }
				}
			}
        }

        echo '<script>var global_variations = {}; </script>';

        if(!empty($accessoriesList)){
            foreach($accessoriesList as $accessoriesItemKey => $accessoriesItemName){
                // the accessories optionts with name, image and price
                $accessoriesItemOptions = $accessoriesOptions[$accessoriesItemKey];

                // Variable to use by js function variantRenderModal(monstacc, centre_modal, button_text, global_var)
                $global_var = "global_".$accessoriesItemKey;
                $modal_var = $accessoriesItemKey."_modal";

                echo "<script>
                var ".$global_var." = JSON.parse('" . json_encode($accessoriesItemOptions) . "'); 
                global_variations['".$modal_var."'] = ".$global_var."; 
                </script>";
                
                // generate the modal in each accessories
                ?>
                <div class="<?=$modal_var;?> monsta_modal" style="display:none;">
                    <div class="centre_modal-inner">
                        <div class="centre_modal-title"><?=$accessoriesItemName;?><a data-action="centreModalClose" class="close_modal">
                                &times;
                            </a> <span></span></div>
                        <div class="centre_modal-body"></div>
                        <div class="centre_footer">
                            <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}
add_action('wp_footer', 'injectVariants_alt');

function injectVariants() {
    global $product, $wpdb;
    
    if (is_product()) {
        $centres = [];
        $ribbons = [];
        $ribbons2 = [];
        $monsta_box = [];
        $medal_boxes = [];
        $neck_ribbons = [];
        $medal_boxes_10 = [];
        $ribbons_80 = [];
        $ribbons_84 = [];

        $centres_array = ['monstacc1', 'monstacc2', 'monsta37', 'monsta16','monsta80', 'monsta98', 'monsta84', 'monsta49', 'monsta39', 
        'monsta151', 'monsta82', 'monsta10', 'monsta67', 'monsta17', 'monsta137', 'monsta138', 'monsta128','monsta65',
        'monsta44','monsta72','monsta73','monsta160','monsta159'];

        if(method_exists($product,'get_visible_children')){
            $variationList = [];

            foreach ($product->get_visible_children() as $variation_id) {
                $monstavariants = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE `attribute_name` like 'monsta%' and `attribute_name` not in ('monstasize' , 'monstamaterial', 'monstaprocess', 'monstacolor')");
    
                foreach ($monstavariants as $k => $variation) {
                    $variation_name = $variation->attribute_name;
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
    
                    $center1_component_price = 0;
                    
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $centres)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $centres[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $ribbons)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $ribbons[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $ribbons2)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $ribbons2[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $ribbons_80)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $ribbons_80[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $ribbons_84)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $ribbons_84[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $medal_boxes)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $medal_boxes[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $medal_boxes_10)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $medal_boxes_10[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $neck_ribbons)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $neck_ribbons[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
    
                    $pamonstaattr = get_post_meta($variation_id, 'pa_' . $variation_name, false);
    
                    $center1_component_price = 0;
                    if (in_array($variation_name, $centres_array )) {
                        if (count($pamonstaattr)) {
                            foreach ($pamonstaattr as $slug) {
                                if (!in_array($slug, $monsta_box)) {
                                    $term = get_term_by('slug', $slug, 'pa_' . $variation_name);
                                    $tax_id = $term->term_taxonomy_id;
                                    $term_meta = get_term_meta($tax_id);
                                    $monsta_box[$slug] = $term_meta;
                                }
                            }
                        }
                    }
    
                }
            }
            
        }
        echo '<script>var global_variations = {}; </script>';
        
        if (!empty($centres)) {
            echo "<script>var global_centres = JSON.parse('" . json_encode($centres) . "'); global_variations['centre_modal'] = global_centres; </script>";
            ?>
            <div class="centre_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Centres<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <div class="centre2_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Centres<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($ribbons)) {
            echo "<script>var global_ribbons = JSON.parse('" . json_encode($ribbons) . "'); global_variations['ribbon_modal'] = global_ribbons;</script>";

            ?>
            <div class="ribbon_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Ribbons<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($ribbons_80)) {
            echo "<script>
            var global_ribbons_80 = JSON.parse('" . json_encode($ribbons_80) . "'); 
            var global_ribbons_98 = JSON.parse('" . json_encode($ribbons_80) . "'); 
            global_variations['ribbon_80_modal'] = global_ribbons_80; 
            global_variations['ribbon_98_modal'] = global_ribbons_98;
            </script>";

            ?>
            <div class="ribbon_80_modal ribbon_98_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Ribbons<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($ribbons_84)) {
            echo "<script>var global_ribbons_84 = JSON.parse('" . json_encode($ribbons_84) . "'); global_variations['ribbon_84_modal'] = global_ribbons_84;</script>";

            ?>
            <div class="ribbon_84_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Ribbons<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($ribbons2)) {
            echo "<script>var global_ribbons2 = JSON.parse('" . json_encode($ribbons2) . "'); global_variations['ribbon_modal2'] = global_ribbons2;</script>";

            ?>
            <div class="ribbon_modal2 monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Ribbons<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($medal_boxes)) {
            echo "<script>var global_monsta_medal_boxes = JSON.parse('" . json_encode($medal_boxes) . "'); global_variations['monsta_medal_box_modal'] = global_monsta_medal_boxes;</script>";

            ?>
            <div class="monsta_medal_box_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Medal Boxes<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($medal_boxes_10)) {
            echo "<script>var global_monsta_medal_boxes_10 = JSON.parse('" . json_encode($medal_boxes_10) . "'); global_variations['monsta_medal_box_modal_10'] = global_monsta_medal_boxes_10;</script>";

            ?>
            <div class="monsta_medal_box_modal_10 monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Medal Boxes<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($medal_boxes_181)) {
            echo "<script>var global_monsta_medal_boxes_181 = JSON.parse('" . json_encode($medal_boxes_181) . "'); global_variations['monsta_medal_box_modal_181'] = global_monsta_medal_boxes_181;</script>";

            ?>
            <div class="monsta_medal_box_modal_10 monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Medal Boxes<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($neck_ribbons)) {
            echo "<script>var global_monsta_neck_ribbons = JSON.parse('" . json_encode($neck_ribbons) . "'); global_variations['monsta_neck_ribbon_modal'] = global_monsta_neck_ribbons;</script>";

            ?>
            <div class="monsta_neck_ribbon_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Neck Ribbons<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
        if (!empty($monsta_box)) {
            echo "<script>var global_monsta_boxes = JSON.parse('" . json_encode($monsta_box) . "'); global_variations['monsta_box_modal'] = global_monsta_boxes;</script>";

            ?>
            <div class="monsta_box_modal monsta_modal" style="display:none;">
                <div class="centre_modal-inner">
                    <div class="centre_modal-title">Ribbons<a data-action="centreModalClose" class="close_modal">
                            &times;
                        </a> <span></span></div>
                    <div class="centre_modal-body"></div>
                    <div class="centre_footer">
                        <button data-action="centreModalClose" class="centre_footer-btn">Done</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
//add_action('wp_footer', 'injectVariants');


// Sort G, S, B, BR, Y, etc in order for color items.
function medal_color_sorting( $a, $b ) {
    // Define custom order with a high number for undefined colors.
    $order = [
        'G'  => 1,
        'S'  => 2,
        'B'  => 3,
        'BR' => 4,
        'Y'  => 5
    ];
    $default_priority = 999;

    // Extract the color key from the input strings.
    $a_key = explode( '|', $a )[0];
    $b_key = explode( '|', $b )[0];

    // Get the priority from the order array or use the default priority.
    $a_priority = isset( $order[$a_key] ) ? $order[$a_key] : $default_priority;
    $b_priority = isset( $order[$b_key] ) ? $order[$b_key] : $default_priority;

    return $a_priority - $b_priority;
}
