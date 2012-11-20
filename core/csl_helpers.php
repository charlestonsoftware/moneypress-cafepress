<?php
/****************************************************************************
 ** file: csl_helpers.php
 **
 ** Generic helper functions.  May live in WPCSL-Generic soon.
 ***************************************************************************/

 
/**************************************
 ** function: csl_slplus_setup_admin_interface
 **
 ** Builds the interface elements used by WPCSL-generic for the admin interface.
 **/
function csl_mpcafe_setup_admin_interface() {
    global $MP_cafepress_plugin;
    
    // Don't have what we need? Leave.
    if (!isset($MP_cafepress_plugin)) { return; }    
    
    // Already been here?  Get out.
    if (isset($MP_cafepress_plugin->settings->sections['How to Use'])) { return; }    
    
    // No SimpleXML Support
    if (!function_exists('simplexml_load_string')) {
        $MP_cafepress_plugin->notifications->add_notice(1, __('SimpleXML is required but not enabled.',MP_CAFEPRESS_PREFIX));
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
    
    
    $MP_cafepress_plugin->settings->add_item(
        'CafePress Communication', 
        'Fetch Products Using ',   
        'list_action',    
        'list', 
        false, 
        'Which CafePress API action should we use to fetch products? (default: List By Store Section).',
        array  (
            'List By Store Section' => 'product.listByStoreSection',
            'List By Store' => 'product.listByStore',
            'List By Deep Store' => 'product.listDeepByStore',
            'List Anonymous Products' => 'product.listAnonymousProducts'
            
            )
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
        'The default store ID to use if not specified (default: cybersprocket).'
    );
    
    
    $MP_cafepress_plugin->settings->add_item(
        'Product Display', 
        'Section ID',   
        'section_id',    
        'text', 
        false,
        'The default section ID to use if not sepcified (default: 0).'
    );
    
    if ($MP_cafepress_plugin->license->packages['Pro Pack']->isenabled) {    
        csl_mpcafe_add_settings();
    }
    
}

/**************************************
 ** function: csl_mpcafe_admin_stylesheet
 **
 ** Add the admin stylesheets to admin pages.
 **/
function csl_mpcafe_activate() {
    global $MP_cafepress_plugin;

    
    // Force local if not set
    //
    if (get_option(MP_CAFEPRESS_PREFIX . '-locale') === '') {
        update_option(MP_CAFEPRESS_PREFIX . '-locale','C');    
    }
    
    // Force Money Format If Not Set
    //
    if (get_option(MP_CAFEPRESS_PREFIX . '-money_format')  === '') {
        update_option(MP_CAFEPRESS_PREFIX . '-money_format','%!i');
    }    
}


/**************************************
 ** function: csl_mpcafe_user_stylesheet
 **
 ** Add the user stylesheets to user pages.
 **/
function csl_mpcafe_user_stylesheet() {
    global $MP_cafepress_plugin;
    $MP_cafepress_plugin->themes->assign_user_stylesheet();
}


/**************************************
 ** function: csl_mpcafe_admin_stylesheet
 **
 ** Add the admin stylesheets to admin pages.
 **/
function csl_mpcafe_admin_stylesheet() {
    if ( file_exists(MP_CAFEPRESS_COREDIR.'css/admin.css')) {
        wp_enqueue_style('csl_mpcafe_admin_css', MP_CAFEPRESS_COREURL .'css/admin.css'); 
    }  
}


