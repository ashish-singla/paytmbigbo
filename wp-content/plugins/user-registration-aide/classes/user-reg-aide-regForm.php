<?php

/**
 * User Registration Aide - Registration Form Options Settings Admin Page
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

/*For Debugging and Testing Purposes ------------


*/

// ----------------------------------------------

/**
 * Couple of includes for functionality
 *
 * @since 1.2.0
 * @updated 1.4.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


/**
 * Class for better functionality for regForm options
  *
 * @category Class
 * @since 1.3.0
 * @updated 1.5.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_REG_FORM_OPTIONS
{

	public static $instance;

	public function __construct() {
		$this->URA_REG_FORM_OPTIONS();
	}
		
	function URA_REG_FORM_OPTIONS() { //constructor
	
		global $wp_version;
		
		self::$instance = $this;
	}
		
	/**
	 * Loads and displays the User Registration Aide administration page
	 * @handles action 'add_submenu_page' line 672 user-registration-aide.php
	 * @since 1.2.0
	 * @updated 1.5.0.0
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function csds_userRegAide_regFormOptions(){

		global $current_user;
		$ura_options = new URA_OPTIONS(); 
		$options = get_option('csds_userRegAide_Options');
		if($options['csds_userRegAide_db_Version'] != "1.5.0.0"){
			$ura_options->csds_userRegAide_updateOptions();
		}
			
		if(empty($csds_userRegAide_registrationFields)){
			$ura_options->csds_userRegAide_updateRegistrationFields();
		}
		
		// nonce security validation
		
		
		// add code to handle new registration form message at top of reg form
		if(isset($_POST['reg_form_message_update'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				// add code to handle new registration form message
				$update = array();
				$ucnt = (int) 0;
				$update = get_option('csds_userRegAide_Options');
				if(!empty($_POST['csds_select_RegFormMessage'])){
					$update['select_pass_message'] = esc_attr(stripslashes($_POST['csds_select_RegFormMessage']));
				}else{
					$ucnt ++;
				}
				if(!empty($_POST['csds_RegForm_Message'])){
					$update['registration_form_message'] = esc_attr(stripslashes($_POST['csds_RegForm_Message']));
				}else{
					$ucnt ++;
				}
				if($ucnt == 0){
					update_option("csds_userRegAide_Options", $update);
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Bottom Registration Form Message Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Bottom Registration Form Message Options empty, not updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
			
		// handles password strength requirement options
		}elseif(isset($_POST['psr_update'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$ucnt = (int) 0;
				$field = (string) '';
				$update = get_option('csds_userRegAide_Options');
				
				if(!empty($_POST['csds_select_DefaultPSR']) && !empty($_POST['csds_select_CustomPSR'])){ // Updates default password strength requirements
					$update['default_xwrd_strength'] = esc_attr(stripslashes($_POST['csds_select_DefaultPSR']));
					if($_POST['csds_select_DefaultPSR'] == 1 && $_POST['csds_select_CustomPSR'] == 2){
						$update['require_xwrd_length'] = 1;
						$update['xwrd_length'] = 8;
						$update['xwrd_uc'] = 1;
						$update['xwrd_lc'] = 1;
						$update['xwrd_numb'] = 1;
						$update['xwrd_sc'] = 1;
						$update['custom_xwrd_strength'] = 2;
					
					}elseif($_POST['csds_select_CustomPSR'] == 1){
						if(!empty($_POST['csds_select_CustomPSR'])){ // Updates custom password strength requirements
							$update['custom_xwrd_strength'] = esc_attr(stripslashes($_POST['csds_select_CustomPSR']));
							$update['default_xwrd_strength'] = 2;
						}else{
							$ucnt ++;
							$field .= ' Custom Password Strength Requirement Option ';
						}
						
						if(!empty($_POST['csds_select_MinXwrdLngth'])){ // Updates minimum password length
							$update['require_xwrd_length'] = esc_attr(stripslashes($_POST['csds_select_MinXwrdLngth']));
						}else{
							$ucnt ++;
							$field .= ' Minimum Password Length Requirement Option ';
						}
						
						if(!empty($_POST['csds_xwrdLength'])){ // Updates minimum password length
							$update['xwrd_length'] = esc_attr(stripslashes($_POST['csds_xwrdLength']));
						}else{
							$ucnt ++;
							$field .= ' Minimum Password Length Requirement Option ';
						}
						
						if(!empty($_POST['csds_select_UCPSR'])){ // Updates password strength requirement upper case letter
							$update['xwrd_uc'] = esc_attr(stripslashes($_POST['csds_select_UCPSR']));
						}else{
							$ucnt ++;
							$field .= ' Password Strength Requirement Option Upper Case Letter ';
						}
						
						if(!empty($_POST['csds_select_LCPSR'])){ // Updates password strength requirement lower case letter
							$update['xwrd_lc'] = esc_attr(stripslashes($_POST['csds_select_LCPSR']));
						}else{
							$ucnt ++;
							$field .= ' Password Strength Requirement Option Lower Case Letter ';
						}
						
						if(!empty($_POST['csds_select_NumbPSR'])){ // Updates password strength requirement number
							$update['xwrd_numb'] = esc_attr(stripslashes($_POST['csds_select_NumbPSR']));
						}else{
							$ucnt ++;
							$field .= ' Password Strength Requirement Option Number ';
						}
						
						if(!empty($_POST['csds_select_SCPSR'])){ // Updates password strength requirement special character
							$update['xwrd_sc'] = esc_attr(stripslashes($_POST['csds_select_SCPSR']));
						}else{
							$ucnt ++;
							$field .= ' Password Strength Requirement Option Special Character ';
						}
					}
				}else{
					$ucnt ++;
					$field = ' Default Password Strength Requirement Option ';
				}
				
				if($ucnt == 0){
					update_option("csds_userRegAide_Options", $update);
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Password Strength Requirement Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Password Strength Requirement Options empty, not updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		// add code to handle new registration & login form redirects
		}elseif(isset($_POST['redirects_update'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$ucnt = (int) 0;
				$field = (string) '';
				$update = get_option('csds_userRegAide_Options');
				if(!empty($_POST['csds_registration_redirect_option'])){
					$update['redirect_registration'] = esc_attr(stripslashes($_POST['csds_registration_redirect_option']));
				}else{
					$ucnt ++;
					$field = ' Registration Redirect URL Option ';
				}
				if(!empty($_POST['csds_registration_redirect_url'])){
					$update['registration_redirect_url'] = esc_url_raw(trim($_POST['csds_registration_redirect_url']));
				}else{
					$ucnt ++;
					$field .= ' Registration Redirect URL ';
				}
				if(!empty($_POST['csds_login_redirect_option'])){
					$update['redirect_login'] = esc_attr(stripslashes($_POST['csds_login_redirect_option']));
				}else{
					$ucnt ++;
					$field .= ' Login Redirect URL Option ';
				}
				if(!empty($_POST['csds_login_redirect_url'])){
					$update['login_redirect_url'] = esc_url_raw(trim($_POST['csds_login_redirect_url']));
				}else{
					$ucnt ++;
					$field .= ' Login Redirect URL ';
				}
				update_option("csds_userRegAide_Options", $update);
				if($ucnt == 0){
				echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Login & Registration Form Custom Redirects Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Login & Registration Form Custom Redirects Options Field '.$field.' Empty!', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		
		// Updates registration form agreement message options
		}elseif (isset($_POST['reg_form_agreement_message_update'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$ucnt = (int) 0;
				$field = (string) '';
				$update = get_option('csds_userRegAide_Options');
				if(!empty($_POST['csds_userRegAide_agreement_link'])){
					$update['show_custom_agreement_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_agreement_link']));
				}else{
					$ucnt ++;
					$field = ' Show User Custom Agreement Link Option ';
				}
				if(!empty($_POST['csds_userRegAide_newAgreementURL'])){
					$update['agreement_link'] = esc_url_raw(trim($_POST['csds_userRegAide_newAgreementURL']));
				}else{
					$ucnt ++;
					$field .= ' Custom Agreement Link ';
				}
				if(!empty($_POST['csds_userRegAide_newAgreementTitle'])){
					$update['agreement_title'] = esc_attr(stripslashes($_POST['csds_userRegAide_newAgreementTitle']));
				}else{
					$ucnt ++;
					$field .= ' Custom Agreement Title ';
				}
				if(!empty($_POST['csds_userRegAide_show_agreement_message'])){
					$update['show_custom_agreement_message'] = esc_attr(stripslashes($_POST['csds_userRegAide_show_agreement_message']));
				}else{
					$ucnt ++;
					$field .= ' Show Custom Agreement Message Confirmation Agreement Option ';
				}
				if(!empty($_POST['csds_userRegAide_agreement_checkbox'])){
					$update['show_custom_agreement_checkbox'] = esc_attr(stripslashes($_POST['csds_userRegAide_agreement_checkbox']));
				}else{
					$ucnt ++;
					$field .= ' Show Custom Agreement Checkbox Option ';
				}
				if(!empty($_POST['csds_RegForm_Agreement_Message'])){
					$update['agreement_message'] = esc_attr(stripslashes($_POST['csds_RegForm_Agreement_Message']));
				}else{
					$ucnt ++;
					$field .= ' Custom Agreement Message ';
				}
				update_option("csds_userRegAide_Options", $update);
				if($ucnt == 0){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Registration Form Agreement Message Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Registration Form Agreement Message Options Fields '.$field.' Empty!', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
			
		// Upates anti-spam math problem settings 
		}elseif (isset($_POST['anti-bot-spammer'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$mcnt = (int) 0;
				$errors = (string) '';
				$update = get_option('csds_userRegAide_Options');
				$update['activate_anti_spam'] = esc_attr(stripslashes($_POST['csds_select_AntiBot']));
				if($_POST['csds_select_AntiBot'] == 1){
					if($_POST['csds_div_AntiBot'] == 2){
						$mcnt ++;
					}
					if($_POST['csds_multiply_AntiBot'] == 2){
						$mcnt ++;
					}
					if($_POST['csds_minus_AntiBot'] == 2){
						$mcnt ++;
					}
					if($_POST['csds_add_AntiBot'] == 2){
						$mcnt ++;
					}
					
					if($mcnt != 4){
						$update['division_anti_spam'] = esc_attr(stripslashes($_POST['csds_div_AntiBot']));
						$update['multiply_anti_spam'] = esc_attr(stripslashes($_POST['csds_multiply_AntiBot']));
						$update['minus_anti_spam'] = esc_attr(stripslashes($_POST['csds_minus_AntiBot']));
						$update['addition_anti_spam'] = esc_attr(stripslashes($_POST['csds_add_AntiBot']));
					}elseif($mcnt == 4){
						$errors = __("You have not selected any operators (+. -, /, *) to use and selected to use the anti-spam math problem! Please try again and select at least one operator of select no to use the anti-spam math problem", 'csds_userRegAide');
					}
				}elseif($_POST['csds_select_AntiBot'] == 2){
					$update['division_anti_spam'] = esc_attr(stripslashes($_POST['csds_div_AntiBot']));
					$update['multiply_anti_spam'] = esc_attr(stripslashes($_POST['csds_multiply_AntiBot']));
					$update['minus_anti_spam'] = esc_attr(stripslashes($_POST['csds_minus_AntiBot']));
					$update['addition_anti_spam'] = esc_attr(stripslashes($_POST['csds_add_AntiBot']));
				}
				
				if(empty($errors) || $errors == ''){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('Anti-Bot-Spammer Math Problem Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
					update_option("csds_userRegAide_Options", $update);
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __($errors, 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
					
				}
			}
		
		// Upates and changes profile page extra fields title 
		}elseif (isset($_POST['update_profile_title'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$ucnt = (int) 0;
				$update = get_option('csds_userRegAide_Options');
				if(!empty($_POST['csds_change_profile_title'])){
					$update['change_profile_title'] = esc_attr(stripslashes($_POST['csds_change_profile_title']));
				}else{
					$ucnt ++;
					$field = ' Change Profile Plugin Title Option ';
				}
				if(!empty($_POST['csds_profile_title'])){
					$update['profile_title'] = esc_attr(stripslashes($_POST['csds_profile_title']));
				}else{
					$ucnt ++;
					$field = ' Profile Plugin Title Option ';
				}
				
				if($ucnt == 0){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Profile Page Extra Fields Title Updated Successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
					update_option("csds_userRegAide_Options", $update);
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('New Profile Page Extra Fields Title Fields '.$field.' Empty!', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		
		// Support updates
		}elseif (isset($_POST['csds_userRegAide_support_submit'])){ // Handles showing support for plugin
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$update = get_option('csds_userRegAide_Options');
				$update['show_support'] = esc_attr(stripslashes($_POST['csds_userRegAide_support']));
				update_option("csds_userRegAide_Options", $update);
				echo '<div id="message" class="updated fade"><p class="my_message">'. __('Support Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
			}
		}
		// end updates
		
		// Loading options prior to loading registration form options page
		$csds_userRegAide_getOptions = get_option('csds_userRegAide');
		$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
		$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
		$options = get_option('csds_userRegAide_Options');
					
		$cnt1 = count($csds_userRegAide_knownFields);
		if($cnt1 < 8 ){
			$ura_options->csds_userRegAide_fill_known_fields(); // Line 229 user-reg-aide-options.php
		}
		
		// Just checking to make sure options are installed before loading admin page
		if(empty($csds_userRegAide_knownFields)){
			$ura_options->csds_userRegAide_fill_known_fields(); // Line 229 user-reg-aide-options.php
		}
		if(empty($options)){
			$ura_options->csds_userRegAide_DefaultOptions(); // Line 57 user-reg-aide-options.php
		}
		
		// Shows Aministration Page 
		$current_user = wp_get_current_user();
		if(current_user_can('manage_options', $current_user->ID)){	
			$tab = 'registration_form_options';
			$h2 = array( 'adminPage', 'User Registration Aide: Custom Registration Form Options', 'csds_userRegAide' );
			$span = array( 'regForm', 'Add Your Own Message to Bottom of Registration Form:', 'csds_userRegAide' );
			$form = array( 'post', 'csds_userRegAide_regForm' );
			$nonce = array( 'csds-regForm', 'wp_nonce_csds-regForm' );
			do_action( 'start_wrapper',  $tab, $form, $h2, $span, $nonce );
		
			//Form for adding different message to bottom of registration form
			?>
				<table class="regForm" width="100%">
				<tr>
					<td width="60%"><?php _e('Choose to add a special message to bottom of registration form: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to add your own custom message to the bottom of the registration form', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_RegFormMessage" id="csds_select_RegFormMessage" value="1" <?php
					if ($options['select_pass_message'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to use the default Wordpress message on the bottom of the registration form',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_RegFormMessage" id="csds_select_RegFormMessage" value="2" <?php
					if ($options['select_pass_message'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
											
					<td width="40%"><?php _e('Enter new Registration form message below: ', 'csds_userRegAide');?></td></tr>
				<tr>
					<td colspan="2"><textarea name="csds_RegForm_Message" id="csds_RegForm_Message" class="regForm" wrap="soft" rows="1" 
					title="<?php _e('Enter a custom message here for bottom of registration form if users can or even can&#39t create their own password!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['registration_form_message']),'csds_userRegAide'));?></textarea>
					</td>
				</tr>
				
				</table>
				<div class="submit"><input type="submit" class="button-primary" name="reg_form_message_update" value="<?php _e('Update Registration Form Message Options', 'csds_userRegAide'); ?>"  /></div>
			</div>
		</div>
			<?php
			//Form for password strength requirements	
			$i1 = (int) 12; 
			$span = array( 'regForm', 'Password Strength Requirements:', 'csds_userRegAide' );
			do_action( 'start_mini_wrap', $span ); ?>		
				<table class="regForm" width="100%">
				<tr> <?php // Default Password Strength Requirements Options Yes/No ?>
					<td width="50%"><?php _e('Use Default Password Strength Requirements: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to use the default password strength requirements (Upper Case, Lower Case letters, number and special character and minimum length of 8)', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_DefaultPSR" id="csds_select_DefaultPSR" value="1" <?php
					if ($options['default_xwrd_strength'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option not to use the default Password Strength Requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_DefaultPSR" id="csds_select_DefaultPSR" value="2" <?php
					if ($options['default_xwrd_strength'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<?php // Custom Password Strength Requirements Option Yes/No ?>
					<td width="50%"><?php _e('Use Custom Password Strength Requirements: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to create your own custom password strength requirements', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_CustomPSR" id="csds_select_CustomPSR" value="1" <?php
					if ($options['custom_xwrd_strength'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option not to use a custom Password Strength Requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_CustomPSR" id="csds_select_CustomPSR" value="2" <?php
					if ($options['custom_xwrd_strength'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirement Password Length ?>
					<td width="50%"><?php _e('Require Minimum Password Length: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to require an Upper Case Letter in the custom Password Strength Requirements', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_MinXwrdLngth" id="csds_select_MinXwrdLngth" value="1" <?php
					if ($options['require_xwrd_length'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option NOT to require an Upper Case Letter in the custom Password Strength Requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_MinXwrdLngth" id="csds_select_MinXwrdLngth" value="2" <?php
					if ($options['require_xwrd_length'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td width="50%"><?php _e('Password Minimum Length: ', 'csds_userRegAide');?><select <?php // class="fieldOrder" ?> name="csds_xwrdLength" id="csds_xwrdLength" title="<?php __('Select the minimum length for the password', 'csds_userRegAide');?>">
						<?php
						for($ii = 1; $ii <= $i1; $ii++){
							if($ii == $options['xwrd_length']){
								//echo '<option selected="'.$fieldKey.'" >'.$fieldOrder.'</option>';
								echo '<option selected="'.$ii.'" >'.$ii.'</option>';
							}else{
								echo '<option value="'.$ii.'">'.$ii.'</option>';
							}									
						} ?>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirement Upper Case Letter ?>
					<td width="50%"><?php _e('Require Upper Case Letter: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to require an Upper Case Letter in the custom Password Strength Requirements', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_UCPSR" id="csds_select_UCPSR" value="1" <?php
					if ($options['xwrd_uc'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option NOT to require an Upper Case Letter in the custom Password Strength Requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_UCPSR" id="csds_select_UCPSR" value="2" <?php
					if ($options['xwrd_uc'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<?php // Custom Password Strength Requirement Lower Case Letter ?>
					<td width="50%"><?php _e('Require Lower Case Letter: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to require a Lower Case Letter in the password strength requirements', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_LCPSR" id="csds_select_LCPSR" value="1" <?php
					if ($options['xwrd_lc'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option NOT to require a Lower Case Letter in the password strength requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_LCPSR" id="csds_select_LCPSR" value="2" <?php
					if ($options['xwrd_lc'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirement Number ?>
					<td width="50%"><?php _e('Require Number: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to require a Number in the custom Password Strength Requirements', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_NumbPSR" id="csds_select_NumbPSR" value="1" <?php
					if ($options['xwrd_numb'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option NOT to require a Number in the custom Password Strength Requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_NumbPSR" id="csds_select_NumbPSR" value="2" <?php
					if ($options['xwrd_numb'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<?php // Custom Password Strength Requirement Special Character ?>
					<td width="50%"><?php _e('Require Special Character: ', 'csds_userRegAide');?>
					<span title="<?php _e('Select this option to require a Special Character','csds_userRegAide');?> (!,@,#,$,%,^,&,*,?,_,~,-,£,(,))<?php _e(' in the password strength requirements', 'csds_userRegAide');?>">
					<input type="radio" name="csds_select_SCPSR" id="csds_select_SCPSR" value="1" <?php
					if ($options['xwrd_sc'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to NOT require an Lower Case Letter in the password strength requirements',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_SCPSR" id="csds_select_SCPSR" value="2" <?php
					if ($options['xwrd_sc'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
				</tr>
				
				</table>
				<div class="submit"><input type="submit" class="button-primary" name="psr_update" value="<?php _e('Update Password Strength Requirement Options', 'csds_userRegAide'); ?>"  />
				</div>
			<?php
			do_action( 'end_mini_wrap' );
			// end new password strength form 
			$reg_redirect_url = (string) '';
			$login_redirect_url = (string) '';
			//Form for adding redirects to registration and login pages 
			if(!empty($options['registration_redirect_url'])){
				$reg_redirect_url = $options['registration_redirect_url'];
			}else{
				$reg_redirect_url = home_url('/wp-login.php?checkemail=registered');
			}
			if(!empty($options['login_redirect_url'])){
				$login_redirect_url = $options['login_redirect_url'];
			}else{
				$login_redirect_url = home_url('/wp-admin/');
			}
			$span = array( 'regForm', 'Add custom redirects after users login or a new user registers here:', 'csds_userRegAide' );
			do_action( 'start_mini_wrap', $span ); ?>	
				<table class="regForm" width="100%">
					<tr>
						<td width="60%"><?php _e('Choose to redirect users to a different page than default WordPress page after successful registration: ', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to redirect new user to custom page after successsful new registration signup',  'csds_userRegAide');?>">
							<input type="radio" name="csds_registration_redirect_option" id="csds_registration_redirect_option" value="1" <?php
							if ($options['redirect_registration'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option to redirect new user to default Wordpress page after successsful new user registration signup',  'csds_userRegAide');?>">
							<input type="radio" name="csds_registration_redirect_option" id="csds_registration_redirect_option" value="2" <?php
							if ($options['redirect_registration'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
												
						<td width="40%"><?php _e('Enter new successfull Registration form redirect url below: ', 'csds_userRegAide');?></td></tr>
					<tr>
						<td colspan="2"><input type="text" name="csds_registration_redirect_url" id="csds_registration_redirect_url" class="regFormRedirect" width="75%" title="<?php _e('Enter a new url here to redirect users to after a successful registration has been completed!', 'csds_userRegAide');?>" value="<?php _e(esc_url($reg_redirect_url),'csds_userRegAide');?>" />
						</td>
					</tr>
					<tr>
						<td width="60%"><?php  _e('Choose to redirect users to a different page than default WordPress page after successful login: ', 'csds_userRegAide');?>
						<span title="<?php _e('Select this option to redirect new user to custom page after successsful login',  'csds_userRegAide');?>">
						<input type="radio" name="csds_login_redirect_option" id="csds_login_redirect_option" value="1" <?php
						if ($options['redirect_login'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option to redirect new user to default Wordpress page after successsful login',  'csds_userRegAide');?>">
						<input type="radio" name="csds_login_redirect_option" id="csds_login_redirect_option" value="2" <?php
						if ($options['redirect_login'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></td></span>
												
						<td width="40%"><?php _e('Enter new successful login redirect url below: ', 'csds_userRegAide');?></td></tr>
					<tr>
						<td colspan="2"><input type="text" name="csds_login_redirect_url" id="csds_login_redirect_url" class="regFormRedirect width="75%" title="<?php _e('Enter a new url here to redirect users to after a successful login has been completed!', 'csds_userRegAide');?>" value="<?php _e(esc_url($login_redirect_url),'csds_userRegAide');?>" />
						</td>
					</tr>
				</table>
				<div class="submit"><input type="submit" class="button-primary" name="redirects_update" value="<?php _e('Update Redirects Options', 'csds_userRegAide'); ?>"  /></div>
				<?php
				do_action( 'end_mini_wrap' );
			// Form for adding additional agreement to signup for website 
			$span = array( 'regForm', 'Add Your Own Agreement Message and Policy Link with Confirmation of Agreement to Bottom of Registration Form:', 'csds_userRegAide' );
			do_action( 'start_mini_wrap', $span ); ?>
			<table class="regForm" width="100%">
				<tr>
					<td colspan="3"><?php _e('Choose to add a special message to bottom of registration form for new users requiring them to read and agree to terms and conditions of the website:', 'csds_userRegAide'); ?></td>
				</tr>
				<tr>
					<td width="25%"><?php _e('Show Custom Link for Agreement/Guidelines/Policy Page: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to show link for custom agreement message page on registration page',  'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_agreement_link" name="csds_userRegAide_agreement_link" value="1" <?php
						if ($options['show_custom_agreement_link'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option to NOT show link for custom agreement message page on registration page',  'csds_userRegAide');?>">
						<input type="radio" id="csds_userRegAide_agreement_link" name="csds_userRegAide_agreement_link"  value="2" <?php
						if ($options['show_custom_agreement_link'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td> 
					<td width="25%"><?php _e('Enter a title to display for link to Agreement/Guidelines/Policies URL: ', 'csds_userRegAide');?>
					<input  style="width: 200px;" type="text" title="<?php _e('Enter the title for the URL where your agreement/guidelines/policy page is located.', 'csds_userRegAide');?>" value="<?php _e($options['agreement_title'], 'csds_userRegAide');?>" name="csds_userRegAide_newAgreementTitle" id="csds_userRegAide_newAgreementTitle" /></td>
					<td width="50%"><?php _e('Enter Link to Agreement/Guidelines/Policies URL: ', 'csds_userRegAide');?>
					<input  style="width: 350px;" type="text" title="<?php esc_url(_e('Enter the URL where your agreement/guidelines/policy page is located . Example: (http://mysite.com/agreement.php)', 'csds_userRegAide'));?>" value="<?php _e($options['agreement_link'], 'csds_userRegAide');?>" name="csds_userRegAide_newAgreementURL" id="csds_userRegAide_newAgreementURL" /></td></tr>
				<tr>
					<td width="25%"><?php _e('Show Message Confirming Agreement for Agreement/Guidelines/Policy Page: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to show custom agreement message on registration page',  'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_show_agreement_message" name="csds_userRegAide_show_agreement_message" value="1" <?php
					if ($options['show_custom_agreement_message'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option NOT to show custom agreement message on registration page',  'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_show_agreement_message" name="csds_userRegAide_show_agreement_message"  value="2" <?php
					if ($options['show_custom_agreement_message'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td width="25%"><?php _e('Show Checkbox Confirming Agreement for Agreement/Guidelines/Policy Page: ', 'csds_userRegAide');?><br/>
					<span title="<?php _e('Select this option to show checkbox for user to check stating they agree to the agreement terms and conditions',  'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_agreement_checkbox" name="csds_userRegAide_agreement_checkbox" value="1" <?php
						if ($options['show_custom_agreement_checkbox'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide'); ?></span>
					<span title="<?php _e('Select this option NOT to show checkbox for user to check stating they agree to the agreement terms and conditions',  'csds_userRegAide');?>">
					<input type="radio" id="csds_userRegAide_agreement_checkbox" name="csds_userRegAide_agreement_checkbox"  value="2" <?php
						if ($options['show_custom_agreement_checkbox'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<td width="50%"><?php _e('Add your special message below to add to bottom of registration form if new users must agree to terms or policies:', 'csds_userRegAide');?></td>
				<tr>
					<td colspan="3"><textarea name="csds_RegForm_Agreement_Message" id="csds_RegForm_Agreement_Message" class="regForm" rows="1" 
					title="<?php _e('Enter a custom message here for bottom of registration form if users can create their own password or for other reasons!', 'csds_userRegAide');?>"><?php _e(esc_textarea(stripslashes($options['agreement_message']),'csds_userRegAide'))?></textarea>
					</td>
				</tr>
			</table>
				<div class="submit"><input type="submit" class="button-primary" name="reg_form_agreement_message_update" value="<?php _e('Update Registration Form Agreement Options', 'csds_userRegAide');?>" />
				</div>
			<?php
			do_action( 'end_mini_wrap' );
			//Form for adding security math problem to registration form
			$span = array( 'regForm', 'Add Anti-Bot-Spammer for Registration Form:', 'csds_userRegAide' );
			do_action( 'start_mini_wrap', $span ); ?>
				<table class="regForm" width="100%">
					<tr>
					<td>
					<?php _e('Choose to add a anti-spammer anti-bot math problem to the registration form to help reduce spammers and bots accessing your site and spamming it: ', 'csds_userRegAide');?>
					<br/>
					<span title="<?php _e('Select this option to activate the anti-spam math problem to registration form',  'csds_userRegAide');?>">
					<input type="radio" name="csds_select_AntiBot" id="csds_select_AntiBot" value="1" <?php
						if ($options['activate_anti_spam'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to de-activate the anti-spam math problem to registration form',  'csds_userRegAide');?>">
						<input type="radio" name="csds_select_AntiBot" id="csds_select_AntiBot" value="2" <?php
						if ($options['activate_anti_spam'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
					</td>
					<?php // use division option in the anti-spam math problem ?>
					<td>
						<?php _e('Choose this option to use division in the anti-spam math problem:', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to use division in the anti-spam math problem on the registration form',  'csds_userRegAide');?>">
					<input type="radio" name="csds_div_AntiBot" id="csds_div_AntiBot" value="1" <?php
						if ($options['division_anti_spam'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to de-activate the anti-spam math problem to registration form',  'csds_userRegAide');?>">
						<input type="radio" name="csds_div_AntiBot" id="csds_div_AntiBot" value="2" <?php
						if ($options['division_anti_spam'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
					</td>
					<?php // use multiplication option in the anti-spam math problem ?>
					<td>
						<?php _e('Choose this option to use multiplication in the anti-spam math problem:', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to use multiplication in the anti-spam math problem on the registration form',  'csds_userRegAide');?>">
					<input type="radio" name="csds_multiply_AntiBot" id="csds_multiply_AntiBot" value="1" <?php
						if ($options['multiply_anti_spam'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to de-activate the anti-spam math problem to registration form',  'csds_userRegAide');?>">
						<input type="radio" name="csds_multiply_AntiBot" id="csds_multiply_AntiBot" value="2" <?php
						if ($options['multiply_anti_spam'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
					</td>
					<?php // use subtraction option in the anti-spam math problem ?>
					<td>
						<?php _e('Choose this option to use subtraction in the anti-spam math problem:', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to use subtraction in the anti-spam math problem on the registration form',  'csds_userRegAide');?>">
					<input type="radio" name="csds_minus_AntiBot" id="csds_minus_AntiBot" value="1" <?php
						if ($options['minus_anti_spam'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to de-activate the anti-spam math problem to registration form',  'csds_userRegAide');?>">
						<input type="radio" name="csds_minus_AntiBot" id="csds_minus_AntiBot" value="2" <?php
						if ($options['minus_anti_spam'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
					</td>
					<?php // use addition option in the anti-spam math problem ?>
					<td>
						<?php _e('Choose this option to use addition in the anti-spam math problem:', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to use addition in the anti-spam math problem on the registration form',  'csds_userRegAide');?>">
					<input type="radio" name="csds_add_AntiBot" id="csds_add_AntiBot" value="1" <?php
						if ($options['addition_anti_spam'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
					<span title="<?php _e('Select this option to de-activate the anti-spam math problem to registration form',  'csds_userRegAide');?>">
						<input type="radio" name="csds_add_AntiBot" id="csds_add_AntiBot" value="2" <?php
						if ($options['addition_anti_spam'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
					</td>
					</tr>
				</table>
				<div class="submit"><input type="submit" class="button-primary" name="anti-bot-spammer" value="<?php _e('Update Anti-Bot-Spammer Math Problem Options', 'csds_userRegAide'); ?>"  />
				</div>
				<?php do_action( 'end_mini_wrap' ); 
			//Form for changing profile extra fields title 
			$span = array( 'regForm', 'Change Title For User Profiles Page:', 'csds_userRegAide' );
			do_action( 'start_mini_wrap', $span ); ?>
			<table class="regForm" width="100%">
				<tr>
				<td width="40%"><?php _e('Choose to change the title for extra fields on the users profile page: ', 'csds_userRegAide'); ?>
				<br />
				<span title="<?php _e('Select this option to add your own special title to the extra fields portion of the users profile',  'csds_userRegAide');?>">
				<input type="radio" name="csds_change_profile_title" id="csds_change_profile_title" value="1" <?php
				if ($options['change_profile_title'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
				<span title="<?php _e('Select this option to keep the default title to the extra fields portion of the users profile',  'csds_userRegAide');?>">
				<input type="radio" name="csds_change_profile_title" id="csds_change_profile_title" value="2" <?php
				if ($options['change_profile_title'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span></td>
				<td width=60%><?php _e('Extra Fields Title: ', 'csds_userRegAide');?>
				<br />
				<input style="width: 90%;" type="text" title="<?php _e(esc_attr('Enter the new title that you would like to have for the extra fields portion on the users profile page here:'), 'csds_userRegAide');?>" value="<?php _e(esc_attr($options['profile_title']), 'csds_userRegAide');?>" name="csds_profile_title" id="csds_profile_title" /></td>
			</tr>
		</table>
				<div class="submit"><input type="submit" class="button-primary" name="update_profile_title" value="<?php _e('Update Profile Title Options', 'csds_userRegAide'); ?>"  />
				</div>
				<?php
				do_action('end_wrapper'); // adds all closing tags for page wrappers
		}else{
			wp_die(__('You do not have permissions to manage options for this plugin, sorry, please check with the site administrators to resolve this issue please!'));
		}
	}
}
?>