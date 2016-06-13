<?php

defined('ABSPATH') or die('No direct access permitted');

// get sfbp settings
$sfbp_settings = get_sfbp_settings();

// add settings link on plugin page
function sfbp_settings_link($links)
{
    // add to plugins links
    array_unshift($links, '<a href="admin.php?page=simple-follow-buttons-plus">Get started</a>');

    // return all links
    return $links;
}

// admin js
function sfbp_preview_js()
{
    // preview js
    wp_register_script('sfbpPreview', plugins_url('js/admin/preview.js', SFBP_FILE));
    wp_enqueue_script('sfbpPreview');
}

// include js files and upload script
function sfbp_admin_scripts()
{
    wp_enqueue_media();

    // ready available with wp
    wp_enqueue_script('media-upload');
    wp_enqueue_script('jquery-ui');
    wp_enqueue_script('jquery-ui-sortable');

    // bootstrap
    wp_register_script('sfbpBootstrap', plugins_url('js/admin/bootstrap.js', SFBP_FILE));
    wp_enqueue_script('sfbpBootstrap');

    // filestyle
    wp_register_script('sfbpFilestyle', plugins_url('js/admin/filestyle.js', SFBP_FILE));
    wp_enqueue_script('sfbpFilestyle');

    // bootstrap switch
    wp_register_script('sfbpSwitch', plugins_url('js/admin/switch.js', SFBP_FILE));
    wp_enqueue_script('sfbpSwitch');

    // bootstrap colorpicker
    wp_register_script('sfbpColorPicker', plugins_url('js/admin/colorpicker.js', SFBP_FILE));
    wp_enqueue_script('sfbpColorPicker');

    // if viewing the styling page
    if ($_GET['page'] == 'simple-follow-buttons-styling') {
        // include custom css file
        add_action('admin_head', 'sfbp_style_head');

        // include preview js
        add_action('admin_head', 'sfbp_preview_js');
    }

    // finish with sfbp admin
    wp_register_script('sfbp-js', plugins_url('js/admin/admin.js', SFBP_FILE));
    wp_enqueue_script('sfbp-js');
}

// include styles for all admin pages
function sfbp_admin_wide_styles()
{
    // add image upload functionality
    wp_register_script('sfbp-upload-js', plugins_url('js/admin/upload.js', SFBP_FILE));
    wp_enqueue_script('sfbp-upload-js');

    // css styling
    $htmlSFBPStyle = '.toplevel_page_simple-follow-buttons-plus.wp-not-current-submenu > a > div.wp-menu-image.dashicons-before {
							content:""; background: url(' . plugins_url() . '/simple-follow-buttons-plus/images/simplefollowbuttons_menu.png) no-repeat center;background-size:26px 26px;background-position: center;
						}';
    $htmlSFBPStyle .= '.toplevel_page_simple-follow-buttons-plus.wp-has-current-submenu > a > div.wp-menu-image.dashicons-before {
							content:""; background: url(' . plugins_url() . '/simple-follow-buttons-plus/images/simplefollowbuttons_menu_selected.png) no-repeat center;background-size:26px 26px;background-position: center;
						}';

    // wrap css in style tags
    $htmlSFBPStyle = '<style type="text/css">' . $htmlSFBPStyle . '</style>';

    // return
    echo $htmlSFBPStyle;
}

// include styles for the sfbp admin panel
function sfbp_admin_styles()
{
    // admin styles
    wp_register_style('sfbp-colorpicker', plugins_url('css/colorpicker.css', SFBP_FILE));
    wp_enqueue_style('sfbp-colorpicker');
    wp_register_style('sfbp-bootstrap-style', plugins_url('css/readable.css', SFBP_FILE));
    wp_enqueue_style('sfbp-bootstrap-style');
    wp_register_style('sfbp-admin-theme', plugins_url('followbuttons/assets/css/sfbp-all.css', SFBP_FILE));
    wp_enqueue_style('sfbp-admin-theme');
    wp_register_style('sfbp-switch-styles', plugins_url('css/switch.css', SFBP_FILE));
    wp_enqueue_style('sfbp-switch-styles');
    wp_register_style('sfbp-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    wp_enqueue_style('sfbp-font-awesome');

    // this one last to overwrite any CSS it needs to
    wp_register_style('sfbp-admin-style', plugins_url('css/style.css', SFBP_FILE));
    wp_enqueue_style('sfbp-admin-style');
}

// menu settings
function sfbp_menu()
{
    // get sfbp settings
    $sfbp_settings = get_sfbp_settings();

    // add menu page
    add_menu_page('Simple Follow Buttons Plus', 'Follow Buttons', 'manage_options', 'simple-follow-buttons-plus', 'sfbp_dashboard', 'none');
    add_submenu_page('Simple Follow Buttons Plus', 'Follow Buttons', 'manage_options', 'simple-follow-buttons-plus', 'sfbp_dashboard');
    add_submenu_page('simple-follow-buttons-plus', 'Setup', 'Setup', 'manage_options', 'simple-follow-buttons-setup', 'sfbp_settings');
    add_submenu_page('simple-follow-buttons-plus', 'Styling', 'Styling', 'manage_options', 'simple-follow-buttons-styling', 'sfbp_styling');
    add_submenu_page('simple-follow-buttons-plus', 'Post Types', 'Post Types', 'manage_options', 'simple-follow-buttons-post_types', 'sfbp_post_types');
    add_submenu_page('simple-follow-buttons-plus', 'Advanced', 'Advanced', 'manage_options', 'simple-follow-buttons-advanced', 'sfbp_advanced');
    add_submenu_page('simple-follow-buttons-plus', 'License', 'License', 'manage_options', 'simple-follow-buttons-license', 'sfbp_licensing');

    // lower than current version
    if (get_option('sfbp_version') < SFBP_VERSION) {
        // run the upgrade function
        upgrade_sfbp(get_option('sfbp_version'));
    }
}

// add latest stats to dashboard
function sfbp_add_dashboard_widgets()
{
    wp_add_dashboard_widget(
        'sfbp_dashboard_widget',         // Widget slug.
        'Simple Follow Buttons Stats',    // Title.
        'sfbp_dashboard_widget_function' // Display function.
    );
}

// export sfbp settings
function export_sfbp_settings()
{
    // get settings
    $jsonSettings = get_option('sfbp_settings');

    // create file name variable
    $sfbpFilename = 'sfbp_settings.txt';

    // output headers so that the file is downloaded rather than displayed
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=$sfbpFilename");
    header('Expires: 0');
    header('Pragma: public');

    // open temp file
    $out = fopen('php://output', 'w');

    // add headings to csv
    fwrite($out, $jsonSettings);

    // close and exit, forcing download
    fclose($out);

    // exit and download
    exit;
}

// import official sfbp settings
function import_official_sfbp_settings()
{
    // get the settings
    $get = wp_remote_get('https://simplefollowbuttons.com/sfbp_settings.php', array('timeout' => 8));

    // if error
    if (is_wp_error($get)) {
        die('Unable to retrieve settings');
    }

    // extract body content
    $body = $get['body'];

    // decode settings
    $settings = json_decode($body, true);

    // array of settings to ignore
    $ignore = array(
        'meta_enabled',
        'meta_title',
        'meta_description',
        'meta_image',
    );

    // remove the ignore keys
    $settings = array_diff_key($settings, array_flip($ignore));

    // update the options with the official ones
    sfbp_update_options($settings);

    // return success
    return true;
}

// import sfbp settings
function import_sfbp_settings()
{
    // if not file has been posted die
    if (!isset($_FILES['sfbp_settings_txt']['tmp_name'])) {
        die('No settings file found');
    }

    // set a variable
    $sfbpSettingsJsonFile = $_FILES['sfbp_settings_txt']['tmp_name'];

    // read json contents and add to variable
    $fp = fopen($sfbpSettingsJsonFile, 'r');
    $jsonSettings = fread($fp, filesize($sfbpSettingsJsonFile));

    // decode and return settings
    $sfbp_settings = json_decode($jsonSettings, true);

    // update the options with the imported ones
    sfbp_update_options($sfbp_settings);

    // return success
    return true;
}
