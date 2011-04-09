<?php
/*
  Plugin Name: MoneyPress : CafePress Edition
  Plugin URI: http://www.cybersprocket.com/products/wpquickcafepress/
  Description: MoneyPress CafePress Edition allows you to quickly and easily display products from CafePress on any page or post via a simple shortcode.
  Author: Cyber Sprocket Labs
  Version: 3.5
  Author URI: http://www.cybersprocket.com/
  License: GPL3

  Our PID: 3783719
  http://www.tkqlhce.com/click-PID-10467594?url=<blah>
  
	Copyright 2010  Cyber Sprocket Labs (info@cybersprocket.com)

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


if (defined('MP_CAFEPRESS_PLUGINDIR') === false) {
    define('MP_CAFEPRESS_PLUGINDIR', plugin_dir_path(__FILE__));
}

if (defined('MP_CAFEPRESS_PLUGINURL') === false) {
    define('MP_CAFEPRESS_PLUGINURL', plugins_url('',__FILE__));
}

if (defined('MP_CAFEPRESS_BASENAME') === false) {
    define('MP_CAFEPRESS_BASENAME', plugin_basename(__FILE__));
}

if (defined('MP_CAFEPRESS_BASENAME') === false) {
    define('MP_CAFEPRESS_BASENAME', plugin_basename(__FILE__));
}

if (defined('MPCP_PREFIX') === false) {
    //define('MPCP_PREFIX', 'mpamz');
    define('MPCP_PREFIX', 'csl-mp-cafepress');
}

require_once('include/config.php');

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

add_filter('wp_print_styles', 'MP_cafepress_user_css');

/**
 * Add the [QuickCafe] short code for backwards compatability.
 *
 * Example:
 * [QuickCafe preview="3" return="21"]http://www.cafepress.com/cybersprocket/ [/QuickCafe]
 *
 * See the knowledgebase for detailed usage at:
 * http://redmine.cybersprocket.com/projects/wpcafepress/wiki/Using_MoneyPress_CafePress_Edition
 */
add_shortcode('QuickCafe', 'MP_cafepress_show_items');

//// FUNCTIONS ///////////////////////////////////////////////////////

/**
 * Adds our user CSS to the page.
 */
function MP_cafepress_user_css() {
    wp_enqueue_style('mp_cafepress_css', plugins_url('css/mp-cafepress.css', __FILE__));
}

/**
 * Processes our short code.
 */
function MP_cafepress_show_items($attributes, $content = null) {
    global $current_user;
    global $prefix;
    global $MP_cafepress_plugin;
    get_currentuserinfo();

    // Make sure the user is either an admin, in which case he        
    // gets to view the results of the plugin, or otherwise
    // make sure the license has been purchased.
    if (!get_option($prefix.'-purchased')) {
        if (!isset($current_user) ) { return; }
        if ($current_user->ID <= 0 ) { return; }
        if (($current_user->wp_capabilities['administrator'] == false) &&
            ($current_user->user_level != '10') ) {
            return;
        }
    }

    $cafepress = $MP_cafepress_plugin->driver;

    extract(
        shortcode_atts(
            array(
                'page'          => null, 
                'preview'       => null,
                'return'        => null,
                'section_id'    => null,
                'wait_for'      => null,
            ),
            $attributes
        )
    );

    // See if we are setting a limit on how many items to show.
    if (isset($attributes['return'])) {
        $product_count = (integer) $attributes['return'];
    }
    else {
        $product_count = (integer) get_option($prefix.'-return');
    }

    // Even after the above two checks for places to get a product count, we may
    // still end up with a count of zero.  So we still have to make sure
    // $product_count is non-zero before calling set_maximum_product_count(),
    // otherwise we will display nothing.
    if ($product_count) {
        $cafepress->set_maximum_product_count($product_count);
    }

    $general_options = MP_cafepress_get_general_options();

    if (isset($keywords) && ($keywords !== null)) {
        $general_options['keywords'] = array($keywords);
    }

    return MP_cafepress_format_all_products( $cafepress->get_products($general_options) );
}

/**
 * Here we set the options that we pass to whatever function we
 * ultimately call to fetch products.  The functions will ignore
 * any options that aren't appropriate to them, so we don't have
 * to be very cautious here about giving the wrong options
 * to the wrong method.
 *
 * This function returns the array of options to use when calling our
 * CafePress driver, if any.
 */
function MP_cafepress_get_general_options() {
    global $prefix;
    $general_options = array();
    $store_id       = get_option($prefix.'-store_id');
    $section_id     = get_option($prefix.'-section_id');

    if ($store_id) {
        $general_options['store_id'] = array($store_id);
    }
    if ($section_id) {
        $general_options['section_id'] = array($section_id);
    }
    
    return $general_options;
}

/**
 * This is our HTML template for display products, which we use as an
 * argument to sprintf() in the MB_cafepress_format_product() function just
 * below.  Eventually this will get factored out elsewhere.  Or that's
 * on the todo list anyways.  We'll see.  For all I know, a ravaging
 * loch ness monster could attack the office and kill us all before we 
 * have a chance to get around to it.
 */
$MB_cafepress_product_template = '<div class="csl-cafepress-product">
  <!-- Product Name -->
  <h3><a href="%s">%s</a></h3>
  <div class="csl-cafepress-product-image">
    <!-- Image URL and Link -->
    <a href="%s" target="_new">
      <img src="%s" alt="%s"/>
    </a>
  </div>
  <!-- Description -->
  <p>%s</p>
  <!-- Price and Purchase URL -->
  <p>
    <a href="%s" target="_new">
      Purchase for %s
    </a>
  </p>
  <div style="clear: both;"></div>
</div>';

/**
 * Takes an PanhandlerProduct object and returns a string of HTML
 * suitabale for displaying that product.
 */
function MB_cafepress_format_product($product) {
    global $MB_cafepress_product_template;

    if ($product->image_urls[0] === null) {
        $product->image_urls[0] = MP_CAFEPRESS_PLUGINURL.'/images/ImageNA.png';
    }

    return sprintf(
        $MB_cafepress_product_template,
        $product->web_urls[0],
        $product->name,
        $product->web_urls[0],
        $product->image_urls[0],
        $product->name,
        $product->description,
        $product->web_urls[0],
        money_format('$%i', $product->price)
    );
}

/**
 * Takes an array of PanhandlerProduct objects and returns all of the
 * HTML for displaying them on the page.
 */
function MP_cafepress_format_all_products($products) {
    return implode('', array_map('MB_cafepress_format_product', $products));
}

