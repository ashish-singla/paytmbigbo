<?php
/**
 * User Registration Aide - Password Actions & Functions
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.2
 * Since Version 1.5.0.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
 
*/

class PASSWORD_FUNCTIONS
{
	
	public static $instance;
	
	public function __construct() {
		$this->PASSWORD_FUNCTIONS();
	}
	
	function PASSWORD_FUNCTIONS() { //constructor
		
		self::$instance = $this;
		$this->install_xwrd_databases();
	}
	
	/**
	 * Install the database for recording password change information
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns array $fields
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function install_xwrd_databases(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		
		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE {$wpdb->collate}";
		}
		
		if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			$sql = "CREATE TABLE " . $table_name . " (
				`xwrd_change_ID` bigint(20) NOT NULL AUTO_INCREMENT,
				`user_ID` bigint(20) NOT NULL,
				`change_date` datetime NOT NULL default '0000-00-00 00:00:00',
				`change_IP` varchar(100) NULL default '',
				`old_password` varchar(100) NOT NULL default '',
				`change_uagent` varchar(100) NULL default '',
				`change_referrer` varchar(100) NULL default '',
				`change_uhost` varchar(100) NULL default '',
				`change_uri_request` varchar(100) NULL default '',
				 PRIMARY KEY  (`xwrd_change_ID`)
				)$charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
	
	/**
	 * Shortcode for showing and processing password change form
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns array $fields
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
		
	function password_change_form(){
		global $wpdb, $current_user, $post;
		
		apply_filters( 'force_ssl', false, $post );
		$options = get_option('csds_userRegAide_Options');
		$table_name = (string) $wpdb->prefix . "ura_xwrd_change";
		$ip = $this->get_user_ip_address();
		$nonce = wp_nonce_field(  'csds-passChange', 'wp_nonce_csds-passChange' );
		
		// declaring function variables
		$line = (string) '';
		$request_uri = (string) '';
		$referer = (string) '';
		$user_host = (string) '';
		$user_agent = (string) '';
		$changed = (string) '';
		$err = (int) 0;
		$ssl = 'NO';
		if( is_ssl() ){
			$ssl = 'YES';
		}
				
		// post id
		$post_id = $post->ID;
		$title_id = $this->title_id( $post );
		
		//server variables
		$method = $_SERVER['REQUEST_METHOD'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$user_host = gethostbyaddr($ip);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if( isset( $_GET['action'] ) && $_GET['action'] == 'new-register' ){
			$line = '<h2>The Password You Received in the Email Was Temporary and Has Expired, Please Change Your Password Now!</h2>';
		}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'expired-password' ){
			$line = '<h2>Your Password Has Expired, Please Change Your Password!</h2>';
			$referer = $_SERVER['HTTP_REFERER'];
		}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'password-never-changed' ){
			$line = '<h2>You Have Not Changed Your Password Since You Signed Up and Your Password Has Expired, Please Change Your Password Now!</h2>';
		}
		
		// form shortcode
		$form = (string) '';
		$form .= '<form method="post" name="change_password" id="change_password">';
		$form .= '<div class="reset-xwrd">';
		$form .= $nonce; //wp_nonce_field( $nonce[0], $nonce[1] );
		$form .= '<h2>Password Change Form</h2>';
		$form .= $line;
		//$form .= 'Post ID: '.$post_id.'<br/>';
		//$form .= 'Post Name: '.$options['xwrd_change_name'].'<br/>';
		//$form .= 'Title ID: '.$title_id.'<br/>';
		$form .= '<table>';
		$form .= '<tr><td><label for="user_email">E-mail:</label></td>';
		$form .= '<td><input type="text" name="user_email" id="user_email" value="" class="reset-xwrd" title="Email Address Used When Registered For Site" /></td></tr>';
		$form .= '<tr><td><label for="user_login">Username:</label>';
		$form .= '</td><td><input type="text" name="user_login" id="user_login" class="reset-xwrd" value="" size="20" title="Login Name For Site" /></td></tr>';
		$form .= '<tr><td><label for="old_pass1">Old Password:</label></td>';
		$form .= '<td><input type="password" name="old_pass1" id="old_pass1" class="reset-xwrd" size="20" value="" autocomplete="off" title="Password Sent to You When Registered OR Current Password For Site" /></td></tr>';
		$form .= '<tr><td><label for="pass1">New Password:</label></td>';
		$form .= '<td><input type="password" name="pass1" id="pass1" class="reset-xwrd" size="20" value="" autocomplete="off" title="Enter Your New Password For This Site,YOU CANNOT USE THE SAME PASSWORD AS BEFORE!" /></td></tr>';
		$form .= '<tr><td><label for="pass2">Confirm Password:</label></td>';
		$form .= '<td><input name="pass2" type="password" id="pass2" class="reset-xwrd" size="20" value="" autocomplete="off" title="Confirm Your New Password For This Site, MUST MATCH NEW PASSWORD!" /></td></tr>';
		$form .= '<tr><td colspan="2"><div id="pass-strength-result">Strength indicator</div>';
		$form .= '<p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp</p></td></tr>';
		$form .= '<br class="clear" />';
		$form .= '<tr><td colspan="2"><div class="submit">';
		$form .= '<input type="submit" class="button-primary" name="update_xwrd-reset" id="update_xwrd-reset" value="Change Password" /></td></tr></table>';
		$form .= '</div>';
		$form .= '</div>';
		$form .= '</form>';
		
		// end form shortcode
		
		if( isset( $_POST['update_xwrd-reset'] ) ){
			$wp_error = new WP_Error();
			if( ! wp_verify_nonce( $_POST['wp_nonce_csds-passChange'], 'csds-passChange' ) ){
				exit( 'Failed Security Validation!' );
			}/* -- testing -- else{
				$wp_error->add( 'nonce_verified' , __( "<b>ERROR</b>: Nonce Verified!",'csds_userRegAide' ) );
			} */
			$request_uri = $_SERVER['REQUEST_URI'];
			$referer = $_SERVER['HTTP_REFERER'];
			//$referrer = (string) '';
			$verify_xwrd = array();
			$login = $_POST['user_login'];
			$email = $_POST['user_email'];
			$email2 = (string) '';
			$password = $_POST['old_pass1'];
			$oldPassHashed = wp_hash_password( $password );
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['pass2'];
			$user = wp_authenticate( $login, $password );
			
			
			if( empty( $login ) ){
				$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Please Enter Your Username!",'csds_userRegAide' ) );
				$err++;
			}elseif( !username_exists( $login ) ){
				$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Username Does Not Exist!",'csds_userRegAide' ) );
				$err++;
			}
			if( empty( $_POST['user_email'] ) ){
				$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Please Enter your Email",'csds_userRegAide' ) );
				$err++;
			}elseif( !empty( $email ) && !is_wp_error( $user ) ){
				$email2 = $user->user_email;
				if( $email != $email2 ){
					$wp_error->add( 'emails_not_match', __( "<b>ERROR</b>: Email Associated With This Account And Email Entered Do Not Match!",'csds_userRegAide' ) );
					$err++;
				}
			}
			if( $password == $pass1 ){
				$wp_error->add( 'old_new_password_match', __( "<b>ERROR</b>: NEW PASSWORD SAME AS OLD PASSWORD, PLEASE ENTER A DIFFERENT PASSWORD!",'csds_userRegAide' ) );
				$err++;
			}
					
			// filter for field errors
			$verify = apply_filters( 'custom_password_strength', $pass1, $pass2, $login, $email, $wp_error, $err );
			if( !is_wp_error( $user ) ){
				$verify_xwrd = apply_filters( 'duplicate_verify', $user, $pass1, $wp_error, $err );
				$err = $verify[0] + $verify_xwrd[0];
				if( is_wp_error( $verify_xwrd[1] ) ){
					$wp_error = $verify_xwrd[1];
				}
			}
									
			// Errors Displayed
						
			if( is_wp_error( $user ) ){
				$errors = $user->get_error_messages();
				foreach( $errors as $error ){
					echo '<div id="my-message" class="my-error">'.$error.'</div>';
				}
			}elseif( $err >= 1 ){
				if( !empty( $wp_error ) ){
					$errors = $wp_error->get_error_messages();
					foreach( $errors as $error ){
						echo '<div id="my-message" class="my-error">'.$error.'</div>';
					}
				}
			}elseif( !is_wp_error( $user ) && $err == 0 ){
				if( $err == 0 ){
					$user_id = $user->ID;
					$newPassHashed = wp_hash_password( $pass1 );
					$credentials = array(
						'user_login' => $login,
						'user_password' => $password
					);
										
					// storing password change data in database
					$insert = "INSERT INTO " . $table_name . " ( user_ID, change_date, change_IP, old_password, change_uagent, change_referrer, change_uhost, change_uri_request ) " ."VALUES ( '" . $user_id . "', now(), '%s', '%s', '%s', '%s', '%s', '%s' )";
					
					// for testing previous duplicate passwords
					//$insert = "INSERT INTO " . $table_name . " ( user_ID, change_date, change_IP, old_password, change_uagent, change_referrer, change_uhost, change_uri_request ) " . "VALUES ( '" . $user_id . "', (now() - INTERVAL 360 DAY), '%s', '%s', '%s', '%s', '%s', '%s' )";
					
					$insert = $wpdb->prepare( $insert, $ip, $oldPassHashed, $user_agent, $referer, $user_host, $request_uri );
					$results = $wpdb->query($insert);
					
					if( $results == 1){
						$changed .= '<div id="my-message" class="my-updated"><b>Updated</b>: Password Change Records Updated!</div>';
					}else{
						$form .= '<div id="my-message" class="my-error"><b>Error</b>: Database Record Failed!</div>';
						$form .= '<div id="my-message" class="my-error"><b>Error</b>: Password Update Failed!</div>';
					}
					
					if( $results == 1 ){
						$results2 = wp_set_password( $pass1, $user_id );
						
						if( is_wp_error( $results2 ) ){
							$errors = $results2->get_error_messages();
							foreach( $errors as $error ){
								$form .= '<div id="my-message" class="my-error">'.$error.'</div>';
							}
							
						}else{
							// shows login form if successful password change - **must log user out to properly change password**
							//$changed .= '<h2>Refer: '.$referer.'</h2>';
							//$changed .= '<h2>Request: '.$request_uri.'</h2>';
							$changed .= '<div id="my-message" class="my-updated"><b>Updated</b>: Password Updated!</div>';
							$changed .= '<div id="my-message" class="my-updated"><b>Message</b>: You Were Logged Out to Change Your Password Properly, Sorry for Any Inconvenience!</div>';
							$args = array(
								'echo'           => true,
								'redirect'       => site_url( $_SERVER['REQUEST_URI'] ), 
								'form_id'        => 'loginform',
								'label_username' => __( 'Username' ),
								'label_password' => __( 'Password' ),
								'label_remember' => __( 'Remember Me' ),
								'label_log_in'   => __( 'Log In' ),
								'id_username'    => 'user_login',
								'id_password'    => 'user_pass',
								'id_remember'    => 'rememberme',
								'id_submit'      => 'wp-submit',
								'remember'       => true,
								'value_username' => NULL,
								'value_remember' => false
							);
							
							$changed .= wp_login_form( $args );
							return $changed;		
								
						}
					}
				}
			}
		}
		
		return $form;
	}
	
	/**
	 * Handles updating settings options for password settings and uses action to View for settings page
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns nothing
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
		
	function pwrd_change_options_view(){
		$options = get_option('csds_userRegAide_Options');
		$interval = (int) 0;
		$change_time = (int) 0;
		$duplicate_time = (int) 0;
		$time = array();
		$dup_times = array();
		$signup = (int) 0;
		$current = (int) 0;
		$reset = (int) 0;
		$url = (string) '';
		$title = (string) '';
		if( isset( $_POST['updt_pwrd_chgd_options'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$signup = esc_attr( stripslashes( $_POST['newUser_xwrdChange'] ) );
				$current = esc_attr( stripslashes( $_POST['xwrd_chg_curUsers'] ) );
				$url = esc_attr( stripslashes( $_POST['xwrd_chg_url'] ) );
				$title = esc_attr( stripslashes( $_POST['xwrd_chg_title'] ) );
				$reset = esc_attr( stripslashes( $_POST['xwrd_reset'] ) );
				$show_fields = esc_attr( stripslashes( $_POST['show_xwrd_fields'] ) );
				$time = $_POST['password_change_interval'];
				$dup_times = $_POST['dup_password_change_times'];
				$ssl = esc_attr( stripslashes( $_POST['xwrd_chg_ssl'] ) );
				foreach( $time as $key	=>	$days ){
					$change_time = $days;
				}
				
				foreach( $dup_times as $keys => $times ){
					$duplicate_time = $times;
				}
				
				$interval = esc_attr( stripslashes( $change_time ) );
				
				$options['xwrd_change_on_signup'] = $signup;
				$options['xwrd_require_change'] = $current;
				$options['xwrd_change_interval'] = $interval;
				$options['xwrd_change_ssl'] = $ssl;
				$options['xwrd_change_name'] = $url;
				$options['xwrd_chng_title'] = $title;
				$options['allow_xwrd_reset'] = $reset;
				$options['show_password_fields'] = $show_fields;
				$options['xwrd_duplicate_times'] = $duplicate_time;
				
				update_option( "csds_userRegAide_Options", $options );
				if( $reset == 2 ){
					add_filter( 'allow_password_reset', array( &$this, 'xwrd_reset_disable' ), 10, 2 );
				}
				
				if( $show_fields == 2 ){
					add_filter( 'show_password_fields', array( &$this, 'xwrd_show_disable' ) );
				}
			}
		}
		
		do_action( 'xwrd_settings_view' );
	}
	
	/**
	 * View for password settings options page
	 * @since 1.5.0.0
	 * @updated 1.5.0.2
	 * @access public
	 *
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function password_settings_view() {
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die(__('You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'csds_userRegAide'));
		}else{
			$options = get_option('csds_userRegAide_Options');
			$span = array( 'regForm', 'Password Change Options:', 'csds_userRegAide' );
				do_action( 'end_mini_wrap' );
				do_action( 'start_mini_wrap', $span ); ?>	
					<table class="regForm" width="100%">
					<tr> <?php // Password Change Options ?>
						<td width="50%"><?php _e('Require Password Change After User Registers and Gets Password From Email: ', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to require new users who have registered for your site and received their Password from the default WordPress Email to change their password before logging in', 'csds_userRegAide');?>">
						<input type="radio" name="newUser_xwrdChange" id="newUser_xwrdChange" value="1" <?php
						if ($options['xwrd_change_on_signup'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option not to require new users to change their password before logging in',  'csds_userRegAide');?>">
						<input type="radio" name="newUser_xwrdChange" id="newUser_xwrdChange" value="2" <?php
						if ($options['xwrd_change_on_signup'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
						</td>
						<?php // Require existing users to change passwords at selected intervals ?>
						<td width="50%"><?php _e('Require Existing Users to Change Passwords After Specified Time Period: ', 'csds_userRegAide');?>
						<br/>
						<span title="<?php _e('Select this option to require current users to change passwords at selected intervals', 'csds_userRegAide');?>">
						<input type="radio" name="xwrd_chg_curUsers" id="xwrd_chg_curUsers" value="1" <?php
						if ($options['xwrd_require_change'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option not to require current users to change passwords at selected intervals',  'csds_userRegAide');?>">
						<input type="radio" name="xwrd_chg_curUsers" id="xwrd_chg_curUsers" value="2" <?php
						if ($options['xwrd_require_change'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
						</td>
					</tr>
					<tr>
						<?php // Custom Password Strength Requirements Option Yes/No ?>
						<td width="50%"><?php _e('Maximum Time Allowed in Days Between Password Change Dates: ', 'csds_userRegAide');?>
						<span title="<?php _e('Enter the amount of time in days between users having to change their passwords, the defaults are every 30 days up to one year. It is good security practice to require a password change at least every 6 months, or 180 days!', 'csds_userRegAide');?>">
						<select name="password_change_interval[]" id="password_changed_interval" title="<?php _e('You can select the maximum time allowed in days here before users are required to change their passwords. Good security practices call for 180 days', 'csds_userRegAide');?>"  size="8" style="height:40px">
						<?php
						$interval = trim( $options['xwrd_change_interval'] );
						for( $days = 30; $days <= 360; $days += 30 ){
						/* testing
						for( $days = 1; $days <= 30; $days += 1 ){ */
							if( $days == $interval ){
								$selected = "selected=\"selected\"";
							}else{
								$selected = NULL;
							}
							
							echo "<option value=\"$days\" $selected >$days</option>";
						}
						?>
						</select>
						</td>
						<?php // Custom Password Strength Requirements Option Yes/No ?>
						<td width="50%"><?php _e('Maximum Password Changes Before Duplicates Allowed: ', 'csds_userRegAide');?>
						<span title="<?php _e('Enter the number of times checked for password duplicates before allowing a duplicate password. For instance, a user has changed password 6 times before allowing duplicates would mean that on the 7th password change, the user could duplicate the first password they entered of those 6 passwords. This is useful to prevent users from entering the same 1 or 2 passwords over and over again making it easier to hack their accounts and your site!', 'csds_userRegAide');?>">
						<select name="dup_password_change_times[]" id="max_dup_xword_change_times" title="<?php _e('You can select the number of password changes allowed before a user can enter a duplicate password. Useful for those users that use the same passwords over and over again which makes it easy to hack their accounts and your site! This option will eliminate that problem for the most part in addition to strong password strength requirements!', 'csds_userRegAide');?>"  size="8" style="height:40px">
						<?php
						$dup_times = trim( $options['xwrd_duplicate_times'] );
						for( $times = 1; $times <= 9; $times += 1 ){
							if( $times == $dup_times ){
								$selected = "selected=\"selected\"";
							}else{
								$selected = NULL;
							}
							
							echo "<option value=\"$times\" $selected >$times</option>";
						}
						?>
						</select>
						</td>
						
					</tr>
					<tr>
						<td width="50%"><label for="xwrd_chg_url" title="<?php _e( 'Only Add The Distinct Page Name Please!! ( EXAMPLE: change-password for page titled Change Password) No /( FORWARD SLASHES!!)', 'csds_userRegAide' );?>"><?php _e('Password Change Shortcode Page Name: ', 'csds_userRegAide');?></label>/
						<input type="text" name="xwrd_chg_url" id="xwrd_chg_url" title="<?php _e( 'Only Add The Distinct Page Name Please!! ( EXAMPLE: change-password for page titled Change Password) No /( FORWARD SLASHES!!)', 'csds_userRegAide' );?>" value="<?php echo $options['xwrd_change_name']; ?>" />/<br/>
						</td>
						
						<?php // Password Change Post Title -- ?>
						<td width="50%"><label for="xwrd_chg_title" title="<?php _e( 'Only Add The Distinct Page Title!!', 'csds_userRegAide' );?>"><?php _e('Password Change Shortcode Page Title: ', 'csds_userRegAide');?></label>
						<input type="text" name="xwrd_chg_title" id="xwrd_chg_title" title="<?php _e( 'Only Add The Distinct Page Title Please!! (Change Password) No /(SLASHES!!)', 'csds_userRegAide' );?>" value="<?php echo $options['xwrd_chng_title']; ?>" /><br/>
						</td>
						
					</tr>
					<tr>
						<td width="50%"><label for="xwrd_chg_ssl" title="<?php _e( 'Requires SSL Certificate on Website Server To Use SSL!', 'csds_userRegAide' );?>"><?php _e('Use SSL(HTTPS://) Secure Page For Password Change Page: ', 'csds_userRegAide');?></label>
						<span title="<?php _e('Select this option to require SSL for Custom Password Change Page! NOTE: REQUIRES SSL CERTIFICATE ON WEBSITE!', 'csds_userRegAide');?>">
						<input type="radio" name="xwrd_chg_ssl" id="xwrd_chg_ssl" value="1" <?php
						if ($options['xwrd_change_ssl'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option to not use SSL on Custom Password Change Page! No Certificate Required, Use This Option if YOu Have No SSL Certificate!',  'csds_userRegAide');?>">
						<input type="radio" name="xwrd_chg_ssl" id="xwrd_chg_ssl" value="2" <?php
						if ($options['xwrd_change_ssl'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
						</td>
						
						<td width="50%"><label for="xwrd_reset" title="<?php _e( 'This will not include Administrators!', 'csds_userRegAide' );?>"><?php _e('Allow Lost Password Reset for Non-Admins: ', 'csds_userRegAide');?></label>
						<span title="<?php _e('Select this option to allow users to reset passwords with the lost password link on login page', 'csds_userRegAide');?>">
						<input type="radio" name="xwrd_reset" id="xwrd_reset" value="1" <?php
						if ($options['allow_xwrd_reset'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option to not allow users to reset passwords with the lost password link on login page',  'csds_userRegAide');?>">
						<input type="radio" name="xwrd_reset" id="xwrd_reset" value="2" <?php
						if ($options['allow_xwrd_reset'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
						</td>
						
					</tr>
					<tr>
						<td colspan="2"><label for="show_xwrd_fields" title="<?php _e( 'This will not include Administrators!', 'csds_userRegAide' );?>"><?php _e('Show Password Fields on Profile/User Edit Page<b> (NOTE: WILL NOT UTILIZE PASSWORD STRENGTH OR PASSWORD CHANGE REQUIREMENTS!): ', 'csds_userRegAide');?></label>
						<span title="<?php _e('Select this option to allow users to change passwords on the user profile/edit page. NOTE: Will not enforce password strength or password change requirements!!!', 'csds_userRegAide');?>">
						<input type="radio" name="show_xwrd_fields" id="show_xwrd_fields" value="1" <?php
						if ($options['show_password_fields'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
						<span title="<?php _e('Select this option to not allow users to change passwords user profile/edit page',  'csds_userRegAide');?>">
						<input type="radio" name="show_xwrd_fields" id="show_xwrd_fields" value="2" <?php
						if ($options['show_password_fields'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<div class="submit">
						<input type="submit" class="button-primary" name="updt_pwrd_chgd_options" id="updt_pwrd_chgd_options" value="<?php _e( 'Update Password Change Options', 'csds_userRegAide' );?>" />
						</div>
					</tr>
				</table>
			<?php
			do_action( 'end_mini_wrap' );
		}
	}
	
	/**
	 * Get the user/client IP address
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns string $ip_address
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function get_user_ip_address() {
		$ip_address = (string) '';
		if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}elseif( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif( !empty( $_SERVER['HTTP_X_FORWARDED'] ) ){
			$ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
		}elseif( !empty( $_SERVER['HTTP_FORWARDED'] ) ){
			$ip_address = $_SERVER['HTTP_FORWARDED'];
		}elseif( !empty( $_SERVER['REMOTE_ADDR'] ) ){
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}else{
			$ip_address = 'UNKNOWN';
		}
		return esc_attr($ip_address);
	}
	
	/**
	 * Handles filter to allow password reset and updates settings according to admin choices
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns boolean 
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_reset_disable(){
		$options = get_option('csds_userRegAide_Options');
		$reset = $options['allow_xwrd_reset'];
		
		if( $reset == 1 ){
			return true;
		}elseif( $reset == 2 ){
			$user_login = $_POST['user_login'];
			$user = get_user_by( 'login', $user_login );
			if( empty( $user ) ){
				$user = get_user_by( 'email', $user_login );
			}
			$cur_user = new WP_User( $user->ID );
			if ( !empty( $cur_user->roles ) && is_array( $cur_user->roles ) && $cur_user->roles[0] == 'administrator' ){
				return true;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * Handles filter to allow password fields to be shown on user profile/edit page
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns boolean
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_show_disable(){
		$options = get_option('csds_userRegAide_Options');
		$show_fields = $options['show_password_fields'];
		
		if( $show_fields == 1 ){
			return true;
		}elseif( $show_fields == 2 ){
			$user_login = $_POST['user_login'];
			$user = get_user_by( 'login', $user_login );
			if( empty( $user ) ){
				$user = get_user_by( 'email', $user_login );
			}
			$cur_user = new WP_User( $user->ID );
			if ( !empty( $cur_user->roles ) && is_array( $cur_user->roles ) && $cur_user->roles[0] == 'administrator' ){
				return true;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * Removes the 'Lost your Password' text/link from login form
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns string $text
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function remove_xwrd_reset_text( $text ){
		$options = get_option('csds_userRegAide_Options');
		if( array_key_exists( 'allow_xwrd_reset', $options ) ){
			$reset = $options['allow_xwrd_reset'];
		}else{
			$reset = 1;
		}
		if( $reset == 2 ){
			if( is_page( 'login_url' ) ){
				$user_login = $_POST['user_login'];
				$user = get_user_by( 'login', $user_login );
				if( empty( $user ) ){
					$user = get_user_by( 'email', $user_login );
				}
				$cur_user = new WP_User( $user->ID );
				if ( !empty( $cur_user->roles ) && is_array( $cur_user->roles ) && $cur_user->roles[0] == 'administrator' ){
					return $text;
				}else{
					return str_replace( array('Lost your password?', 'Lost your password'), '', trim($text, '?') );
				}
			}else{
				return $text;
			}
			
		}
		
		return $text;
	}
	
	/**
	 * Checks password fields for strength settings and errors for shortcode and registration form pages
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @returns array $results of error count and WP_ERRORS
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_strength_verify( $pass1, $pass2, $login, $email, $errors, $error ){
		$options = get_option('csds_userRegAide_Options');
		$dup_times = trim( $options['xwrd_duplicate_times'] );
		// //to check password -- password fields empty
		if( empty( $pass1 ) || $pass1 == '' ){
				$errors->add('empty_password', __("<strong>ERROR</strong>: Please Enter Your Password!", 'csds_userRegAide'));
				$error ++;
				
		}
		if( empty( $pass2 ) || $pass2 == ''){
				$errors->add('empty_confirm_password', __("<strong>ERROR</strong>: Please Confirm Your Password!", 'csds_userRegAide'));
				$error ++;
				
		}		
		if( $pass1 != $pass2 ){ // passwords do not match
				$errors->add('passwords_not_match', __("<strong>ERROR</strong>: Password and Confirm Password do not Match!", 'csds_userRegAide'));
				$error ++;
		}
		if( $pass1 == $login ){ // password same as user login
			$errors->add('password_and_login_match', __("<strong>ERROR</strong>: Username and Password are the Same, They Must be Different!", 'csds_userRegAide'));
				$error ++;
		}
		
		// Password strength requirements
		if( strlen( trim( $pass1 ) ) < $options['xwrd_length'] ){ // password length too short
			if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['require_xwrd_length'] == 1)){
				$errors->add('password_too_short', __("<strong>ERROR</strong>: Password length too short! Should be at least ".$options['xwrd_length']." characters long!", 'csds_userRegAide'));
					$error ++;
			}
		// no number in password
		}
		if( $pass1 == $pass2 && !preg_match("/[0-9]/", $pass1 )){
			if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_numb'] == 1)){
				$errors->add('password_missing_number', __("<strong>ERROR</strong>: There is no number in your password!", 'csds_userRegAide'));
					$error ++;
			}
		// no lower case letter in password
		}
		if( $pass1 == $pass2 && !preg_match("/[a-z]/", $pass1 )){
			if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_lc'] == 1)){
				$errors->add('password_missing_lower_case_letter', __("<strong>ERROR</strong>: Password missing lower case letter!", 'csds_userRegAide'));
					$error ++;
			}
		// no upper case letter in password
		}
		if( $pass1 == $pass2 && !preg_match("/[A-Z]/", $pass1 )){
			if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_uc'] == 1)){
				$errors->add('password_missing_upper_case_letter', __("<strong>ERROR</strong>: Password missing upper case letter!", 'csds_userRegAide'));
					$error ++;
			}
		// no special character in password
		}
		if( $pass1 == $pass2 && !preg_match("/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/", $pass1 )){
			if($options['default_xwrd_strength'] == 1 || ($options['custom_xwrd_strength'] == 1 && $options['xwrd_sc'] == 1)){
				$errors->add('password_missing_symbol', __("<strong>ERROR</strong>: Password missing symbol!", 'csds_userRegAide'));
					$error ++;
			}
		}
		$results = array( $error, $errors );
		return $results;
	}
	
	/**
	 * Checks password for duplicate password entries
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @param object $user to verify information in db
	 * @param string $password new password to check
	 * @param int $error error counter
	 * @param array $errors WP_ERROR object
	 * @returns array $results
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_change_duplicate_verify( $user, $password, $errors, $error ){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$dup_times = $options['xwrd_duplicate_times'];
		$user_id = $user->ID;
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		$sql = "SELECT old_password FROM $table_name WHERE user_ID = '$user_id' ORDER BY change_date DESC LIMIT $dup_times";
		$xwrds = $wpdb->get_results( $sql, ARRAY_A ); 
		$match = (boolean) false;
		$i = (int) 0;
		
		// to check password -- password fields empty
		if( !empty( $xwrds ) ){
			foreach( $xwrds as $xwrds1 ){
				foreach( $xwrds1 as $xwrd ){
					if( $i < $dup_times ){
						$match = wp_check_password( $password, $xwrd, $user_id );
						if( $match == true ){
							$errors->add('duplicate_password', __("<strong>ERROR</strong>: Please Change Your Password! This Password Matches A Previous Password!", 'csds_userRegAide'));
							$error ++;
						}
					}
					$i++;
				}
			}
		}
		$results = array( $error, $errors );
		return $results;
	}
	
	/**
	 * Redirect user after successful login.
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request URL the user is coming from.
	 * @param object $user Loggedin user's data.
	 * @return string $redirect_to
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function non_admin_login_redirect( $redirect_to, $request, $user ) {
		global $user;
		$options = get_option('csds_userRegAide_Options');
		$redirect = $options['redirect_login'];
		$redirect_url = $options['login_redirect_url'];
		//echo $redirect_to;
		if( $redirect == 1 ){
			if ( isset( $user->roles ) && is_array( $user->roles ) ) {
				//check for admins
				if ( in_array( 'administrator', $user->roles ) ) {
					// redirect them to the default redirect page
					return $redirect_to;
				} else {
					// redirect users to the specified page
					return $redirect_url;
				}
			} else {
				// redirect them to the default redirect page
				return $redirect_to;
			}
		}else{
			// redirect them to the default redirect page
			return $redirect_to;
		}
	}
	
	/**
	 * Checks user for last password change on authentication - if need password change * redirects to password change page
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @param string $username login name for user verification
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_change_login_check( $username ){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		
		if( !username_exists( $username ) ){
			return;
		}
		
		$change = $options['xwrd_require_change'];
		
		if( $change == 2 ){
			return;
		}
		
		// if user never changed password check signup/registration date for when plugin is new users->user_registered
		$days = $options['xwrd_change_interval'];
		$user = get_user_by( 'login', $username );
		$xwrd_chg_name = $options['xwrd_change_name'];
		$expired_password = $options['xwrd_chng_exp_url'];
		$never_changed = $options['xwrd_chng_nc_url'];
		$site = site_url();
		$user_id = $user->ID;
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		$sql_cnt = "SELECT COUNT(user_ID) FROM $table_name WHERE user_ID = '$user_id'";
		$cnt = $wpdb->get_var( $sql_cnt );
		if( empty( $cnt ) || $cnt <= 0 || $cnt == '' ){
			$table_name = $wpdb->prefix . "users";
			$sql = "SELECT ID FROM $table_name WHERE ID = '$user_id' AND date_add(user_registered, INTERVAL ".$days." DAY) < NOW()";
			$date = $wpdb->get_var( $sql );
			if( !empty( $date ) ){
				$url = $xwrd_chg_name.$never_changed;
				$redirect = $site.'/'.$url;
				wp_redirect( $redirect );
				exit;
			}
		}else{
			$table_name = $wpdb->prefix . "ura_xwrd_change";
			$sql = "SELECT change_date FROM $table_name WHERE user_ID = '$user_id'  AND date_add(change_date, INTERVAL " .
				$days . " DAY) > NOW() ORDER BY user_ID DESC";
			$date = $wpdb->get_var( $sql );
			if( empty( $date ) ){
				$url = $xwrd_chg_name.$expired_password;
				$redirect = $site.'/'.$url;
				wp_redirect( $redirect );
				exit;
			}
			
		}
		
	}
	
	/**
	 * Redirect to ssl change password page if ssl is available
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * 
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_chng_ssl_redirect(){
		global $post;
		$id = $post->ID;
		$xwrd_id = $this->title_id( $post );
		$options = get_option('csds_userRegAide_Options');
		$ssl = $options['xwrd_change_ssl'];
		$name = $options['xwrd_change_name'];
		$action = $options['xwrd_chng_email_url'];
		$action1 = $options['xwrd_chng_exp_url'];
		if( $ssl == 1 ){
			if( $id == $xwrd_id && !is_ssl() ){
				if( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ){
					wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']), 301 );
					exit();

				}else{
					wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
					exit();
				}
			}elseif(  $id != $xwrd_id && is_ssl() && !is_admin() ){

				if( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ){
					wp_redirect( preg_replace( '|^https://|', 'http://', $_SERVER['REQUEST_URI'] ), 301 );
					exit();

				}else{
					wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
					exit();
				}

			}
		}

	}
	
	/**
	 * Returns Post ID for checking if page is Password Change Page
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @param accept object WP_Post $post
	 * @param return int $titleid WordPress post_id
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function title_id( $post ){
		global $wpdb, $post;
		$options = get_option('csds_userRegAide_Options');
		$name = $options['xwrd_change_name'];
		$ssl = $options['xwrd_change_ssl'];
		$titleid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $name . "'");
		return $titleid;
	}
	
	/**
	 * pre_post_link filter for SSL Change Password Page if used
	 * @since 1.5.0.0
	 * @updated 1.5.0.0
	 * @access public
	 * @param accept string $permalink
	 * @param accept object WP_Post $post
	 * @param accept bool $leavename
	 * @param return string $permalink
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function xwrd_chng_ssl( $permalink, $post, $leavename ) {
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$name = $options['xwrd_change_name'];
		$ssl = $options['xwrd_change_ssl'];
		$titleid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $name . "'");
		
		if( $ssl == 1 ){
			if( $titleid == $post->ID && !is_ssl() ){
				return preg_replace( '|^http://|', 'https://', $permalink );
			}
		}
		
		return $permalink;

	}
	
}