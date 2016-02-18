<?php
/**
 * User Registration Aide - Dashboard Widget for WordPres Admin Page
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.1
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
 * @updated 1.3.6
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


class URA_DASHBOARD_WIDGET
{
	
	public static $instance;
	
	public function __construct() {
		$this->URA_DASHBOARD_WIDGET();
		$this->index = (int) 0;
		
	}
		
	function URA_DASHBOARD_WIDGET() { //constructor
		global $wp_version;
		self::$instance = $this;
	}

	/**
	 * Adds dashboard widget to wp admin page for news & update information
	 * @since 1.3.0
	 * @updated 1.3.0
	 * @handles action 'wp_dashboard_setup' line 126 user-registration-aide.php
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function ura_dashboard_widget_action(){
		$options = get_option('csds_userRegAide_Options');
		if( array_key_exists( 'show_dashboard_widget', $options ) ){
			$show_widget = $options['show_dashboard_widget'];
		}else{
			$show_widget = 2;
		}
		if( $show_widget == 1){
			wp_add_dashboard_widget('csds_ura_dash_widget', '<a class="ura-dash-widget" href="http://creative-software-design-solutions.com">Creative Software Design Solutions</a><b> User Registration Aide</b>', array(&$this, 'ura_dashboard_widget_display'));
		}
	}
	
	/**
	 * Adds dashboard widget to wp admin page for news & update information
	 * @since 1.3.0
	 * @updated 1.3.6
	 * @handles action line 60 &$this for 'wp_add_dashboard_widget'
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function ura_dashboard_widget_display(){
		
		global $wpdb, $role;
		$index = (int) 0;
		$cnt = (int) 0;
		$page = (int) 1;
		$load = (int) 0;
		$id = array();
		$uid = array();
		$field1 = array();
		$field2 = array();
		$field3 = array();
		$field4 = array();
		$field5 = array();
		$ucnt = (int) 0;
		$count = (int) 0;
		
		
		$user_args = array(
			'fields' 	=>	'all_with_meta'
		);
		$users = get_users($user_args);
		
		if($index == "0"){
			$this->display_users($users);
		}
				
	}
	
	/**
	 * Displays dashboard widget users for wp admin page for news & update information
	 * @since 1.3.0
	 * @updated 1.3.7.3
	 * @handles display users line 97 &$this
	 * @params $users array of wordpress site users
	 * @access private
	 * @accepts array $users WordPress Class
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function display_users($users){
		
		global $wpdb, $role, $current_user;
		
		$logo = IMAGES_PATH."csds-dash_logo.png";
		$cols = array();
		$cols = $this->selected_dw_fields_array();
		$count = count($users);
		$col_cnt = count($cols);
		$col_span = $col_cnt + 1;
		
		echo '<div class="csds-dash-widget" id="csds-dash-widget">';
		
		$current_user = wp_get_current_user();
		if(current_user_can('manage_options', $current_user->ID)){					
			echo '<table class="admin-dash">';
				echo '<tr>';
				echo '<th colspan="'.$col_span.'" class="main_title">';
				echo __('Quick Glance Current Site Users:', 'csds_userRegAide');
				echo '</th>';
				echo '</tr>';
				echo '<tr>';
				foreach($cols as $key => $name){
					echo '<th class="col_titles">'. __($name, 'csds_userRegAide').'</th>';
				}
				echo '</tr>';
				
				foreach($users as $nuser){ 
				
					echo '<tr>';
					
					foreach($cols as $key => $value){
					
						if($key != 'roles'){
							echo '<td>'.$nuser->$key.'</td>';
						}elseif($key == 'roles'){
							$roles = $nuser->$key;
							$userrole = array_shift($roles);
							echo '<td>'.$userrole.'</td>';
						
						}
						
					}
					
					echo '</tr>';
				}
				
				echo '</table>';
				echo '<br />';
			echo '<a href="http://creative-software-design-solutions.com" target="_blank" rel="follow"><img src="'.$logo.'" /></a>';
			echo '</div>';
		}else{
			echo '<a href="http://creative-software-design-solutions.com" target="_blank" rel="follow"><img src="'.$logo.'" /></a>';
			echo '</div>';
		}
				
	}
	
	/**
	 * Displays all the fields user can choose from to display on his dashboard widget
	 * @since 1.3.6
	 * @access public
	 * @returns array $all_fields
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	 *
	*/
	
	function fields_array(){
		
		$existing_fields = array(
			'user_login'	=>	__('Login', 'csds_userRegAide'),
			'user_nicename'	=>	__('Username', 'csds_userRegAide'),
			'user_email'	=>	__('Email', 'csds_userRegAide'),
			'user_url'		=>	__('Website', 'csds_userRegAide'),
			'display_name'	=>	__('Display Name', 'csds_userRegAide'),
			'first_name'	=>	__('First Name', 'csds_userRegAide'),
			'last_name'		=>	__('Last Name', 'csds_userRegAide'),
			'nickname'		=>	__('Nickname', 'csds_userRegAide'),
			'roles'			=>	__('Role', 'csds_userRegAide')
		);
		
		$new_fields = array();
		$new_fields = get_option('csds_userRegAide_NewFields');
		$all_fields = array();
		if(!empty($new_fields)){
			$all_fields = array_merge($existing_fields, $new_fields);
		}else{
			$all_fields = $existing_fields;
		}
		return $all_fields;		
			
	}
	
	/**
	 * Creates and returns an array of all the chosen dashboard widget fields
	 * @since 1.3.6
	 * @access public
	 * @returns array $selected_fields
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	 *
	*/
	
	function selected_dw_fields_array(){
		
		$options = get_option('csds_userRegAide_Options');
		
		$sel_fields = array();
		if(!empty($options['dwf1_key']) && !empty($options['dwf1'])){
			$sel_fields[$options['dwf1_key']] = $options['dwf1'];
		}
		if(!empty($options['dwf2_key']) && !empty($options['dwf2'])){
			$sel_fields[$options['dwf2_key']] = $options['dwf2'];
		}
		if(!empty($options['dwf3_key']) && !empty($options['dwf3'])){
			$sel_fields[$options['dwf3_key']] = $options['dwf3'];
		}
		if(!empty($options['dwf4_key']) && !empty($options['dwf4'])){
			$sel_fields[$options['dwf4_key']] = $options['dwf4'];
		}
		if(!empty($options['dwf5_key']) && !empty($options['dwf5'])){
			$sel_fields[$options['dwf5_key']] = $options['dwf5'];
		}
		
		return $sel_fields;	

	} // end function
	
	/**
	 * Sets the fields in order for the dashboard widget fields display
	 * @since 1.3.6
	 * @access public
	 * 
	 * @returns array $order_array
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function dash_widget_fields_array(){
		
		$options = get_option('csds_userRegAide_Options');
		
		if(!empty($options['dwf1_key']) && !empty($options['dwf1']) && !empty($options['dwf1_order'])){
			$order_array[1] = array("key" => $options['dwf1_key'], "name" => $options['dwf1'], "order" => $options['dwf1_order']);
		}
		if(!empty($options['dwf2_key']) && !empty($options['dwf2']) && !empty($options['dwf2_order'])){
			$order_array[2] = array("key" => $options['dwf2_key'], "name" => $options['dwf2'], "order" => $options['dwf2_order']);
		}
		if(!empty($options['dwf3_key']) && !empty($options['dwf3']) && !empty($options['dwf3_order'])){
			$order_array[3] = array("key" => $options['dwf3_key'], "name" => $options['dwf3'], "order" => $options['dwf3_order']);
		}
		if(!empty($options['dwf4_key']) && !empty($options['dwf4']) && !empty($options['dwf4_order'])){
			$order_array[4] = array("key" => $options['dwf4_key'], "name" => $options['dwf4'], "order" => $options['dwf4_order']);
		}
		if(!empty($options['dwf5_key']) && !empty($options['dwf5']) && !empty($options['dwf5_order'])){
			$order_array[5] = array("key" => $options['dwf5_key'], "name" => $options['dwf5'], "order" => $options['dwf5_order']);
		}
				
		return $order_array;
		
	}
	
	/**
	 * Sets the fields in order for the dashboard widget fields display after update
	 * @since 1.3.6
	 * @access public
	 * 
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_dash_widget_fields_order(){
	
		$cnt = (int) 0;
		$dwf = (int) 1;
		$update = array();
		$update = get_option('csds_userRegAide_Options');
		$dw_array = $this->dash_widget_fields_array();
		$temp = array();
		$cnt = count($dw_array);
		
		foreach($dw_array as $key => $value){
			foreach($value as $key1 => $value1){
				if($value1 == 1 && $cnt >=  1){
					$temp[1]['key'] = $dw_array[$dwf]['key'];
					$temp[1]['name'] = $dw_array[$dwf]['name'];
					$temp[1]['order'] = $dw_array[$dwf]['order'];
				}elseif($value1 == 2 && $cnt >=  2){
					$temp[2]['key'] = $dw_array[$dwf]['key'];
					$temp[2]['name'] = $dw_array[$dwf]['name'];
					$temp[2]['order'] = $dw_array[$dwf]['order'];				
				}elseif($value1 == 3 && $cnt >=  3){
					$temp[3]['key'] = $dw_array[$dwf]['key'];
					$temp[3]['name'] = $dw_array[$dwf]['name'];
					$temp[3]['order'] = $dw_array[$dwf]['order'];
				}elseif($value1 == 4 && $cnt >=  4){
					$temp[4]['key'] = $dw_array[$dwf]['key'];
					$temp[4]['name'] = $dw_array[$dwf]['name'];
					$temp[4]['order'] = $dw_array[$dwf]['order'];
				}elseif($value1 == 5 && $cnt ==  5){
					$temp[5]['key'] = $dw_array[$dwf]['key'];
					$temp[5]['name'] = $dw_array[$dwf]['name'];
					$temp[5]['order'] = $dw_array[$dwf]['order'];
				}
			}
			$dwf ++;
		}
		
		if($cnt >=  1){
			$update['dwf1_key'] = $temp[1]['key'];
			$update['dwf1'] = $temp[1]['name'];
			$update['dwf1_order'] = $temp[1]['order'];
		}
		if($cnt >=  2){
			$update['dwf2_key'] = $temp[2]['key'];
			$update['dwf2'] = $temp[2]['name'];
			$update['dwf2_order'] = $temp[2]['order'];
		}
		if($cnt >=  3){
			$update['dwf3_key'] = $temp[3]['key'];
			$update['dwf3'] = $temp[3]['name'];
			$update['dwf3_order'] = $temp[3]['order'];
		}
		if($cnt >=  4){
			$update['dwf4_key'] = $temp[4]['key'];
			$update['dwf4'] = $temp[4]['name'];
			$update['dwf4_order'] = $temp[4]['order'];
		}
		if($cnt >=  5){
			$update['dwf5_key'] = $temp[5]['key'];
			$update['dwf5'] = $temp[5]['name'];
			$update['dwf5_order'] = $temp[5]['order'];
		}
		
		update_option('csds_userRegAide_Options', $update);
		
		return $cnt; // testing
		
	}
	
	/**
	 * Displays options for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @handles action 'display_dw_options' line 245 user-registration-aide.php
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function dashboard_widget_options(){
		
		$options = get_option('csds_userRegAide_Options');
		$all_fields = $this->fields_array();
		$sel_fields = $this->selected_dw_fields_array();
		$order_array = $this->dash_widget_fields_array()
		?>
		<table class="adminPage_Dash">
			<tr colspan="2">
				<th colspan="3"><?php _e('Dashboard Widget Display Options', 'csds_userRegAide');?> </th>
			</tr>
			<tr>
				<td><?php _e('Choose to display or not display the dashboard widget on the WordPress Admin page: ', 'csds_userRegAide');?>
				<span title="<?php _e('Select this option to show the Creative Software Design Solutions Users table Dashboard Widget', 'csds_userRegAide');?>">
				<br/>
				<input type="radio" name="csds_dashWidgetDisplay" id="csds_dashWidgetDisplay" value="1" <?php
				if ($options['show_dashboard_widget'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'csds_userRegAide');?></span>
				<span title="<?php _e('Select this option NOT to show the Creative Software Design Solutions Users table Dashboard Widget',  'csds_userRegAide');?>">
				<input type="radio" name="csds_dashWidgetDisplay" id="csds_dashWidgetDisplay" value="2" <?php
				if ($options['show_dashboard_widget'] == 2) echo 'checked' ;?> /> <?php _e('No', 'csds_userRegAide'); ?></span>
				<br/>
				<div class="submit"><input type="submit" class="button-primary" name="dash_widget_display_option" value="<?php _e('Update Dashboard Widget Display Option', 'csds_userRegAide');?>"/></div>
				</td>
								
				<td><?php// adding option for user to choose own fields for dashboard widget ?>
				<p><?php _e('Here, you can select your own fields to show on the User Registration Aide Dashboard Widget.', 'csds_userRegAide');?></p>
				<p><b><?php _e('Note: You can only select a maximum of 5 fields due to the space constraints in the WordPress Dashboard! (Hold down the control key to select more than 1 field just like adding new fields to the registration form.)', 'csds_userRegAide');?></b></p>
				<p class="adminPage"><select name="selectedFields[]" id="csds_userRegMod_Select" title="<?php _e('You can only select up to 5 fields due to space limitations, just hold down the control key while selecting multiple fields.', 'csds_userRegAide');?>" size="8" multiple style="height:100px">
				<?php
				foreach($all_fields as $key1 => $value1){
					if(!empty($sel_fields)){
						if(in_array("$value1",$sel_fields)){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						} // end if
					}else{
						$selected = NULL;
					} // end if
					
				echo "<option value=\"$key1\" $selected >$value1</option>";
					
				} // end foreach ?>
				</select>
				</p>
				<div class="submit"><input type="submit" class="button-primary" name="dash_widget_fields_update" value="<?php _e('Update Dashboard Widget Fields', 'csds_userRegAide');?>"/></div>
				</td>
				
				<?php // setting field order for field in dashboard widget 
				$i = (int) 0;
				$fieldKey = (string) '';
				$fieldOrder = (int) 0;
				$fieldKey = (string) '';
				$fieldKeyUpper = (string) '';
				$i = count($sel_fields);
				$cnt = (int) 1; 
				// Table for field order ?>
				<td>
				<br/>
				<table class="newFields1">
				<tr>
				<th colspan="2"><?php _E('Set Dashboard Widget Field Order', 'csds_userRegAide');?></th>
				</tr>
				<tr>
				<th><?php _e('Dashboard Widget Field Name: ', 'csds_userRegAide');?></th>
				<th><?php _e('Current Field Order: ', 'csds_userRegAide');?></th>
				</tr><?php
				if(!empty($sel_fields)){
					foreach($sel_fields as $skey => $svalue){ ?>
					<tr>
						<td class="fieldName"> <?php
							$fieldKeyUpper = strtoupper($svalue);
							echo '<label for="'.$skey.'"><b>'.$fieldKeyUpper.'</b></label>';
							//Changed from check box to label here ?>
						</td>
						<td class="fieldOrder">
							<select  class="fieldOrder" name="csds_editDWFieldOrder[]" title="<?php __('Make sure that there are no duplicate field order numbers, like two fields having number 2 for their order!', 'csds_userRegAide');?>">
							<?php
							for($ii = 1; $ii <= $i; $ii++){
								//if($ii == $cnt){
									$fieldOrder = $order_array[$cnt]['order'];
									$fieldKey = $order_array[$cnt]['key'];
								//}
								if($ii == $fieldOrder){
									echo '<option selected="'.$fieldOrder.'" >'.$fieldOrder.'</option>';
								}else{
									echo '<option value="'.$ii.'">'.$ii.'</option>';
								}									
							}?>
							</select>
						</td>
					</tr>
						<?php
						$cnt ++;
					}
				echo '</table>';
				} ?>
				
				<div class="submit"><input type="submit" class="button-primary" name="dash_widget_field_order_update" value="<?php _e('Update Dashboard Widget Field Order', 'csds_userRegAide');?>"/></div>
					
				</td>
			</tr>
			
		</table> <?php
	} // end function
	
	/**
	 * Updates display option for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @handles action 'update_dw_display_options' line 246 user-registration-aide.php
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_dashboard_widget_options(){
		
		$update = get_option('csds_userRegAide_Options');
		$update['show_dashboard_widget'] = $_POST['csds_dashWidgetDisplay'];
		update_option("csds_userRegAide_Options", $update);
		echo '<div id="message" class="updated fade"><p class="my_message">'. __('Dashboard Widget Fields Display Option Updated Successfully!', 'csds_userRegAide') .'</p></div>';					//Report to the user that the data has been updated successfully
		
	} // end function
	
	/**
	 * Updates selected fields for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @handles action 'update_dw_field_options' line 247 user-registration-aide.php
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_dashboard_widget_fields(){
		$results = array();
		$fields = array();
		$update = array();
		$cnt = (int) 1;
		$errors = (int) 0;
		$err_message = (string) '';
		$update = get_option('csds_userRegAide_Options');
		$fields = $this->fields_array();
		$fcnt = (int) 1;
		
		if(!empty($_POST['selectedFields'])){
		
			$results = $_POST['selectedFields'];
			$fcnt = count($results);
			foreach($results as $key => $value){
				foreach($fields as $key1 => $value1){
					if($cnt == 1 && $fcnt >= 1){
						if($value == $key1){
							$update['dwf1_key'] = $key1;
							$update['dwf1'] = $value1;
							$update['dwf1_order'] = $cnt;
						}
					}else{
						
					}
					if($cnt == 2 && $fcnt >= 2){
						if($value == $key1){
							$update['dwf2_key'] = $key1;
							$update['dwf2'] = $value1;
							$update['dwf2_order'] = $cnt;
						}
					}elseif($fcnt <= 1){
						$update['dwf2_key'] = '';
						$update['dwf2'] = '';
						$update['dwf2_order'] = '';
					}
					if($cnt == 3 && $fcnt >= 3){
						if($value == $key1){
							$update['dwf3_key'] = $key1;
							$update['dwf3'] = $value1;
							$update['dwf3_order'] = $cnt;
						}
					}elseif($fcnt <= 2){
						$update['dwf3_key'] = '';
						$update['dwf3'] = '';
						$update['dwf3_order'] = '';
					}
					if($cnt == 4 && $fcnt >= 4){
						if($value == $key1){
							$update['dwf4_key'] = $key1;
							$update['dwf4'] = $value1;
							$update['dwf4_order'] = $cnt;
						}
					}elseif($fcnt <= 3){
						$update['dwf4_key'] = '';
						$update['dwf4'] = '';
						$update['dwf4_order'] = '';
					}
					if($cnt == 5 && $fcnt == 5){
						if($value == $key1){
							$update['dwf5_key'] = $key1;
							$update['dwf5'] = $value1;
							$update['dwf5_order'] = $cnt;
						}
					}elseif($fcnt <= 4){
						$update['dwf5_key'] = '';
						$update['dwf5'] = '';
						$update['dwf5_order'] = '';
					}
					if($cnt >= 6){
						$errors ++;
						$err_message = "You can only have a maximum of 5 fields to show in dashboard widget!";
					} // end if
				} // end foreach
				$cnt ++;			
			} // end foreach
			
			
		}elseif(empty($_POST['selectedFields'])){
			$errors ++;
			$err_message = "You haven't selected any fields to show in dashboard widget!";
		} // end if
		
		update_option("csds_userRegAide_Options", $update);
				
		if($errors == 0){
			echo '<div id="message" class="updated fade"><p class="my_message">'. __('Dashboard Widget Fields Updated Successfully!', 'csds_userRegAide') .'</p></div>';					//Report to the user that the data has been updated successfully
		}else{
			echo '<div id="message" class="updated fade"><p class="my_message">'. __($err_message, 'csds_userRegAide') .'</p></div>';
			//exit();
		} // end if
	}
	
	/**
	 * Updates fields order for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @handles action 'update_dw_field_order' line 248 user-registration-aide.php
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_dashboard_widget_field_order(){
		$errors = (int) 0;
		$err_message = (string) '';
		$results = array();
		$dwf_cnt = (int) 1;
		$message = (string) '';
		$results = $_POST['csds_editDWFieldOrder'];
		$update = get_option('csds_userRegAide_Options');
		
		foreach($results as $key => $value){
			foreach($results as $key1 => $value1){
				if($key != $key1 && ($value == $value1)){
					$errors ++;
					$err_message = 'Duplicate Field Orders, can\'t assign two fields the same order number! Please try again!';
					break;
				}
			}
		}
		
		if($errors == 0){
			foreach($results as $rkey => $rname){
				if($dwf_cnt == 1){
					$update['dwf1_order'] = $rname;
				}elseif($dwf_cnt == 2){
					$update['dwf2_order'] = $rname;
				}elseif($dwf_cnt == 3){
					$update['dwf3_order'] = $rname;
				}elseif($dwf_cnt == 4){
					$update['dwf4_order'] = $rname;
				}elseif($dwf_cnt == 5){
					$update['dwf5_order'] = $rname;
				}
				$dwf_cnt ++;
			}
			update_option('csds_userRegAide_Options', $update);
			$this->update_dash_widget_fields_order();
		}
		
		if($errors == 0){
			echo '<div id="message" class="updated fade"><p class="my_message">'. __('Dashboard Widget Fields Order Updated Successfully!', 'csds_userRegAide') .'</p></div>';					//Report to the user that the data has been updated successfully
		}else{
			echo '<div id="message" class="updated fade"><p class="my_message">'. __($err_message, 'csds_userRegAide') .'</p></div>';
			//exit();
		} // end if
	}
	
	/**
	 * Adds stylesheets for dashboard widget to wp admin page for news & update information
	 * @since 1.3.0
	 * @handles action 'admin_print_styles' line 218 user-registration-aide.php
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function csds_dashboard_widget_style(){
		wp_register_style('user_regAide_admin_dash_style', CSS_PATH.'/admin-dash.css', false);
		wp_enqueue_style('user_regAide_admin_dash_style');
	}
	
	
} // end class