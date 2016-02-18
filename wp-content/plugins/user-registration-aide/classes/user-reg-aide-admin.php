<?php

/**
 * User Registration Aide - Plugin Main Administration Page
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/*
 * Couple of includes for functionality
 *
 * @since 1.2.0
 * @updated 1.5.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once (URA_PLUGIN_PATH."user-registration-aide.php");
require_once ("user-reg-aide-options.php");
require_once ("user-reg-aide-newFields.php");
require_once ("user-reg-aide-regForm.php");
require_once ("user-reg-aide-dashWidget.php");
//require_once ("user-reg-aide-mailer.php");

// -------------------------------------------------------------------------------------

/**
 * Class to improve functionality and privacy and security
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.3.6
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class CSDS_URA_ADMIN_SETTINGS
{

	
	public static $instance;
	
	public function __construct() {
		$this->CSDS_URA_ADMIN_SETTINGS();
	}
	
	function CSDS_URA_ADMIN_SETTINGS(){
		
		self::$instance = $this;
		
	}
	
	/**
	 * Loads and displays the User Registration Aide main administration page
	 * @since 1.0.0
	 * @updated 1.5.0.0
	 * @handles action 'add_menu_page' line 651 user-registration-aide.php
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function csds_userRegAide_myOptionsSubpanel(){

		global $current_user;
		$ura_options = new URA_OPTIONS(); 
		$options = get_option('csds_userRegAide_Options');
		$current_user = wp_get_current_user();
		
		// Checking to see that database options are up to date to the latest version
		
		if($options['csds_userRegAide_db_Version'] != "1.5.0.0"){
			
				$ura_options->csds_userRegAide_updateOptions();
			
		}
			
		// Checking to see that registration fields isn't empty
		
		if(empty($registratrionFields)){
			
				$ura_options->csds_userRegAide_updateRegistrationFields();
			
		}
				
		// Declaring - Defining Variables
		$all_fields = array();
		$regFields = array();
		$seperator = (string) '';
		$csds_userRegAide_newFieldKey = (string) '';
		$csds_userRegAide_newField = (string) '';
		$csds_userRegMod_fields_missing = (string) '';
		$csds_userRegMod_fields_missing1 = (string) '';
		$csds_userRegMod_fields_missing2 = (string) '';
		$key = (string) '';
		$key1 = (string) '';
		$key2 = (string) '';
		$value = (string) '';
		$value1 = (string) '';
		$value2 = (string) '';
		$cnt = (int) '';
		$cnt1 = (int) 0;
		$field = (string) '';
		$new_field = (string) '';
		$results2 = array();
		$show_logo = (string) '';
		$change_logo_link = (string) '';
		$csds_userRegAide_newLogoURL = (string) '';
		$show_background_image = (string) '';
		$csds_userRegAide_newBackgroundImageURL = (string) '';
		$show_background_color = (string) '';
		$csds_userRegAide_newBackgroundColor = (string) '';
		$dashWidget = new URA_DASHBOARD_WIDGET;
		
		
		// Updating Arrays from options db
		
		$options = get_option('csds_userRegAide_Options');
		$csds_userRegAideFields = get_option('csds_userRegAideFields');
		$registratrionFields = get_option('csds_userRegAide_registrationFields');
		$knownFields = get_option('csds_userRegAide_knownFields');
		$knownFields_count = count($knownFields);
		$newFields = get_option('csds_userRegAide_NewFields');
		$fieldOrder = get_option('csds_userRegAide_fieldOrder');
						
			// Handles adding fields to registration form
		// checking nonce
		
		if( isset( $_POST['dash_widget_display_option'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				do_action('update_dw_display_options'); // handles updates to dashboard widget options line 244 user-registration-aide.php
			}
		}elseif( isset( $_POST['dash_widget_fields_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				do_action('update_dw_field_options'); // handles updates to dashboard widget options line 244 user-registration-aide.php
			}
		}elseif( isset( $_POST['dash_widget_field_order_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){				
				do_action('update_dw_field_order'); // handles updates to dashboard widget options line 244 user-registration-aide.php
			}
		}elseif( isset( $_POST['selectNone'] ) ){ // unselect all fields for registration form
			$registratrionFields = array();
			update_option( "csds_userRegAide_registrationFields", $registratrionFields );
			$optional_fields = array();
			update_option( 'csds_ura_optionalFields', $optional_fields );
		}elseif( isset( $_POST['reg_fields_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				$options = array();
				$options = get_option('csds_userRegAide_Options');
				$knownFields = get_option('csds_userRegAide_knownFields');
				$newFields = get_option('csds_userRegAide_NewFields');
				$registratrionFields = array();
				
				$xwrd = 2;
				if( current_user_can( 'activate_plugins', $current_user->ID ) ){
					if( !empty( $_POST['additionalFields'] ) ){
						$results2 =  $_POST['additionalFields'];
							if( !empty( $results2 ) ){
								foreach( $results2 as $key => $value ){
									if( !empty( $knownFields ) ){
										foreach( $knownFields as $key1 => $value1 ){
											if( $value == $key1 ){
												$registratrionFields[$key1] = $value1;
												if( $key1 != 'user_pass' ){
													$xwrd = 0;
													$options['password'] = $xwrd;
													$options['user_password'] = $xwrd;
													update_option("csds_userRegAide_Options", $options);
												}elseif( $key1 == 'user_pass' ){
													$xwrd = 1;
													$options['password'] = $xwrd;
													$options['user_password'] = $xwrd;
													update_option( "csds_userRegAide_Options", $options );
												}
												$registratrionFields = $registratrionFields;
												update_option( "csds_userRegAide_registrationFields", $registratrionFields );
											}	
										}
									}
									if( !empty( $newFields ) ){
										foreach( $newFields as $key2 => $value2 ){
											if( $value == $key2 ){
												$registratrionFields[$key2] = $value2;
												$registratrionFields = $registratrionFields;
												update_option( "csds_userRegAide_registrationFields", $registratrionFields );
											}		
										}
									}
								}
							}else{
								$selected = '';
								$registratrionFields = array();
								update_option( "csds_userRegAide_registrationFields", $registratrionFields );
							}
					}else{
						$selected = '';
						$registratrionFields = array();
						update_option( "csds_userRegAide_registrationFields", $registratrionFields );
					}
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('Registration Form Add New Field Options Updated Successfully!', 'csds_userRegAide') .'</p></div>';					//Report to the user that the data has been updated successfully
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
					exit();
				}
			}
		}elseif( isset($_POST['selectNoOptions'] ) ){
			$optional_fields = array();
			update_option( 'csds_ura_optionalFields', $optional_fields );
		}elseif( isset($_POST['reg_fields_optional_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				$options = array();
				$options = get_option('csds_userRegAide_Options');
				
				$registrationFields = get_option('csds_userRegAide_registrationFields');
				$optional_fields = array();
				$xwrd = 2;
				$msg = ( string ) '';
				$class = ( string ) '';
				if( current_user_can( 'activate_plugins', $current_user->ID ) ){
					if( !empty( $_POST['additionalFieldsOptional'] ) ){
						$results2 =  $_POST['additionalFieldsOptional'];
						if( $results2 != 'no_registration_fields' ){
							if( !empty( $results2 ) ){
								foreach( $results2 as $key => $value ){
									if( !empty( $registrationFields ) ){
										foreach( $registrationFields as $key1 => $value1 ){
											if( $value == $key1 ){
												if( $key1 != 'user_pass' ){
													$xwrd = 0;
													$options['password'] = $xwrd;
													$options['user_password'] = $xwrd;
													update_option("csds_userRegAide_Options", $options);
													$optional_fields[$key1] = $value1;
												}elseif( $key1 == 'user_pass' ){
													$xwrd = 1;
													$options['password'] = $xwrd;
													$options['user_password'] = $xwrd;
													update_option( "csds_userRegAide_Options", $options );
												}
												
											}	
										}
										$optional_fields = $optional_fields;
										update_option( "csds_ura_optionalFields", $optional_fields );
										$msg = 'Registration Form Optional Fields Successfully Updated!';
										$class = 'updated fade';
									}else{
										$msg = 'No Registration Form Fields Exist, Select Some Registration Form Fields First!';
										$class = 'updated fade';
									}
									
								}
							}else{
								$msg = __( 'No Optional Registration Form Fields Selected, Cannot Add Optional Field!', 'csds_userRegAide' );
								$class = 'error';
								$selected = '';
								$optional_fields = array();
								update_option( "csds_ura_optionalFields", $optional_fields );
							}
						}else{
							$msg = __( 'No Registration Form Fields Selected, Cannot Add Optional Field!', 'csds_userRegAide' );
							$class = 'error';
							$selected = '';
							$optional_fields = array();
							update_option( "csds_ura_optionalFields", $optional_fields );
						}
					}else{
						$msg = __( 'No Optional Registration Form Fields Selected, Cannot Add Optional Field!', 'csds_userRegAide' );
						$class = 'error';
						$selected = '';
						$optional_fields = array();
						update_option( "csds_ura_optionalFields", $optional_fields );
					}
					echo '<div id="message" class="'.$class.'"><p class="my_message">'. __( $msg, 'csds_userRegAide' ) .'</p></div>';					//Report to the user that the data has been updated successfully
				}else{
					echo '<div id="message" class="error"><p class="my_message">'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
					exit();
				}
			}
		// handles required fields asterisk update
		}elseif( isset( $_POST['update-asterisk'] ) ){ 
			if( isset( $_POST['use_asterisk'] ) ){
				$options['designate_required_fields'] = $_POST['use_asterisk'];
			}
			update_option( "csds_userRegAide_Options", $options );
			echo '<div id="message" class="updated fade"><p class="my_message">'. __( 'Asterisk Options Updated Successfully!', 'csds_userRegAide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
		// Handles adding new fields to database for user profiles and registration
		
		}elseif( isset( $_POST['new_fields_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				// Updating Arrays from options db
			
				$options = get_option('csds_userRegAide_Options');
				$csds_userRegAideFields = get_option('csds_userRegAideFields');
				$registratrionFields = get_option('csds_userRegAide_registrationFields');
				$knownFields = get_option('csds_userRegAide_knownFields');
				$knownFields_count = count($knownFields);
				$newFields = get_option('csds_userRegAide_NewFields');
				$fieldOrder = get_option('csds_userRegAide_fieldOrder');
				$field_missing = '';
				$number_fields = (int) 0;
				$duplicate = (int) 0;
			
			// Checking for blank fields
				if( $_POST['csds_userRegAide_newFieldKey'] == '' && $_POST['csds_userRegAide_newField'] == ''){
					$seperator = " & ";
					$csds_userRegMod_fields_missing = ' New Field Key ' . $seperator . ' New Field Name ';
					$number_fields = 2;
					
				}elseif( $_POST['csds_userRegAide_newFieldKey'] == '' ) {
					$csds_userRegMod_fields_missing1 = " New Field Key ";
					$csds_userRegAide_newField = esc_attr(stripslashes(trim($_POST['csds_userRegAide_newField'])));
					$csds_userRegMod_fields_missing = $csds_userRegMod_fields_missing1;
					$field_missing = esc_attr(stripslashes($_POST['csds_userRegAide_newField']));
					$duplicate = $this->duplicate_fields($field_missing);
					$number_fields = 1;
				}elseif( $_POST['csds_userRegAide_newField'] == '' ){
					$csds_userRegMod_fields_missing2 = " New Field Name ";
					$csds_userRegAide_newFieldKey = esc_attr(stripslashes(trim($_POST['csds_userRegAide_newFieldKey'])));
					$csds_userRegMod_fields_missing = $csds_userRegMod_fields_missing2;
					$field_missing = esc_attr( stripslashes( $_POST['csds_userRegAide_newFieldKey'] ) );
					$duplicate = $this->duplicate_fields( $field_missing );
					$number_fields = 1;
				}else{
					$csds_userRegMod_fields_missing = '';
				}
				if( $csds_userRegMod_fields_missing != '' && $number_fields == 2 && $duplicate == 0 ){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('***Add New Field Options not updated successfully. Missing both fields for: '.$csds_userRegMod_fields_missing.'***', 'csds_userRegAide') .'</p></div>';
				}elseif( $csds_userRegMod_fields_missing != '' && $number_fields == 1  && $duplicate == 0 ){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('***Add New Field Options not updated successfully. Missing following field for: '.$field_missing.': '.$csds_userRegMod_fields_missing.'***', 'csds_userRegAide') .'</p></div>';
				}elseif( $csds_userRegMod_fields_missing != '' && $number_fields == 1  && $duplicate == 1 ){
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('***Add New Field Options not updated successfully. Following field already exists: '.$field_missing.' and you are missing the following field: '.$csds_userRegMod_fields_missing.'***', 'csds_userRegAide') .'</p></div>';
				}else{
					$results = esc_attr( stripslashes( $_POST['csds_userRegAide_newFieldKey'] ) );
					$new_field = esc_attr( stripslashes( $_POST['csds_userRegAide_newField'] ) );
					
					// Checking for duplicate field in new fields
				
					if( !empty( $newFields ) ){
						foreach( $newFields as $key => $field ){
							if( $key != $results && $field != $new_field ){
								$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('Add New Field '.$new_field.' option updated successfully!', 'csds_userRegAide').' </p></div>';
							}elseif( $key == $results ){
								$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('***Cannot add duplicate fields, '. $results . ' is already included in the extra fields!***', 'csds_userRegAide') .'</p></div>';
								break;
								
							}elseif( $field == $new_field ){
								$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('***Cannot add duplicate fields, '. $new_field . ' is already included in the extra fields!***', 'csds_userRegAide') .'</p></div>';
								break;
							}else{
							 // do nothing yet
							}
						}	
					}else{
					
						// Checking for duplicate fields in known fields
					
						foreach( $knownFields as $key => $field ){
							if( $key != $results && $field != $new_field ){
								$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('Add New Field '.$new_field.' option updated successfully!', 'csds_userRegAide').'</p></div>';
							}elseif( $key == $results ){
								$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('***Cannot add duplicate fields, '. $results . ' is already included in the extra fields!***', 'csds_userRegAide') .'</p></div>';
								break;
							}elseif( $field == $new_field ){
								$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('***Cannot add duplicate fields, '. $new_field . ' is already included in the extra fields!***', 'csds_userRegAide') .'</p></div>';
								break;
							}else{
							
							}
						}
					}
					
					// Updating arrays and database options
					
					if( current_user_can( 'activate_plugins', $current_user->ID ) ){
						if( function_exists( 'csds_add_field_to_users_meta' ) ){
							csds_add_field_to_users_meta( $results );
						}
						$all_fields = array();
						$all_fields = get_option( 'csds_userRegAideFields' );
						$newFields[$results] = $new_field;
						$all_fields[$results] = $new_field;
						update_option( 'csds_userRegAide_NewFields', $newFields );
						$newFields = get_option( 'csds_userRegAide_NewFields' );
						$ura_fields = array();
						$ura_fields = $all_fields + $newFields;
						update_option( 'csds_userRegAideFields', $ura_fields );
						$cnt = count( $newFields );
						$fieldOrder[$results] = $cnt;
						update_option( "csds_userRegAide_fieldOrder", $fieldOrder );
						echo $msg;
					}else{
						echo '<div id="message" class="updated fade"><p class="my_message">'. __( 'You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide' ) .'</p></div>';
						exit();
					}
				}
			}
			
		// Handles showing support for plugin
		
		}elseif( isset( $_POST['csds_userRegAide_support_submit'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				$update = array();
				$update = get_option( 'csds_userRegAide_Options' );
				$update['show_support'] = esc_attr( stripslashes( $_POST['csds_userRegAide_support'] ) );
				update_option("csds_userRegAide_Options", $update);
				echo '<div id="message" class="updated fade"><p class="my_message">'. __('Support Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
			}
		
		}
	

		if(!empty($newFields)){
			if(empty($fieldOrder)){
				$ura_options->csds_userRegAide_update_field_order();
			}
		}
						
		// Making sure all arrays for page are fully loaded from options before page loads
		$csds_userRegAide_getOptions = get_option( 'csds_userRegAide' );
		$registratrionFields = get_option('csds_userRegAide_registrationFields');
		$knownFields = get_option( 'csds_userRegAide_knownFields' );
		$newFields = get_option( 'csds_userRegAide_NewFields' );
		$fieldOrder = get_option( 'csds_userRegAide_fieldOrder' );
		$options = get_option( 'csds_userRegAide_Options' );
				
		// Checks to make sure known fields are correct and up to date
		$cnt1 = count($knownFields);
		if($cnt1 < 8 ){
			$ura_options->csds_userRegAide_fill_known_fields(); // Line 229 user-reg-aide-options.php
		}
		
		// Checking that known fields isn't empty to avoid errors in form loading
		
		if(!empty($knownFields)){
			$ura_options->csds_userRegAide_fill_known_fields(); // Line 229 user-reg-aide-options.php
		}
		
		if( $options['csds_userRegAide_db_Version'] != '1.5.0.9' ){
			//exit( 'UPDATE OPTIONS' );
			do_action( 'update_options' );
		}
					
		// Shows Aministration Page 
		$current_user = wp_get_current_user();
		if(current_user_can('manage_options', $current_user->ID)){	
			$tab = 'registration_fields';
			$h2 = array( 'adminPage', 'User Registration Aide: Force New User Registration Fields Administration Page', 'csds_userRegAide' );
			$span = array( 'regForm', 'Choose to display Creative Software Design Solutions Dashboard Widget Here:', 'csds_userRegAide' );
			$form = array( 'post', 'csds_userRegAide_admin' );
			$nonce = array( 'csds-regFieldsAdmin', 'wp_nonce_csds-regFieldsAdmin' );
			do_action( 'start_wrapper',  $tab, $form, $h2, $span, $nonce );
		
			do_action( 'display_dw_options' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
			?>
			<br />
			<?php
			do_action( 'end_mini_wrap' );
			?>
			
				<?php //Form for selecting fields to add to registration form 
				$span = array( 'regForm', 'Add Existing Fields To Registration Form or Create New Fields Here:', 'csds_userRegAide' );
				do_action( 'start_mini_wrap', $span ); ?>
					
				<table class="adminPage">
					<tr>
						<th class="adminPage"><?php _e( 'Add Additional Fields to Registration Form', 'csds_userRegAide' );?> </th>
						<th class="adminPage"><?php _e( 'Add New Field', 'csds_userRegAide' );?> </th>
					</tr>
					<tr>
						<td width="50%"><?php
						echo '<p class="adminPage">' . __('By default, Wordpress will only require an email address and username to register an account. Here, you can select additional fields required for registration.', 'csds_userRegAide') .'</p>';
						echo '<p class="adminPage"><b>' . __('Note: Any fields selected here will be required fields on the registration form, however you can add fields in the Add New Field options which will be added to the profile but not to the registration form unless you select them here.', 'csds_userRegAide') .'</b></p>';
						echo '<p class="adminPage">'. __('Select Additional Fields to Require for Registration Form:', 'csds_userRegAide') .'</p>';
						echo '<p class="adminPage"><select name="additionalFields[]" id="csds_userRegMod_Select" title="'.__('You can select as many fields here as you want, just hold down the control key while selecting multiple fields. These fields are required on the registration form, so if you can do without them and just have them on the user profile page then leave them out of the registration form!', 'csds_userRegAide').'" size="8" multiple style="height:100px">';
						$registratrionFields = get_option('csds_userRegAide_registrationFields');
						$knownFields = get_option('csds_userRegAide_knownFields');
						$newFields = get_option('csds_userRegAide_NewFields');
						$reqdFields = get_option ('csds_userRegAide_requiredFields');
						$regFields = $registratrionFields;
						if(!empty($registratrionFields)){
							if(is_array($regFields)){
								foreach($knownFields as $key1 => $value1){
									if(!empty($regFields)){
										if(in_array("$value1",$regFields)){
											$selected = "selected=\"selected\"";
										}else{
											$selected = NULL;
										}
									}else{
									$selected = NULL;
									}
									
								echo "<option value=\"$key1\" $selected >$value1</option>";
									
								}
								if(!empty($newFields)){
									foreach($newFields as $key2 => $value2){
										if(!empty($regFields)){
											if(in_array("$value2",$regFields)){
												$selected = "selected=\"selected\"";
											}else{
												$selected = NULL;
											}
										}else{
										$selected = NULL;
										}
										
									echo "<option value=\"$key2\" $selected >$value2</option>";
										
									} // end foreach
								} // end if
								
							}else{
								exit();
							}
						}else{
							foreach($knownFields as $key1 => $value1){
								if(!empty($regFields)){
									if(in_array("$value1",$regFields)){
										$selected = "selected=\"selected\"";
									}else{
										$selected = NULL;
									}
								}else{
									$selected = NULL;
								}
								
								echo "<option value=\"$key1\" $selected >$value1</option>";
								
							}
							
							if(!empty($newFields)){
								foreach($newFields as $key2 => $value2){
									if(!empty($regFields)){
										if(in_array("$value2",$regFields)){
											$selected = "selected=\"selected\"";
										}else{
											$selected = NULL;
										}
									}else{
										$selected = NULL;
									}
									
								echo "<option value=\"$key2\" $selected >$value2</option>";
								
								}
							}
						}
													
						echo '</select></p>';
						echo '<p class="adminPage"><b>'. __( 'Hold down "Ctrl" button on keyboard to select or unselect multiple options!', 'csds_userRegAide' ) .'</b></p>';
					?>
					<input type="submit" name="selectNone" class="button-primary" value="<?php _e('Select None', 'csds_userRegAide'); ?>" onClick="document.getElementById('csds_userRegMod_Select').options.selectedIndex=-1;"/>
					<div class="submit"><input type="submit" class="button-primary" name="reg_fields_update" value="<?php _e('Update Options', 'csds_userRegAide');?>"/></div>
					</td>
					<?php
					// Form for adding new fields for users profile and registration
					?>							
					<td width="50%">
					<p class="adminPage"><?php _e( 'Here is where you can enter your custom additional fields, the key name should be lower case and correlate to the field name that the user sees on the registration form and profile.','csds_userRegAide' ); ?></p>
					<p class="addNewField"><?php _e( 'Examples:','csds_userRegAide' ); ?></p>
					<p class="addNewField"><?php _e( 'Field Key Name: address - Field Name: Address','csds_userRegAide' ); ?></p>
					<p class="addNewField"><?php _e( 'Field Key Name: address_2 - Field Name: Address 2','csds_userRegAide' ); ?> </p>
						<table class="newFields">
							<tr>
								<th><?php _e( 'Field Key Name: ', 'csds_userRegAide' ); ?></th>
								<th><?php _e( 'Field Name: ', 'csds_userRegAide' ); ?></th>
							</tr>
							<tr>
							<?php
							echo '<td><input  style="width: 100%;" type="text" title="'.__( 'Enter the database name for your field here, like dob for Date of Birth or full_name, use lower case letters and _ (underscores) ONLY! Keep it short and simple and relative to the field you are creating!', 'csds_userRegAide' ) . '" value="'. $csds_userRegAide_newFieldKey . '" name="csds_userRegAide_newFieldKey" id="csds_userRegAide_newFieldKey" /></td>';
							echo '<td><input  style="width: 100%;" type="text" title="'.__( 'Enter the user friendly name for your field here, like Date of Birth for dob, ect. Keep it short & simple and relative to the field you are creating!', 'csds_userRegAide' ) . '" value="'. $csds_userRegAide_newField . '" name="csds_userRegAide_newField" id="csds_userRegAide_newField" /></td>';
							?>
							</tr>
						</table>
				<br/>
				<?php
				echo '<input type="submit" class="button-primary" name="new_fields_update" value="'. __( 'Add Field', 'csds_userRegAide' ).'" />';
				?>
						</td>
					
				<?php
					// form for selecting registration form optional fields
					?>
				
					<tr>
						<th colspan="2" class="adminPage"><?php _e( 'Select Fields to make Optional ( Not Required ) for Registration Form', 'csds_userRegAide' );?> </th>
					</tr>
					<tr>
						<td colspan="2"><?php
						echo '<p class="adminPage">' . __( 'Select Fields here that will be optional for the registration form, that is they are not required to be filled in by the user when they register.', 'csds_userRegAide' );
						echo '<br/><b>' . __( 'Note: Any fields selected here will NOT be a required field on the registration form but will be on the registration form as optional', 'csds_userRegAide' ) .'</b>';
						echo '<br/>'. __( 'Select Fields that are optional on Registration Form:', 'csds_userRegAide' );
						echo '<br/><select name="additionalFieldsOptional[]" id="csds_userRegMod_SelectOptions" title="'.__( 'You can select as many fields here as you want, just hold down the control key while selecting multiple fields. These fields will not be required and are optional on the registration form!', 'csds_userRegAide' ).'" size="8" multiple style="height:100px">';
						$registratrionFields = get_option( 'csds_userRegAide_registrationFields' );
						$optional_fields = get_option( 'csds_ura_optionalFields' );
						$knownFields = get_option( 'csds_userRegAide_knownFields' );
						$newFields = get_option( 'csds_userRegAide_NewFields' );
						$reqdFields = get_option ( 'csds_userRegAide_requiredFields' );
						$regFields = $registratrionFields;
						if( !empty( $registratrionFields ) ){
							if( is_array( $regFields ) ){
								foreach( $registratrionFields as $key1 => $value1 ){
									if( $key1 != 'user_pass' ){
										if( !empty( $regFields ) ){
											if( in_array( "$value1", $optional_fields ) ){
												$selected = "selected=\"selected\"";
											}else{
												$selected = NULL;
											}
										}else{
										$selected = NULL;
										}
										
										echo "<option value=\"$key1\" $selected >$value1</option>";
									}
									
								}
								
							}else{
								exit();
							}
						}else{
							echo '<option value="no_registration_fields" >No Fields Selected for Registration Form!</option>';
						}
													
						echo '</select>';
						echo '<br/><b>'. __( 'Hold down "Ctrl" button on keyboard to select or unselect multiple options!', 'csds_userRegAide' ) .'</b>';
					?>
					<br/>
					<input type="submit" name="selectNoOptions" class="button-primary" value="<?php _e( 'Select no Optional Fields', 'csds_userRegAide' ); ?>" onClick="document.getElementById('csds_userRegMod_SelectOptions').options.selectedIndex=-1;"/>
					<?php _e( ' - - - - - ', 'csds_userRegAide' ); ?>
					<input type="submit" class="button-primary" name="reg_fields_optional_update" value="<?php _e('Update Optional Fields', 'csds_userRegAide' );?>"/>
					</p>
					<?php // option to use * asterick for required fields or not
					$options = get_option( 'csds_userRegAide_Options' );
					?>
					<p class="adminPage">
					<?php _e( 'Use the * ( Asterisk ) on the Registration Form to Designate a Field is Required', 'csds_userRegAide' );?>
					<span title="<?php _e( 'Select this option to use the * ( Asterisk ) to Designate a Required Field on the Registration Form', 'csds_userRegAide' );?>">
					<input type="radio" name="use_asterisk" id="use_asterisk" value="1" <?php
					
					if ( $options['designate_required_fields'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'csds_userRegAide' );?></span>
					<span title="<?php _e('Select this option not to use the * ( Asterisk ) to Designate a Required Field on the Registration Form',  'csds_userRegAide' );?>">
					<input type="radio" name="use_asterisk" id="use_asterisk" value="2" <?php
					if ( $options['designate_required_fields'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'csds_userRegAide' ); ?></span>
					<br/>
					<input type="submit" class="button-primary" name="update-asterisk" value="<?php _e( 'Update Asterisk Option', 'csds_userRegAide' );?>"/>
					</p>
					
					</td>
					</tr>
				</table>
			</table>
				<?php
			do_action( 'end_wrapper' );
		}else{
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'csds_userRegAide' ) );
		}
	}
	
	/* Checks new fields for duplicate field entries
	* @handles $this->duplicate_fields line 218 & 225 &$this
	* @since version 1.3.0
	* @param string $field
	* @return $duplicate 0 for no duplicate field - 1 for duplicate field
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function duplicate_fields( $field ){
		$fields = get_option( 'csds_userRegAideFields' );
		$duplicate = ( int ) 0;
		foreach( $fields as $key => $value ){
			if( $field == $key ){
				$duplicate = 1;
				return $duplicate;
				break;
			}elseif( $field == $value ){
				$duplicate = 1;
				return $duplicate;
				break;
			}else{
				$duplicate = 0;
			} // end if
		} // end foreach
		return $duplicate; //returns any duplicates if found
	} // end function
} // end class
?>