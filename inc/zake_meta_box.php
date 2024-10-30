<?php
/*
Metabox for HoverMbengue */
add_action('add_meta_boxes', 'IHEM_cd_meta_box_add');
if (!function_exists('IHEM_cd_meta_box_add')) {
    function IHEM_cd_meta_box_add()
    {
        global $post;

        if(!empty($post))
        {
        	if($post->post_type == 'hovers'  )
            {
                add_meta_box( 'hovers-meta', "Image Hover Mbengue - Images Front & Back", 'IHEM_cd_meta_box_cb_hovers', 'hovers', 'normal', 'high' );
                function load_wp_media_files() {
                    wp_enqueue_media();
                }
                add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
            }
        }

    }
}
/********* Flip Image ***************************************/
if (!function_exists('IHEM_cd_meta_box_cb_hovers')) {
    function IHEM_cd_meta_box_cb_hovers( $post ){
        // $post is already set, and contains an object: the WordPress post
        global $post;
        $values = get_post_custom( $post->ID );
        //image "Expérience unique"
        $mytheme_image_front = ( isset( $values['mytheme_image_front'][0] ) ) ? $values['mytheme_image_front'][0] : '';
        //image "Ils ont du talent"
        $mytheme_image_back = ( isset( $values['mytheme_image_back'][0] ) ) ? $values['mytheme_image_back'][0] : '';

        // We'll use this nonce field later on when saving.
        wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
        ?>
        <table cellspacing="10">
        <tr>
            <td>
                <label for="mytheme_image_front"><?php esc_html_e( 'Image &laquo; Front &raquo;', 'zake' ); ?></label>
                <div class="uploaded_image">
                <?php if ( '' !== $mytheme_image_front ) : ?>
                    <img src="<?php echo esc_url( $mytheme_image_front ); ?>" width="100"/>
                <?php endif; ?>
                </div>
                <input type="text" name="mytheme_image_front" value="<?php echo esc_url( $mytheme_image_front ); ?>" class="featured_image_upload"/>
                <input type="button" name="image_upload" value="<?php esc_html_e( '+ image', 'zake' ); ?>" class="button upload_image_button"/>
                <input type="button" name="remove_image_upload" value="<?php esc_html_e( 'Supprimer', 'zake' ); ?>" class="button remove_image_button"/>
            </td>
            <td>
                <label for="mytheme_image_back"><?php esc_html_e( 'Image &laquo; Back &raquo;', 'zake' ); ?></label>
                <div class="uploaded_image">
                <?php if ( '' !== $mytheme_image_back ) : ?>
                    <img src="<?php echo esc_url( $mytheme_image_back ); ?>" width="100"/>
                <?php endif; ?>
                </div>
                <input type="text" name="mytheme_image_back" value="<?php echo esc_url( $mytheme_image_back ); ?>" class="featured_image_upload"/>
                <input type="button" name="image_upload" value="<?php esc_html_e( '+ image', 'zake' ); ?>" class="button upload_image_button"/>
                <input type="button" name="remove_image_upload" value="<?php esc_html_e( 'Supprimer', 'zake' ); ?>" class="button remove_image_button"/>
            </td>
        </tr>
        </table>
        <style>
        .uploaded_image{display: block; width: 200px;}
        .uploaded_image img{width: 200px;}
        .featured_image_upload{display: block; margin-bottom: 5px}
        .meta_zake_text_ont_du_talent{width:100%;}
        .h1{font-size: 2em; font-weight: 500; border-bottom: 1px dotted #000}
        </style>
        <?php
    }
}
if (!function_exists('IHEM_cd_meta_box_hovers_save')) {
    function IHEM_cd_meta_box_hovers_save( $post_id )
    {

        global $meta_box;
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

        // if our current user can't edit this post, bail
        //if( !current_user_can( 'edit_post' ) ) return;
        // check permissions
        if ('hovers' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        // now we can actually save the data
        $allowed = array(
            'a' => array( // on allow a tags
                'href' => array() // and those anchors can only have href attribute
            )
        );
         //enregistrement image "front"
        if ( isset( $_POST['mytheme_image_front'] ) ) { // Input var okay.
            update_post_meta( $post_id, 'mytheme_image_front', sanitize_text_field( wp_unslash( $_POST['mytheme_image_front'] ) ) ); // Input var okay.
        }
        //enregistrement image "back"
        if ( isset( $_POST['mytheme_image_back'] ) ) { // Input var okay.
            update_post_meta( $post_id, 'mytheme_image_back', sanitize_text_field( wp_unslash( $_POST['mytheme_image_back'] ) ) ); // Input var okay.
        }

    }



}
if (!function_exists('IHEM_zake_load_admin_script')) {
    function IHEM_zake_load_admin_script( $hook ) {
    	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    		//esc_url( plugins_url( 'images/wordpress.png', __FILE__ ) )
    		wp_enqueue_script( 'admin_js',esc_url(plugins_url( '../js/admin.js', __FILE__ ) ) , array(), _S_VERSION, true);
    	}
    }
}
add_action( 'admin_enqueue_scripts', 'IHEM_zake_load_admin_script' );
add_action( 'save_post_hovers', 'IHEM_cd_meta_box_hovers_save' );
