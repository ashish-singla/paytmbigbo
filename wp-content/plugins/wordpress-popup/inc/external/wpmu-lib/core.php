<?php
/**
Plugin Name: WPMU Dev code library
Plugin URI:  http://premium.wpmudev.org/
Description: Framework to support creating WordPress plugins and themes.
Version:     2.0.3
Author:      WPMU DEV
Author URI:  http://premium.wpmudev.org/
Textdomain:  wpmu-lib
*/

/**
 * Constants for wp-config.php
 *
 * define( 'WDEV_UNMINIFIED', true ) // Load the unminified JS/CSS files
 * define( 'WDEV_DEBUG', true ) // Activate lib2()->debug->dump() without having to enable WP_DEBUG
 * define( 'WDEV_AJAX_DEBUG', false ) // Disable lib2()->debug->dump() for Ajax requests
 */

$version = '2.0.3'; // Remember to update the class-name in functions-wpmulib.php!!

/**
 * Load TheLib class definition if not some other plugin already loaded it.
 */
$dirname = dirname( __FILE__ ) . '/inc/';
$file_ver = str_replace( '.', '_', $version );
$class_base = 'TheLib_' . $file_ver;
$main_class = $class_base . '_Core';

$files = array(
	$class_base                  => 'class-thelib.php',
	$main_class                  => 'class-thelib-core.php',
	$class_base . '_Array'       => 'class-thelib-array.php',
	$class_base . '_Debug'       => 'class-thelib-debug.php',
	$class_base . '_Html'        => 'class-thelib-html.php',
	$class_base . '_Net'         => 'class-thelib-net.php',
	$class_base . '_Session'     => 'class-thelib-session.php',
	$class_base . '_Updates'     => 'class-thelib-updates.php',
	$class_base . '_Ui'          => 'class-thelib-ui.php',
);


foreach ( $files as $class_name => $class_file ) {
	if ( ! class_exists( $class_name ) && file_exists( $dirname . $class_file ) ) {
		require_once( $dirname . $class_file );
	}
}

if ( ! class_exists( 'TheLib2Wrap' ) ) {
	/**
	 * The wrapper class is used to handle situations when some plugins include
	 * different versions of TheLib.
	 *
	 * TheLib2Wrap will always keep the latest version of TheLib for later usage.
	 */
	class TheLib2Wrap {
		static public $version = '0.0.0';
		static public $object = null;

		static public function set_obj( $version, $obj ) {
			if ( version_compare( $version, self::$version, '>' ) ) {
				self::$version = $version;
				self::$object = $obj;
			}
		}
	};
}
$obj = new $main_class();
TheLib2Wrap::set_obj( $version, $obj );

if ( ! function_exists( 'lib2' ) ) {
	/**
	 * This is a shortcut function to access the latest TheLib_x_y_z_Core object.
	 *
	 * The shortcut function is called `lib2` because it is incompatible with
	 * the old WDev() function. The number (2) reflects the main version of this
	 * module.
	 *
	 * The main version is only increased when backwards compatibility fails!
	 *
	 * Usage:
	 *   lib2()->ui->admin_message();
	 */
	function lib2() {
		return TheLib2Wrap::$object;
	}
}
