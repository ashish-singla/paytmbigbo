<?php

/**
 * User Registration Aide - Registration Form CSS & Messages Admin Page Options
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Since Version 1.3.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/**
 * Couple of includes for functionality
  *
 * @category Class
 * @since 1.2.0
 * @updated 1.3.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once (URA_PLUGIN_PATH."user-registration-aide.php");
require_once ("user-reg-aide-options.php");
require_once ("user-reg-aide-newFields.php");
require_once ("user-reg-aide-admin.php");

/**
 * Class for better functionality
  *
 * @category Class
 * @since 1.3.0
 * @updated 1.3.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_REG_FORM_CSS_OPTIONS
{

	public static $instance;

	public function __construct() {
		$this->URA_REG_FORM_CSS_OPTIONS();
	}
		
	function URA_REG_FORM_CSS_OPTIONS() { //constructor
		global $wp_version;
		self::$instance = $this;
	}
	
	/**
	 * Loads and displays the User Registration Aide administration page
	 * @handles action 'add_submenu_page' line 694 user-registration-aide.php
	 * @since 1.3.0
	 * @updated 1.5.0.0
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function csds_userRegAide_regFormCSSOptions(){

		global $current_user;
		$ura_options = new URA_OPTIONS(); 
		$options = get_option('csds_userRegAide_Options');
		if($options['csds_userRegAide_db_Version'] != "1.5.0.0"){
			
			$ura_options->csds_userRegAide_updateOptions();
				
		}
		
		// add code to handle new registration form message at top of reg form
		if(isset($_POST['reg_form_login_message_update'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
				$update = array();
				$efields = array();
				$efield = (string) '';
				$ecnt = (int) 0;
				$update = get_option('csds_userRegAide_Options');
				if(!empty($_POST['csds_select_RegFormLoginMessage'])){
					$update['show_login_message'] = esc_attr(stripslashes($_POST['csds_select_RegFormLoginMessage']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Login Message';
				}
				if(!empty($_POST['csds_RegFormTopLogin_Message'])){
					$update['login_message'] = esc_attr(stripslashes($_POST['csds_RegFormTopLogin_Message']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Top Login Form Message';
				}
				if(!empty($_POST['csds_RegFormTopRegistration_Message'])){
					$update['reg_top_message'] = esc_attr(stripslashes($_POST['csds_RegFormTopRegistration_Message']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Registration Form Message';
				}
				if(!empty($_POST['csds_LoginFormLogin_Message'])){
					$update['login_messages_login'] = esc_attr(stripslashes($_POST['csds_LoginFormLogin_Message']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Extra Login Messages';
				}
				if(!empty($_POST['csds_LoginFormLoggedOut_Message'])){
					$update['login_messages_logged_out'] = esc_attr(stripslashes($_POST['csds_LoginFormLoggedOut_Message']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Logged Out Message';
				}
				if(!empty($_POST['csds_LoginFormRegisteredSuccess_Message'])){
					$update['login_messages_registered'] = esc_attr(stripslashes($_POST['csds_LoginFormRegisteredSuccess_Message']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Succesful Registration Message';
				}
				if(!empty($_POST['csds_LostPassword_Message'])){
					$update['login_messages_lost_password'] = esc_attr(stripslashes($_POST['csds_LostPassword_Message'])); 
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Lost Password Message';
				}
				update_option("csds_userRegAide_Options", $update);
				if($ecnt == 0){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Top Registration Form Message Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					foreach($efields as $key => $value){
						if($key == 0){
							$efield = $value;
						}else{
							$efield .= ' & '. $value;
						}
					}
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Top Registration Form Message Options Fields Empty '.$efield, 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
			
		//updates login/registration form custom css options
		
		}elseif (isset($_POST['csds_userRegAide_logo_update'])){ 
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
				$ecnt = (int) 0;
				$efields = array();
				$update = array();
				$update = get_option('csds_userRegAide_Options');
				// show custom logo options
				if(!empty($_POST['csds_userRegAide_logo'])){
					$update['show_logo'] = esc_attr(stripslashes($_POST['csds_userRegAide_logo']));
						if(!empty($_POST['csds_userRegAide_newLogoURL']) && $_POST['csds_userRegAide_logo'] == 1){
							$update['logo_url'] = esc_url_raw(trim($_POST['csds_userRegAide_newLogoURL']));
						}elseif(empty($_POST['csds_userRegAide_newLogoURL']) && $_POST['csds_userRegAide_logo'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'New Logo URL';
						}elseif($_POST['csds_userRegAide_logo'] == 2){
							$update['logo_url'] = esc_url_raw(trim($_POST['csds_userRegAide_newLogoURL']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Logo Option';
				}
				if(!empty($_POST['csds_userRegAide_change_logo_link'])){
					$update['change_logo_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_change_logo_link']));
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Change Logo Link';
				}
				
				// Show custom Form Background Image options
				if(!empty($_POST['csds_userRegAide_background_image'])){
					$update['show_background_image'] = esc_attr(stripslashes($_POST['csds_userRegAide_background_image']));
						if(!empty($_POST['csds_userRegAide_newBackgroundImageURL']) && $_POST['csds_userRegAide_background_image'] == 1){
							$update['background_image_url'] = esc_url_raw(trim($_POST['csds_userRegAide_newBackgroundImageURL']));
						}elseif($_POST['csds_userRegAide_background_image'] == 1 && empty($_POST['csds_userRegAide_newBackgroundImageURL'])){
							$ecnt ++;
							$efields[$ecnt] = 'Background Image URL';
						}elseif($_POST['csds_userRegAide_background_image'] == 2){
							$update['background_image_url'] = esc_url_raw(trim($_POST['csds_userRegAide_newBackgroundImageURL']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Custom Form Background Image Option';
				}
				
				// Show Custion Page Background Image Options
				if(!empty($_POST['csds_userRegAide_page_background_image'])){
					$update['show_reg_form_page_image'] = esc_attr(stripslashes($_POST['csds_userRegAide_page_background_image']));
						if(!empty($_POST['csds_userRegAide_newPageBackgroundImage']) && $_POST['csds_userRegAide_page_background_image'] == 1){
							$update['reg_form_page_image'] = esc_url_raw(trim($_POST['csds_userRegAide_newPageBackgroundImage']));
						}elseif(empty($_POST['csds_userRegAide_newPageBackgroundImage']) && $_POST['csds_userRegAide_page_background_image'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'Custom Page Background Image URL';
						}elseif($_POST['csds_userRegAide_page_background_image'] == 2){
							$update['reg_form_page_image'] = esc_url_raw(trim($_POST['csds_userRegAide_newPageBackgroundImage']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Custom Page Background Image';
				}
				
				// Show Custom Form Background Color Options
				if(!empty($_POST['csds_userRegAide_background_color'])){
					$update['show_background_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_background_color']));
						if(!empty($_POST['csds_userRegAide_newBackgroundColor']) && $_POST['csds_userRegAide_background_color'] == 1){
							$update['reg_background_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newBackgroundColor']));
						}elseif(empty($_POST['csds_userRegAide_newBackgroundColor']) && $_POST['csds_userRegAide_background_color'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'Custom Form Background Color';
						}elseif($_POST['csds_userRegAide_background_color'] == 2){
							$update['reg_background_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newBackgroundColor']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Custom Form Background Color Option';
				}
				
				// Show Custom Page Background Color Options
				if(!empty($_POST['csds_userRegAide_page_background_color'])){
					$update['show_reg_form_page_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_page_background_color']));
						if(!empty($_POST['csds_userRegAide_newPageBackgroundColor']) && $_POST['csds_userRegAide_page_background_color'] == 1){
							$update['reg_form_page_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newPageBackgroundColor']));
						}elseif(empty($_POST['csds_userRegAide_newPageBackgroundColor']) && $_POST['csds_userRegAide_page_background_color'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'Custom Page Background Color';
						}elseif($_POST['csds_userRegAide_page_background_color'] == 2){
							$update['reg_form_page_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newPageBackgroundColor']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Custom Page Background Color Option';
				}
				// Show Custom Text - Links Colors
				if(!empty($_POST['csds_userRegAide_text_color'])){
					$update['show_login_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_text_color']));
						if(!empty($_POST['csds_userRegAide_newTextColor']) && $_POST['csds_userRegAide_text_color'] == 1){
							$update['login_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newTextColor']));
						}elseif(empty($_POST['csds_userRegAide_newTextColor']) && $_POST['csds_userRegAide_text_color'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'Login Form Text-Links Color';
						}elseif($_POST['csds_userRegAide_text_color'] == 2){
							$update['login_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newTextColor']));
						}
						if(!empty($_POST['csds_userRegAide_newHoverTextColor']) && $_POST['csds_userRegAide_text_color'] == 1){
							$update['hover_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newHoverTextColor']));
						}elseif(empty($_POST['csds_userRegAide_newHoverTextColor']) && $_POST['csds_userRegAide_text_color'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'New Links Hover Color';
						}elseif($_POST['csds_userRegAide_text_color'] == 2){
							$update['hover_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newHoverTextColor']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Login Text Color Option';
				}
				// Show Link Shadows
				if(!empty($_POST['csds_userRegAide_show_shadow'])){
					$update['show_shadow'] = esc_attr(stripslashes($_POST['csds_userRegAide_show_shadow']));
						if(!empty($_POST['csds_userRegAide_shadowSize']) && $_POST['csds_userRegAide_show_shadow'] == 1){
							$update['shadow_size'] = esc_attr(stripslashes($_POST['csds_userRegAide_shadowSize']));
						}elseif(empty($_POST['csds_userRegAide_shadowSize']) && $_POST['csds_userRegAide_show_shadow'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'Shadow Size in PX';
						}elseif($_POST['csds_userRegAide_show_shadow'] == 2){
							$update['shadow_size'] = esc_attr(stripslashes($_POST['csds_userRegAide_shadowSize']));
						}
						if(!empty($_POST['csds_userRegAide_shadowColor']) && $_POST['csds_userRegAide_show_shadow'] == 1){
							$update['shadow_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_shadowColor']));
						}elseif(empty($_POST['csds_userRegAide_shadowColor']) && $_POST['csds_userRegAide_show_shadow'] == 1){
							$ecnt ++;
							$efields[$ecnt] = 'Shadow Color';
						}elseif($_POST['csds_userRegAide_show_shadow'] == 2){
							$update['shadow_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_shadowColor']));
						}
				}else{
					$ecnt ++;
					$efields[$ecnt] = 'Show Link Shadows Option';
				}
				update_option("csds_userRegAide_Options", $update);
				if($ecnt == 0){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Registration Form Logo Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					foreach($efields as $key => $value){
						if($key == 1){
							$efield = $value;
						}else{
							$efield .= ' & '. $value;
						}
					}
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Registration Form Logo Options Empty '.$efield, 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		}elseif (isset($_POST['csds_userRegAide_support_submit'])){ // Handles showing support for plugin
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
				$update = array();
				$update = get_option('csds_userRegAide_Options');
				$update['show_support'] = esc_attr(stripslashes($_POST['csds_userRegAide_support']));
				update_option("csds_userRegAide_Options", $update);
				echo '<div id="message" class="updated fade"><p class="my_message">'. __('Support Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
			}
		
		}
		
		if(empty($options)){
			$ura_options->csds_userRegAide_DefaultOptions(); // Line 57 user-reg-aide-options.php
		}
		
		// Shows Aministration Page 
		$current_user = wp_get_current_user();
		if(current_user_can('manage_options', $current_user->ID)){
			$tab = 'registration_form_css_options';
			$h2 = array( 'adminPage', 'User Registration Aide: Custom Registration Form Options', 'csds_userRegAide' );
			$span = array( 'regForm', 'Add Your Own Custom Message to Top of Login & Registration Forms:', 'csds_userRegAide' );
			$form = array( 'post', 'csds_userRegAide_regFormCSSMsgs' );
			$nonce = array( 'csds-regFormCSSMsgs', 'wp_nonce_csds-regFormCSSMsgs' );
			do_action( 'start_wrapper',  $tab, $form, $h2, $span, $nonce );
		
			//Form for adding different message to top of login/registration form 
			?>
				
			<table class="regForm" width="100%">
				<tr>
					<td colspan="2"><?php _e('Choose to add a special message to top of the registration form:', 'csds_userRegAide');?>
					<span title="<?php _e('Add your own custom messages to the top of the Wordpress login-registration-lost-password pages', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_RegFormLoginMessage" id="csds_select_RegFormLoginMessage" value="1" <?php
					if ($options['show_login_message'] == 1) echo 'checked' ;?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Use the default Wordpress messages for the login-registration-lost-password pages','');?>">
					<input type="radio" name="csds_select_RegFormLoginMessage" id="csds_select_RegFormLoginMessage" value="2" <?php
					if ($options['show_login_message'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?> </span>
					</td>
				</tr>
				<tr>						
					<td width="40%"><?php _e('Enter new Login form message for top of login form here: ', 'csds_userRegAide');?></td>
					<td width="60%">
					<textarea name="csds_RegFormTopLogin_Message" id="csds_RegFormTopLogin_Message" class="regForm" rows="1" title="<?php _e('Enter a custom message here for top of login form!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['login_message']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e('Enter new Registration form message for top of page here: ', 'csds_userRegAide');?></td>
					<td width="60%">
					<textarea name="csds_RegFormTopRegistration_Message" id="csds_RegFormTopLogin_Message" class="regForm" rows="1" title="<?php _e('Enter a custom message here for top of registration form!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['reg_top_message']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e('Enter additional new Login form message for top of login page here: ', 'csds_userRegAide');?>
					</td>
					<td width="60%">
					<textarea name="csds_LoginFormLogin_Message" id="csds_LoginFormLogin_Message" class="regForm" rows="1" title="<?php _e('Enter an additional new custom message here for top of login form!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['login_messages_login']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e('Enter new Login form message for top of login page when users log out here: ', 'csds_userRegAide');?>
					</td>
					<td width="60%">
					<textarea name="csds_LoginFormLoggedOut_Message" id="csds_LoginFormLoggedOut_Message" class="regForm" rows="1" title="<?php _e('Enter a custom message here for top of login form after user is logged out!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['login_messages_logged_out']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e('Enter new Login Form message for top of Login Page after user succesfully registers here: ', 'csds_userRegAide');?>
					</td>
					<td width="60%">
					<textarea name="csds_LoginFormRegisteredSuccess_Message" id="csds_LoginFormRegisteredSuccess_Message" class="regForm" rows="1" title="<?php _e('Enter a custom message here for top of login form after user has succesfully registered!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['login_messages_registered']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e('Enter new Lost Password Form message for top of Lost Password Page Here: ', 'csds_userRegAide');?>
					</td>
					<td width="60%">
					<textarea name="csds_LostPassword_Message" id="csds_LostPassword_Message" class="regForm" rows="1" title="<?php _e('Enter a custom message here for top of lost password form if user attempts to recover lost password!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['login_messages_lost_password']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
			</table>
			<div class="submit">
			<input type="submit" class="button-primary" name="reg_form_login_message_update" value="<?php _e('Update Top Registration Form Message Options', 'csds_userRegAide'); ?>"  />
			</div>
			<?php
			do_action( 'end_mini_wrap' );
				
			// Form area for adding custom logo
			$span = array( 'regForm', 'Add Your Own Logo and Registration Form Customizations:', 'csds_userRegAide' );
			do_action( 'start_mini_wrap', $span );
			$logo_url = (string) '';
			if(!empty($options['logo_url'])){
				$logo_url = $options['logo_url'];
			}elseif(empty($options['logo_url']) && $options['show_logo'] == 1){
				$logo_url = home_url('/wp-admin/images/wordpress-logo.png');
			}
			?>
			<table class="regForm" width="100%">
				<tr>
				<?php
				// Custom Logo
				?>
					<td width="25%"><?php _e('Show Custom Logo: ', 'csds_userRegAide'); ?>
					<span title="<?php _e('Select this option to show your own logo on the login-registration pages', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_logo" name="csds_userRegAide_logo" value="1"
					<?php if ($options['show_logo'] == 1) echo 'checked' ;?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to show the default Wordpress logo on the login-registration pages', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_logo" name="csds_userRegAide_logo"  value="2" <?php
					if ($options['show_logo'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td width="25%" title="<?php _e('Changes the link from the login form image from the wordpress site to your site, automatically defaults to your home page!', 'csds_userRegAide');?>">
					<?php _e('Change Custom Logo Link: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to add your own logo link to the logo on the login-registration pages, automatically defaults to your site homepage!', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_change_logo_link" name="csds_userRegAide_change_logo_link" value="1" <?php
					if ($options['change_logo_link'] == 1) echo 'checked' ;?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress logo link on the logo on the login-registration pages', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_change_logo_link" name="csds_userRegAide_change_logo_link"  value="2" <?php
					if ($options['change_logo_link'] == 2) echo 'checked' ;?>/><?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td width="50%"><?php _e('New Logo URL: ', 'csds_userRegAide');?>
					<input  style="width: 550px;" type="text" title="<?php esc_url(_e('Enter the URL where your new logo is for your register/login page -- (http://mysite.com/wp-content/uploads/9/5/thislogo.png)', 'csds_userRegAide'));?>" value="<?php _e(esc_url($logo_url), 'csds_userRegAide');?>" name="csds_userRegAide_newLogoURL" id="csds_userRegAide_newLogoURL" />
					</td>
				</tr>
				
				<?php // Form Background Image
				$bckgrd_image_url = (string) '';
				if(!empty($options['background_image_url'])){
					$bckgrd_image_url = $options['background_image_url'];
				}else{
					$bckgrd_image_url = home_url('/add-background-image-location-here.img');
				}?>
				
				<tr>
					<td><?php _e('Show Custom Background Image: ', 'csds_userRegAide'); ?>
					<br/>
					<span title="<?php _e('Select this option to add your own custom background image on the login-registration form', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_background_image" name="csds_userRegAide_background_image" value="1" <?php
					if ($options['show_background_image'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress background image on the login-registration form', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_background_image" name="csds_userRegAide_background_image" value="2"
					<?php if ($options['show_background_image'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td colspan="2"><?php _e('New Background Image URL: ', 'csds_userRegAide');?>
					<input  style="width: 550px;" type="text" title="<?php esc_url(_e('Enter the URL where your new background image is for your login/register forms --  (http://mysite.com/wp-content/uploads/9/5/this-background-image.png)', 'csds_userRegAide'));?>" value="<?php _e(esc_url($bckgrd_image_url), 'csds_userRegAide');?>" name="csds_userRegAide_newBackgroundImageURL" id="csds_userRegAide_newBackgroundImageURL" />
					</td>
				</tr>
				<?php
				// Page Background Image 	
				$pg_bckgrd_image_url = (string) '';
				if(!empty($options['reg_form_page_image'])){
					$pg_bckgrd_image_url = $options['reg_form_page_image'];
				}else{
					$pg_bckgrd_image_url = home_url('/enter-new-page-background-image-location-here.img');
				}
				?>
				<tr>
					<td><?php _e('Show Custom Page Background Image: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to add your own custom background image on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_page_background_image" name="csds_userRegAide_page_background_image" value="1"
					<?php if ($options['show_reg_form_page_image'] == 1) echo 'checked';?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress background image on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_page_background_image" name="csds_userRegAide_page_background_image" value="2" 
					<?php if ($options['show_reg_form_page_image'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td colspan="2"><?php _e('New Page Background Image URL: ', 'csds_userRegAide');?>
					<input  style="width: 550px;" type="text" title="<?php esc_url(_e('Enter the new page background image url for your register/login pages (http://mysite.com/content/uploads/myimage.png)', 'csds_userRegAide'));?>" value="<?php _e(esc_url($pg_bckgrd_image_url), 'csds_userRegAide');?>" name="csds_userRegAide_newPageBackgroundImage" id="csds_userRegAide_newPageBackgroundImage" />
					</td>
				</tr>
				<?php						
				// Form Background Color
				?>
				<tr>
					<td><?php _e('Show Custom Form Background Color: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to add your own custom background color on the login-registration form', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_background_color" name="csds_userRegAide_background_color" value="1"
					<?php if ($options['show_background_color'] == 1) echo 'checked';?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress background color on the login-registration form', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_background_color" name="csds_userRegAide_background_color" value="2" 
					<?php if ($options['show_background_color'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td colspan="2"><?php _e('New Background Color: ', 'csds_userRegAide');?>
					<input  style="width: 150px;" type="text" title="<?php _e(esc_attr('Enter the new background color for your login/register form (#FFFFFF)'), 'csds_userRegAide');?>" value="<?php _e(esc_attr($options['reg_background_color']), 'csds_userRegAide');?>" name="csds_userRegAide_newBackgroundColor" id="csds_userRegAide_newBackgroundColor" />
					</td>
				</tr>
				<?php
					// Page Background Color
				?>
				<tr>
					<td><?php _e('Show Custom Page Background Color: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to add your own custom background color on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_page_background_color" name="csds_userRegAide_page_background_color" value="1"
					<?php if ($options['show_reg_form_page_color'] == 1) echo 'checked';?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress background color on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_page_background_color" name="csds_userRegAide_page_background_color" value="2" 
					<?php if ($options['show_reg_form_page_color'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td colspan="2"><?php _e('New Page Background Color: ', 'csds_userRegAide');?>
					<input  style="width: 150px;" type="text" title="<?php _e(esc_attr('Enter the new page background color for your register/login form (#FFFFFF)'), 'csds_userRegAide');?>" value="<?php _e(esc_attr($options['reg_form_page_color']), 'csds_userRegAide');?>" name="csds_userRegAide_newPageBackgroundColor" id="csds_userRegAide_newPageBackgroundColor" />
					</td>
				</tr>
				<?php
					// Text label and link colors	?>
				<tr>
					<td><?php _e('Show Custom Text/Links Colors: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to add your own custom text and link colors on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_text_color" name="csds_userRegAide_text_color" value="1"
					<?php if ($options['show_login_text_color'] == 1) echo 'checked';?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress text and link colors on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_text_color" name="csds_userRegAide_text_color" value="2" 
					<?php if ($options['show_login_text_color'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td><?php _e('New Text/Links Color: ', 'csds_userRegAide');?>
					<input  style="width: 100px;" type="text" title="<?php _e(esc_attr('Enter the new text/links color for your site (#FFFFFF)'), 'csds_userRegAide');?>" value="<?php _e(esc_attr($options['login_text_color']), 'csds_userRegAide');?>" name="csds_userRegAide_newTextColor" id="csds_userRegAide_newTextColor" /></td>
					<td><?php _e('New Links Hover Color: ', 'csds_userRegAide');?>
					<input  style="width: 100px;" type="text" title="<?php _e(esc_attr('Enter the new hover color for your login page links (#FFFFFF)'), 'csds_userRegAide');?>" value="<?php _e(stripslashes($options['hover_text_color']), 'csds_userRegAide');?>" name="csds_userRegAide_newHoverTextColor" id="csds_userRegAide_newHoverTextColor" />
					</td>
				</tr>
				<?php
				// Link Shadow Size & colors
				?>
				<tr>
					<td><?php _e('Show Link Shadows: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to add your own custom link shadow size and colors on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_show_shadow" name="csds_userRegAide_show_shadow" value="1"
					<?php if ($options['show_shadow'] == 1) echo 'checked';?>/> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to use the default Wordpress link shadow size and colors on the login-registration page', 'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_show_shadow" name="csds_userRegAide_show_shadow" value="2" 
					<?php if ($options['show_shadow'] == 2) echo 'checked' ;?>/> <?php _e('No', 'csds_userRegAide'); ?>
					</td>
					<td><?php _e('Shadow Size in PX: ', 'csds_userRegAide');?>
					<input  style="width: 100px;" type="text" title="<?php _e(esc_attr('Enter the new size of shadow for login/registration page links in PX for your site! Example: 2px'), 'csds_userRegAide');?>" value="<?php _e(esc_attr($options['shadow_size']), 'csds_userRegAide');?>" name="csds_userRegAide_shadowSize" id="csds_userRegAide_newTextColor" />
					</td>
					<td><?php _e('Shadow Color: ', 'csds_userRegAide');?>
					<input  style="width: 100px;" type="text" title="<?php _e(esc_attr('Enter the new color for your login page links shadows (#FFFFFF)'), 'csds_userRegAide');?>" value="<?php _e(esc_attr($options['shadow_color']), 'csds_userRegAide');?>" name="csds_userRegAide_shadowColor" id="csds_userRegAide_shadowColor" />
					</td>
				</tr>
			</table>
			<div class="submit">
			<input type="submit" class="button-primary" name="csds_userRegAide_logo_update" id="csds_userRegAide_logo_update" value="<?php _e('Update Login-Reg Form Style Options', 'csds_userRegAide');?>" />
			</div>
			<?php
			do_action( 'end_wrapper' ); 
		}else{
			wp_die(__('You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!'));
		}
	} // end function
	
} // end class