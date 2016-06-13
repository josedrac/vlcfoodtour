<?php
defined('ABSPATH') or die('No direct access permitted');

// main dashboard
function sfbp_dashboard()
{
    // check if user has the rights to manage options
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // --------- ADMIN DASHBOARD ------------ //
    sfbp_admin_dashboard();
}

// main settings
function sfbp_settings()
{
    // check if user has the rights to manage options
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // if a post has been made
    if (isset($_POST['sfbpData'])) {
        // get posted data
        $sfbpPost = $_POST['sfbpData'];
        parse_str($sfbpPost, $sfbpPost);

        // if the nonce doesn't check out...
        if (!isset($sfbpPost['sfbp_save_nonce']) || !wp_verify_nonce($sfbpPost['sfbp_save_nonce'], 'sfbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'pages' => (isset($sfbpPost['pages']) ? $sfbpPost['pages'] : null),
            'posts' => (isset($sfbpPost['posts']) ? $sfbpPost['posts'] : null),
            'before_or_after' => (isset($sfbpPost['before_or_after']) ? $sfbpPost['before_or_after'] : null),
            'follow_text' => (isset($sfbpPost['follow_text']) ? stripslashes_deep($sfbpPost['follow_text']) : null),
            'selected_buttons' => (isset($sfbpPost['selected_buttons']) ? $sfbpPost['selected_buttons'] : null),
        );

        // prepare array of buttons
        $arrButtons = json_decode(get_option('sfbp_buttons'), true);

        // loop through each button
        foreach ($arrButtons as $button => $arrButton) {
            // add custom button to array of options
            $arrOptions['url_'.$button] = $sfbpPost['url_'.$button];
        }

        // save the settings
        sfbp_update_options($arrOptions);

        // delete the existing css file
        // it will be recreated the next time a page is loaded
        @unlink(SFBP_CSS);

        return true;
    }

    // include required admin view
    include_once SFBP_ROOT . '/system/views/settings.php';

    // --------- ADMIN PANEL ------------ //
    sfbp_admin_panel();
}

// styling settings
function sfbp_styling()
{
    // check if user has the rights to manage options
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // if a post has been made
    if (isset($_POST['sfbpData'])) {
        // get posted data
        $sfbpPost = $_POST['sfbpData'];
        parse_str($sfbpPost, $sfbpPost);

        // if the nonce doesn't check out...
        if (!isset($sfbpPost['sfbp_save_nonce']) || !wp_verify_nonce($sfbpPost['sfbp_save_nonce'], 'sfbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'one_breakpoint' => (isset($sfbpPost['one_breakpoint']) ? $sfbpPost['one_breakpoint'] : null),
            'one_toggle' => (isset($sfbpPost['one_toggle']) ? $sfbpPost['one_toggle'] : null),
            'one_responsive' => (isset($sfbpPost['one_responsive']) ? $sfbpPost['one_responsive'] : null),
            'default_style' => (isset($sfbpPost['default_style']) ? $sfbpPost['default_style'] : null),
            'set_one_position' => (isset($sfbpPost['set_one_position']) ? $sfbpPost['set_one_position'] : null),
            'additional_css' => (isset($sfbpPost['additional_css']) ? stripslashes_deep($sfbpPost['additional_css']) : null),
            'icon_color' => (isset($sfbpPost['icon_color']) ? $sfbpPost['icon_color'] : null),
            'icon_color_hover' => (isset($sfbpPost['icon_color_hover']) ? $sfbpPost['icon_color_hover'] : null),
            'icon_size' => (isset($sfbpPost['icon_size']) ? $sfbpPost['icon_size'] : null),
            'button_height' => (isset($sfbpPost['button_height']) ? $sfbpPost['button_height'] : null),
            'button_width' => (isset($sfbpPost['button_width']) ? $sfbpPost['button_width'] : null),
            'custom_styles_enabled' => (isset($sfbpPost['custom_styles_enabled']) ? $sfbpPost['custom_styles_enabled'] : null),
            'custom_styles' => (isset($sfbpPost['custom_styles']) ? $sfbpPost['custom_styles'] : null),
            'button_margin' => (isset($sfbpPost['button_margin']) ? $sfbpPost['button_margin'] : null),
            'text_placement' => (isset($sfbpPost['text_placement']) ? $sfbpPost['text_placement'] : null),
            'font_family' => $sfbpPost['font_family'],
            'font_color' => $sfbpPost['font_color'],
            'font_size' => $sfbpPost['font_size'],
            'font_weight' => $sfbpPost['font_weight'],
            'color_main' => (isset($sfbpPost['color_main']) ? $sfbpPost['color_main'] : null),
            'color_hover' => (isset($sfbpPost['color_hover']) ? $sfbpPost['color_hover'] : null),
            'custom_images' => (isset($sfbpPost['custom_images']) ? $sfbpPost['custom_images'] : null),
            'image_width' => (isset($sfbpPost['image_width']) ? $sfbpPost['image_width'] : null),
            'image_height' => (isset($sfbpPost['image_height']) ? $sfbpPost['image_height'] : null),
            'image_padding' => (isset($sfbpPost['image_padding']) ? $sfbpPost['image_padding'] : null),
        );

        // prepare array of buttons
        $arrButtons = json_decode(get_option('sfbp_buttons'), true);

        // loop through each button
        foreach ($arrButtons as $button => $arrButton) {
            // add custom button to array of options
            $arrOptions['custom_'.$button] = $sfbpPost['custom_'.$button];
        }

        // save the settings
        sfbp_update_options($arrOptions);

        // delete the existing css file
        // it will be recreated the next time a page is loaded
        @unlink(SFBP_CSS);

        return true;
    }

    // include required admin view
    include_once SFBP_ROOT . '/system/views/styling.php';

    // --------- STYLING PANEL ------------ //
    sfbp_admin_styling();
}

// advanced settings
function sfbp_advanced()
{
    // if a post has been made
    if (isset($_POST['sfbpData'])) {
        // get posted data
        $sfbpPost = $_POST['sfbpData'];
        parse_str($sfbpPost, $sfbpPost);

        // if the nonce doesn't check out...
        if (!isset($sfbpPost['sfbp_save_nonce']) || !wp_verify_nonce($sfbpPost['sfbp_save_nonce'], 'sfbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'sfbp_content_priority' => (isset($sfbpPost['sfbp_content_priority']) ? $sfbpPost['sfbp_content_priority'] : null),
            'lazy_load' => (isset($sfbpPost['lazy_load']) ? $sfbpPost['lazy_load'] : null),
            'rel_nofollow' => (isset($sfbpPost['rel_nofollow']) ? stripslashes_deep($sfbpPost['rel_nofollow']) : null),
            'widget_text' => stripslashes_deep($sfbpPost['widget_text']),
            'email_message' => stripslashes_deep($sfbpPost['email_message']),
            'email_popup' => stripslashes_deep($sfbpPost['email_popup']),
            'email_popup_brute_time' => stripslashes_deep($sfbpPost['email_popup_brute_time']),
            'email_popup_alert_brute' => stripslashes_deep($sfbpPost['email_popup_alert_brute']),
            'email_popup_alert_success' => stripslashes_deep($sfbpPost['email_popup_alert_success']),
            'email_popup_alert_warning' => stripslashes_deep($sfbpPost['email_popup_alert_warning']),
            'email_popup_subject' => stripslashes_deep($sfbpPost['email_popup_subject']),
            'email_popup_from_name' => stripslashes_deep($sfbpPost['email_popup_from_name']),
            'email_popup_to_email' => stripslashes_deep($sfbpPost['email_popup_to_email']),
            'use_sfb' => (isset($sfbpPost['use_sfb']) ? $sfbpPost['use_sfb'] : null),
            'load_font_in_head' => (isset($sfbpPost['load_font_in_head']) ? $sfbpPost['load_font_in_head'] : null),
        );

        // save the settings
        sfbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SFBP_ROOT . '/system/views/advanced.php';

    // --------- ADVANCED PANEL ------------ //
    sfbp_admin_advanced();
}

// custom post type settings
function sfbp_post_types()
{
    // if a post has been made
    if (isset($_POST['sfbpData'])) {
        // get posted data
        $sfbpPost = $_POST['sfbpData'];
        parse_str($sfbpPost, $sfbpPost);

        // if the nonce doesn't check out...
        if (!isset($sfbpPost['sfbp_save_nonce']) || !wp_verify_nonce($sfbpPost['sfbp_save_nonce'], 'sfbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'disabled_types' => (isset($sfbpPost['sfbp_disabled_types']) ? implode(',', $sfbpPost['sfbp_disabled_types']) : null),
        );

        // save the settings
        sfbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SFBP_ROOT . '/system/views/post_types.php';

    // --------- CUSTOM POST TYPES PANEL ------------ //
    sfbp_admin_post_types();
}
