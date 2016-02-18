<?php

/*
Plugin Name: Anything Popup
Description: This is a simple plugin to display the entered content in to unblockable popup window. popup will open by clicking the text or image button.
Author: Gopi.R
Version: 3.0
Plugin URI: http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
Author URI: http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
Donate link: http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("AnythingPopupTable", $wpdb->prefix . "AnythingPopup");

function AnythingPopup($pop_widget)
{
	global $wpdb, $wp_version;
	
	$sSql = "select * from ".AnythingPopupTable." where 1=1";
	if($pop_widget == "RANDOM" || $pop_widget == "")
	{
		$sSql = $sSql . " Order by rand()";
	}
	else
	{
		list($caption, $value) = split('[/.:]', $pop_widget);
		$value = substr($value,0,(strlen($value)-1));
		if(is_numeric(@$value)) 
		{
			$sSql = $sSql . " and pop_id=$value";
		}
	}
	
	$sSql = $sSql . " LIMIT 0,1";
	
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$data = $data[0];
		$pop_width = stripslashes($data->pop_width);
		$pop_height = stripslashes($data->pop_height);
		$pop_headercolor = stripslashes($data->pop_headercolor);
		$pop_bordercolor = stripslashes($data->pop_bordercolor);
		$pop_header_fontcolor = stripslashes($data->pop_header_fontcolor);
		$pop_title = stripslashes($data->pop_title);
		$pop_content = stripslashes($data->pop_content);
		$pop_content = str_replace("\n", "<br />", $pop_content);
		$pop_caption = stripslashes($data->pop_caption);
	?>
	<style type="text/css">
	#AnythingPopup_BoxContainer	{
		width:<?php echo $pop_width; ?>px;
		height:<?php echo $pop_height; ?>px;
		background:#FFFFFF;
		border:1px solid <?php echo $pop_bordercolor; ?>;
		padding:0;
		position:absolute;
		z-index:99999;
		cursor:default;   
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		-khtml-border-radius: 10px;
		border-radius: 10px;   
		display:none;
	}
	
	#AnythingPopup_BoxContainerHeader {
		height:30px;
		background:<?php echo $pop_bordercolor; ?>;
		border-top-right-radius:10px;
		-moz-border-radius-topright:10px;
		-webkit-border-top-right-radius:10px;
		-khtml-border-top-right-radius: 10px;
		border-top-left-radius:10px;
		-moz-border-radius-topleft:10px;
		-webkit-border-top-left-radius:10px;
		-khtml-border-top-left-radius: 10px;   
	}
	
	#AnythingPopup_BoxContainerHeader a {
	   color:<?php echo $pop_header_fontcolor; ?>;
	   font-family:Verdana,Arial;
	   font-size:10pt;
	   font-weight:bold;
	}
	
	#AnythingPopup_BoxTitle {
	   float:left;
	   padding-left: 3px;
	   color:<?php echo $pop_header_fontcolor; ?>;
	   font-family:Verdana,Arial;
	   font-size:12pt;
	   font-weight:bold;   
	}
	
	#AnythingPopup_BoxClose {
	   float:right;
	   width:50px;
	   margin:5px;
	}
	#AnythingPopup_BoxContainerBody {
	   margin:15px;
	}
	#AnythingPopup_BoxContainerFooter {
	   position: fixed; 
	   top:0; 
	   left:0; 
	   bottom:0; 
	   right:0;
	   background:#000000;
	   opacity: .3;
	   -moz-opacity: .3;
	   filter: alpha(opacity=30);
	   border:1px solid <?php echo $pop_bordercolor; ?>;
	   z-index:1;
	   display:none;
	}
	</style>
	<a href='javascript:AnythingPopup_OpenForm("AnythingPopup_BoxContainer","AnythingPopup_BoxContainerBody","AnythingPopup_BoxContainerFooter","<?php echo $pop_width; ?>","<?php echo $pop_height; ?>");'><?php echo $pop_caption; ?></a>
	<div style="display: none;" id="AnythingPopup_BoxContainer">
	  <div id="AnythingPopup_BoxContainerHeader">
		<div id="AnythingPopup_BoxTitle"><?php echo $pop_title; ?></div>
		<div id="AnythingPopup_BoxClose"><a href="javascript:AnythingPopup_HideForm('AnythingPopup_BoxContainer','AnythingPopup_BoxContainerFooter');">Close</a></div>
	  </div>
	  <div id="AnythingPopup_BoxContainerBody"><?php echo $pop_content; ?></div>
	</div>
	<div style="display: none;" id="AnythingPopup_BoxContainerFooter"></div>
	<?php
	}
}

add_filter('the_content', 'AnythingPopup_filter');
function AnythingPopup_filter($content)
{
	return 	preg_replace_callback('/\[ANYTHING-POPUP:(.*?)\]/sim','AnythingPopup_filter_Callback',$content);
}

function AnythingPopup_filter_Callback($matches) 
{
	global $wpdb;
	// [ANYTHING-POPUP:1]
	$scode = $matches[1];
	$sSql = "select * from ".AnythingPopupTable." where 1=1";
	if(is_numeric(@$scode)) 
	{
		$sSql = $sSql . " and pop_id=$scode";
	}
	
	$sSql = $sSql . " LIMIT 0,1";
	$pop = "";
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$data = $data[0];
		$pop_width = stripslashes($data->pop_width);
		$pop_height = stripslashes($data->pop_height);
		$pop_headercolor = stripslashes($data->pop_headercolor);
		$pop_bordercolor = stripslashes($data->pop_bordercolor);
		$pop_header_fontcolor = stripslashes($data->pop_header_fontcolor);
		$pop_title = stripslashes($data->pop_title);
		$pop_content = stripslashes($data->pop_content);
		$pop_content = str_replace("\n", "<br />", $pop_content);
		$pop_caption = stripslashes($data->pop_caption);

		$pop = $pop . '<style type="text/css">';
		$pop = $pop . '#AnythingPopup_BoxContainer	{';
			$pop = $pop . 'width:'.$pop_width.'px;';
			$pop = $pop . 'height:'.$pop_height.'px;';
			$pop = $pop . 'background:#FFFFFF;';
			$pop = $pop . 'border:1px solid '.$pop_bordercolor.';';
			$pop = $pop . 'padding:0;';
			$pop = $pop . 'position:absolute;';
			$pop = $pop . 'z-index:99999;';
			$pop = $pop . 'cursor:default;';   
			$pop = $pop . '-moz-border-radius: 10px;';
			$pop = $pop . '-webkit-border-radius: 10px;';
			$pop = $pop . '-khtml-border-radius: 10px;';
			$pop = $pop . 'border-radius: 10px;   ';
			$pop = $pop . 'display:none;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerHeader {';
			$pop = $pop . 'height:30px;';
			$pop = $pop . 'background:'.$pop_bordercolor.';';
			$pop = $pop . 'border-top-right-radius:10px;';
			$pop = $pop . '-moz-border-radius-topright:10px;';
			$pop = $pop . '-webkit-border-top-right-radius:10px;';
			$pop = $pop . '-khtml-border-top-right-radius: 10px;';
			$pop = $pop . 'border-top-left-radius:10px;';
			$pop = $pop . '-moz-border-radius-topleft:10px;';
			$pop = $pop . '-webkit-border-top-left-radius:10px;';
			$pop = $pop . '-khtml-border-top-left-radius: 10px;';   
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerHeader a {';
		   $pop = $pop . 'color:'.$pop_header_fontcolor.';';
		   $pop = $pop . 'font-family:Verdana,Arial;';
		   $pop = $pop . 'font-size:10pt;';
		   $pop = $pop . 'font-weight:bold;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxTitle {';
		   $pop = $pop . 'float:left;';
		   $pop = $pop . ' margin:5px;';
		   $pop = $pop . 'color:'.$pop_header_fontcolor.';';
		   $pop = $pop . 'font-family:Verdana,Arial;';
		   $pop = $pop . 'font-size:12pt;';
		   $pop = $pop . 'font-weight:bold;';   
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxClose {';
		   $pop = $pop . 'float:right;';
		   $pop = $pop . 'width:50px;';
		   $pop = $pop . 'margin:5px;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerBody {';
		   $pop = $pop . 'margin:15px;';
		$pop = $pop . '} ';
		$pop = $pop . '#AnythingPopup_BoxContainerFooter {';
		   $pop = $pop . 'position: fixed;'; 
		   $pop = $pop . 'top:0;'; 
		   $pop = $pop . 'left:0;'; 
		   $pop = $pop . 'bottom:0;'; 
		   $pop = $pop . 'right:0;';
		   $pop = $pop . 'background:#000000;';
		   $pop = $pop . 'opacity: .3;';
		   $pop = $pop . '-moz-opacity: .3;';
		   $pop = $pop . 'filter: alpha(opacity=30);';
		   $pop = $pop . 'border:1px solid '.$pop_bordercolor.';';
		   $pop = $pop . 'z-index:1;';
		   $pop = $pop . 'display:none;';
		$pop = $pop . '} ';
		$pop = $pop . '</style>';
		
		$HrefOpen = 'javascript:AnythingPopup_OpenForm("AnythingPopup_BoxContainer","AnythingPopup_BoxContainerBody","AnythingPopup_BoxContainerFooter","'.$pop_width.'","'.$pop_height.'");';
		$HrefClose = "javascript:AnythingPopup_HideForm('AnythingPopup_BoxContainer','AnythingPopup_BoxContainerFooter');";
	
		$pop = $pop . "<a href='".$HrefOpen."'>".$pop_caption."</a>";
		$pop = $pop . '<div style="display: none;" id="AnythingPopup_BoxContainer">';
		  $pop = $pop . '<div id="AnythingPopup_BoxContainerHeader">';
			$pop = $pop . '<div id="AnythingPopup_BoxTitle">'.$pop_title.'</div>';
			$pop = $pop . '<div id="AnythingPopup_BoxClose"><a href="'.$HrefClose.'">Close</a></div>';
		  $pop = $pop . '</div>';
		  $pop = $pop . '<div id="AnythingPopup_BoxContainerBody">'.$pop_content.'</div>';
		$pop = $pop . '</div>';
		$pop = $pop . '<div style="display: none;" id="AnythingPopup_BoxContainerFooter"></div>';
	}
	return $pop;
}

function AnythingPopup_install() 
{
	global $wpdb, $wp_version;
	if($wpdb->get_var("show tables like '". AnythingPopupTable . "'") != AnythingPopupTable) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". AnythingPopupTable . "` (";
		$sSql = $sSql . "`pop_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`pop_width` int(11) NOT NULL default '380' ,";
		$sSql = $sSql . "`pop_height` int(11) NOT NULL default '260' ,";
		$sSql = $sSql . "`pop_headercolor` VARCHAR( 10 ) NOT NULL default '#4D4D4D' ,";
		$sSql = $sSql . "`pop_bordercolor` VARCHAR( 10 ) NOT NULL default '#4D4D4D',";
		$sSql = $sSql . "`pop_header_fontcolor` VARCHAR( 10 ) NOT NULL default '#FFFFFF' ,";
		$sSql = $sSql . "`pop_title` VARCHAR( 1024 ) NOT NULL default 'Anything Popup' ,";
		$sSql = $sSql . "`pop_content`TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`pop_caption` VARCHAR( 2024 ) NOT NULL default 'Click to open popup' ,";
		$sSql = $sSql . "PRIMARY KEY ( `pop_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		
		$sSql = "";
		$con = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,";
		$con = $con . " when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap";
		$con = $con . " into electronic typesetting, remaining essentially unchanged."; 
		
		$IsSql = "INSERT INTO `". AnythingPopupTable . "` (`pop_content`)"; 
		$sSql = $IsSql . " VALUES ('".$con."');";
		$wpdb->query($sSql);
	}
	add_option('pop_widget', "RANDOM");
}

function AnythingPopup_widget($args) 
{
	extract($args);
	echo $before_widget;
	$pop_widget = get_option('pop_widget');
	AnythingPopup($pop_widget);
	echo $after_widget;
}
	
function AnythingPopup_control() 
{
	$pop_widget = get_option('pop_widget');
	if (@$_POST['pop_submit']) 
	{
		$pop_widget = stripslashes(trim($_POST['pop_widget']));
		update_option('pop_widget', $pop_widget );
	}
	
	echo '<p>Short Ccde<br>';
	echo '<input  style="width: 200px;" maxlength="100" type="text" value="';
	echo $pop_widget . '" name="pop_widget" id="pop_widget" /></p>';
	echo '<input type="hidden" id="pop_submit" name="pop_submit" value="1" />';
}

function AnythingPopup_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Anything Popup', 'Anything Popup', 'AnythingPopup_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Anything Popup', array('Anything Popup', 'widgets'), 'AnythingPopup_control');
	} 
}

function AnythingPopup_deactivation() 
{
	delete_option( 'pop_widget' ); 
}

function AnythingPopup_admin()
{
	include_once("content-management.php");
}

function AnythingPopup_add_to_menu() 
{
	add_options_page('Anything Popup', 'Anything Popup', 'manage_options', __FILE__, 'AnythingPopup_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'AnythingPopup_add_to_menu');
}

function AnythingPopup_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'anything-popup-js', get_option('siteurl').'/wp-content/plugins/anything-popup/anything-popup.js');
	}
}   

// Version 2.0 strat
//add_action('init', 'AnythingPopup_add_javascript_files');
add_action('wp_enqueue_scripts', 'AnythingPopup_add_javascript_files');
// Version 2.0 end

add_action("plugins_loaded", "AnythingPopup_widget_init");
register_activation_hook(__FILE__, 'AnythingPopup_install');
register_deactivation_hook(__FILE__, 'AnythingPopup_deactivation');
add_action('init', 'AnythingPopup_widget_init');
?>
