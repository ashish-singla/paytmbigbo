<?php
/*
  Plugin Name: Contact Form by ContactUs
  Version: 5.0.8
  Plugin URI:  http://help.contactus.com/hc/en-us/sections/200204997-Contact-Form-Plugin-by-ContactUs-com
  Description: Contact Form by ContactUs.com Plugin for Wordpress.
  Author: contactus.com
  Author URI: http://www.contactus.com/
  License: GPLv2 or later

  Copyright 2014  ContactUs.com  ( email: support@contactuscom.zendesk.com )
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

//INCLUDE WP HOOKS ACTIONS & FUNCTIONS
require_once( dirname(__FILE__) . '/contactus_form_conf.php' );

//INCLUDE WP HOOKS ACTIONS & FUNCTIONS
require_once( dirname(__FILE__) . '/helpers/contactus_form_functions.php' );

/*
 * Method in charge to render plugin layout
 * @since 1.0
 * @return string Render HTML layout into WP admin
 */
if (!function_exists('cUsCF_menu_render')) {

    function cUsCF_menu_render() {

        //cUsCF_plugin_db_uninstall();
        $aryUserCredentials = get_option('cUsCF_settings_userCredentials'); //get the values, wont work the first time

        ?>
        <div id="cu_plugin-container">
            <?php
                /*
                * PLUGIN HEADER
                * @since 5.0
                */
                require_once( cUsCF_DIR . 'views/header.php');
            ?>

            <?php
                if(!empty($aryUserCredentials) && is_array($aryUserCredentials)) {
                    require_once( cUsCF_DIR . 'views/priv-uix.php');
                }else{
                    require_once( cUsCF_DIR . 'views/pub-uix.php');
                }
            ?>

        </div>

    <?php
    } //cUsCF_menu_render ends

} // END IF FUNCTION RENDER