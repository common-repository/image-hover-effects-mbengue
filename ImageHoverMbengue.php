<?php
/*Plugin Name: Image Hover Effects Mbengue
Description: Ce plugin développé par Zake dans le but de donner un effet survol sur image.Remplacer une image par une autre au survol
Version: 1.1
Date: 11.05.2023
Author: Elhadji Mamadou Mbengue
License: GPLv2
*/
require plugin_dir_path( __FILE__ ) . 'inc/zake_meta_box.php';
function IHEM_custom_post_hovers() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Hovers Rollovers', 'Post Type General Name', 'zake' ),
        'singular_name'       => _x( 'Hover', 'Post Type Singular Name', 'zake' ),
        'menu_name'           => __( 'Hover Images Rollover', 'zake' ),
        'parent_item_colon'   => __( 'Parent Hover', 'kirene' ),
        'all_items'           => __( 'Tous les Hovers Images', 'zake' ),
        'view_item'           => __( 'Voir Hover', 'zake' ),
        'add_new_item'        => __( 'Ajouter Nouvelle Hover Image', 'zake' ),
        'add_new'             => __( 'Ajouter Nouvelle', 'zake' ),
        'edit_item'           => __( 'Editer Hover Image', 'zake' ),
        'update_item'         => __( 'Mettre à jour Hover Image', 'zake' ),
        'search_items'        => __( 'Chercher Hover', 'zake' ),
        'not_found'           => __( 'Non trouvé', 'zake' ),
        'not_found_in_trash'  => __( 'Non Trouvé dans la corbeille', 'zake' ),
    );

// Set other options for Custom Post Type

    $args = array(
        'label'               => __( 'hovers', 'zake' ),
        'description'         => __( 'hovers de Zake', 'zake' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'author','revisions'),
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => array( 'hovers' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => plugins_url( 'img/icone@2x.png', __FILE__ ) ,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,

    );

    // Registering your Custom Post Type
    register_post_type( 'hovers', $args );

}
if (!function_exists('IHEM_hover_shortcode_zake')) {
    function IHEM_hover_shortcode_zake($atts,$content=null){
    	$id = $atts['id'];
    	//print_r($tab);die();
        $args = array(
                        'post_type'      => ['hovers'],
                        'p' => $id,
                        'publish_status' => 'published'
                     );

        $query = new WP_Query($args);
        if($query->post_count == 1){
    	 	//$result='<div class="row row-cols-1 row-cols-md-3 g-4">';
    	    if($query->have_posts()) :
              $j=0;
    	        while($query->have_posts()) :

    	            $query->the_post() ;
      	        	$front_image = get_post_meta( get_the_ID(), 'mytheme_image_front', true );
      	        	$back_image = get_post_meta( get_the_ID(), 'mytheme_image_back', true );
      	        	//$text_right_top = @get_post_meta( get_the_ID(), 'blog_meta_kirene_text_top_right_head', true );
          				$result ='<div class="figure">
            							<img class="image-main" id="image-main_'.$j.'" src="'.$front_image.'" alt="Front"/>
            							<img class="image-hover" id="image-hover_'.$j.'" src="'.$back_image.'" alt="Back"/>
          						</div>';
                /*  $result .=  '<script>
                      var bodyRect = document.body.getBoundingClientRect();
                      elemRect = document.getElementById("image-main_'.$j.'").getBoundingClientRect();
                      offset   = (elemRect.top - bodyRect.top)/2;
                      document.getElementById("image-hover_'.$j.'").style.top = offset+"px";
                      </script>';*/


    	 		    $j++;
    	        endwhile;
    	        wp_reset_postdata();
    	 		$result .="<style>
    			    /*
    			      Rollover Image
    			     */
    			    .figure img{border-radius:10px !important}
    			    .figure {
    			        position: relative;
    			        max-width: 100%;
    			    }
    			    .figure img.image-hover {
    			      position: absolute;
                top:32px;
    			      right: 0;
    			      left: 0;
    			      bottom: 0;
    			      object-fit: contain;
    			      opacity: 0;
    			      transition: opacity .2s;
    			    }
    			    .figure:hover img.image-hover {
    			      opacity: 1;
    			    }
    			</style>";
    	    endif;
    	 }

        return $result;
    }
}
if (!function_exists('IHEM_set_custom_edit_hovers_columns')) {
    function IHEM_set_custom_edit_hovers_columns($columns) {
        $columns['wps_post_id'] = __( 'Copy/Paste Shortcode', 'zake' );

        return $columns;
    }
}
// Add the data to the custom columns for the book post type:
if (!function_exists('IHEM_custom_hovers_column')) {
    function IHEM_custom_hovers_column( $column, $post_id ) {
    	if($column == 'wps_post_id'){
    		echo '<input type="text" readonly value="'.esc_attr('[HOVERMBENGUE id="'.$post_id.'"]').'" style="width:200px"/>';
    	}
    	return;
    }
}
add_shortcode('HOVERMBENGUE','IHEM_hover_shortcode_zake');
add_action( 'init', 'IHEM_custom_post_hovers', 0 );
add_filter( 'manage_hovers_posts_columns', 'IHEM_set_custom_edit_hovers_columns' );
add_action( 'manage_hovers_posts_custom_column' , 'IHEM_custom_hovers_column', 10, 2 );

?>
