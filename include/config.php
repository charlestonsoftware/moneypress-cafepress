<?php

/**
 * We need the generic WPCSL plugin class, since that is the
 * foundation of much of our plugin.  So here we make sure that it has
 * not already been loaded by another plugin that may also be
 * installed, and if not then we load it.
 */
if (defined('MP_CAFEPRESS_PLUGINDIR')) {
    if (class_exists('wpCSL_plugin__mpcafe') === false) {
        require_once(MP_CAFEPRESS_PLUGINDIR.'WPCSL-generic/classes/CSL-plugin.php');
    }
    
    $MP_cafepress_plugin = new wpCSL_plugin__mpcafe(
        array(
            'use_obj_defaults'      => true,        
            'prefix'                => MPCAFE_PREFIX,
    'name'                  => 'MoneyPress : CafePress Edition',
            'url'                   => 'http://www.cybersprocket.com/products/wpquickcafepress/',
            'paypal_button_id'      => 'NRMZK9MRR7AML',
            'basefile'              => MP_CAFEPRESS_BASENAME,
            'plugin_path'           => MP_CAFEPRESS_PLUGINDIR,
            'plugin_url'            => MP_CAFEPRESS_PLUGINURL,
            'cache_path'            => MP_CAFEPRESS_PLUGINDIR . 'cache',
            'driver_name'           => 'CafePress',
            'driver_type'           => 'Panhandler',
            'driver_args'           => array(
                    'api_key'   => get_option(MPCAFE_PREFIX.'-api_key'),
                    'cj_pid'    => get_option(MPCAFE_PREFIX.'-cj_pid'),
                    'return'    => get_option(MPCAFE_PREFIX.'-return'),
                    'wait_for'  => get_option(MPCAFE_PREFIX.'-wait_for')
                    ),
            'shortcodes'            => array('mp-cafepress','mp_cafepress','QuickCafe')
        )
    );

}

//-------------------------
// How to Use Section
//-------------------------

$MP_cafepress_plugin->settings->add_section(
    array(
        'name' => 'How to Use',
        'description' => file_get_contents(MP_CAFEPRESS_PLUGINDIR.'/how_to_use.txt')
    )
);

//---------------------------------
// CafePress Communications Section
//---------------------------------

$MP_cafepress_plugin->settings->add_section(
    array(
        'name'        => 'CafePress Communication',
        'description' => 'These settings affect how the plugin communicates with CafePress to get your listings.'.
                            '<br/><br/>'
    )
);

$MP_cafepress_plugin->settings->add_item(
    'CafePress Communication', 
    'CafePress API Key', 
    'api_key', 
    'text', 
    false,
    'Your CafePress API Key.  You can use our demo key jvkq6kq4qysvyztj6hkgghk7 until you get your own key.  '.
        'This is a shared demo key and should not be used to run your plugin. '
);

$MP_cafepress_plugin->settings->add_item(
    'CafePress Communication', 
    'Affiliate ID (CJ PID)', 
    'cj_pid', 
    'text', 
    false,
    'If you have a CafePress Affiliate account, enter your CJ PID here to earn commission on products '.
        'you list on this site from other CafePress sellers.'
);

$MP_cafepress_plugin->settings->add_item(
    'CafePress Communication', 
    'Wait For ',   
    'wait_for',    
    'text', 
    false, 
    'How long to wait for the CafePress server to respond in seconds (default: 30).'
);


//-------------------------
// Product Display Section
//-------------------------

$MP_cafepress_plugin->settings->add_section(
    array(
        'name'        => 'Product Display',
        'description' => 'The values that are entered here are the defaults whenever you use a shortcode.' .
                         'You can override these settings via the shortcode qualifiers when you put the code into a page or post.<br/><br/>'
    )
);

$MP_cafepress_plugin->settings->add_item(
    'Product Display', 
    'Number of products to show',   
    'return',    
    'text', 
    false,
    'Default number of product to show when listing products (default: 10).'
);


$MP_cafepress_plugin->settings->add_item(
    'Product Display', 
    'Store ID',
    'store_id',    
    'text', 
    false,
    'The default store ID to use if not sepcified (default: cybersprocket).'
);


$MP_cafepress_plugin->settings->add_item(
    'Product Display', 
    'Section ID',   
    'section_id',    
    'text', 
    false,
    'The default section ID to use if not sepcified (default: 0).'
);

?>
