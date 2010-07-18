<?php

/**
 * We need the generic WPCSL plugin class, since that is the
 * foundation of much of our plugin.  So here we make sure that it has
 * not already been loaded by another plugin that may also be
 * installed, and if not then we load it.
 */
if (class_exists('wpCSL_plugin_mpcafe') === false) {
    require_once(MP_CAFEPRESS_PLUGINDIR.'WPCSL-generic/classes/CSL-plugin.php');
}

//// SETTINGS ////////////////////////////////////////////////////////

/**
 * This section defines the settings for the admin menu.
 */

$MP_cafepress_plugin = new wpCSL_plugin_mpcafe(
    array(
        'prefix'                 => 'csl-mp-cafepress',
        'name'                   => 'MoneyPress : CafePress Edition',
        'url'                    => 'http://www.cybersprocket.com/products/wpquickcafepress/',
        'paypal_button_id'       => 'NRMZK9MRR7AML',
        'plugin_path'            => MP_CAFEPRESS_PLUGINDIR,
        'plugin_url'             => MP_CAFEPRESS_PLUGINURL,
        'cache_path'             => MP_CAFEPRESS_PLUGINDIR . 'cache',
        'driver_name'            => 'CafePress',
        'driver_args'            => array(get_option('csl-mp-cafepress-api_key')),
        'shortcodes'             => array('mp-cafepress','mp_cafepress','QuickCafe')
    )
);

$MP_cafepress_plugin->settings->add_section(
    array(
        'name' => 'How to Use',
        'description' =>
        '<p>To use MoneyPress : CafePress Edition you only need to add a simple '                   .
        'shortcode to any page where you want to show CafePress products.  An example '              .
        'of the shortcode is <code>[mp-cafepress]http://www.cafepress.com/cybersprocket[/mp-cafepress]</code>. '     .
        'Putting this code on a page would show ten products from youre CafePress store (you need to. '         .
        'change "cybersprocket" in that URL to your CafePress store URL).  The list will include links ' .
        'to each item and their current price.  If you want '        .
        'to change how many products are shown, you can either change the default value below ' .
        'or you can change it in the shortcode itself, e.g. <code>[mp-cafepress '            .
        'return="5"]http://www.cafepress.com/cybersprocket[/mp-cafepress], which would only show '       .
        'five items.</p>' 
    )
);

$MP_cafepress_plugin->settings->add_section(
    array(
        'name'        => 'Store Configuration',
        'description' => ''
    )
);

$MP_cafepress_plugin->settings->add_item('Store Configuration', 'CafePress API Key', 'api-key', 'text', false,
                                  'Your CafePress API Key.  You can use our demo key jvkq6kq4qysvyztj6hkgghk7 until you get your own key.  '.
                                  'This is a shared demo key and should not be used to run your plugin. ');

$MP_cafepress_plugin->settings->add_item('Store Configuration', 'Affiliate ID (CJ PID)', 'cj-pid', 'text', false,
                           'If you have a CafePress Affiliate account, enter your CJ PID here to earn commission on products you list on '.
                           'this site from other CafePress sellers.');

$MP_cafepress_plugin->settings->add_section(
    array(
        'name'        => 'Product Display',
        'description' => 'The values that are entered here are the defaults whenever you use a shortcode.' .
                         'You can override these settings via the shortcode qualifiers when you put the code into a page or post.<br/><br/>'
    )
);

$MP_cafepress_plugin->settings->add_item('Product Display', 'Number of products to show',   'product-count',    'text', false,'Default number of product to show.');
$MP_cafepress_plugin->settings->add_item('Product Display', 'Store ID',                     'storeid',          'text', false,'The default store ID.  The plugin will show items from this store if you don\'t specify a store in the shortcode.');
$MP_cafepress_plugin->settings->add_item('Product Display', 'Section ID',                   'sectionid',        'text', false,'The default section ID.  The plugin will show items from this section within your store if you don\'t specify a store in the shortcode.');

?>
