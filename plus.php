<?php
/****************************************************************************
 ** file: plus.php
 **
 ***************************************************************************/
  
/**************************************
 ** function: csl_mpcafe_add_settings()
 ** 
 ** Add plus settings to the admin interface.
 **
 **/
function csl_mpcafe_add_settings() {
    global $MP_cafepress_plugin;
    
    $MP_cafepress_plugin->settings->add_section(
        array(
            'name'          => __('Display Settings',MP_CAFEPRESS_PREFIX),
            'description'   => ''
        )
    );    
    
    $MP_cafepress_plugin->settings->add_item(
        'Display Settings', 
        __('Link Modifiers',MP_CAFEPRESS_PREFIX), 
        'link_modifiers', 
        'text', 
        false,
        __('Enter any modifiers you want to add to the external product link. ' .
        'Must be a fully formed anchor (A tag) qualifier.  Example: rel="nofollow"',MP_CAFEPRESS_PREFIX)
    );    
}

