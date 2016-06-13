<?php
/*
Plugin Name: Simple Follow Buttons Plus
Plugin URI: https://simplefollowbuttons.com/plus/
Description: One of the most advanced WordPress follow button plugins available.
Version: 1.0.1
Author: Simple Share Buttons
Author URI: http://simplesharebuttons.com
License: GPLv2

Copyright 2014 - 2015 Simple Follow Buttons admin@simplesharebuttons.com

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    __     _ _            _         _   _
   / _|___| | |_____ __ _| |__ _  _| |_| |_ ___ _ _  ___
  |  _/ _ \ | / _ \ V  V / '_ \ || |  _|  _/ _ \ ' \(_-<
  |_| \___/_|_\___/\_/\_/|_.__/\_,_|\__|\__\___/_||_/__/

*/

//======================================================================
// 		CONSTANTS
//======================================================================

// set constants
define('SFBP_FILE', __FILE__);
define('SFBP_ROOT', dirname(__FILE__));
define('SFBP_VERSION', '1.0.1');
define('SFBP_CSS', plugin_dir_path(__FILE__) . 'sfbp.min.css');

//======================================================================
// 		 SFBP SETTINGS
//======================================================================

// get sfbp settings
$sfbp_settings = get_sfbp_settings();

//======================================================================
// 		INCLUDES
//======================================================================

// must be available for ajax calls
include_once plugin_dir_path(__FILE__) . '/system/views/admin_panel.php';

// frontend side functions
include_once plugin_dir_path(__FILE__) . '/system/controllers/buttons.php';
include_once plugin_dir_path(__FILE__) . '/system/controllers/styles.php';
include_once plugin_dir_path(__FILE__) . '/system/controllers/widget.php';

// the main follow buttons class that is called via AJAX
include_once plugin_dir_path(__FILE__) . '/system/controllers/lazy.php';

//======================================================================
// 		ADMIN ONLY
//======================================================================

// update first if needed
include_once plugin_dir_path(__FILE__) . '/system/models/database.php';

// register/deactivate/uninstall
register_activation_hook(__FILE__, 'sfbp_activate');
//register_deactivation_hook( __FILE__, 'sfbp_deactivate' );
register_uninstall_hook(__FILE__, 'sfbp_uninstall');

// sfbp admin area hook
add_action('plugins_loaded', 'sfbp_admin_area');

// sfbp admin area
function sfbp_admin_area()
{
    // if in admin area
    if (is_admin()) {
        // editor role effectively
        if (current_user_can('edit_posts')) {
            include_once plugin_dir_path(__FILE__) . '/system/controllers/admin_bits.php';

            // add the admin-wide styles
            add_action('admin_head', 'sfbp_admin_wide_styles');

            // if viewing an sfbp admin page
            if (is_sfbp_admin_page() === true) {
                // add the admin styles
                add_action('admin_print_styles', 'sfbp_admin_styles');

                // also include js
                add_action('admin_print_scripts', 'sfbp_admin_scripts');
            }

            // add menu to dashboard
            add_action('admin_menu', 'sfbp_menu');
        }

        // check user has the right kind of access
        if (current_user_can('manage_options')) {
            // lower than current version
            if (get_option('sfbp_version') < SFBP_VERSION) {
                // run upgrade script
                upgrade_sfbp(get_option('sfbp_version'));
            }

            // admin side functions
            include_once plugin_dir_path(__FILE__) . '/system/controllers/licensing.php';

            // if a post is set
            if (isset($_POST)) {
                // if the export settings function has been called
                if (isset($_POST['export_sfbp_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'export_sfbp_settings_nonce')) {
                    // export sfbp settings
                    export_sfbp_settings();
                }

                // if the import settings function has been called
                if (isset($_POST['import_sfbp_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'import_sfbp_settings_nonce')) {
                    // import sfbp settings
                    if (import_sfbp_settings()) {
                        // start a session if needed
                        @session_start();

                        // save success bool
                        $_SESSION['sfbp_import'] = true;
                    }
                }

                // if the import official settings function has been called
                if (isset($_POST['import_official_sfbp_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'import_official_sfbp_settings_nonce')) {
                    // delete the existing css file
                    // it will be recreated the next time a page is loaded
                    @unlink(SFBP_CSS);

                    // import official sfbp settings
                    if (import_official_sfbp_settings()) {
                        // start a session if needed
                        @session_start();

                        // save success bool
                        $_SESSION['sfbp_official_import'] = true;
                    }
                }
            }

            // if viewing an sfbp admin page
            if (is_sfbp_admin_page() === true) {
                // admin and sfbp admin pages only includes
                include_once plugin_dir_path(__FILE__) . '/system/models/admin_save.php';
                include_once plugin_dir_path(__FILE__) . '/system/helpers/forms.php';
            }
        }
    }
}

//======================================================================
// 		ADMIN HOOKS
//======================================================================

// add filter hook for plugin action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sfbp_settings_link');

//======================================================================
// 		SHORTCODES
//======================================================================

// if option is set to use free version shortcode
if ($sfbp_settings['use_sfb'] == 'Y') {
    // use old shortcode for new functions
    add_shortcode('sfb', 'sfbp_buttons');
    add_shortcode('sfb_hide', 'sfbp_hide');
} // using new shortcode
else {
    // register shortcode [sfbp] to show [sfbp_hide]
    add_shortcode('sfbp', 'sfbp_buttons');
    add_shortcode('sfbp_hide', 'sfbp_hide');
}

//======================================================================
// 		FRONTEND HOOKS
//======================================================================

// add follow buttons to content and/or excerpts
add_filter('the_content', 'sfbp_show_follow_buttons', (int)$sfbp_settings['sfbp_content_priority']);

// add sfbp to available widgets
add_action('widgets_init', create_function('', 'register_widget( "sfbp_widget" );'));

// add the hook to include css
add_action('wp_enqueue_scripts', 'sfbp_stylesheets');

// call scripts add function
add_action('wp_enqueue_scripts', 'sfbp_page_scripts');

// if lazy load is enabled
if ($sfbp_settings['lazy_load'] == 'Y') {
    // register the ajax action for lazy load
    add_action('wp_ajax_sfbp_lazy', 'sfbp_lazy_callback');
    add_action('wp_ajax_nopriv_sfbp_lazy', 'sfbp_lazy_callback');
}

// if email popup option is set
if ($sfbp_settings['email_popup'] == 'Y') {
    // include all required sfbp email functionality
    include_once SFBP_ROOT.'/system/controllers/email.php';
}

// if we need to load the font in the head
if ($sfbp_settings['load_font_in_head'] == 'Y') {
    // add sfbp font in head
    add_action('wp_head', 'sfbp_font_in_head');
}

//======================================================================
// 		GET SFBP SETTINGS
//======================================================================

// return sfbp settings
function get_sfbp_settings()
{
    // get json array settings from DB
    $jsonSettings = get_option('sfbp_settings');

    // decode and return settings
    return json_decode($jsonSettings, true);
}

//======================================================================
// 		VIEWING AN SFBP ADMIN PAGE?
//======================================================================

// check we're on an SFBP admin page
function is_sfbp_admin_page()
{
    // are we viewing a page?
    if (isset($_GET['page'])) {
        // an array of sfbp admin pages
        $arrSFBPAdmin = array(
            'simple-follow-buttons-plus',
            'simple-follow-buttons-setup',
            'simple-follow-buttons-styling',
            'simple-follow-buttons-post_types',
            'simple-follow-buttons-advanced',
            'simple-follow-buttons-license',
        );

        // are we viewing an sfbp admin page?
        if (in_array($_GET['page'], $arrSFBPAdmin)) {
            // return true
            return true;
        } // not an sfbp admin page
        else {
            // return false
            return false;
        }
    } // not viewing an admin page
    else {
        // return false
        return false;
    }
}

//======================================================================
// 		UPDATE SFBP SETTINGS
//======================================================================

// update an array of options
function sfbp_update_options($arrOptions)
{
    // if not given an array
    if (!is_array($arrOptions)) {
        die('Value parsed not an array');
    }

    // get sfbp settings
    $jsonSettings = get_option('sfbp_settings');

    // decode the settings
    $sfbp_settings = json_decode($jsonSettings, true);

    // loop through array given
    foreach ($arrOptions as $name => $value) {
        // update/add the option in the array
        $sfbp_settings[$name] = $value;
    }

    // encode the options ready to save back
    $jsonSettings = json_encode($sfbp_settings);

    // update the option in the db
    update_option('sfbp_settings', $jsonSettings);
}
