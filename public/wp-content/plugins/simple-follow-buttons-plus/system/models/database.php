<?php

defined('ABSPATH') or die('No direct access permitted');

// activate sfbp function
function sfbp_activate()
{
    // likely a reactivation, return doing nothing
    if (get_option('sfbp_version') !== false) {
        return;
    }

    // get site name and admin email
    $site_name = get_bloginfo('name');
    $admin_email = get_bloginfo('admin_email');

    // array ready with defaults
    $sfbp_settings = array(
        'additional_css' => '',
        'before_or_after' => 'after',
        'button_margin' => '',
        'button_height' => '',
        'button_width' => '',
        'cats_archs' => '',
        'color_main' => '',
        'color_hover' => '',
        'custom_images' => '',
        'custom_styles' => '',
        'custom_styles_enabled' => '',
        'default_style' => '2',
        'disabled_types' => '',
        'email_popup' => 'Y',
        'email_popup_brute_time' => '5',
        'email_popup_alert_brute' => 'The email popup functionality is restricted to one email every five minutes, please try again soon',
        'email_popup_alert_success' => 'Thanks, your email has been sent',
        'email_popup_alert_warning' => 'Please check the fields and try again',
        'email_popup_subject' => "Someone sent you an email using Simple Follow Buttons Plus",
        'email_popup_from_name' => $site_name,
        'email_popup_to_email' => $admin_email,
        'font_color' => '',
        'font_family' => '',
        'font_size' => '20',
        'font_weight' => 'normal',
        'icon_color' => '',
        'icon_color_hover' => '',
        'icon_size' => '',
        'image_height' => '32',
        'image_padding' => '5',
        'image_width' => '32',
        'lazy_load' => '',
        'load_font_in_head' => '',
        'one_breakpoint' => '480',
        'one_responsive' => 'Y',
        'one_follow_counts' => '',
        'one_toggle' => 'Y',
        'pages' => '',
        'posts' => '',
        'rel_nofollow' => '',
        'selected_buttons' => 'facebook,google,linkedin,twitter',
        'set_one_position' => 'fixed-left',
        'follow_text' => '',
        'show_full_stats' => '',
        'sfbp_content_priority' => '10',
        'text_placement' => 'above',
        'use_sfb' => '',
        'widget_text' => '',
    );

    // prepare array of buttons
    $arrButtons = sfbp_button_helper_array();

    // update/add buttons helper
    update_option('sfbp_buttons', json_encode($arrButtons));

    // loop through each button
    foreach ($arrButtons as $button => $arrButton) {
        // add custom button to array of options
        $sfbp_settings['sfbp_custom_'.$button] = '';
        $sfbp_settings['url_'.$button] = '';
    }

    // json encode
    $jsonSettings = json_encode($sfbp_settings);

    // insert default options for sfbp
    add_option('sfbp_settings', $jsonSettings);

    // save settings to json file
    sfbp_update_options($sfbp_settings);

    // add email log table
    sfbp_add_email_log_table();

    // button helper array
    sfbp_button_helper_array();

    // add sfbp version as a separate option
    add_option('sfbp_version', SFBP_VERSION);
}

// add email log table
function sfbp_add_email_log_table()
{
    // wpdb global
    global $wpdb;

    // use prefix to signup table name
    $table_name = $wpdb->prefix.'sfbp_email_log';

    // prepare sql
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  url VARCHAR(250) NOT NULL,
			  email VARCHAR(250) NOT NULL,
			  ip VARCHAR(45) NOT NULL,
			  datetime DATETIME NOT NULL,
			  UNIQUE KEY id (id)
			) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

    // include wp upgrade functionality and add table
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// uninstall sfbp function
function sfbp_uninstall()
{
    //if uninstall not called from WordPress exit
    if (defined('WP_UNINSTALL_PLUGIN')) {
        exit();
    }

    // delete sfbp options
    delete_option('sfbp_version');
    delete_option('sfbp_settings');
    delete_option('sfbp_buttons');

    // global db
    global $wpdb;

    // drop email log
    $table_name = $wpdb->prefix . 'sfbp_email_log';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

// the upgrade function
function upgrade_sfbp($sfbpVersion)
{
    // initial installation, do not proceed with upgrade script
    if ($sfbpVersion === false) {
        return;
    }

    // lower than 1.0.1
    if ($sfbpVersion < '1.0.1') {
        // new buttons
        $newButtons = array(
            'instagram' => array(
                'full_name'    => 'Instagram',
                'url_prefix' => 'https://www.instagram.com/',
            ),
            'soundcloud' => array(
                'full_name'    => 'SoundCloud',
                'url_prefix' => 'https://soundcloud.com/',
            ),
            'youtube' => array(
                'full_name'    => 'YouTube',
                'url_prefix' => 'https://www.youtube.com/user/',
            ),
        );

        $new = array();

        // loop through each new button
        foreach ($newButtons as $button => $arrButton) {
            // add custom button to array of options
            $new['sfbp_custom_'.$button] = '';
            $new['url_'.$button] = '';
        }

        // save new settings
        sfbp_update_options($new);
    }

//    // lower than 1.0.2
//    if ($sfbpVersion < '1.0.2') {
//        // new settings
//        $new = array(
//            '' => '',
//        );
//
//        // save new settings
//        sfbp_update_options($new);
//    }

    // prepare array of buttons
    $arrButtons = sfbp_button_helper_array();

    // update/add buttons helper
    update_option('sfbp_buttons', json_encode($arrButtons));

    // set new version number
    update_option('sfbp_version', SFBP_VERSION);
}

// button helper option
function sfbp_button_helper_array()
{
    // helper array for sfbp
    return array(
        'diggit' => array(
            'full_name' => 'Diggit',
            'url_prefix' => 'http://digg.com/source/',
        ),
        'email' => array(
            'full_name'    => 'Email',
        ),
        'facebook' => array(
            'full_name'    => 'Facebook',
            'url_prefix' => 'https://www.facebook.com/',
        ),
        'google' => array(
            'full_name'    => 'Google+',
            'url_prefix' => 'https://plus.google.com/+',
        ),
        'instagram' => array(
            'full_name'    => 'Instagram',
            'url_prefix' => 'https://www.instagram.com/',
        ),
        'linkedin' => array(
            'full_name'    => 'LinkedIn',
            'url_prefix' => 'https://linkedin.com/in/',
        ),
        'pinterest' => array(
            'full_name'    => 'Pinterest',
            'url_prefix' => 'https://www.pinterest.com/',
        ),
        'reddit' => array(
            'full_name'    => 'Reddit',
            'url_prefix' => 'https://www.reddit.com/user/',
        ),
        'soundcloud' => array(
            'full_name'    => 'SoundCloud',
            'url_prefix' => 'https://soundcloud.com/',
        ),
        'tumblr' => array(
            'full_name'    => 'Tumblr',
            'url_prefix' => 'http://',
            'url_suffix' => '.tumblr.com',
        ),
        'twitter' => array(
            'full_name'    => 'Twitter',
            'url_prefix' => 'https://twitter.com/',
        ),
        'vk' => array(
            'full_name'    => 'VK',
            'url_prefix' => 'https://vk.com/',
        ),
        'youtube' => array(
            'full_name'    => 'YouTube',
            'url_prefix' => 'https://www.youtube.com/user/',
        ),
        'yummly' => array(
            'full_name'    => 'Yummly',
            'url_prefix' => 'http://www.yummly.co.uk/page/',
        ),
    );
}
