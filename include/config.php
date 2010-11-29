<?php

/**
 * We need the generic WPCSL plugin class, since that is the
 * foundation of much of our plugin.  So here we make sure that it has
 * not already been loaded by another plugin that may also be
 * installed, and if not then we load it.
 */
if (class_exists('wpCSL_plugin__mpcafe') === false) {
    require_once(MP_CAFEPRESS_PLUGINDIR.'WPCSL-generic/classes/CSL-plugin.php');
}

//// SETTINGS ////////////////////////////////////////////////////////

/**
 * This section defines the settings for the admin menu.
 */

$MP_cafepress_plugin = new wpCSL_plugin__mpcafe(
    array(
        'use_obj_defaults'      => true,        
        'prefix'                => 'csl-mp-cafepress',
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
                'api_key'   => get_option('csl-mp-cafepress-api-key'),
                'wait_for'  => get_option('csl-mp-cafepress-wait_for')
                ),
        'shortcodes'            => array('mp-cafepress','mp_cafepress','QuickCafe')
    )
);

//-------------------------
// How to Use Section
//-------------------------

$MP_cafepress_plugin->settings->add_section(
    array(
        'name' => 'How to Use',
        'description' =>
        '<p>To use MoneyPress : CafePress Edition you only need to add a simple '                       .
        'shortcode to any page where you want to show a list of CafePress products. '                   .
        'The following code will show products from your CafePress store (you need to change '          .
        '"cybersprocket" to your CafePress store name):<br/>' .
        '<code>[mp-cafepress]http://www.cafepress.com/cybersprocket[/mp-cafepress]</code><br/>'         .
        '<br/>'.
        'If you want to change how many products are shown, you can change the default '                .
        'value below or you can specify it in the shortcode itself.  For example the following code '   .
        'would show 5 items:<br/>'.
        '<code>[mp-cafepress return="5"]http://www.cafepress.com/cybersprocket[/mp-cafepress]</code><br/>'.
        '<br/>' .
        '<strong>Need More Assistance?</strong><br/><br/>'.
        'For help setting up and using this plugin, please visit the '.
        '<strong><a href="http://redmine.cybersprocket.com/projects/wpcafepress/wiki" target="newinfo">'.
        'MoneyPress:CafePress Edition Knowledgebase</a></strong>.<br/> We recommend registering on the site and '.
        'using the forums to post questions.  It is the best way to get a response from our development team.'.
        '<br/><br/>Register here:<br/>'.
        '<a href="http://redmine.cybersprocket.com/account/register">'.
        'http://redmine.cybersprocket.com/account/register</a>'.
        '<br/><br/>Review the forum here:<br/>'.
        '<a href="http://redmine.cybersprocket.com/projects/moneypress-buyat/boards">'.
        'http://redmine.cybersprocket.com/projects/moneypress-buyat/boards</a>'.
        '</p>'
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
    'api-key', 
    'text', 
    false,
    'Your CafePress API Key.  You can use our demo key jvkq6kq4qysvyztj6hkgghk7 until you get your own key.  '.
        'This is a shared demo key and should not be used to run your plugin. '
);

$MP_cafepress_plugin->settings->add_item(
    'CafePress Communication', 
    'Affiliate ID (CJ PID)', 
    'cj-pid', 
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
    'product-count',    
    'text', 
    false,
    'Default number of product to show.'
);

$MP_cafepress_plugin->settings->add_item(
    'Product Display', 
    'Store ID',                     
    'storeid',          
    'text', 
    false,
    'The default store ID. The plugin will show items from this store '.
        'if you don\'t specify a store in the shortcode.'
);

$MP_cafepress_plugin->settings->add_item(
    'Product Display', 
    'Section ID',                   
    'sectionid',        
    'text', 
    false,
    'The default section ID.  The plugin will show items from this section ' .
        'within your store if you don\'t specify a store in the shortcode.'
);

?>
