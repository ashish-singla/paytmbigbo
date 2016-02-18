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

v1.9.1

**/

function bex_upload( $atts ) {

	$atts = shortcode_atts(
		array(
			'root' => get_option('bex_folder'),
		), $atts, 'bex_folder' );

	$rootFolder = trailingslashit($atts['root']);

	if (!empty($_GET["folder"])) {
		$folder = esc_attr($_GET["folder"]);
		$folder = ltrim($folder, ".");
	} else {
		$folder = "";
	}

	$out = '<form id="bexUpload" class="bex-upload-form" action="' .admin_url( 'admin-ajax.php' ) .'" method="post" enctype="multipart/form-data">';
	$out .= '  Select file to upload:';
	$out .= '  <input type="file" name="bexFile" id="bexFile">';
	$out .= '  <input type="submit" value="Upload" name="bexSubmit" id="bexSubmit" class="bex-button-submit">';
	$out .= '  <input type="hidden" name="bexFolder" id="bexFolder" value="' .$rootFolder .$folder .'">';
	$out .= '<br /><br /><div class="bex-progress">';
	$out .= '  <div class="bex-bar"></div >';
	$out .= '  <div class="bex-percent">0%</div >';
	$out .= '</div><br />';
  $out .= '<div id="bexStatus">Ready to upload.</div>';
	$out .= '</form>';

	return $out;
}

function bex_submission_processor_nopriv() {
	if (get_option('bex_noauth_uploads')) {
		bex_submission_processor();
	} else {
		echo 'No access.';
	}
	die();
}

function bex_submission_processor() {

	if(empty($_FILES["bexFile"])) {
		echo 'No file selected.';
		die();
	}

	global $dropbox;

	$access_token = bex_load_token("access");
	if(empty($access_token)) {
		echo 'Dropbox not authorized. Check the settings for Blighty Explorer (bex_upload - 1)';
		die();
	}

	$dropbox->SetAccessToken($access_token);
	if (!$dropbox->IsAuthorized()) {
		echo 'Dropbox not authorized. Check the settings for Blighty Explorer (bex_upload - 2)';
		die();
	}

	$rootFolder = trailingslashit(get_option('bex_folder'));
	if (get_option('bex_allow_uploads')) {
		$folder = esc_attr($_POST["bexFolder"]);
	} else {
		$folder = BEX_UPLOADS_FOLDER;
	}

	//$workingFolder = trailingslashit($rootFolder .$folder);
	$workingFolder = trailingslashit($folder);

	if (!empty($_POST['bexSubmit'])) {
		$dropbox->UploadFile($_FILES["bexFile"]["tmp_name"], $workingFolder .$_FILES["bexFile"]["name"], false);
		echo 'File uploaded.';
	}

	if (get_option('bex_email_upload')) {

    	if (is_user_logged_in()) {
			global $current_user;
    		get_currentuserinfo();
	    	$userLogin = $current_user->user_login;
    		$userEmail = $current_user->user_email;
    	} else {
    		$userLogin = 'anonymous';
	    	$userEmail = 'no email';
    	}

		$headers = 'From: ' .get_bloginfo('name') .' <' .get_bloginfo('admin_email') .'>' . "\r\n";
		$subj = '[' .get_bloginfo('name') .'] File Upload';
		$body = 'The file "' .$_FILES["bexFile"]["name"] .'" has just been uploaded to ' .$workingFolder
			  .' by ' .$userLogin .' (' .$userEmail .')';
		wp_mail( get_bloginfo('admin_email'), $subj, $body, $headers );
	}

	die();

}

?>
