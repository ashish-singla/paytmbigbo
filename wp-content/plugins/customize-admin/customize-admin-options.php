<?php

if ( ! defined( 'ABSPATH' ) ) die( 'Error!' );

// Create custom plugin settings menu
add_action( 'admin_menu', 'ca_create_menu' );
function ca_create_menu() {

	// Create a submenu page in the 'Settings' menu
	add_submenu_page( 'options-general.php', 'Customize Admin', 'Customize Admin', 'manage_options', 'customize-admin/customize-admin-options.php', 'ca_settings_page' );

	// Call register settings function
	add_action( 'admin_init', 'ca_register_settings' );
}

// Register the settings
function ca_register_settings() {
	register_setting( 'customize-admin-settings-group', 'ca_logo_file', 'ca_sanitisation_url' );
	register_setting( 'customize-admin-settings-group', 'ca_logo_url', 'ca_sanitisation_url' );
	register_setting( 'customize-admin-settings-group', 'ca_login_background_color', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_custom_css', 'ca_sanitisation_css' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_meta_generator', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_meta_rsd', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_meta_wlw', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_rss_links', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_dashboard_quick_press', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_dashboard_activity', 'ca_sanitisation' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_dashboard_wordpress_news', 'ca_sanitisation' );
}

function ca_sanitisation ( $input ) { 	
	$input = sanitize_text_field( $input );
	return $input;
} 

function ca_sanitisation_url ( $input ) { 	
	$input = strip_tags( $input );
	return $input;
} 

function ca_sanitisation_css ( $input ) { 	
	$ca_sanitisation_allowed_html = array();
	$input = wp_kses( $input, $ca_sanitisation_allowed_html );
	return $input;
}

function ca_admin_scripts() {
	wp_register_script( 'ca-color-picker', WP_PLUGIN_URL . '/customize-admin/js/color-picker.js', array( 'jquery' ) );
	wp_enqueue_script( 'ca-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	wp_enqueue_media();
	wp_register_script( 'ca-media-upload', WP_PLUGIN_URL . '/customize-admin/js/media-upload.js', array( 'jquery' ) );
	wp_enqueue_script( 'ca-media-upload' );
}

function ca_admin_styles() {
	wp_enqueue_style( 'wp-color-picker' );
}

// Only include media uploader scripts and styles on custmize options page
if ( isset( $_GET['page'] ) && $_GET['page'] == 'customize-admin/customize-admin-options.php' ) {
	add_action( 'admin_print_scripts', 'ca_admin_scripts' );
	add_action( 'admin_print_styles', 'ca_admin_styles' );
}

function ca_settings_page() { ?>
	<div class="wrap">
	<h2><?php _e( 'Customize Admin Options', 'customize-admin-plugin' ); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'customize-admin-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Custom Logo Link', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_logo_url">
					<input type="text" id="ca_logo_url" name="ca_logo_url" value="<?php echo esc_url ( get_option( 'ca_logo_url' ) ); ?>" />
					<p class="description"><?php _e( 'If not specified, clicking on the logo will return you to the homepage.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Custom Logo', 'customize-admin-plugin' ); ?></th>
				<td><label for="upload_image">
					<input id="upload_image" type="text" size="36" name="ca_logo_file" value="<?php echo esc_url ( get_option( 'ca_logo_file' ) ); ?>" />
					<input id="upload_image_button" type="button" value="<?php _e( 'Choose Image', 'customize-admin-plugin' ); ?>" class="button" />
					<p class="description"><?php _e( 'Enter a URL or upload logo image. Maximum height: 70px, width: 310px.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Custom Login Background', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_login_background_color">
					<input type="text" id="ca_login_background_color" class="color-picker" name="ca_login_background_color" value="<?php echo get_option( 'ca_login_background_color' ); ?>" />
					<p class="description"></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Remove Generator Meta Tag', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_remove_meta_generator">
					<input id="ca_remove_meta_generator" type="checkbox" name="ca_remove_meta_generator" value="1" <?php checked( '1', get_option( 'ca_remove_meta_generator' ) ); ?> />
					<p class="description"><?php _e( 'Selecting this option removes the generator meta tag from the html source.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Remove RSD Meta Tag', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_remove_meta_rsd">
					<input id="ca_remove_meta_rsd" type="checkbox" name="ca_remove_meta_rsd" value="1" <?php checked( '1', get_option( 'ca_remove_meta_rsd' ) ); ?> />
					<p class="description"><?php _e( 'Selecting this option removes the RSD meta tag from the html source.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Remove WLW Meta Tag', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_remove_meta_wlw">
					<input id="ca_remove_meta_wlw" type="checkbox" name="ca_remove_meta_wlw" value="1" <?php checked( '1', get_option( 'ca_remove_meta_wlw' ) ); ?> />
					<p class="description"><?php _e( 'Selecting this option removes the WLW meta tag from the html source.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Remove RSS feed links', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_remove_rss_links">
					<input id="ca_remove_rss_links" type="checkbox" name="ca_remove_rss_links" value="1" <?php checked( '1', get_option( 'ca_remove_rss_links' ) ); ?> />
					<p class="description"><?php _e( 'Selecting this option removes the RSS feed link from the html source.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Remove Dashboard Widgets', 'customize-admin-plugin' ); ?></th>
				<td><label for="ca_remove_dashboard_quick_press">
					<input id="ca_remove_dashboard_quick_press" type="checkbox" name="ca_remove_dashboard_quick_press" value="1" <?php checked( '1', get_option( 'ca_remove_dashboard_quick_press' ) ); ?> /> <?php _e( 'Quick Draft', 'customize-admin-plugin' ); ?>
					<p class="description"><?php _e( 'Selecting this option removes the Quick Press dashboard widget.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><label for="ca_remove_dashboard_activity">
					<input id="ca_remove_dashboard_activity" type="checkbox" name="ca_remove_dashboard_activity" value="1" <?php checked( '1', get_option( 'ca_remove_dashboard_activity' ) ); ?> /> <?php _e( 'Activity', 'customize-admin-plugin' ); ?>
					<p class="description"><?php _e( 'Selecting this option removes the activity dashboard widget.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><label for="ca_remove_dashboard_wordpress_news">
					<input id="ca_remove_dashboard_wordpress_news" type="checkbox" name="ca_remove_dashboard_wordpress_news" value="1" <?php checked( '1', get_option( 'ca_remove_dashboard_wordpress_news' ) ); ?> /> <?php _e( 'WordPress Site News', 'customize-admin-plugin' ); ?>
					<p class="description"><?php _e( 'Selecting this option removes the WordPress Site News dashboard widget.', 'customize-admin-plugin' ); ?></p>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Custom CSS', 'customize-admin-plugin' ); ?></th>
				<td>
					<textarea id="ca_custom_css" name="ca_custom_css" cols="70" rows="5"><?php echo get_option( 'ca_custom_css' ); ?></textarea>
					<p class="description"><?php _e( 'Add your own css to the WordPress dashboard.', 'customize-admin-plugin' ); ?></p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'customize-admin-plugin' ); ?>" />
		</p>
	</form>
	</div>
<?php }; ?>