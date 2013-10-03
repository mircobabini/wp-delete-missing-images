<?php
/*
Plugin Name: delete Missing Images
Plugin URI: http://github.com/mirkolofio/wp-delete-missing-images/
Description: Deletes all the attachments without an image
Author: Mirco Babini <mirkolofio@gmail.com
Version: 1.0.0
Author URI: http://github.com/mirkolofio

	Copyright (c) 2009-2012 Mirco Babini (http://github.com/mirkolofio)
	WordPress Reset is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl-2.0.txt
*/

// Only run the code if we are in the admin
if ( is_admin() ) :

class WordPressdeleteMissingImages {

	// Action/Filter Hooks
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'add_page' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
	}

	function admin_init() {
		if ( array_key_exists( 'delete-missing-images', $_GET ) ) {
			$imgs = get_posts("post_type=attachment&numberposts=-1");

			foreach($imgs as $img){
				$file = get_attached_file($img->ID);

				if(!file_exists($file)){
					wp_delete_post( $img->ID, false );
				}
			}			
			
			wp_redirect( admin_url() . '?missing-images-deleted' );
			exit();
		}
		else if ( array_key_exists( 'missing-images-deleted', $_GET ) )
			add_action( 'admin_notices', array( &$this, 'ok_notice' ) );
	}

	// admin_notices action hook operations
	// Inform the user that the dirty work is done
	function ok_notice() {
		echo '<div id="message" class="updated fade"><p><strong>Missing Images deleted. Yuppy!</strong></p></div>';
	}
	
	// admin_menu action hook operations
	// Add the delete menu item
	function add_page() {
		global $submenu;
		if ( current_user_can( 'level_10' ) && function_exists( 'add_management_page' ) )
			$submenu['upload.php'][667] = array( 'delete Missing Images', 'manage_options' , admin_url() . '?delete-missing-images' ); 
	}
}

// Instantiate the class
$WordPressdeleteMissingImages = new WordPressdeleteMissingImages();

// End if for is_admin
endif;
