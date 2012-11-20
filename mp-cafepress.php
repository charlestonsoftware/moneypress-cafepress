<?php
/*
  Plugin Name: MoneyPress : CafePress Edition
  Plugin URI: http://www.charlestonsw.com/product/moneypress-cafepress-edition/
  Description: Display CafePress products on your pages and posts with a simple short code. Affiliate enabled.
  Version: 3.9.1
  Author: Charleston Software Associates
  Author URI: http://www.charlestonsw.com
  License: GPL3

Our PID: 3783719
http://www.tkqlhce.com/click-PID-10467594?url=<blah>

 Copyright (C) 2012 Charleston Software Associates

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Drive Path Defines 
//
if (defined('MP_CAFEPRESS_PLUGINDIR') === false) {
    define('MP_CAFEPRESS_PLUGINDIR', plugin_dir_path(__FILE__));
}
if (defined('MP_CAFEPRESS_COREDIR') === false) {
    define('MP_CAFEPRESS_COREDIR', MP_CAFEPRESS_PLUGINDIR . 'core/');
}
if (defined('MP_CAFEPRESS_ICONDIR') === false) {
    define('MP_CAFEPRESS_ICONDIR', MP_CAFEPRESS_COREDIR . 'images/icons/');
}

// URL Defines
//
if (defined('MP_CAFEPRESS_PLUGINURL') === false) {
    define('MP_CAFEPRESS_PLUGINURL', plugins_url('',__FILE__));
}
if (defined('MP_CAFEPRESS_COREURL') === false) {
    define('MP_CAFEPRESS_COREURL', MP_CAFEPRESS_PLUGINURL . '/core/');
}
if (defined('MP_CAFEPRESS_ICONURL') === false) {
    define('MP_CAFEPRESS_ICONURL', MP_CAFEPRESS_COREURL . 'images/icons/');
}

// The relative path from the plugins directory
//
if (defined('MP_CAFEPRESS_BASENAME') === false) {
    define('MP_CAFEPRESS_BASENAME', plugin_basename(__FILE__));
}

// Our product prefix
//
if (defined('MP_CAFEPRESS_PREFIX') === false) {
    define('MP_CAFEPRESS_PREFIX', 'csl-mp-cafepress');
}

// Include our needed files
//
include_once(MP_CAFEPRESS_PLUGINDIR . '/include/config.php'   );
include_once(MP_CAFEPRESS_PLUGINDIR . 'plus.php'   );
include_once(MP_CAFEPRESS_COREDIR   . 'csl_helpers.php'       );
if (class_exists('PanhandlerProduct') === false) {
    try {
        require_once('Panhandler/Panhandler.php');
    }
    catch (PanhandlerMissingRequirement $exception) {
        add_action('admin_notices', array($exception, 'getMessage'));
        exit(1);
    }
}
if (class_exists('CafePressPanhandler') === false) {
    try {
        require_once('Panhandler/Drivers/CafePress.php');
    }
    catch (PanhandlerMissingRequirement $exception) {
        add_action('admin_notices', array($exception, 'getMessage'));
        exit(1);
    }
}

register_activation_hook( __FILE__, 'csl_mpcafe_activate');

add_action('wp_footer', 'csl_mpcafe_user_stylesheet');
add_action('admin_print_styles','csl_mpcafe_admin_stylesheet');
add_action('admin_init','csl_mpcafe_setup_admin_interface',10);
