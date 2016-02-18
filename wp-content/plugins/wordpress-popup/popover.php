<?php
/**
Plugin Name: WordPress PopUp
Plugin URI:  http://premium.wpmudev.org/project/the-pop-over-plugin/
Description: Allows you to display a fancy PopUp to visitors sitewide or per blog. A *very* effective way of advertising a mailing list, special offer or running a plain old ad.
Version:     4.7.1.1
Author:      WPMU DEV
Author URI:  http://premium.wpmudev.org
Textdomain:  popover
WDP ID:      123

Copyright 2007-2013 Incsub (http://incsub.com)
Author - Barry (Incsub)
Contributors - Marko Miljus (Incsub), Ve Bailovity (Incsub)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

function inc_popup_free_init() {
	// Check if the PRO plugin is present and activated.
	if ( defined( 'PO_VERSION' ) && 'pro' == PO_VERSION ) {
		return false;
	}

	if ( ! defined( 'PO_LANG' ) ) {
		// Used for more readable i18n functions: __( 'text', PO_LANG );
		define( 'PO_LANG', 'popover' );
		define( 'PO_VERSION', '4.7' );

		/**
		 * The current DB/build version. NOT THE SAME AS THE PLUGIN VERSION!
		 * Increase this when DB structure changes, migration code is required, etc.
		 * See IncPopupDatabase: db_is_current() and db_update()
		 */
		define( 'PO_BUILD', 6 );

		$plugin_dir = trailingslashit( dirname( __FILE__ ) );
		$plugin_dir_rel = trailingslashit( dirname( plugin_basename( __FILE__ ) ) );
		$plugin_url = plugin_dir_url( __FILE__ );

		define( 'PO_LANG_DIR', $plugin_dir_rel . 'lang/' );
		define( 'PO_TPL_DIR', $plugin_dir . 'css/tpl/' );
		define( 'PO_INC_DIR', $plugin_dir . 'inc/' );
		define( 'PO_JS_DIR', $plugin_dir . 'js/' );
		define( 'PO_CSS_DIR', $plugin_dir . 'css/' );
		define( 'PO_VIEWS_DIR', $plugin_dir . 'views/' );

		define( 'PO_TPL_URL', $plugin_url . 'css/tpl/' );
		define( 'PO_JS_URL', $plugin_url . 'js/' );
		define( 'PO_CSS_URL', $plugin_url . 'css/' );
		define( 'PO_IMG_URL', $plugin_url . 'img/' );

		// Include function library.
		require_once PO_INC_DIR . 'external/wpmu-lib/core.php';

		lib2()->translate_plugin( PO_LANG, PO_LANG_DIR );

		require_once PO_INC_DIR . 'external/wdev-frash/module.php';

		// Register the current plugin.
		do_action(
			'wdev-register-plugin',
			/*             Plugin ID */ plugin_basename( __FILE__ ),
			/*          Plugin Title */ 'WordPress PopUp',
			/* https://wordpress.org */ '/plugins/wordpress-popup/',
			/*      Email Button CTA */ __( 'Get Tips!', PO_LANG ),
			/*  getdrip Plugin param */ ''
		);
		// All done!

		require_once( PO_INC_DIR . 'config-defaults.php');
		if ( is_admin() ) {
			// Defines class 'IncPopup'.
			require_once( PO_INC_DIR . 'class-popup-admin.php');
		} else {
			// Defines class 'IncPopup'.
			require_once( PO_INC_DIR . 'class-popup-public.php');
		}

		// Initialize the plugin as soon as we have identified the current user.
		IncPopup::instance();
	}
}

add_action(
	'plugins_loaded',
	'inc_popup_free_init'
);
