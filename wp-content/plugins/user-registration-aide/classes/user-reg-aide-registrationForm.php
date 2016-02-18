<?php
/**
 * User Registration Aide - Registration Form Functions
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.7
 * Since Version 1.3.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/**
 * Class added for better functionality
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.4.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_REGISTRATION_FORM
{

	public static $instance;
	public $numb1;
	public $numb2;
	public $op;
	
	public function __construct() {
		$this->URA_REGISTRATION_FORM();
	}
		
	function URA_REGISTRATION_FORM() { //constructor
	
		global $wp_version, $new_fields, $admin, $admin_page, $ual, $ual_admin_settings; 
		self::$instance = $this;
				
	}
	
	function display_password_fields(){
		
		?>
		<p class="user-pass1-wrap">
			<label for="pass1"><?php _e('Password') ?></label><br />
			<div class="wp-pwd">
				<span class="password-input-wrapper">
					<input type="password" data-reveal="1" data-pw="" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" />
				</span>
				<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php _e( 'Strength Indicator', 'csds_userRegAide' ); ?></div>
			</div>
		</p>
		<?php
		
	}
	
	function display_other_fields( $fieldKey, $fieldName, $value ){
		$label = ( string ) '';
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		$options = get_option('csds_userRegAide_Options');
		if( !empty( $optional_fields ) && is_array( $optional_fields ) ){
			if( array_key_exists( $fieldKey, $optional_fields ) ){
				$label = $fieldName.':';
			}else{
				if ( $options['designate_required_fields'] == 1 ){
					$label = $fieldName.'*:';
				}else{
					$label = $fieldName.':';
				}
			}
		}else{
			if ( $options['designate_required_fields'] == 1 ){
				$label = $fieldName.'*:';
			}else{
				$label = $fieldName.':';
			}
		}
		?>
		<p>
		<label><?php _e( $label, 'csds_userRegAide' ); ?><br />
		<input type="text" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" autocomplete="on" class="input" value="<?php echo $value;?>" size="25" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;" /></label>
		</p>
		<?php
	}
	
	function display_other_fields_theme_my_login( $fieldKey, $fieldName, $value ){
		$label = ( string ) '';
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		$options = get_option('csds_userRegAide_Options');
		if( !empty( $optional_fields ) && is_array( $optional_fields ) ){
			if( array_key_exists( $fieldKey, $optional_fields ) ){
				$label = $fieldName.':';
			}else{
				if ( $options['designate_required_fields'] == 1 ){
					$label = $fieldName.'*:';
				}else{
					$label = $fieldName.':';
				}
			}
		}else{
			if ( $options['designate_required_fields'] == 1 ){
				$label = $fieldName.'*:';
			}else{
				$label = $fieldName.':';
			}
		}
		?>
		<p>
		<label><?php _e( $label, 'csds_userRegAide' ); ?><br />
		<input type="text" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" autocomplete="on" class="input" value="" size="25" /></label>
		</p>
		<?php
	}
	
	function display_text_area_fields( $fieldKey, $fieldName, $value ){
		$label = ( string ) '';
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		$options = get_option('csds_userRegAide_Options');
		if( !empty( $optional_fields ) && is_array( $optional_fields ) ){
			if( array_key_exists( $fieldKey, $optional_fields ) ){
				$label = $fieldName.':';
			}else{
				if ( $options['designate_required_fields'] == 1 ){
					$label = $fieldName.'*:';
				}else{
					$label = $fieldName.':';
				}
			}
		}else{
			if ( $options['designate_required_fields'] == 1 ){
				$label = $fieldName.'*:';
			}else{
				$label = $fieldName.':';
			}
		}
		?>
		<p>
		<label><?php _e( $label, 'csds_userRegAide' ); ?></label>
		<br />
		<textarea name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" class="input" value="<?php echo esc_textarea( $value );?>" rows="5" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;" ></textarea>
		</p>
		<?php
	}
	
	function display_text_area_fields_theme_my_login( $fieldKey, $fieldName, $value ){
		$label = ( string ) '';
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		$options = get_option('csds_userRegAide_Options');
		if( !empty( $optional_fields ) && is_array( $optional_fields ) ){
			if( array_key_exists( $fieldKey, $optional_fields ) ){
				$label = $fieldName.':';
			}else{
				if ( $options['designate_required_fields'] == 1 ){
					$label = $fieldName.'*:';
				}else{
					$label = $fieldName.':';
				}
			}
		}else{
			if ( $options['designate_required_fields'] == 1 ){
				$label = $fieldName.'*:';
			}else{
				$label = $fieldName.':';
			}
		}
		?>
		<p>
		<label><?php _e( $label, 'csds_userRegAide' ); ?></label>
		<br />
		<textarea name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" class="input" value="<?php echo esc_textarea( $value );?>" rows="5" ></textarea>
		</p>
		<?php
	}
	
	/**
	 * Add fields to the new user registration page that the user must fill out when they register
	 * @since 1.0.0
	 * @updated 1.5.0.7
	 * @handles action 'register_form' line 217 user-registration-aide.php
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_addFields(){
		global $numbs, $numb1, $numb2, $op;
		$fieldKey = '';
		$fieldName = '';
		$regFields = array();
		$options = array();
		$regFields = get_option('csds_userRegAide_registrationFields');
		$options = get_option('csds_userRegAide_Options');	
		if( is_array( $regFields ) ){
			if( !empty( $regFields ) ){
				foreach( $regFields as $fieldKey => $fieldName ){
					/*
					if( $fieldKey == 'first_name' ){
						$fieldName = 'YOUR TRANSLATION HERE';
					}elseif( $fieldKey = 'last_name' ){
						$fieldName = 'YOUR TRANSLATION HERE';
					}
					*/
					if( !empty( $_POST[$fieldKey] ) ){
						foreach( $_POST as $id => $value ){
							if( $id == $fieldKey ){
								if( !is_plugin_active('theme-my-login/theme-my-login.php' ) ){ // Compensates for theme my login bug
									if( $fieldKey == 'user_pass' ){ // adding password fields to form
										do_action( 'password_input' );
										
									}elseif( $fieldKey == 'description' ){
										do_action( 'ta_input', $fieldKey, $fieldName, $value );
									}else{
										do_action( 'fields_input', $fieldKey, $fieldName, $value );
									}
								}else{
									if( $fieldKey == 'user_pass' ){
										do_action( 'password_input' );
										
									}elseif( $fieldKey == 'description' ){
										do_action( 'tml_ta_input', $fieldKey, $fieldName, $value );
									}else{
										do_action( 'tml_fields_input', $fieldKey, $fieldName, $value );
									}
								}
							}
						}
					}else{
						$value = ( string ) '';
						if( !is_plugin_active('theme-my-login/theme-my-login.php' ) ){ //compensates for theme my login bug
							if( $fieldKey == 'user_pass' ){
								do_action( 'password_input' );
							}elseif( $fieldKey == 'description' ){
								do_action( 'ta_input', $fieldKey, $fieldName, $value );
							}else{
								do_action( 'fields_input', $fieldKey, $fieldName, $value );
							}
						}else{
							if( $fieldKey == 'user_pass' ){
								do_action( 'password_input' );
								
							}elseif( $fieldKey == 'description' ){
								do_action( 'tml_ta_input', $fieldKey, $fieldName, $value );
							}else{
								do_action( 'tml_fields_input', $fieldKey, $fieldName, $value );
							}
						}
					
					}
				}
			}
		}
		
		if( $options['show_custom_agreement_message'] == "1" ){
			_e( $options['agreement_message'], 'csds_userRegAide' ); 
			echo '<br/>';
		}	
		
		if( $options['show_custom_agreement_checkbox'] == "1" ){
			echo '<br/>';
			$avalue = '';
			 _e( 'I Agree with the Terms and Conditions: ', 'csds_userRegAide' ); 
			 echo '<br/>';
			 echo '<br/>';
			 if( !empty($_POST['csds_userRegAide_agree'] ) ){
				$avalue = $_POST['csds_userRegAide_agree'];
				?><input type="radio" id="csds_userRegAide_agree" name="csds_userRegAide_agree" value="1" <?php
				if ($avalue == 1) echo 'checked' ; ?> /> <?php _e('I Agree', 'csds_userRegAide'); ?>
					<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_agree"  value="2" <?php
				if ($avalue == 2) echo 'checked' ; ?> /> <?php _e('I Do Not Agree', 'csds_userRegAide'); ?>
				<?php
				echo '<br/>';
			 }else{
				?><input type="radio" id="csds_userRegAide_agree" name="csds_userRegAide_agree" value="1" <?php
				if ( $options['new_user_agree'] == 1 ) echo 'checked' ; ?> /> <?php _e( 'I Agree', 'csds_userRegAide' ); ?>
					<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_agree"  value="2" <?php
				if ( $options['new_user_agree'] == 2 ) echo 'checked' ; ?> /> <?php _e( 'I Do Not Agree', 'csds_userRegAide' ); ?>
				<?php
				echo '<br/>';
			}
		}
		if( $options['show_custom_agreement_link'] == "1" ){
			echo '<br />';
			echo '<a href="'.esc_url( $options['agreement_link'] ).'" target="_blank">'.$options['agreement_title'].'</a>';
			echo '<br />';
		}
				
		// For anti-spammer attempts to prevent bots from registering
		if( $options['activate_anti_spam'] == "1" ){
			$answer = $options['math_answer'];
			$math = new URA_MATH_FUNCTIONS();
			$numbs = array();
			$numbs = $math->random_numbers();
			$numb1 = $numbs['first'];
			$numb2 = $numbs['second'];
			$operator = $numbs['operator'];
			$op = $math->get_operator( $operator );
			//exit( 'Numbers: '.$numb1.' - '.$numb2.' - '.$op );
			_e( '*Please complete the following arithmatic problem to prove you are human!*:', 'csds_userRegAide' ); ?>
			<br />
			<br />
			<p style="text-align: center; border-style: solid; border-width: 1px; padding: 3px;">
			<b>
			<?php _e( $numb1, 'csds_userRegAide' ); ?><?php _e( ' '.$op.' ', 'csds_userRegAide' ); ?><?php _e( $numb2.' = ', 'csds_userRegAide' ); ?>
			</b>
			</p>
			<br />
			<?php
			$user_answer = '';
			echo '<input  autocomplete="off" style="width: 100%;" type="text" title="'.__( 'Enter the answer to the artithmatic problem here! ***Important: In certain division problems, the answer may not be a whole number so just round UP to the nearest 10th(Example: 5/3 = 1.6666 rounded to the nearest tenth is 1.6!***', 'csds_userRegAide' ) . '" value="'. $user_answer . '" name="'.$answer.'" />';
			
		}
			
		wp_nonce_field( 'userRegAideRegForm_Nonce', 'userRegAide_RegFormNonce' );
					
		if( $options['select_pass_message'] == "1" ){
			echo '<div style="margin:10px 0;border:1px solid #e5e5e5;padding:10px">';
			echo '<p class="message register" style="margin:5px 0;">';
			_e( $options['registration_form_message'], 'csds_userRegAide' );
			echo '</p>';
			echo '</div>';
			?>
		<style type="text/css">
		#reg_passmail{
			display:none;
		}
		</style>
		<?php
		}
			
		if($options['show_support'] == "1"){
				echo '<a href="'.$options['support_display_link'].'" target="_blank">' . $options['support_display_name'] . '</a><br/>';
				echo '<br/>';
		}
	}
	
	/**
	 * Add the additional metadata into the database after the new user is created from registration form
	 *
	 * @since 1.0.0
	 * @updated 1.3.0
	 * @handles action 'user_register' line 218 user-registration-aide.php (Priority: 1 - Params: 1)
	 * @param int $user_id
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_updateFields($user_id){

		global $regFields, $wpdb, $table_prefix;
		$options = get_option('csds_userRegAide_Options');
		$thisValue = (string) '';
		$fieldName = (string) '';
		$newValue = (string) '';
		$newPass = (string) '';
		$addData = (string) '';
		$newWebsite = (string) '';
		$newCredentials = (string) '';
		$regFields = array();
		$regFields = get_option('csds_userRegAide_registrationFields');
		$ura = new CSDS_USER_REG_AIDE();	
		if( !empty( $regFields ) ){
			if ( wp_verify_nonce( $_POST['userRegAide_RegFormNonce'], 'userRegAideRegForm_Nonce' ) ){
				foreach( $regFields as $thisValue => $fieldName ){
					if( $_POST[$thisValue] != '' ){
						if( $thisValue == "first_name" ){
							$newValue = apply_filters( 'pre_user_first_name', $_POST[$thisValue] );
						}elseif( $thisValue == "last_name" ){
							$newValue = apply_filters( 'pre_user_last_name', $_POST[$thisValue] );
						}elseif( $thisValue == "nickname" ){
							$newValue = apply_filters( 'pre_user_nickname', $_POST[$thisValue] );
						}elseif( $thisValue == "description" ){
							$newValue = apply_filters( 'pre_user_description', $_POST[$thisValue] );
						}elseif( $thisValue == "user_url" ){
							$newWebsite = apply_filters( 'pre_user_url', $_POST[$thisValue] );
							$addData = $wpdb->prepare( "UPDATE $wpdb->users SET user_url =('$newWebsite') WHERE ID = '$user_id'" );
							$wpdb->query( $addData );
						}elseif( $thisValue == "user_pass" ){
							$newPass = $_POST['pass1'];
							$newPass = wp_hash_password( $newPass );
							//$addData = $wpdb->prepare("UPDATE $wpdb->users SET user_pass = md5('$newPass') WHERE ID = $user_id");
							$addData = $wpdb->prepare( "UPDATE $wpdb->users SET user_pass = %s WHERE ID = %d", $newpass, $user_id );
							$wpdb->query( $addData );
							//$ura->remove_default_password_nag($user_id);  // to  remove password nag from new users who fill out own password if this bullsit even works wordpress is sucky this way line 926 &$ura
						}elseif( $thisValue == "security_question"){
							$newValue = apply_filters( 'pre_user_description', $_POST[$thisValue] );
						}elseif( $thisValue == "security_answer"){
							$newValue = apply_filters( 'pre_user_description', $_POST[$thisValue] );
						}else{
							$newValue = apply_filters( 'pre_user_description', $_POST[$thisValue] );
						}
						if( $thisValue != 'user_url' || $thisValue != "user_pass" ){
							update_user_meta( $user_id, $thisValue, $newValue );
						}
						
						if( $options['show_custom_agreement_checkbox'] == "1" ){
							if( $_POST['csds_userRegAide_agree'] == "1" ){
								update_user_meta( $user_id, new_user_agreed, "Yes" );
							}
						}
						// for future use maybe???----------------------
						if( $options['add_security_question'] == "1" ){
							if( $_POST['security_question'] != 'select' ){
								$newValue = apply_filters( 'pre_user_description', $_POST['security_question'] );
								update_user_meta( $user_id, 'security_question', $newValue );
							}
							if( !empty( $_POST['security_answer'] ) ){
								$newValue = apply_filters( 'pre_user_description', $_POST['security_answer'] );
								update_user_meta( $user_id, 'security_answer', $newValue );
							}
						}
						// end security question
						
						// updates custom display name fields as needed
						if( $options['custom_display_name'] == '1' ){
							
							$current_role = get_option( 'default_role' );
							$selRole = $options['display_name_role'];
							$selField = $options['custom_display_field'];
							foreach( $selRole as $role_key => $role_value ){
								if( $role_value == 'all_roles' || $role_value == $current_role ){
									if( $selField == 'first_last_name' ){
										$display_name = $_POST['first_name'].' '.$_POST['last_name'];
										$add_display_name = $wpdb->prepare("UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user_id);
										$wpdb->query( $add_display_name );
									}elseif( $selField == 'last_first_name' ){
										$display_name = $_POST['last_name'].' '.$_POST['first_name'];
										$add_display_name = $wpdb->prepare("UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user_id);
										$wpdb->query( $add_display_name );
									}else{
										$display_name = $_POST[$selField];
										$add_display_name = $wpdb->prepare("UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user_id);
										$wpdb->query( $add_display_name );
									}
								}
							}
							
							$field = $_POST['user_login'];
							if( !empty( $field ) ){
								update_user_meta( $user_id, 'default_display_name', $field );
							}
							
						}
						
					} // end if check for empty post
				} // end foreach
			}else{
				exit( __( 'Failed Security Verification', 'csds_userRegAide' ) );
			}
		} // end if empty registraion form extra fields
	} // end function
	
	/**
	 * Check the new user registration form for errors
	 * @since 1.0.0
	 * @updated 1.3.0
	 * @Filters 'registration_errors' line 219 user-registration-aide.php (Priority: 1 - Params: 3)
	 * @access private
	 * @accepts $errors, $username, $email
	 * @returns $errors array
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_checkFields($errors, $username, $email){
		global $numbs, $numb1, $numb2, $op;
		//exit( 'Numbers: '.$numb1.' - '.$numb2.' - '.$op );
		$error = (int) 0;
		$pwd = '';
		$thisValue = '';
		$fieldName1 = '';
		$csds_userRegAide_registrationFields = array();
		$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
		$options = get_option('csds_userRegAide_Options');
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		if( !empty( $csds_userRegAide_registrationFields ) ){
			foreach( $csds_userRegAide_registrationFields as $thisValue => $fieldName1 ){
				if( $thisValue != "user_pass" ){
					if( !empty( $optional_fields ) && is_array( $optional_fields ) ){
						if( !array_key_exists( $thisValue, $optional_fields ) ){
							if( $_POST[$thisValue] == '' ) {
								$errors->add('empty_'.$thisValue , __("<strong>ERROR</strong>: Please type your ".$csds_userRegAide_registrationFields[$thisValue].".",'csds_userRegAide'));
								$error ++;
							}else{ // checking for duplicate entries to weed out spammers
								foreach($csds_userRegAide_registrationFields as $thisValue1 => $fieldName2){
									if($thisValue1 != "user_pass"){
										if($thisValue != $thisValue1){
											if($_POST[$thisValue] == $_POST[$thisValue1]){
												$errors->add('duplicate_spam_check'.$thisValue , __("<strong>ERROR</strong>: Your ".$csds_userRegAide_registrationFields[$thisValue]." and ".$csds_userRegAide_registrationFields[$thisValue1]." are the same, please enter different values!",'csds_userRegAide'));
											}
										}
									}
								}
							}
						}
					}else{
						if( $_POST[$thisValue] == '' ) {
							$errors->add('empty_'.$thisValue , __("<strong>ERROR</strong>: Please type your ".$csds_userRegAide_registrationFields[$thisValue].".",'csds_userRegAide'));
							$error ++;
						}else{ // checking for duplicate entries to weed out spammers
							foreach($csds_userRegAide_registrationFields as $thisValue1 => $fieldName2){
								if($thisValue1 != "user_pass"){
									if($thisValue != $thisValue1){
										if($_POST[$thisValue] == $_POST[$thisValue1]){
											$errors->add('duplicate_spam_check'.$thisValue , __("<strong>ERROR</strong>: Your ".$csds_userRegAide_registrationFields[$thisValue]." and ".$csds_userRegAide_registrationFields[$thisValue1]." are the same, please enter different values!",'csds_userRegAide'));
										}
									}
								}
							}
						}
					}
				}elseif( $thisValue == "user_pass" ){
					// //to check password -- password fields empty
					if( empty( $_POST['pass1'] ) || $_POST['pass1'] == '' ){
							$errors->add( 'empty_password', __( "<strong>ERROR</strong>: Please enter and confirm your Password!", 'csds_userRegAide' ) );
							$error ++;
							
					}
					/*
					if($_POST['pass1'] != $_POST['pass2']){ // passwords do not match
							$errors->add('password_mismatch', __("<strong>ERROR</strong>: Passwords do not match!", 'csds_userRegAide'));
							$error ++;
					}
					*/
					if( $_POST['pass1'] == $_POST['user_login'] ){ // password same as user login
						$errors->add( 'password_and_login_match', __( "<strong>ERROR</strong>: Username and Password are the same, they must be different!", 'csds_userRegAide' ) );
							$error ++;
					// Password strength requirements 	
					}
					if( strlen( trim( $_POST['pass1'] ) ) < $options['xwrd_length'] ){ // password length too short
						if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['require_xwrd_length'] == 1 ) ){
							$errors->add('password_too_short', __("<strong>ERROR</strong>: Password length too short! Should be at least ".$options['xwrd_length']." characters long!", 'csds_userRegAide'));
								$error ++;
						}
					// no number in password
					}
					if( !empty( $_POST['pass1'] ) && !preg_match("/[0-9]/", $_POST['pass1'] )){
						if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_numb'] == 1)){
							$errors->add('password_missing_number', __("<strong>ERROR</strong>: There is no number in your password!", 'csds_userRegAide'));
								$error ++;
						}
					// no lower case letter in password
					}
					if( !empty( $_POST['pass1'] ) && !preg_match("/[a-z]/", $_POST['pass1'] )){
						if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_lc'] == 1)){
							$errors->add('password_missing_lower_case_letter', __("<strong>ERROR</strong>: Password missing lower case letter!", 'csds_userRegAide'));
								$error ++;
						}
					// no upper case letter in password
					}
					if( !empty( $_POST['pass1'] ) && !preg_match("/[A-Z]/", $_POST['pass1'] )){
						if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_uc'] == 1)){
							$errors->add('password_missing_upper_case_letter', __("<strong>ERROR</strong>: Password missing upper case letter!", 'csds_userRegAide'));
								$error ++;
						}
					// no special character in password
					}
					if(  !empty( $_POST['pass1'] )  && !preg_match("/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/", $_POST['pass1'] ) ){
						if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_sc'] == 1 ) ){
							$errors->add( 'password_missing_symbol', __( "<strong>ERROR</strong>: Password missing symbol!", 'csds_userRegAide' ) );
								$error ++;
						}
					}else{
						//exit('Blow Up');//$_POST['user_pw'] = $_POST['pass1'];
					}
				}
			}
		}
		
		if( $options['show_custom_agreement_checkbox'] == "1" ){
			if( $_POST['csds_userRegAide_agree'] == "2" ){
				$errors->add('agreement_confirmation', __( "<strong>ERROR</strong>: You must agree to the terms and conditions!", 'csds_userRegAide' ) );
				$error ++;
			}elseif( empty( $_POST['csds_userRegAide_agree'] ) ){
				$errors->add( 'agreement_confirmation', __("<strong>ERROR</strong>: You must agree to the terms and conditions!", 'csds_userRegAide' ) );
				$error ++;
			}
		}
		// anti-spam math problem
		if( $options['activate_anti_spam'] == "1" ){
			$options = get_option( 'csds_userRegAide_Options' );
			$num1 = $options['math_num1'];
			$num2 = $options['math_num2'];
			$oper = $options['math_oper'];
			$answer = $options['math_answer'];
			$math = new URA_MATH_FUNCTIONS();
			$operator = $math->get_operator( $oper );
			$math_answer = ( double ) 0;
			/*
			if( $operator == "+" ){
				$math_answer = round( $_POST[$num1] + $_POST[$num2], 1 );
			}elseif( $operator == "-" ){
				$math_answer = round( $_POST[$num1]  - $_POST[$num2], 1 );
			}elseif( $operator == "*" ){
				$math_answer = round( $_POST[$num1]  * $_POST[$num2], 1 );
			}elseif( $operator == "/"){
				$math_answer = round( $_POST[$num1]  / $_POST[$num2], 1 );
			}
			*/
			if( $operator == "+" ){
				$math_answer = round( $num1 + $num2, 1 );
			}elseif( $operator == "-" ){
				$math_answer = round( $num1 - $num2, 1 );
			}elseif( $operator == "*" ){
				$math_answer = round( $num1 * $num2, 1 );
			}elseif( $operator == "/"){
				$math_answer = round( $num1 / $num2, 1 );
			}
			
			if( empty( $_POST[$answer] ) ){
				$errors->add( 'anti_spammer_security', __( "<strong>ERROR</strong>: You must enter the anti-spam math problem answer!", 'csds_userRegAide' ) );
				$error ++;
			}elseif( $_POST[$answer] != $math_answer ){
				$errors->add( 'anti_spammer_security', __( "<strong>ERROR</strong>: Your anti-spam math problem answer is incorrect! Try using your calculator if you are having problems!", 'csds_userRegAide' ) );
				$error ++;
			}
		}
		
		if( empty( $_POST['user_login'] ) ){
			$error ++;
		}
		if( empty( $_POST['user_email'] ) ){
			$error ++;
		}
		$math = new URA_MATH_FUNCTIONS();
		$scramble = $math->scramble_variables();
		return $errors;
	}
	
} // end class
?>