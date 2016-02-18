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

**/

function bex_format_bytes($bytes, $precision = 2) { 
	// Thanks to PHP.Net for this piece of code...
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow);
    
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function bex_store_token($token, $name)
{
	if ($name == 'access') {
		update_option('bex_dropbox_token',serialize($token));
	} else {
		update_option('bex_dropbox_temp_token',serialize($token));
	}
}

function bex_load_token($name)
{
	if ($name == 'access') {
		return @unserialize(get_option('bex_dropbox_token'));
	} else {
		return @unserialize(get_option('bex_dropbox_temp_token'));
	}
}

function bex_delete_token($name)
{
	if ($name == 'access') {
		delete_option('bex_dropbox_token');
	} else {
		delete_option('bex_dropbox_temp_token');
	}
}

function bex_handle_dropbox_auth($dropbox)
{

	// first try to load existing access token
	$access_token = bex_load_token("access");
	if(!empty($access_token)) {
		if(!empty($_GET['bex_reset'])) // are we performing a dropbox connection reset?
		{
			bex_delete_token("access");
			return 2;
		}
		$dropbox->SetAccessToken($access_token);
		return 0;
	}
	elseif(!empty($_GET['auth_callback']) && $_GET['not_approved'] != 'true') // are we coming from dropbox's auth page?
	{
		// then load our previosly created request token
		$request_token = bex_load_token($_GET['oauth_token']);
		if(empty($request_token)) die('Dropbox request token not found!');
	
		// get & store access token, the request token is not needed anymore
		$access_token = $dropbox->GetAccessToken($request_token);	
		bex_store_token($access_token, "access");
		bex_delete_token($_GET['oauth_token']);
		return 0;
	}

	elseif(!$dropbox->IsAuthorized())
	{
		// redirect user to dropbox auth page
		$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?page=blighty-explorer-plugin&auth_callback=1";
		$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
		$request_token = $dropbox->GetRequestToken();
		bex_store_token($request_token, $request_token['t']);
		echo("Dropbox authentication is required. <a href='$auth_url'>Authenticate with Dropbox.</a>");
		return 3;
	} else {
		return 1;
	}
}

?>
