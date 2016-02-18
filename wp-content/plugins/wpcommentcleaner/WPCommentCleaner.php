<?php
/*
Plugin Name: WPCommentCleaner
Plugin URI: http://www.wordpresslover.com/wpcommentcleaner/
Author: Quazi Touheed
Author URI: http://www.wordpresslover.com/
Description: WPCommentCleaner is a very simple tool which can batch process SPAM, un-apporved and approved comments.
Version: 1.2
*/

// Define Plugin URL For Convenience
define('WPCommentCleanerURL',plugins_url('/',__FILE__));

// Hook Plugin Admin Menu
add_action('admin_menu','WPCommentCleanerMenu');

// Plugin Admin Menu Function
function WPCommentCleanerMenu(){
	$mainpage = add_menu_page(__('CommentCleaner'), __('CommentCleaner'), 'create_users', 'WPCommentCleanerMenu', 'WPCommentCleanerAdmin', WPCommentCleanerURL.'WPCommentCleaner.png');
	add_submenu_page('WPCommentCleanerMenu', __('CommentCleaner'), __('CommentCleaner'), 'create_users', 'WPCommentCleanerMenu', 'WPCommentCleanerAdmin');

	// Load CSS Only In The Plugin Page Itself
	add_action('admin_print_styles-' . $mainpage, 'WPCommentCleanerCSS' );

	// Function To Enqueue CSS File
	function WPCommentCleanerCSS(){
		wp_enqueue_style( 'WPCommentCleanerCSS',WPCommentCleanerURL.'WPCommentCleanerCSS.css' );
	}
}

// WPCommentCleaner Admin Page Stuffs
function WPCommentCleanerAdmin(){
	include "WPCommentCleanerAdmin.php";
}
?>