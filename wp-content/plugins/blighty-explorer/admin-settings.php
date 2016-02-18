<?php
/**

Copyright (C) 2015 Chris Murfin

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

v1.9.3

**/

function bex_plugin_prequesites() {
	global $current_user;
	$userid = $current_user->ID;

	// If "Dismiss" link has been clicked, user meta field is added
	if ( isset( $_GET['dismiss_me'] ) && 'yes' == $_GET['dismiss_me'] ) {
		add_user_meta( $userid, 'bex_ignore_warning_notice', 'yes', true );
		return;
	}

	global $pagenow;

    if ($pagenow != 'plugins.php' && $pagenow != 'admin.php') {
        return;
    }

    // Only show this notice if user hasn't already dismissed it...
    if ( get_user_meta( $userid, 'bex_ignore_warning_notice' ) ) {
    	return;
    }

	$slug = 'svg-vector-icon-plugin';
	$path = $slug .'/wp-svg-icons.php';
	$plugins = get_plugins();

	// This plugin previously used the WP SVG Icons plugin. With version 1.3.0, it is no longer required.
	// Check to see if it exists and prompt to uninstall. The prompt can be dismissed.

	if (empty($plugins[$path])) {
		return;
	}

	$dismiss_url = $_SERVER['REQUEST_URI'];
	if (strpos($dismiss_url,'?') > 0) {
		$dismiss_url .= '&';
	} else {
		$dismiss_url .= '?';
	}
	$dismiss_url .= 'dismiss_me=yes';

	echo '<div class="update-nag"><p>The <b>' . BEX_PLUGIN_NAME .'</b> plugin used to require the <b>WP SVG Icons</b> plugin. If you\'re not using <b>WP SVG Icons</b> elsewhere, it can be safely removed. <a href="' .$dismiss_url .'">Dismiss</a><br /><br />';

	if (is_plugin_active($path)) {
		$deactivate_url = wp_nonce_url(admin_url('plugins.php?action=deactivate&plugin=' .$path), 'deactivate-plugin_' .$path );
		echo '<a href="' .$deactivate_url .'">Deactivate Plugin</a>';
	}

	echo '</p></div>';
}

function bex_init() {
	register_setting( 'bex_option-options', 'bex_folder');
	register_setting( 'bex_option-options', 'bex_show_moddate');
	register_setting( 'bex_option-options', 'bex_show_size');
	register_setting( 'bex_option-options', 'bex_download');
	register_setting( 'bex_option-options', 'bex_sort_dir');
	register_setting( 'bex_option-options', 'bex_noauth_uploads');
	register_setting( 'bex_option-options', 'bex_email_upload');
	register_setting( 'bex_option-options', 'bex_allow_uploads');
	register_setting( 'bex_option-auth', 'bex_folder_auth', 'bex_folder_auth_validate');
	register_setting( 'bex_option-options-bts', 'bex_dropbox_token' );
	register_setting( 'bex_option-options-bts', 'bex_dropbox_temp_token' );
}

function bex_setup_menu(){
	add_options_page( 'Blighty Explorer', 'Blighty Explorer', 'manage_options', 'blighty-explorer-plugin', 'bex_admin_settings' );
}

	add_filter( 'plugin_action_links_blighty-explorer/blighty-explorer.php', 'bex_add_action_links' );

function bex_add_action_links ( $links ) {
	$url = '<a href="' . admin_url( 'options-general.php?page=blighty-explorer-plugin' ) . '">Settings</a>';
	$mylinks = array( $url );
	return array_merge( $mylinks, $links );
}

function bex_admin_settings(){
	global $dropbox;
?>
	<div class="wrap">
		<h2><?php echo BEX_PLUGIN_NAME; ?> version <?php echo BEX_PLUGIN_VERSION; ?></h2>
		<?php
		if ($_GET['not_approved'] == 'true') {
			echo '<div class="error"><p>Error authenticating Dropbox.</p></div>';
		} elseif (isset($_GET['auth_callback'])) {
			echo '<div class="updated"><p>Dropbox connection successful.</p></div>';
		} else if (isset($_GET['bex_reset'])) {
			echo '<div class="updated"><p>Dropbox connection has been reset.</p></div>';
		}

		?>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar">
					<div id="side-sortables" class="meta-box-sortables ui-sortable" style="position:relative;">
						<div class="postbox">
							<h3>Dropbox Information</h3>
							<div class="inside">
		<?php
		$rc = bex_handle_dropbox_auth($dropbox);
		if ($rc == 0) {
			$info = $dropbox->GetAccountInfo();
			$quota = $info->quota_info->quota;
			$used = $info->quota_info->normal + $info->quota_info->shared;
			$pc = $used / $quota * 100;
			echo '<b>Account:</b> ' .$info->display_name .'<br />';
			echo '<b>Quota:</b> ' .bex_format_bytes($quota) .'<br />';
			echo '<b>Used:</b> ' .bex_format_bytes($used) .' (' .sprintf("%.1f%%", $pc) .')<br /><br />';
			echo '<a href="?page=blighty-explorer-plugin&bex_reset=1">Reset Dropbox connection.</a><br />';

		} elseif ($rc == 2) {
			$rc = bex_handle_dropbox_auth($dropbox);
		}
		?>
							</div>
						</div>
						<div class="postbox">
							<h3>Support This Plugin</h3>
							<div class="inside">
								If you find this plugin useful, please consider supporting it and future development. Thank you.<br /><br />
								<div align="center">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
									<input type="hidden" name="cmd" value="_donations">
									<input type="hidden" name="business" value="2D9PDAS9FDDCA">
									<input type="hidden" name="lc" value="US">
									<input type="hidden" name="item_name" value="Blighty Explorer Plugin">
									<input type="hidden" name="item_number" value="BEP001A">
									<input type="hidden" name="button_subtype" value="services">
									<input type="hidden" name="no_note" value="1">
									<input type="hidden" name="no_shipping" value="1">
									<input type="hidden" name="currency_code" value="USD">
									<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donateCC_LG.gif:NonHosted">
									<input type="hidden" name="on0" value="website">
									<input type="hidden" name="os0" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
									<input type="radio" name="amount" value="5">$5&nbsp;
									<input type="radio" name="amount" value="10">$10&nbsp;
									<input type="radio" name="amount" value="20">$20&nbsp;
									<input type="radio" name="amount" value="50">$50&nbsp;
									<input type="radio" name="amount" value="">Other<br /><br />
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
									</form>
								</div>
							</div>
						</div>
						<div class="postbox">
							<h3>Technical Support</h3>
							<div class="inside">
								If you need technical support or would like to see a new featured implemented, please provide your feedback via the <a href="https://wordpress.org/support/plugin/blighty-explorer">WordPress Plugin Forums</a>.
							</div>
						</div>
					</div>
				</div>

				<div id="post-body-content" class="has-sidebar-content">
					<div class="meta-box-sortables">
						<div class="postbox">
							<h3>Configuration and Usage</h3>
							<div class="inside">
								<ol>
									<li>Connect this plugin to your Dropbox account (see top right).</li>
									<li>This will create a subfolder called <b>Blighty Explorer</b> in your <b>Apps</b> folder within Dropbox.</li>
									<li>Place your folders and files you wish to share with this WordPress installation inside the <b>Apps/Blighty Explorer</b> subfolder.</li>
									<li>Use the shortcode <b>[bex_folder]</b> in your post or page to display a folder structure / file navigator.</li>
									<li>Use the shortcode <b>[bex_upload]</b> in your post or page to display a file upload dialog.</li>
								</ol>
							</div>
						</div>
						<?php if ($dropbox->IsAuthorized()) { ?>
						<div class="postbox">
							<h3>Options</h3>
							<div class="inside">
								<form method="post" action="options.php">
								<?php

								settings_fields('bex_option-options');

								if ( get_option('bex_show_moddate') == '1' ) {
									$checkedModDate = 'checked ';
								} else {
									$checkedModDate = '';
								}

								if ( get_option('bex_show_size') == '1' ) {
									$checkedSize = ' checked';
								} else {
									$checkedSize = '';
								}

								if ( get_option('bex_download') == '1' ) {
									$checkedDownload = ' checked';
								} else {
									$checkedDownload = '';
								}

								if ( get_option('bex_email_upload') == '1' ) {
									$checkedEmail = ' checked';
								} else {
									$checkedEmail = '';
								}

								if ( get_option('bex_allow_uploads') == '1' ) {
									$checkedAllowUploads = ' checked';
								} else {
									$checkedAllowUploads = '';
								}

								if ( get_option('bex_noauth_uploads') == '1' ) {
									$checkedNoAuthUploads = ' checked';
								} else {
									$checkedNoAuthUploads = '';
								}

								if ( get_option('bex_nav_type') == '1' ) {
									$navType0Checked = '';
									$navType1Checked = ' checked';
								} else {
									$navType0Checked = ' checked';
									$navType1Checked = '';
								}

								if ( get_option('bex_sort_dir') == 'D' ) {
									$checkedSortDirA = '';
									$checkedSortDirD = ' checked';
								} else {
									$checkedSortDirA = ' checked';
									$checkedSortDirD = '';
								}

								echo 'By default, folders and files are shared from your <strong>Dropbox Folder/Apps/Blighty Explorer</strong>. ';
								echo 'If you want to share a subfolder under <strong>Apps/Blighty Explorer</strong>, set it here as the root folder. ';
								echo 'This allows you to share different subfolders on different WordPress installations.<br /><br />';
								echo '<b>Root folder:</b><br />';

								$files = $dropbox->GetFiles('/');
								if (count($files) == 0) {
									echo '<input type="radio" name="bex_folder" value="/" checked />/<br />';
								} else {
									foreach ($files as $file) {
										if ($file->is_dir && $filePath != BEX_UPLOADS_FOLDER) {
											if ($file->path == get_option('bex_folder')) {
												$checkedFolder = ' checked';
											} else {
												$checkedFolder = '';
											}
											echo '<input type="radio" name="bex_folder" value="' .$file->path .'"'.$checkedFolder .' />' .$file->path .'<br />';
										}
									}
								}

								echo '<br />';
								echo '<b>Show modification date:</b>&nbsp;<input type="checkbox" name="bex_show_moddate" value="1"' .$checkedModDate .' />&nbsp;';
								echo '<b>Show size:</b>&nbsp;<input type="checkbox" name="bex_show_size" value="1"' .$checkedSize .' /><br /><br />';
								echo '<b>Default filename sort:</b>&nbsp;<input type="radio" name="bex_sort_dir" value="A"' .$checkedSortDirA .' />Ascending&nbsp;';
								echo '<input type="radio" name="bex_sort_dir" value="D"' .$checkedSortDirD .' />Descending<br /><br />';
								echo '<b>Download Files:</b>&nbsp;<input type="checkbox" name="bex_download" value="1"' .$checkedDownload .' /> Files can either be shown in the browser (default) or selected to download.<br />';
								echo '<br />';
								echo 'File uploads via this plugin will be stored in the folder <strong>' .BEX_UPLOADS_FOLDER .'</strong> under the <strong>Root folder</strong> above.<br /><br />';
								echo 'If you want to allow uploads into the folder that the user has navigated to, then check the <strong>Allow Uploads in Active Folder</strong> option below.<br /><br />';
								echo '<b>Allow Uploads in Active Folder:</b>&nbsp;<input type="checkbox" name="bex_allow_uploads" value="1"' .$checkedAllowUploads .' /><br /><br />';
								echo '<b>Allow uploads to Dropbox when the WordPress user is not logged in:</b>&nbsp;<input type="checkbox" name="bex_noauth_uploads" value="1"' .$checkedNoAuthUploads .' /><br /><br />';
								echo '<b>Email admin on upload:</b>&nbsp;<input type="checkbox" name="bex_email_upload" value="1"' .$checkedEmail .' />';
								echo '&nbsp;Check this box to receive an email every time a user uploads a file.<br />';

								submit_button();

								?>
								</form>
							</div>
						</div>
						<div class="postbox">
							<h3>Access Control</h3>
							<div class="inside">
								<form method="post" action="options.php">
								<?php
									global $wp_roles;
									$roles = $wp_roles->get_names();
									sort($roles);
									settings_fields('bex_option-auth');

									$i = 1;

									echo 'Use these options to allow only logged-in WordPress users with specific roles access to individual folders under the <strong>Root Folder</strong>.<br /><br />';
									echo 'To restrict access to the plugin completely, use a plugin such as <a href="https://wordpress.org/plugins/user-specific-content" target="_blank">User Specific Content</a> in conjunction with this one.<br /><br />';
									echo '<b>Available Roles:</b><br />';
									echo '<input type="checkbox" name="role_0" value="' .BEX_ANONYMOUS .'" />' .BEX_ANONYMOUS .'&nbsp;';
									foreach ($roles as $role) {
										echo '<input type="checkbox" name="role_' .$i .'" value="' .$role .'" />' .$role .' ';
										$i++;
									}
									echo '<br /><br />';
									echo '<b>Set on the following top-level folders:</b><br />';

									$folderAuth = get_option('bex_folder_auth');

									if (!$folderAuth) {
										$folderAuth = array();
									}

								  $rootFolder = trailingslashit(get_option('bex_folder'));
									$files = $dropbox->GetFiles($rootFolder);

									$i = 0;
									foreach ($files as $file) {

										$filePath = $file->path;
										$filePathWorking = $filePath;

										$len = strlen($rootFolder);
										if (strcasecmp(substr($filePath,0,$len),$rootFolder) == 0) {
											$filePath = substr($filePath,$len);
										}
										if ($file->is_dir && $filePath != BEX_UPLOADS_FOLDER) {
											echo '<input type="checkbox" name="bex_folder_auth_' .$i .'" value="' .$file->path .'">&nbsp;<b>' .$filePath .'</b> - (';
											if ($folderAuth[trailingslashit($file->path)] == '') {
												echo BEX_ANONYMOUS;
											} else {
												echo $folderAuth[trailingslashit($file->path)];
											}
											$i++;
											echo ')<br />';
										}
									}

									submit_button();
								?>
								</form>
							</div>
						</div>
						<?php } ?>
				</div>
				<?php echo BEX_PLUGIN_NAME; ?> version <?php echo BEX_PLUGIN_VERSION; ?> by <a href="http://blighty.net" target="_blank">Blighty</a>
			</div>

	</div>
<?php
}

/* Remove code in 1.7.0
function bex_folder_validate($input){

	if (preg_match('#^(\/)?((\w)+(\.| |\&|\-|\(|\))*(\w)*(\/)*)*(\/)?$#',$input)) {
		$output = $input;
		// Valid path, but add / if not at front...
		if (substr($input,0,1) != '/') {
			$output = '/' .$output;
		}
	} else {
		add_settings_error( 'mbex_option-options', 'invalid-folder', 'You have entered an invalid root folder.', "error" );
		$output = "";
	}

	return $output;

}
*/

function bex_folder_auth_validate($input) {
	$role = '';
	foreach ($_POST as $field => $value) {
		if (substr($field,0,5) == 'role_') {
				$role .= $value .', ';
		}
	}

	$role = substr($role,0,strlen($role)-2);

	$auth = get_option('bex_folder_auth');

	foreach ($_POST as $field => $value) {
		if (substr($field,0,16) == 'bex_folder_auth_') {
				$auth[trailingslashit($value)] = $role;
		}
	}

	return $auth;
}

?>
