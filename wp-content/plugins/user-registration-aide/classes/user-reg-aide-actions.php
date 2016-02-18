<?php

/**
 * User Registration Aide - Actions & Filters
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.5.0.2
 * Since Version 1.3.0
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/*
 * Couple of includes for functionality
 *
 * @since 1.2.0
 * @updated 1.3.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

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

class CSDS_URA_ACTIONS
{

	public static $instance;

	public function __construct() {
		$this->CSDS_URA_ACTIONS();
	}
		
	function CSDS_URA_ACTIONS() { //constructor
			
		self::$instance = $this;
	}
	
	/**
	 * Creates array for menu tabs titles
	 *
     * @since 1.3.0
     * @updated 1.4.0.0
	 * @returns array $tabs - array of menu tabs Titles for plugin
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function menu_tabs_array(){
		$tabs = array(
			'registration_fields' 			=> 'Registration Fields',
			'edit_new_fields' 				=> 'Edit New Fields',
			'registration_form_options' 	=> 'Registration Form Options', 'registration_form_css_options' => 'Registration Form Messages & CSS Options',
			'custom_options'				=> 'Custom Options'
		);
		
		return $tabs;
	}
	
	/**
	 * Creates array for menu tabs titles
	 *
     * @since 1.4.0.0
     * @updated 1.5.0.2
	 * @returns array $tabs - array of menu tabs Titles for plugin
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function menu_titles_array(){
		$tabs = array(
			'registration_fields' 			=> 'Set Dashboard Widget Options & Select Fields to add to Registration Form or Add New Custom Fields Here',
			'edit_new_fields' 				=> 'Edit New Fields For Registration Form/User Profile Here Like Field Order, Field Titles Or Delete Fields',
			'registration_form_options' 	=> 'Customize Bottom Registration Form Message, Password Strength Options, Custom Redirects, Agreement Message, Anti-Bot Spammer & Title for Profile Pages Here', 'registration_form_css_options' => 'Customize Registration Form Messages & Custom Registration Form CSS Options Here',
			'custom_options'				=> 'Password Change Settings Options, Change Display Name Options or URA Admin Page Style Sheet Settings Here'
		);
		
		return $tabs;
	}
	
	/**
	 * Creates array for menu tabs links
	 *
     * @since 1.3.0
     * @updated 1.4.0.0
	 * @returns array $menu_links - array of links for menu tabs
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function menu_links_array(){
		$menu_links = array(
			'registration_fields' 			=> 'admin.php?page=user-registration-aide', 'edit_new_fields' 				=> 'admin.php?page=edit-new-fields', 'registration_form_options' 	 => 'admin.php?page=registration-form-options', 'registration_form_css_options' => 'admin.php?page=registration-form-css-options',
			'custom_options' 				=> 'admin.php?page=custom-options'
		);
		
		return $menu_links;
	}
	
	/**
	 * Shows tabs menu at top of options pages for easier user access -- wont work for different pages so i do it separately on each page
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.3.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function options_tabs_page($current_page){
		
		$tabs = $this->menu_tabs_array();  // line 71 &$this
		$menu_links = $this->menu_links_array(); // line 88 &$this
		$tab_links = array();
		$titles = $this->menu_titles_array();
		foreach($menu_links as $menu_key => $menu_name){
			foreach($tabs as $tab_key => $tab_name){
			
				if($menu_key == $tab_key && $tab_key == $current_page ){
					$tab_links[$tab_key] = '<a class="nav-tab nav-tab-active" title="'.$titles[$tab_key].'" href="'.admin_url($menu_name).'">'.$tab_name.'</a>';
				}elseif($menu_key == $tab_key){
					$tab_links[$tab_key] = '<a class="nav-tab" title="'.$titles[$tab_key].'" href="'.admin_url($menu_name).'">'.$tab_name.'</a>';
				}
			}
		}
		echo '<h2>';
		foreach($tab_links as $link_key => $link_name){
			echo $link_name;
		}
		echo '</h2>';
	
	}
	
	/**
	 * One action for adding admin page wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function start_wp_wrapper( $tab, $form, $h2, $span, $nonce ){
		?>
		<div id="wpbody">
			<div class="wrap"> <?php
			do_action( 'create_tabs', $tab ); // Line 255 user-registration-aide.php
			?>
				<form method="<?php echo $form[0]; ?>" name="<?php echo $form[1]; ?>" id="<?php echo $form[1]; ?>">
				<h2 class="<?php echo $h2[0]; ?>"><?php _e( $h2[1], $h2[2] ); ?></h2>
					<div id="poststuff">
					<?php  //Form for dashboard widget options ?>
						<div class="stuffbox">
						<span class="<?php echo $span[0]; ?>"><?php _e( $span[1], $span[2] ); ?> </span>
							<div class="inside">
							<?php
							wp_nonce_field( $nonce[0], $nonce[1] );
	}
	
	/**
	 * One action for ending admin page wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function end_wp_wrapper(){
		?>
							<div class="clear"></div></div> <?php // inside ?>
						<div class="clear"></div></div> <?php // stuffbox ?>
						<?php
						do_action('show_support');
						?>
					<div class="clear"></div></div> <?php // poststuff ?>
				</form>
			<div class="clear"></div></div> <?php // wrap ?>
		<div class="clear"></div></div> <?php // wpbody ?>
		
		<?php
		
	}
	
	/**
	 * One action for adding admin page mini wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function start_mini_wp_wrapper( $span ){
		?>
		<div class="stuffbox">
			<span class="<?php echo $span[0]; ?>"><?php _e( $span[1], $span[2] );?></span>
					<div class="inside">
		<?php
	}
	
	/**
	 * One action for ending admin page wrappers
	 * @handles action for menu tabs 'create tabs' $ura line 246
     * @since 1.4.0.0
     * @updated 1.4.0.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function end_mini_wp_wrapper(){
		?>
			<div class="clear"></div></div> <?php // stuffbox ?>
		<div class="clear"></div></div> <?php // inside ?>
		<?php
	}
	
	/**
	 * Shows support section for options pages
	 * @handles custom action for show support 'show_support' $ura line 240
     * @since 1.3.0
     * @updated 1.3.0
	 * @accepts $current_page (current menu page)
     * @access private
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function show_support_section(){ 
		$options = get_option('csds_userRegAide_Options');
		$span = array( 'regForm', 'Plugin Support and Configuration Information:', 'csds_userRegAide' );
		do_action( 'start_mini_wrap',  $span ); ?>
		
			<table class="csds_support">
			<tr>
			<th class="csds_support_links" colspan="4"><a href="http://creative-software-design-solutions.com" target="_blank">Creative Software Design Solutions</a></th>
			</tr>
			<tr>
			<th class="csds_support_th" colspan="4"><?php _e('Please show your support & appreciation and help us out with a donation!', 'csds_userRegAide');?></th>
			</tr>
			<tr>
			<td>
			<p><?php _e('Show Plugin Support: ', 'csds_userRegAide');?><input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="1" <?php
				if ($options['show_support'] == 1) echo 'checked' ;?>/> Yes
				<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="2"<?php
				if ($options['show_support'] == 2) echo 'checked' ;?>/> No
			
			<div class="submit">
			<input name="csds_userRegAide_support_submit" id="csds_userRegAide_support_submit" lang="publish" class="button-primary" value="Update" type="Submit" />
			</div>
			</td>
			<td>
			<h2 class="support"><?php _e('Plugin Configuration Help', 'csds_userRegAide');?></h2>
			<?php
			echo '<ul>';
			echo '<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Plugin Page & Screenshots</a></li>';
			echo '</ul>';
			echo '</td>';
			echo '<td>';
			echo '<h2 class="support">'.__('Coming Soon 1.6.0.0 Lots of New Features!', 'csds_userRegAide').'</h2>';
			echo '<h2 class="support">'.__('Check Official Website', 'csds_userRegAide').'</h2>';
			echo '<ul>';
			echo '<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Check official website for live demo</a></li></ul></td>';?>
			<td>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="6BCZESUXLS9NN" />
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
				</form>
			</td>
			</tr>
			</table>
			
		<?php
		
	}
} // end class