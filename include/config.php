<?php

/**
 * We need the generic WPCSL plugin class, since that is the
 * foundation of much of our plugin.  So here we make sure that it has
 * not already been loaded by another plugin that may also be
 * installed, and if not then we load it.
 */
if (class_exists('wpCSL_plugin') === false) {
    require_once(MP_CAFEPRESS_PLUGINDIR.'WPCSL-generic/CSL-plugin.php');
}

//// SETTINGS ////////////////////////////////////////////////////////

/**
 * This section defines the settings for the admin menu.
 */

$MP_cafepress_plugin = new wpCSL_plugin(
    array(
        'prefix'                 => 'csl-mp-cafepress',
        'name'                   => 'MoneyPress : CafePress Edition',
        'url'                    => 'http://www.cybersprocket.com/products/wpquickcafepress/',
        'paypal_button_id'       => 'NRMZK9MRR7AML',
        'cache_path'             => MP_CAFEPRESS_PLUGINDIR,
        'plugin_url'             => '/wp-content/plugins/wpquickcafepress/',
        'notifications_obj_name' => 'default',
        'settings_obj_name'      => 'default',
        'license_obj_name'       => 'default'
    )
);

$MP_cafepress_plugin->settings->add_section(
    array(
        'name' => 'How to Use',
        'description' =>
        '<p>To use MoneyPress : CafePress Edition you only need to add a simple '                   .
        'shortcode to any page where you want to show CafePress products.  An example '              .
        'of the shortcode is <code>[QuickCafe]http://www.cafepress.com/cybersprocket[/QuickCafe]</code>. '     .
        'Putting this code on a page would show ten products from youre CafePress store (you need to. '         .
        'change "cybersprocket" in that URL to your CafePress store URL).  The list will include links ' .
        'to each item and their current price.  If you want '        .
        'to change how many products are shown, you can either change the default value below ' .
        'or you can change it in the shortcode itself, e.g. <code>[QuickCafe '            .
        'return="5"]http://www.cafepress.com/cybersprocket[/QuickCafe], which would only show '       .
        'five items.</p>' 
    )
);

$MP_cafepress_plugin->settings->add_section(
    array(
        'name'        => 'Store Configuration',
        'description' => ''
    )
);

$MP_cafepress_plugin->settings->add_item('Store Configuration', 'CafePress API Key', 'csl-mp-cafepress-api-key', 'text', false,
                                  'Your CafePress API Key.  You can use our demo key ut3dcs8rr3svqt5r4r2u8677 until you get your own key.  '.
                                  'This is a shared demo key and should not be used to run your plugin. ');

$MP_cafepress_plugin->settings->add_item('Store Configuration', 'Affiliate ID (CJ PID)', 'csl-mp-cafepress-cj-pid', 'text', false,
                           'If you have a CafePress Affiliate account, enter your CJ PID here to earn commission on products you list on '.
                           'this site from other CafePress sellers.');

$MP_cafepress_plugin->settings->add_section(
    array(
        'name'        => 'Product Display',
        'description' => 'The values that are entered here are the defaults whenever you use a shortcode.' .
                         'You can override these settings via the shortcode qualifiers when you put the code into a page or post.'
    )
);

$MP_cafepress_plugin->settings->add_item('Product Display', 'Number of products to show', 'csl-mp-cafepress-product-count', 'text', false,
                           'If you have a CafePress Affiliate account, enter your CJ PID here to earn commission on products you list on '.
                           'this site from other CafePress sellers.');

$MP_cafepress_plugin->settings->add_item('Product Display', 'Store ID', 'csl-mp-cafepress-storeid', 'text', false,
                           'The default store ID.  The plugin will show items from this store if you don\'t specify a store in the shortcode.'
                           );

$MP_cafepress_plugin->settings->add_item('Product Display', 'Section ID', 'csl-mp-cafepress-sectionid', 'text', false,
                           'The default section ID.  The plugin will show items from this section within your store if you don\'t specify a store in the shortcode.'
                           );


/* Need to put in the CSS Settings */

?>