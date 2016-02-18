<?php

/**
 * User Registration Aide - Edit New Fields Administration Page
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/**
 * Class for new field options and editing
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.5.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


class URA_NEW_FIELDS
{

	public static $instance;
	
	public function __construct() {
		$this->URA_NEW_FIELDS();
		
	}

	function URA_NEW_FIELDS(){
		self::$instance = $this;
	}
	
	/**
	 * Loads and displays the User Registration Aide Edit New Fields Administration Page
	 * @handles action 'add_submenu_page csds_userRegAide_editNewFields_optionsPage() line 715 &$ura
	 * @since 1.1.0
	 * @updated 1.5.0.0
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function csds_userRegAide_editNewFields(){

		global $wpdb, $current_user, $count;
		
		$current_user = wp_get_current_user();
		$options = get_option('csds_userRegAide_Options');
		$nFields = get_option('csds_userRegAide_NewFields');
		$delete_error = (int) 0;
		$msg = (string) '';
		$count = (int) 0;
		
		//$ura_options = new URA_OPTIONS();
		if($options['csds_userRegAide_db_Version'] != "1.5.0.0"){
			//$ura_options->csds_userRegAide_updateOptions();
			do_action('update_options'); // Line 259 user-registration-aide.php
		}
				
		$seperator = '';
		$results1 = '';
		$csds_userRegAideFields = get_option('csds_userRegAideFields');
		$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
		$csds_userRegAide_support = get_option('csds_userRegAide_support');
		$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
		$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
		$fieldKey = '';
		$fieldOrder = '';
		$options = get_option('csds_userRegAide_Options');
		$delete = (int) 0;
		// Double checks to see that the new field order is updated
		if(!empty($nFields)){
			//$ura_options->csds_userRegAide_update_field_order();
			do_action('update_field_order'); // Line 257 user-registration-aide.php
		}
		
		
		// Handles the delete field form
		if (isset($_POST['delete_field'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
				$results1 = '';
				if(current_user_can('activate_plugins')){
					
					// Checking for field to delete if empty gives warning and exits
					if(!empty($_POST['deleteNewFields'])){
						$results1 =  $_POST['deleteNewFields'];
						$delete_error = 2;
					}else{
						$delete_error = 1;
					}
					unset($nFields[$results1]);
					unset($csds_userRegAideFields[$results1]);
					unset($csds_userRegAide_fieldOrder[$results1]);
					unset($csds_userRegAide_registrationFields[$results1]);
					$csds_userRegAide_NewFields = $nFields;
					$csds_userRegAideFields = $csds_userRegAideFields;
					$csds_userRegAide_fieldOrder = $csds_userRegAide_fieldOrder;
					$csds_userRegAide_registrationFields = $csds_userRegAide_registrationFields;
					update_option("csds_userRegAide_NewFields", $csds_userRegAide_NewFields);
					update_option("csds_userRegAideFields", $csds_userRegAideFields);
					update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
					update_option("csds_userRegAide_registrationFields", $csds_userRegAide_registrationFields);
					do_action('update_field_order'); // Line 239 user-registration-aide.php
					do_action('delete_usermeta_field', $results1); // Deletes field from user meta line 258 user-registration-aide.php
					
					
					//Report to the user that the data has been updated successfully or that an error has occurred
					if($delete_error == 2){
						$msg = '<div id="message" class="updated fade"><p class="my_message">'. __( $results1.' Successfully Deleted!' , 'csds_userRegAide') .'</p></div>';
					}elseif($delete_error == 1){
						$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('No field was selected for deletion, you must select a field to delete first!' , 'csds_userRegAide') .'</p></div>';
					}
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
				}
			}
		
		// Handles the new fields order form
		}elseif (isset($_POST['field_order'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
				$key = '';
				$key1 = '';
				$key2 = '';
				$value = '';
				$value1 = '';
				$value2 = '';
				$results = '';
				$aa = '';
				$field_order_error = (int) 0;
				$newFields = get_option('csds_userRegAide_NewFields');		
							
				if(current_user_can('activate_plugins')){
					
					// Getting values from new field order select options
					$results = $_POST['csds_editFieldOrder'];
					$aa = (int) 0;
						
					foreach($csds_userRegAide_fieldOrder as $key => $value){
						foreach($results as $key1 => $value1){
							foreach($results as $key2 => $value2){
								if($key1 != $key2 && ($value1 == $value2)){
									$field_order_error = 1;
									break;
								}
							}
							if($field_order_error != 1){
								if($aa == $key1){
									$csds_userRegAide_fieldOrder_temp[$key] = $value1;
								}
							}else{
								$csds_userRegAide_fieldOrder_temp[$key] = $value;
							}
						}
						$aa++;
					}
							
					// Updating New Field Order
					if($field_order_error != 1){	// Checking for errors duplicate fields before updating
						$csds_userRegAide_fieldOrder = $csds_userRegAide_fieldOrder_temp;
						asort($csds_userRegAide_fieldOrder);
						update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
						
						// Updating New Fields to new Order
						foreach($csds_userRegAide_fieldOrder as $key => $order){
							foreach($newFields as $key1 => $name1){
								if($key == $key1){
									$csds_userRegAide_NewFields_temp[$key1] = $name1;
								}
							}
						}
						
						// Updating field order in database
						$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
						$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
						$newFields = $csds_userRegAide_NewFields_temp;
						update_option("csds_userRegAide_NewFields", $newFields);
						$csds_userRegAideFields = array();
						$csds_userRegAideFields = $csds_userRegAide_knownFields + $nFields;
						$regFields_temp = array();
						update_option("csds_userRegAideFields", $csds_userRegAideFields);
						if(!empty($csds_userRegAide_knownFields)){
							foreach($csds_userRegAide_knownFields as $key2 => $value2){
								if(!empty($csds_userRegAide_registrationFields)){
									foreach($csds_userRegAide_registrationFields as $key4 => $value4){
										if($key2 == $key4){
											$regFields_temp[$key2] = $value2;
										}
									}
								}
							}
						}
						if(!empty($newFields)){
							foreach($newFields as $key3 => $value3){
								if(!empty($csds_userRegAide_registrationFields)){
									foreach($csds_userRegAide_registrationFields as $key5 => $value5){
										if($key3 == $key5){
											$regFields_temp[$key3] = $value3;
										}
									}
								}
							}
						}
						update_option('csds_userRegAide_registrationFields', $regFields_temp);
						$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('New Field Order Options updated successfully.', 'csds_userRegAide') .'</p></div>';
					}else{ // Duplicate fields error display message
						$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('***Error Updating New Field Order Options, two or more fields have the same order!***', 'csds_userRegAide') .'</p></div>';
					}
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
					exit();
				}
			}
		}elseif (isset($_POST['edit_field_name'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
				$results = array();
				$changed = (int) 0;
				$empty = (int) 0;
				$emptyFields = array();
				$update = get_option('csds_userRegAide_NewFields');
				$regFields = get_option('csds_userRegAide_registrationFields');
				$fields = get_option('csds_userRegAideFields');
				if(current_user_can('activate_plugins')){
					
					// Checking for field to delete if empty gives warning and exits
					foreach($_POST as $fieldKey => $fieldName){
						foreach($update as $key => $name){
							if($fieldKey == $key && $fieldName != $name){
								if(!empty($fieldName)){
									$update[$fieldKey] = $fieldName;
									$regFields[$fieldKey] = $fieldName;
									$fields[$fieldKey] = $fieldName;
									$results[$fieldKey] = $fieldName;
									$changed ++;
								}else{
									$results[$fieldKey] = $fieldName;
									$empty ++;
									$changed ++;
								}
							}
						}
					}
					
					update_option("csds_userRegAide_NewFields", $update);
					update_option("csds_userRegAide_registrationFields", $regFields);
					update_option("csds_userRegAideFields", $fields);
						
					//Report to the user that the data has been updated successfully or that an error has occurred
						if($changed != 0){
							$msg = '<div id="message" class="updated fade"><p class="my_message">';
							$i = (int) 1;
							foreach( $results as $keys => $values){
								
								if($i < $changed){
									if(!empty($values)){
										$msg .= __( strtoupper($keys).' Name Successfully changed! & ' , 'csds_userRegAide');
									}else{
										$msg .= __( strtoupper($keys).' Name Not Entered & ' , 'csds_userRegAide');
									}
								}else{
									if(!empty($values)){
										$msg .= __( strtoupper($keys).' Name Successfully changed!' , 'csds_userRegAide');
									}else{
										$msg .= __( strtoupper($keys).' Name Not Entered!' , 'csds_userRegAide');
									}
								}
								$i++ ;
							}
							$msg .= '</p></div>';
						}elseif($changed == 0){
							$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('No field was changed!' , 'csds_userRegAide') .'</p></div>';
						}
				}else{
					echo '<div id="message" class="updated fade"><p class="my_message">'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
				}
			}
		
		}elseif (isset($_POST['csds_userRegAide_support_submit'])){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
				$update = array();
				$update = get_option('csds_userRegAide_Options');
				$update['show_support'] = esc_attr(stripslashes($_POST['csds_userRegAide_support']));
				update_option("csds_userRegAide_Options", $update);
				$msg = '<div id="message" class="updated fade"><p class="my_message">'. __('Support Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
			}
		}
		
		
		
		// Checks to see if any new fields exist and if not displays message telling user to create some new fields before editing them
		$newFields = get_option('csds_userRegAide_NewFields');
		if(empty($newFields)){
			$msg = '<div id="message" class="updated fade"><p>'. __('No new fields have been created, please go to the main page to add some new fields first before you try and edit them! ', 'csds_userRegAide').'</p></div>';
			$msg .= '<br />';
		}
		
		// Displays the Edit New Additional Fields Administration Page
		if(current_user_can('manage_options')){
			$tab = 'edit_new_fields';
			$h2 = array( 'adminPage', 'User Registration Aide: Edit New Fields', 'csds_userRegAide' );
			$span = array( 'regForm', 'Delete or Change Field Order or Change Field Display Title for New Fields Here:', 'csds_userRegAide' );
			$form = array( 'post', 'csds_userRegAide_newFields' );
			$nonce = array( 'csds-newFields', 'wp_nonce_csds-newFields' );
			do_action( 'start_wrapper',  $tab, $form, $h2, $span, $nonce );
		
			if(!empty($msg)){
				echo $msg;
			} ?>
				
			<table class="newFields">
			<tr>
			<th><?php _e('Edit Additional Fields for Profile &  Registration Form: Delete Field', 'csds_userRegAide');?> </th>
			<th><?php _e('Edit New Field Order', 'csds_userRegAide');?> </th>
			<th><?php _e('Edit New Field Name', 'csds_userRegAide');?> </th>
			</tr>
			<tr>
			<td><?php
			if(!empty($newFields)){
				echo '<p class="deleteFields">'.__('Here you can select the new additional fields you added that you want to delete.', 'csds_userRegAide').'</p>';
				echo '<p class="editFields"><select name="deleteNewFields" id="csds_userRegMod_delete_Select" title="'.__('Please choose a field to delete here, you can only select one field at a time to delete however', 'csds_userRegAide').'" size="8"  class="deleteFields">';
				foreach($newFields as $fieldKey => $fieldName){
					echo '<option value="'.$fieldKey.'">'.$fieldName.'</option>';
				}
				echo '</select></p>';
				echo '<br/>';
				echo '<div class="submit"><input type="submit" class="button-primary" name="delete_field" value="'.__('Delete New Field', 'csds_userRegAide').'"/></div>';
			}else{
				echo '<p class="deleteFields">'.__('No new fields currently exist, you have to add new fields on the main page before you can delete any!', 'csds_userRegAide').'</p>';
			}?>
			</td>
			<td>						
			<?php
					
			// Edit new field order form 
			$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
			$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
			?>
			<p><?php _e('Here you can select or change the order for the new additional fields on the registration form and profile. You must not have the same number twice, so make sure you change all fields accordingly so there are no duplicates or you will generate an error!', 'csds_userRegAide');?></p>
			<?php
			$i = '';
			$cnt = '';
			$fieldKey = '';
			$fieldOrder = '';
			$fieldKeyUpper = '';
			$i = count($csds_userRegAide_NewFields);
			$cnt = 1;
			
			// Table for field order
			?>
			<br/>
			<table class="newFields">
			<tr>
			<th><?php _e('Additional New Field Name: ', 'csds_userRegAide'); ?></th>
			<th><?php _e('Current Field Order: ', 'csds_userRegAide'); ?></th>
			</tr>
			<?php
			if(!empty($csds_userRegAide_fieldOrder)){
				foreach($csds_userRegAide_fieldOrder as $fieldKey => $fieldOrder){ ?>
					<tr>
					<td class="fieldName"> <?php
					$fieldKeyUpper = strtoupper($fieldKey);
					echo '<label for="'.$fieldKey.'">'.$fieldKeyUpper.'</label>';
					//Changed from check box to label here ?>
					</td>
					<td class="fieldOrder">
					<select  class="fieldOrder" name="csds_editFieldOrder[]" title="<?php __('Make sure that there are no duplicate field order numbers, like two fields having number 2 for their order!', 'csds_userRegAide');?>">
					<?php
					for($ii = 1; $ii <= $i; $ii++){
						if($ii == $fieldOrder){
							echo '<option selected="'.$fieldKey.'" >'.$fieldOrder.'</option>';
						}else{
							echo '<option value="'.$ii.'">'.$ii.'</option>';
						}									
					}
					$cnt ++; ?>
					</select>
					</td>
					</tr>
					<?php
				}
				?>
				</table>
					<div class="submit"><input type="submit" class="button-primary" name="field_order" value="<?php _e('Update Field Order', 'csds_userRegAide');?>"/></div>
					<?php
			}else{
				?>
				<tr>
				<td class="fieldName" colspan="2">
				<p class="deleteFields">
				<?php _e('No new fields currently exist, you have to add new fields on the main page before you can change the order!', 'csds_userRegAide'); ?>
				</p>
				</td>
				</tr>
				</table> <?php
			}
			
			// Edit new field fields
			$newFields = get_option('csds_userRegAide_NewFields');
			?>
			<td>
			<p><?php _e('Here you can change the field name displayed to the user for the new additional fields on the registration form and profile. !', 'csds_userRegAide');?></p>
			<?php
			$i = '';
			$cnt = '';
			$fieldKey = '';
			$fieldKeyUpper = '';
			
			// Table for new field edits
			?>
			<br/>
			<table class="newFields">
			<tr>
			<th><?php _e('Additional New Field ID: ', 'csds_userRegAide');?></th>
			<th><?php _e('Current Field Title: ', 'csds_userRegAide');?></th>
			</tr>
			<?php
			if(!empty($newFields)){
				foreach($newFields as $fieldKey => $fieldName){ ?>
					<tr>
					<td class="fieldName"> <?php
					$fieldKeyUpper = strtoupper($fieldKey);
					echo '<label for="'.$fieldKey.'">'. __($fieldKeyUpper, 'csds_userRegAide').'</label>'; ?>
					</td>
					<td class="fieldOrder">
					<input  type="text" class="fieldOrder" name="<?php echo $fieldKey ?>" id="<?php echo $fieldKey ?>" title="<?php _e('Edit your field name here', 'csds_userRegAide');?>" value="<?php _e($fieldName, 'csds_userRegAide');?>" />
					</td>
					</tr> 
					<?php
				} 
			}else{
				?>
				<tr>
				<td class="fieldName" colspan="2">
				<p class="deleteFields">
				<?php _e('No new fields currently exist, you have to add new fields on the main page before you can change the order!', 'csds_userRegAide'); ?>
				</p>
				</td>
				</tr>
				<?php
			}?>
			</table>
			<div class="submit"><input type="submit" class="button-primary" name="edit_field_name" value="<?php _e('Update Field Names', 'csds_userRegAide');?>"  /></div>
			</td>
			</tr>
			</table>
			<?php
			do_action('end_wrapper'); // adds all closing tags for page wrappers
		}else{
			wp_die(__('You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!'));
		}
	}
	
}	
?>