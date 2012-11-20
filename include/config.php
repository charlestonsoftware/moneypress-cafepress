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
    
    global $MP_cafepress_plugin;
    
    $MP_cafepress_plugin = new wpCSL_plugin__mpcafe(
        array(
            'prefix'                => MP_CAFEPRESS_PREFIX,
            'cache_path'            => MP_CAFEPRESS_PLUGINDIR . 'cache',
            'plugin_url'            => MP_CAFEPRESS_PLUGINURL,
            'plugin_path'           => MP_CAFEPRESS_PLUGINDIR,
            'basefile'              => MP_CAFEPRESS_BASENAME,

            'name'                  => 'MoneyPress : CafePress Edition',
            'url'                   => 'http://www.charlestonsw.com/product/moneypress-cafepress-edition/',
            'support_url'           => 'http://wordpress.org/support/plugin/moneypress-cafepress-edition',
            'purchase_url'          => 'http://www.charlestonsw.com/product/moneypress-cafepress-edition-pro-pack/',
            'rate_url'              => 'http://wordpress.org/extend/plugins/moneypress-cafepress-edition/',
            'forum_url'             => 'http://wordpress.org/support/plugin/moneypress-cafepress-edition/',

            'has_packages'          => true,

            'use_obj_defaults'      => true,
            'no_default_css'         => false,
            'css_prefix'            => 'csl_themes',

            'cache_obj_name'        => 'mpcafecache',            
            
            'driver_name'           => 'CafePress',
            'driver_type'           => 'Panhandler',
            'driver_args'           => array(
                    'api_key'       => get_option(MP_CAFEPRESS_PREFIX.'-api_key'),
                    'cj_pid'        => get_option(MP_CAFEPRESS_PREFIX.'-cj_pid'),
                    'return'        => get_option(MP_CAFEPRESS_PREFIX.'-return'),
                    'wait_for'      => get_option(MP_CAFEPRESS_PREFIX.'-wait_for'),
                    'list_action'   => get_option(MP_CAFEPRESS_PREFIX.'-list_action'),
                    ),
            'shortcodes'            => array('mpcafe','mp-cafepress','mp_cafepress','QuickCafe'),
            
        )
    );
    
    
    // Setup our optional packages
    //
    add_options_packages_for_mpcafe();        

}


/**************************************
 ** function: add_options_packages_for_mpcafe
 **
 ** Setup the option package list.
 **/
function add_options_packages_for_mpcafe() {
    global $MP_cafepress_plugin;
   
    // Setup metadata
    //
    $MP_cafepress_plugin->license->add_licensed_package(
            array(
                'name'              => 'Pro Pack',
                'help_text'         => 'A variety of enhancements are provided with this package.  ' .
                                       'See the <a href="'.$MP_cafepress_plugin->purchase_url.'" target="CSA">product page</a> for details.  If you purchased this add-on ' .
                                       'come back to this page to enter the license key to activate the new features.',
                'sku'               => 'MPCAFE-PRO',
                'paypal_button_id'  => 'DVLXFERYYF2VE',
                'paypal_upgrade_button_id' => 'DVLXFERYYF2VE'
            )
        );
    
    // Enable Features Is Licensed
    //
    if ($MP_cafepress_plugin->license->packages['Pro Pack']->isenabled_after_forcing_recheck()) {
             //--------------------------------
             // Enable Themes
             //
             $MP_cafepress_plugin->themes_enabled = true;
             $MP_cafepress_plugin->themes->css_dir = MP_CAFEPRESS_PLUGINDIR . 'css/';
    }        
}
