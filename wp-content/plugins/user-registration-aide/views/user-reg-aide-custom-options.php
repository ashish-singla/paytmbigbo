<?php

/**
 * User Registration Aide Pro - Custom Options Settings Page
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-pro-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

class URA_CUSTOM_OPTIONS
{

	public static $instance;
	
	public function __construct() {
		$this->URA_CUSTOM_OPTIONS(); 
	}
	
	function URA_CUSTOM_OPTIONS(){
		
		self::$instance = $this;
		
	}
	
	function custom_options_views(){
		global $current_user;
		// Shows Aministration Page 
		$current_user = wp_get_current_user();
		if(current_user_can('manage_options', $current_user->ID)){	
			$tab = 'custom_options';
			$h2 = array( 'adminPage', 'User Registration Aide: Custom Options', 'csds_userRegAide' );
			$span = array( 'regForm', 'Change Custom Settings Here:', 'csds_userRegAide' );
			$form = array( 'post', 'csds_userRegAide_customOptions' );
			$nonce = array( 'csds-customOptions', 'wp_nonce_csds-customOptions' );
			do_action( 'start_wrapper',  $tab, $form, $h2, $span, $nonce );
			//do_action( 'end_mini_wrap' );
			do_action( 'xwrd_set_options_view' );
			do_action( 'change_display_name_view' );
			
			do_action( 'stylesheet_settings_view' );
			do_action( 'end_wrapper' );
			
		}
	}


}
?>