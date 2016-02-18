<?php
/**
 * User Registration Aide - Custom CSS Functions For Registration - Login Pages Custom Styling
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Since Version 1.3.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

/*
 * URA CUSTOM CSS Adds Styles and Scripts to Login/Registration Forms
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.3.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_CUSTOM_CSS
{

	public static $instance;
	
	public function __construct() {
		$this->URA_CUSTOM_CSS();
	}
		
	function URA_CUSTOM_CSS() { //constructor
	
		global $wp_version;
		self::$instance = $this;
	}
	
	/**
	 * Sets the text color and logo links & shadows in registration/login pages
	 * @since 1.2.0
	 * @updated 1.3.0
	 * @Filters 'login_headertitle' Line 125 & 145 (multisite) $this
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_Logo_Title_Color(){
		$options = get_option('csds_userRegAide_Options');
		$show_text_color = $options['show_login_text_color'];
		$text_color = $options['login_text_color'];
		$hover_color = $options['hover_text_color'];
		$show_shadow = $options['show_shadow'];
		$shadow_size = $options['shadow_size'];
		$shadow_color = $options['shadow_color'];
		
		if($show_text_color == "1" && $show_shadow == "2"){
			echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;}';
			echo '#registerform label{font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;} ';
			echo 'body.login #nav a  {color:'.$text_color.' !important; font-weight:bold; font-size:1.4em; margin-left:-9999; text-shadow: 0px 0px 0px #000000;}';
			echo '.login #backtoblog a { color:'.$text_color.' !important; font-weight:bold; font-size:1.5em; text-shadow: 0px 0px 0px #000000;}';
			echo '.login #nav a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.4em;}';
			echo '.login #backtoblog a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.5em;} </style>';
			
		}elseif($show_text_color == "1" && $show_shadow == "1"){
			echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;}';
			echo '#registerform label{font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;} ';
			echo 'body.login #nav a  {color:'.$text_color.' !important; font-weight:bold; font-size:1.4em; margin-left:-9999; text-shadow:'.$shadow_size.' '.$shadow_size.' '.$shadow_size.' '.$shadow_color.';}';
			echo '.login #backtoblog a { color:'.$text_color.' !important; font-weight:bold; font-size:1.5em; text-shadow:'.$shadow_size.' '.$shadow_size.' '.$shadow_size.' '.$shadow_color.';}';
			echo '.login #nav a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.4em;}';
			echo '.login #backtoblog a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.5em;} </style>';
		}
		
		if ($options['change_logo_link'] == "2"){
			return;
		}elseif($options['change_logo_link'] == "1"){
			return get_bloginfo('name');
		}
		
	}

	/**
	 * Sets up head for custom login and registration pages
	 * @since 1.2.0
	 * @updated 1.3.7.2
	 * @handles action 'login_head' Line 126 &$this Handles multisite action 'signup_header' line 146 &$this
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_Logo_Head(){
		$options = get_option('csds_userRegAide_Options');
		$show_logo = $options['show_logo'];
		$logo_url = $options['logo_url'];
		$show_background_image = $options['show_background_image'];
		if($show_background_image == 1 && !empty($options['background_image_url'])){
			$background_image = $options['background_image_url'];
		}
		$show_background_color = $options['show_background_color'];
		$background_color = $options['reg_background_color'];
		$show_page_image = $options['show_reg_form_page_image'];
		if($show_page_image == 1 && !empty($options['reg_form_page_image'])){
			$page_image = $options['reg_form_page_image'];
		}
		$show_page_color = $options['show_reg_form_page_color'];
		$page_color = $options['reg_form_page_color'];
		
		if ($show_logo == "1"){
			if ( function_exists( 'theme_my_login' ) ) {
				echo '<style type="text/css">div.site-content header.entry-header h1.entry-title a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
				echo '<style type="text/css">header.entry-header a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
				echo '<style>h1.entry-title a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
			}else{
				echo '<style type="text/css">body.login div#login h1 a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
			}
		}
		
		
		
		if($show_background_image =="1" && !empty($background_image)){
			echo '<style type="text/css">#loginform{background:url('.$background_image.') no-repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">#registerform{background:url('.$background_image.') no-repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">#lostpasswordform{background:url('.$background_image.') no-repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
		}
			
		if($show_background_color == "1"){
			 echo '<style type="text/css">#loginform{background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
			 echo '<style type="text/css">#registerform{background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
			  echo '<style type="text/css">#lostpasswordform{background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
			 echo '<style type="text/css">p.message{background-color:'.$background_color.' !important;} </style>';
		}
				
		if($show_page_image == "1" && !empty($page_image)){
			echo '<style type="text/css">body.login{height:350%; background:url('.$page_image.') repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">body.register{background:url('.$page_image.') repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">body.login login-action-lostpassword wp-core-ui{height:350%; background:url('.$page_image.') repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			
		}
			
		if($show_page_color == "1"){
			echo '<style type="text/css">body.login{height:350%; background-color:'.$page_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
			echo '<style type="text/css">body.login login-action-lostpassword wp-core-ui{height:350%; background-color:'.$page_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
			echo '<style type="text/css">body.login{height:350%; background-color:'.$page_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
		}
		
	}

	/**
	 * Sets Custom Logo link to site url if option chosen to add custom logo
	 *
	 * @since 1.2.0
	 * @updated 1.2.0
	 * @Filters 'login_headerurl' Lines 124 & 144 (for multisite) &$this
	 * @access private
	 * @returns logo_link
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_CustomLoginLink(){
		$options = get_option('csds_userRegAide_Options');
		if ($options['change_logo_link'] == "2"){
			return 'http://www.wordpress.org';
		}
		if ($options['change_logo_link'] == "1"){
			return site_url();
		}
	}
	
	/**
	 * Handles scripts and css for password headers on registration forms
	 *
	 * @since 1.0.0
	 * @updated 1.3.2
	 * @handles action 'login_head' Line 123 &$this
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function csds_userRegAide_Password_Header(){
		
		$options = get_option('csds_userRegAide_Options');
			
		$css = CSS_PATH."user-reg-aide-style.php";
		
		$wp_pswrd_strength = admin_url().'js/user-profile.js';
		$wp_admin_psm_js = admin_url().'js/password-strength-meter.js';
		$wp_incl_jq = includes_url().'js/jquery/jquery.js';
		$jq_color = JS_PATH."jquery.color.js";
		$jq_color_min = JS_PATH."jquery.color.min.js";
				
		if($options['user_password'] == "1"){
			wp_register_script("jquery", $wp_incl_jq, false);
			wp_enqueue_script('jquery');
					
			wp_register_script("jquery_color", $jq_color, false);
			wp_enqueue_script('jquery_color');
			
			wp_register_script("jquery_color_min", $jq_color_min, false);
			wp_enqueue_script('jquery_color_min');
					
			wp_register_style("user-reg-aide-style", $css, false, false);
			wp_enqueue_style('user-reg-aide-style');
			
            wp_enqueue_script('password-strength-meter');
            wp_enqueue_script('user-profile');
				
		}else{
		
		}
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('utils');
		
	}
	
	/**
	 * Handles scripts and css for password headers on password change form
	 *
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @handles 
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function password_options(){
		global $post;
		$xwrds = new PASSWORD_FUNCTIONS();
		$id = $post->ID;
		$xwrd_id = $xwrds->title_id( $post );
		$wp_pswrd_strength = admin_url().'js/user-profile.js';
		$wp_admin_psm_js = admin_url().'js/password-strength-meter.js';
		$wp_incl_jq = includes_url().'js/jquery/jquery.js';
		$jq_color = JS_PATH."jquery.color.js";
		$jq_color_min = JS_PATH."jquery.color.min.js";
		if( $id == $xwrd_id ){
			wp_register_style( 'user_regAide_style', plugins_url( 'css/user-reg-aide-style.php', __FILE__ ) );
			wp_enqueue_style( 'user_regAide_style' );
			wp_register_script("jquery", $wp_incl_jq, false);
			wp_enqueue_script('jquery');
					
			wp_register_script("jquery_color", $jq_color, false);
			wp_enqueue_script('jquery_color');
			
			wp_register_script("jquery_color_min", $jq_color_min, false);
			wp_enqueue_script('jquery_color_min');
			wp_enqueue_script('password-strength-meter');
            wp_enqueue_script('user-profile');
			wp_enqueue_script('jquery');
			wp_enqueue_script('utils');
		}
	}
} // end class ?>