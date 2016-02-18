<?php
/*
Plugin Name: Insert or Embed Articulate Content into Wordpress Trial
Plugin URI: http://www.elearningfreak.com/presenter/insert-or-embed-articulate-content-into-wordpress-plugin-premium/ ?
Description: Quickly embed or insert Articulate content into a post or page
Version: 4.1.4
Author: Brian Batt
Author URI: http://www.elearningfreak.com
*/

define ( 'WP_QUIZ_EMBEDER_PLUGIN_DIR', dirname(__FILE__)); // Plugin Directory
define ( 'WP_QUIZ_EMBEDER_PLUGIN_URL', plugin_dir_url(__FILE__)); // Plugin URL (for http requests)

global $wpdb;
require_once("settings_file.php");
require_once("functions.php");
include_once(WP_QUIZ_EMBEDER_PLUGIN_DIR."/include/shortcode.php");





register_activation_hook(__FILE__,'quiz_embeder_install'); 
/*add_action( 'admin_notices', 'quiz_embeder_banner');*/

register_deactivation_hook( __FILE__, 'quiz_embeder_remove' );
function quiz_embeder_count(){
	$count = 2;
return apply_filters('quiz_embeder_count', $count);
}
function quiz_embeder_install() {

@mkdir(getUploadsPath());
@file_put_contents(getUploadsPath()."index.html","");

}
function quiz_embeder_remove() {

$qz_upload_path=getUploadsPath();
if(file_exists($qz_upload_path."/.htaccess")){unlink($qz_upload_path."/.htaccess");}

}


add_action( 'wp_ajax_quiz_upload', 'wp_ajax_quiz_upload' );
add_action( 'wp_ajax_del_dir', 'wp_ajax_del_dir' );
add_action( 'wp_ajax_rename_dir', 'wp_ajax_rename_dir');


function wp_myplugin_media_button() {
	$wp_myplugin_media_button_image = getPluginUrl().'quiz.png';
	echo '<a href="media-upload.php?type=upload&TB_iframe=true&tab=upload" class="thickbox">
  <img src="'.$wp_myplugin_media_button_image.'"  width=15 height=15 /></a>';
}

function media_upload_quiz_form()
{
	print_tabs();
	echo '<div class="wrap" style="margin-left:20px;  margin-bottom:50px;">';
		echo '<div id="icon-upload" class="icon32"><br></div><h2>Upload File</h2>';
		print_upload();
	echo "</div>";

}
function media_upload_quiz_content()
{
	print_tabs();
	echo '<div class="wrap" style="margin-left:20px;  margin-bottom:50px;">';
		echo '<div id="icon-upload" class="icon32"><br></div><h2>Content Library</h2>';
		printInsertForm();
	echo "</div>";
}



function media_upload_quiz()
{
	wp_iframe( "media_upload_quiz_content" );
}

function media_upload_upload()
{
	/*if($_GET['tab']=='quiz') #I added this technique because: on wordpress 3.4  'media_upload_quiz' action hook does not work.*/
	if ( isset( $_REQUEST[ 'tab' ] ) && strstr( $_REQUEST[ 'tab' ], 'quiz') ) {
	wp_iframe( "media_upload_quiz_content" );
	}
	else
	{
	wp_iframe( "media_upload_quiz_form" );
	}
	
}

function print_tabs()
{

	
	function quiz_tabs($tabs) 
	{
	$newtab1 = array('upload' => 'Upload File');
	$newtab2 = array('quiz' => 'Content Library');
	return array_merge($newtab1,$newtab2);
	}
add_filter('media_upload_tabs', 'quiz_tabs');
media_upload_header();

}
/*
if ( ! function_exists ( 'quiz_embeder_banner' ) ) {
	function quiz_embeder_banner() {
		global $hook_suffix;
		if ( 'plugins.php' == $hook_suffix ) {
					?>
					<div id ="message" class="updated">
								<?php _e( "Upgrade the <strong>Insert or Embed Articulate Content into Wordpress Trial</strong> plugin to the <strong>Premium</strong> version today!", 'quiz_embeder' ); ?><br />
								<span><?php _e( "Includes unlimited uploads and premium support! ", 'quiz_embeder' ); ?><a target="_blank" href="http://www.elearningfreak.com/presenter/insert-or-embed-articulate-content-into-wordpress-plugin-premium/"><?php _e( 'Learn More', 'quiz_embeder' ); ?></a></span>
					</div>
					<?php }
					}
}
*/
if ( ! function_exists ( 'quiz_embeder_register_plugin_links' ) ) {
	function quiz_embeder_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			if ( ! is_network_admin() )
			$links[] = '<a href="http://www.elearningfreak.com/presenter/insert-or-embed-articulate-content-into-wordpress-plugin-premium/" target="_blank">' . __( 'Buy premium version','quiz_embeder' ) . '</a>';
			$links[] = '<a href="admin.php?page=articulate_content">' . __( 'Dashboard','quiz_embeder' ) . '</a>';
			$links[] = '<a href="https://www.youtube.com/watch?v=AwcIsxpkvM4" target="_blank">' . __( 'How to use','quiz_embeder' ) . '</a>';
			$links[] = '<a href="http://www.elearningfreak.com/uncategorized/increase-maximum-upload-file-size/" target="_blank">' . __( 'Maximum upload size','quiz_embeder' ) . '</a>';
			$links[] = '<a href="http://www.elearningfreak.com/contact-us/" target="_blank">' . __( 'Support','quiz_embeder' ) . '</a>';
		}
		return $links;
	}
}

add_action('media_upload_upload','media_upload_upload');
add_action('media_upload_quiz','media_upload_quiz');
add_action( 'media_buttons', 'wp_myplugin_media_button',100);
/*add_filter( 'plugin_row_meta', 'quiz_embeder_register_plugin_links', 10, 2 );*/


/* added by oneTarek --*/
//add_action('wp_head','quiz_embeder_wp_head');
add_action('wp_footer','quiz_embeder_wp_head');

function quiz_embeder_enqueue_script() {
	wp_enqueue_script('jquery');
}    
 
add_action('wp_enqueue_scripts', 'quiz_embeder_enqueue_script');

include_once(WP_QUIZ_EMBEDER_PLUGIN_DIR."/admin_page.php");



?>