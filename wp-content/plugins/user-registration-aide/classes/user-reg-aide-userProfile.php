<?php
/**
 * User Registration Aide - User Profile Functions
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
 * Since Version 1.3.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/**
 * URA USER PROFILE CLASS For Handling User Profile Actions & Filters
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.4.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_USER_PROFILE
{

	public static $instance;
	
	public function __construct() {
		$this->URA_USER_PROFILE();
	}
		
	function URA_USER_PROFILE() { //constructor
	
		self::$instance = $this;
	}
	
	/**
	 * Show the additional fields added on the user profile page
	 * @since 1.0.0
	 * @updated 1.5.0.0
	 * @handles action 'show_user_profile' line 259 user-registration-aide.php (Priority: 0 - Params: 1)
	 * @handles action 'edit_user_profile' line 260 user-registration-aide.php (Priority: 0 - Params: 1)
	 * @access private
	 * @accepts $user
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
		
	function csds_show_user_profile($user){
	 
	 global $current_user;
		$options = get_option('csds_userRegAide_Options');
		$current_role = get_option('default_role');
		$selRole = $options['display_name_role'];
		$show_display_name = $options['show_profile_disp_name'];
		if( $options['custom_display_name'] == '1' && $show_display_name == '2' ){
			$user_displayed = new WP_User( $user->ID );
			$user_role = array_shift( $user->roles );
			foreach( $selRole as $rkey => $rvalue ){
				if( $rvalue == 'all_roles' || $user_role == $rvalue ){
					?>
					<script>
						jQuery(document).ready(function() {
							jQuery('#display_name').parent().parent().hide();
						});
					</script>
					<?php
				}
			}
		}
		$user_id = $user->ID;
		$fieldKey = '';
		$fieldName = '';
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		if($options['change_profile_title'] == "1"){
			echo '<h3>'. __($options['profile_title'], 'csds_userRegAide').'</h3>';
		}else{
			echo '<h3>'. __('User Registration Aide Additional Fields', 'csds_userRegAide').'</h3>';
		}
		echo '<table class="form-table">';
		$current_user = wp_get_current_user();
		if(current_user_can('edit_user', $current_user->ID) || current_user_can('edit_users', $current_user->ID)){
			if(!empty($csds_userRegAide_NewFields)){
				foreach($csds_userRegAide_NewFields as $fieldKey => $fieldName){ ?>
					
					<tr>
					<th><label for="<?php echo $fieldKey ?>"><?php echo $fieldName ?></label></th>
					<td><input type="text" name="<?php echo $fieldKey ?>" id="<?php echo $fieldKey ?>" value="<?php echo esc_attr(get_user_meta($user_id, $fieldKey, TRUE)) ?>" class="regular-text" /></td>
					</tr>
					
					<?php
				}
			} ?>
			</table>
			<br/>
			<?php
			if($options['show_support'] == "1"){
				echo '<a target="_blank" href="'.$options['support_display_link'].'">' . $options['support_display_name'] . '</a>';
				echo '<br/>';
			}
			wp_nonce_field('userRegAideProfileForm', 'userRegAideProfileNonce');
			
		}else{
			if(is_user_logged_in()){ // wordpress or theme bug for some people
				exit(__('Naughty, Naughty! You do not have permissions to do this!', 'csds_userRegAide'));
			}else{
				wp_safe_redirect(wp_login_url());
				exit;
			}
		}
	}
	
	 
	 /**
	 * Updates the additional fields data added to the user profile page
	 * @since 1.0.0 
	 * @updated  1.3.0
	 * @handles action 'personal_options_update' line 261 user-registration-aide.php (Priority: 0 - Params: 1)
	 * @handles action 'edit_user_profile_update' line 262 user-registration-aide.php (Priority: 0 - Params: 1)
	 * @handles action 'profile_update' line 263 user-registration-aide.php (Priority: 0 - Params: 1)
	 * @access private
	 * @accepts $user_id
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	 
	 function csds_update_user_profile($user){
	 
		global $wpdb, $current_user, $pagename, $errors;
		
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		$userID = $user;
		$fieldKey = '';
		$fieldName = '';
		$newValue = '';
		$newValue1 = (string) '';
		$newValue2 = (string) '';
		$current_user = wp_get_current_user();
		$options = get_option('csds_userRegAide_Options');
		
		if(current_user_can('edit_user', $current_user->ID)  || current_user_can('create_users', $current_user->ID)){
			if(!empty($csds_userRegAide_NewFields)){
				if(!is_multisite()){
					if(wp_verify_nonce($_POST["userRegAideProfileNonce"], 'userRegAideProfileForm')){
						foreach( $csds_userRegAide_NewFields as $fieldKey => $fieldName ){
							if( isset( $_POST[$fieldKey] ) ){
								$newValue = esc_attr( stripslashes( $_POST[$fieldKey] ) );
								$newValue = apply_filters( 'pre_user_description', $newValue );
								update_user_meta($userID, $fieldKey, $newValue);
							}else{
								//exit(__('New Value empty!'));
							}
						}
					}else{
						exit(__('Failed Security Check', 'csds_userRegAide'));
					}
				
				}else{
					if(wp_verify_nonce($_POST["userRegAideProfileNonce"], 'userRegAideProfileForm')){
						foreach($csds_userRegAide_NewFields as $fieldKey => $fieldName){
							if( isset( $_POST[$fieldKey] ) ){
								$newValue = esc_attr( stripslashes( $_POST[$fieldKey] ) );
								$newValue = apply_filters( 'pre_user_description', $newValue );
								update_user_meta($userID, $fieldKey, $_POST[$fieldKey]);
							}
							else{
								//exit(__('New Value empty!'));
							}
						}
					}else{
						exit(__('Failed Security Check', 'csds_userRegAide'));
					}
				}
			}
			
		}else{
			if(is_user_logged_in()){ // wordpress or theme bug for some
				exit(__('You do not have sufficient permissions to edit this user, contact a network administrator if this is an error!', 'csds_userRegAide'));
			}else{
				wp_safe_redirect(wp_login_url());
				//exit;
			}
		}
		
	}
	
} // end class
?>