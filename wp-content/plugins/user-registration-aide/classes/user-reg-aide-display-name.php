<?php

/**
 * User Registration Aide - WordPress Display Name Modifier
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

class URA_DISPLAY_NAME
{
	public static $instance;
	
	public function __construct() {
		$this->URA_DISPLAY_NAME();
	}
	
	function URA_DISPLAY_NAME(){
		
		self::$instance = $this;
		
	}
	
	function names_array(){
		$display_fields = array(
			'nickname'			=>	'Nickname',
			'first_name'		=>	'First Name',
			'last_name'			=>	'Last Name',
			'first_last_name'	=>	'First - Last Name',
			'last_first_name'	=>	'Last - First Name'		
		);
		return $display_fields;
	}
	
	/* Handles Display Name settings and options updates and view for WordPress Display Name
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	function display_name_options(){
		
		$options = get_option('csds_userRegAide_Options');
		
		if( isset( $_POST['update_display_name_field'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$options['custom_display_name'] = esc_attr( stripslashes( $_POST['csds_displayNameYesNo'] ) );
				$field = (string) '' ;
				$register = (int) 0;
				$fields = array();
				$regFields = array();
				$display_fields = $this->names_array();
				$fields = $_POST['display_name_field'];
				foreach( $fields as $key	=>	$title ){
					$field = $title;
				}
				$options['custom_display_field'] = esc_attr( stripslashes( $field ) );
				$roles = $_POST['display_name_role_select'];
				$select_roles = array();
				foreach( $roles as $role_key	=>	$role_value ){
					$select_roles[$role_key] = $role_value;
				}
				$options['display_name_role'] = $select_roles;
				$profile_update = $_POST['csds_profileDispNameYN'];
				$default_role = $_POST['default_role'];
				if( isset( $_POST['csds_users_can_register'] ) ){
					$register = 1;
					update_option( "users_can_register", $register );
				}else{
					$register = 0;
					update_option( "users_can_register", $register );
				}
				
				$options['default_user_role'] = $default_role;
				$options['show_profile_disp_name'] = $profile_update;
				update_option("csds_userRegAide_Options", $options);
				update_option( "default_role", $default_role );
				if( $_POST['csds_displayNameYesNo'] == '1' ){
					$regFields = get_option('csds_userRegAide_registrationFields');
					$display_field  = $options['custom_display_field'];
					if( $display_field != 'last_name' ){
						if( is_array( $regFields ) ){
							if( !in_array( 'First Name', $regFields ) ){
								$regFields['first_name'] = 'First Name';
								update_option( "csds_userRegAide_registrationFields", $regFields );
							}
						}
					}
					if( $display_field != 'first_name' ){
						if( is_array( $regFields ) ){
							if( !in_array( 'Last Name', $regFields ) ){
								$regFields['last_name'] = 'Last Name';
								update_option( "csds_userRegAide_registrationFields", $regFields );
							}
						}
					}
				}
			}
		}elseif( isset( $_POST['change_user_display_names'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$this->update_current_user_display_names();
			}
		}elseif( isset( $_POST['restore_default_display_names'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$this->restore_user_display_names();
			}
		}
				
		do_action( 'display_name_view' );
		
	}
	
	/* Handles Display Name settings view
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function user_display_name_view(){
		global $wp_roles, $current_user;
		$options = get_option('csds_userRegAide_Options');
		$span = array( 'regForm', 'Choose User Display Name Options Here:', 'csds_userRegAide' );
		$current_role = get_option('default_role');
		$selRole = $options['display_name_role'];
		$selField = $options['custom_display_field'];
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die(__('You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'csds_userRegAide'));
		}else{
			do_action( 'start_mini_wrap', $span );
			?>
			<table class="displayName">
				<tr>
					<th class="displayName"><?php _e('Display Name Choices', 'csds_userRegAide');?> </th>
					<th class="displayName"><?php _e('Display Name Field', 'csds_userRegAide');?> </th>
					<th class="displayName"><?php _e('Display Name Role', 'csds_userRegAide');?> </th>
				</tr>
				<tr>
					<td>
					<p><label for="csds_displayNameYesNo" title="Using this option will remove the option for users to choose their own display name on the user profile and will give total control of user display names to the administrators of this site"><?php _e('Use Custom Display Name?: ', 'csds_userRegAide');?><input type="radio" id="csds_displayNameYesNo" name="csds_displayNameYesNo"  value="1" <?php
					if ($options['custom_display_name'] == 1) echo 'checked' ;?>/> Yes
					<input type="radio" id="csds_displayNameYesNo" name="csds_displayNameYesNo"  value="2"<?php
					if ($options['custom_display_name'] == 2) echo 'checked' ;?>/> No
					</label>
					</td>
					<td class="displayName">
					<p class="displayName">
					<select name="display_name_field[]" id="display_name_fields" title="<?php _e('You can select the field(s) here that you want to use in place of the current default WordPress User Display Name which is the User Login', 'csds_userRegAide');?>"  size="8" style="height:40px">
					<?php
					$dn_fields = $this->names_array();
					$options = get_option( 'csds_userRegAide_Options' );
					$custom_display_name = $options['custom_display_field'];
										
					foreach($dn_fields as $key1 => $value1){
						if( $key1 == $custom_display_name ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						
						echo "<option value=\"$key1\" $selected >$value1</option>";
					}
							
					?>								
					</select>
					</p>
					</td>
					<td class="displayName">
					<p class="displayName"><select name="display_name_role_select[]" id="display_name_roles_select" title="<?php _e('You can select the role here that you want to use for the Custom WordPress User Display Name or Select All Roles for everyone on the site', 'csds_userRegAide');?>" multiple size="8" style="height:40px">
					<?php
					$selected = (string) '';
					$register = (int) 0;
					$checked = (string) '';
					$def_role = 'all_roles';
					$def_role_title = 'All Roles';
					$role_names = $wp_roles->get_names();
					$roles = array();
					$sel_roles = $options['display_name_role'];
					
					if( in_array( "all_roles", $sel_roles ) ){
						$selected = "selected=\"selected\"";
						echo "<option value=\"$def_role\" $selected >$def_role_title</option>";
					}else{
						$selected = NULL;
						echo "<option value=\"$def_role\" $selected >$def_role_title</option>";
					}
					
					foreach($role_names as $role_id => $role_name){
						foreach( $sel_roles as $key => $value ){
							if( in_array( $role_id, $sel_roles ) ){
								$selected = "selected=\"selected\"";
							}else{
								$selected = NULL;
							}
							
							echo "<option value=\"$role_id\" $selected >$role_name</option>";
							break;
						}
						
					}
					
					
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td>
					<p><label for="csds_profileDispNameYN" title="Using this option will remove the option for users to choose their own display name on the user profile and will give total control of user display names to the administrators of this site"><?php _e('Allow User To Update Own Display Name in User Profile: ', 'csds_userRegAide');?><input type="radio" id="csds_profileDispNameYN" name="csds_profileDispNameYN"  value="1" <?php
					if ($options['show_profile_disp_name'] == 1) echo 'checked' ;?>/> Yes
					<input type="radio" id="csds_profileDispNameYN" name="csds_profileDispNameYN"  value="2"<?php
					if ($options['show_profile_disp_name'] == 2) echo 'checked' ;?>/> No
					</label>
					</td>
					<td>
					<p><label for="csds_profileDispNameYN" title="Using this option will remove the option for users to choose their own display name on the user profile and will give total control of user display names to the administrators of this site"><?php _e('New User Default Role: ', 'csds_userRegAide');?>
					<select name="default_role" id="select_default_role" title="<?php _e('You can select the role here that you want to use for the Default WordPress New User Sign-up', 'csds_userRegAide');?>" multiple size="8" style="height:40px"><?php
					$current_role = get_option('default_role');
					foreach($role_names as $role_id => $role_name){
						if( $role_id == $current_role ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						
						echo "<option value=\"$role_id\" $selected >$role_name</option>";
					}
					?>
					</label>
					</td>
					<td>
					<?php $register = get_option( 'users_can_register' ); ?>
					<label for="csds_users_can_register">
					<input name="csds_users_can_register" type="checkbox" id="csds_users_can_register" value="<?php echo $register; ?>"
					<?php
					if( $register == '1' ){ 
						echo 'checked="yes">';
					}else{
						echo '>';
					}
					?>
					Anyone can register</label>
					
				</tr>
				<tr>
					<td colspan="3" class="displayName">
					<div class="submit">
					<input type="submit" class="button-primary" name="update_display_name_field" id="update_display_name_field" value="<?php _e( 'Update Display Name Options', 'csds_userRegAide' );?>" />
					</div>
					</td>
				<tr>
					<td colspan="2" class="displayName">
					<div class="submit">
					<label for="change_display_names">Change Existing Users Display Names to Your Custom Display Name Field - Current Display Name Field: <?php echo $custom_display_name; ?>
					<br/>
					<b>CAUTION - THIS CAN TAKE QUITE SOME TIME IF YOU HAVE A SLOW SERVER OR LOTS OF USERS!!</b></label>
					<br/>
					<input type="submit" class="button-primary" name="change_user_display_names" id="change_user_display_names" title="<?php _e( 'Changes the Current Users Display Names to the Field of Your Choice and Creates Backup Existing User Display Names to the User-Meta Table in Case you Wish to Go Back to The Default WordPress Display Name', 'csds_userRegAide' );?>" value="<?php _e( 'Update Display Names', 'csds_userRegAide' );?>" />
					</div>
					</td>
					<td colspan="1" class="displayName">
					<div class="submit">
					<label for="restore_default_display_names">Restore Existing Users Display Names to WordPress Default Display Name:</label>
					<br/>
					<input type="submit" class="button-primary" name="restore_default_display_names" id="restore_default_display_names" title="<?php _e( 'Restores Existing Users Display Names to the Default WordPress Display Name From the Backup Created When Display Names Were Customized',  'csds_userRegAide' );?>" value="<?php _e( 'Restore Default Display Names', 'csds_userRegAide' );?>" />
					</div>
					</td>
				</tr>
				</table>
				
			<?php
			do_action( 'end_mini_wrap' );
		}
	}
	
	/* Updates existing users display name to chosen field by role
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function update_current_user_display_names(){
		global $wp_roles;
		$options = get_option('csds_userRegAide_Options');
		
		if( $options['custom_display_name'] == 2 ){
			?>
			<div class="error">
			<?php _e( 'You Must Set The Use Custom Display Name Field to Yes to Use This Feature!', 'csds_userRegAide' );?>
			</div>
			<?php
		}elseif( $options['custom_display_name'] == 1 ){
			$roles = $wp_roles->get_names();
			$custom_display_role = $options['display_name_role'];
			$custom_display_name = $options['custom_display_field'];
			
			foreach( $custom_display_role as $key => $value ){
				if( $value == 'all_roles' ){
					$user_args = array(
						'fields' 	=>	'all_with_meta'
					);
					$this->update_user_display_names( $user_args, $custom_display_name );
					break;
				// Admin specified custom role
				}else{			
					$user_args = array(
						'role' 	=>	$value
					);
					$this->update_user_display_names( $user_args, $custom_display_name );
				}
			}
			if( !in_array( 'all_roles', $custom_display_role ) ){
				foreach( $roles as $rkey => $rvalue ){
					if( !in_array( $rkey, $custom_display_role ) ){
						$user_args = array(
							'role' 	=>	$rkey
						);
						$this->check_unused_roles_display_names( $user_args, $custom_display_name );
					}
				}
			}
			
			
		}
	}
	
	/* Reverts previously changed users display name to default display name by role if roles have been changed and user display name was not changed back to original
	* @handles 
	* @Since Version: 1.4.0.0
	* @param $user_args, $custom_display_name
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function check_unused_roles_display_names( $user_args, $custom_display_name ){
		global $wpdb;
		$field = (string) '';
		$users = get_users($user_args);
		$display_name = (string) '';
		$old_display_name = (string) '';
		
		foreach( $users as $user ){
		
			// backing up original display name just in case it need to be restored
			$field = get_user_meta( $user->ID, 'default_display_name', true );
			if( $field ){
				$display_name = $field;
			}else{
				$display_name = $user->user_login;
			}
			
			if( empty( $field ) ){
				update_user_meta( $user->ID, 'default_display_name', $display_name );
			}
			
			// updating new display name
			$add_display_name = $wpdb->prepare("UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user->ID);
			$wpdb->query( $add_display_name );
			
		}
	}
	
	/* Function to process new display name updates 
	* @handles 
	* @Since Version: 1.4.0.0
	* @param $user_args, $custom_display_name
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
	
	function update_user_display_names( $user_args, $custom_display_name ){
		global $wpdb;
		$field = (string) '';
		$users = get_users( $user_args );
		$display_name = (string) '';
		$old_display_name = (string) '';
		
		foreach( $users as $user ){
		
			if( $custom_display_name == 'first_last_name' ){
				$display_name = $user->first_name.' '.$user->last_name;
			}elseif( $custom_display_name == 'last_first_name' ){
				$display_name = $user->last_name.' '.$user->first_name;
			}elseif( $custom_display_name == 'first_name' ){
				$display_name = $user->first_name;
			}elseif( $custom_display_name == 'last_name' ){
				$display_name = $user->last_name;
			}elseif( $custom_display_name == 'nickname' ){
				$display_name = $user->nickname;
			}
			
			// backing up original display name just in case it need to be restored
			if( $user->display_name ){
				$old_display_name = $user->display_name;
			}else{
				$old_display_name = $user->user_login;
			}
			
			$field = get_user_meta( $user->ID, 'default_display_name', true );
			if( empty( $field ) ){
				update_user_meta( $user->ID, 'default_display_name', $old_display_name );
			}
			
			// updating new display name
			$add_display_name = $wpdb->prepare("UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user->ID);
			$wpdb->query( $add_display_name );
			
		}
	}
	
	/* Restores default users display name to chosen field by role
	* @handles 
	* @Since Version: 1.4.0.0
	* @param 
	* @return 
	* @access private
	* @author Brian Novotny
	* @website http://creative-software-design-solutions.com
	*/
		
	function restore_user_display_names(){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$custom_display_name = $options['custom_display_field'];
		$user_args = array(
			'fields' 	=>	'all_with_meta'
		);
		$field = (string) '';
		$users = get_users($user_args);
		$display_name = (string) '';
		
		foreach( $users as $user ){
			$field = get_user_meta( $user->ID, 'default_display_name', true );
			if( empty( $field ) ){
				$display_name = $user->user_login;
			}else{
				$display_name = $field;
			}
			$add_display_name = $wpdb->prepare("UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user->ID);
			$wpdb->query( $add_display_name );
		}
		$options['custom_display_name'] = "2";
		$options['custom_display_role'] = "2";
		update_option("csds_userRegAide_Options", $options);
	}
	
	
}
