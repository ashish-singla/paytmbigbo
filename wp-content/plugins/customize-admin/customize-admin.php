<?php
/*
Plugin Name: Customize Admin
Plugin URI: http://www.vanderwijk.com/wordpress/customize-admin/
Description: This plugin allows you to customize the appearance and branding of the WordPress admin interface.
Version: 1.7.1
Author: Johan van der Wijk
Author URI: http://www.vanderwijk.com
Text Domain: customize-admin-plugin
Domain Path: /languages

Release notes: 1.7.1 Updated translation

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) die( 'Error!' );

// Load the required files needed for the plugin to run in the proper order and add needed functions to the required hooks.
function customize_admin_plugin_init() {
	load_plugin_textdomain( 'customize-admin-plugin', false, 'customize-admin/languages' );
}
add_action( 'plugins_loaded', 'customize_admin_plugin_init' );

// Title attribute for the logo on the login screen
function ca_logo_title() {
	if ( get_option( 'ca_logo_url' ) != '' ) {
		return sprintf ( __( 'Go to %1$s', 'customize-admin-plugin' ), esc_url( get_option ( 'ca_logo_url' ) ) );
	}
}

// URL for the logo on the login screen
function ca_logo_url($url) {
	if ( get_option( 'ca_logo_url' ) != '' ) {
		return esc_url( get_option( 'ca_logo_url' ) );
	} else {
		return esc_url( get_bloginfo( 'url' ) );
	}
}

// CSS for custom logo on the login screen
function ca_logo_file() {
	if ( get_option( 'ca_logo_file' ) != '' ) {
		echo '<style>.login h1 a { background-image: url("' . esc_url ( get_option( 'ca_logo_file' ) ) . '"); background-size: auto auto; width: 320px; }</style>';
	} else {
		echo '<style>.login h1 a { background-image: url("' . plugins_url( 'vanderwijk.png' , __FILE__ ) . '"); background-size: auto auto; width: 320px; }</style>';
	}
}

// CSS for custom background color
function ca_login_background_color() {
	if ( get_option( 'ca_login_background_color' ) != '' ) {
		echo '<style>body { background-color: ' . esc_html ( get_option( 'ca_login_background_color' ) ) . '!important; } </style>';
	}
}

// CSS for custom CSS
function ca_custom_css() {
	if ( get_option( 'ca_custom_css' ) != '' ) {
		echo '<style>'. strip_tags( get_option( 'ca_custom_css' ) ) . '</style>';
	}
}

// Remove the generator meta tag
function ca_remove_meta_generator() {
	if ( get_option( 'ca_remove_meta_generator' ) != '' ) {
		remove_action( 'wp_head', 'wp_generator' );
	}
}

// Remove the RSD meta tag
function ca_remove_meta_rsd() {
	if ( get_option( 'ca_remove_meta_rsd' ) != '' ) {
		remove_action( 'wp_head', 'rsd_link' );
	}
}

// Remove the WLW meta tag
function ca_remove_meta_wlw() {
	if ( get_option( 'ca_remove_meta_wlw' ) != '' ) {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}
}

// Remove the RSS feed links
function ca_remove_rss_links() {
	if ( get_option( 'ca_remove_rss_links' ) != '' ) {
		remove_action( 'wp_head', 'feed_links', 2 ); //removes feeds
		remove_action( 'wp_head', 'feed_links_extra', 3 ); //removes comment feed links
	}
}

// Remove Quick Press widget from dashboard
function ca_remove_dashboard_quick_press() {
	if ( get_option( 'ca_remove_dashboard_quick_press' ) != '' ) {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	}
}

// Remove Plugins widget from dashboard
function ca_remove_dashboard_plugins() {
	if ( get_option( 'ca_remove_dashboard_plugins' ) != '' ) {
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	}
}

// Remove Activity  widget from dashboard
function ca_remove_dashboard_activity() {
	if ( get_option( 'ca_remove_dashboard_activity' ) != '' ) {
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	}
}

// Remove WordPress Site News widget from dashboard
function ca_remove_dashboard_wordpress_news() {
	if ( get_option( 'ca_remove_dashboard_wordpress_news' ) != '' ) {
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	}
}

// Remove WordPress Other News widget from dashboard
function ca_remove_dashboard_wordpress_other() {
	if ( get_option( 'ca_remove_dashboard_wordpress_other' ) != '' ) {
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
	}
}

add_filter( 'login_headertitle', 'ca_logo_title' );
add_filter( 'login_headerurl', 'ca_logo_url' );
add_action( 'login_head', 'ca_logo_file' );
add_action( 'login_head', 'ca_login_background_color' );
add_action( 'login_head', 'ca_custom_css' );
add_action( 'init', 'ca_remove_meta_generator' );
add_action( 'init', 'ca_remove_meta_rsd' );
add_action( 'init', 'ca_remove_meta_wlw' );
add_action( 'init', 'ca_remove_rss_links' );
add_action( 'wp_dashboard_setup', 'ca_remove_dashboard_quick_press' );
add_action( 'wp_dashboard_setup', 'ca_remove_dashboard_plugins' );
add_action( 'wp_dashboard_setup', 'ca_remove_dashboard_activity' );
add_action( 'wp_dashboard_setup', 'ca_remove_dashboard_wordpress_news' );

require_once( 'customize-admin-options.php' );