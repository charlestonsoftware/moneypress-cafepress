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
            'use_obj_defaults'      => true,        
            'prefix'                => MP_CAFEPRESS_PREFIX,
            'css_prefix'            => 'csl_themes',
            'name'                  => 'MoneyPress : CafePress Edition',
            'url'                   => 'http://www.cybersprocket.com/products/wpquickcafepress/',
            'basefile'              => MP_CAFEPRESS_BASENAME,
            'plugin_path'           => MP_CAFEPRESS_PLUGINDIR,
            'plugin_url'            => MP_CAFEPRESS_PLUGINURL,
            'cache_path'            => MP_CAFEPRESS_PLUGINDIR . 'cache',
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
            
            'has_packages'           => true,       
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
    configure_mpcafe_propack();
}


/**************************************
 ** function: configure_mpcafe_propack
 **
 ** Configure the Pro Pack.
 **/
function configure_mpcafe_propack() {
    global $MP_cafepress_plugin;
   
    // Setup metadata
    //
    $MP_cafepress_plugin->license->add_licensed_package(
            array(
                'name'              => 'Pro Pack',
                'help_text'         => 'A variety of enhancements are provided with this package.  ' .
                                       'See the <a href="'.$MP_cafepress_plugin->purchase_url.'" target="Cyber Sprocket">product page</a> for details.  If you purchased this add-on ' .
                                       'come back to this page to enter the license key to activate the new features.',
                'sku'               => 'MPCAFE',
                'paypal_button_id'  => 'NRMZK9MRR7AML',
                'paypal_upgrade_button_id' => 'NRMZK9MRR7AML'
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
