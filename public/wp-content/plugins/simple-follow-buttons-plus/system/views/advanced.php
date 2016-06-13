<?php

defined('ABSPATH') or die('No direct access permitted');

function sfbp_admin_advanced()
{

    // make sure we have settings ready
    $sfbp_settings = get_sfbp_settings();

    // sfbp header
    $htmlFollowButtonsForm = sfbp_admin_header();

    // heading
    $htmlFollowButtonsForm .= '<h2>Advanced Settings</h2>';

    // initiate forms helper
    $sfbpForm = new sfbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-follow-buttons-advanced');

    // opening form tag
    $htmlFollowButtonsForm .= $sfbpForm->open(false, $action);

    // tabs
    $htmlFollowButtonsForm .= '<ul class="nav nav-tabs">
							  <li class="active"><a href="#misc" data-toggle="tab">Misc</a></li>
							  <li><a href="#email" data-toggle="tab">Email</a></li>
							</ul>';
    // tab content div
    $htmlFollowButtonsForm .= '<div id="sfbpTabContent" class="tab-content">';

    //======================================================================
    // 		MISC
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade active in" id="misc">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>You\'ll find a number of advanced and miscellaneous options below, to get your follow buttons functioning just how you would like.</p></blockquote>';

    // column for padding
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';

    // content priority
    $opts = array(
        'form_group' => false,
        'type' => 'number',
        'placeholder' => '10',
        'name' => 'sfbp_content_priority',
        'label' => 'Content Priority',
        'tooltip' => 'Set the priority for your follow buttons within your content. 1-10, default is 10',
        'value' => $sfbp_settings['sfbp_content_priority'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // load_font_in_head
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'load_font_in_head',
        'label' => 'Load SFBP Font in Head',
        'tooltip' => 'Enable this option to allow for the use of Async CSS loading',
        'value' => 'Y',
        'checked' => ($sfbp_settings['load_font_in_head'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // lazy loading
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'lazy_load',
        'label' => 'Lazy Loading',
        'tooltip' => 'Buttons will appear after page loads, strongly recommended if counters are enabled',
        'value' => 'Y',
        'checked' => ($sfbp_settings['lazy_load'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // use sfb
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'use_sfb',
        'label' => 'Use [sfb]',
        'tooltip' => 'Disable Simple Follow Buttons first! Use [sfb] instead of [sfbp]',
        'value' => 'Y',
        'checked' => ($sfbp_settings['use_sfb'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // nofollow
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'rel_nofollow',
        'label' => 'Nofollow',
        'tooltip' => 'Switch on to add nofollow to links',
        'value' => 'Y',
        'checked' => ($sfbp_settings['rel_nofollow'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // widget follow text
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Keeping sharing simple...',
        'name' => 'widget_text',
        'label' => 'Widget Follow Text',
        'tooltip' => 'Add custom follow text when used as a widget',
        'value' => $sfbp_settings['widget_text'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close misc
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		EMAIL
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="email">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>Customisable email functionality options.</p></blockquote>';

    // column for padding
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';

    // open well
    $htmlFollowButtonsForm .= '<div class="well">';

    // email popup heading
    $htmlFollowButtonsForm .= '<h3>Email Popup</h3>';

    // email_popup
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'email_popup',
        'label' => 'Enable Email Popup',
        'tooltip' => 'Enables the SFBP Email Follow functionality. Disabling this results in mailto links in it\'s place',
        'value' => 'Y',
        'checked' => ($sfbp_settings['email_popup'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_subject
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "Your friend thinks you'd be interested in this",
        'name' => 'email_popup_subject',
        'label' => 'Email Subject',
        'tooltip' => 'The subject that will be used for Email Popup emails',
        'value' => $sfbp_settings['email_popup_subject'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_from_name
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "Site Name",
        'name' => 'email_popup_from_name',
        'label' => 'Email From Name',
        'tooltip' => 'The from name that will be used for Email Popup emails',
        'value' => $sfbp_settings['email_popup_from_name'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_to_email
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "your@domain.com",
        'name' => 'email_popup_to_email',
        'label' => 'Email to Address',
        'tooltip' => 'The email to send Email Popup emails to',
        'value' => $sfbp_settings['email_popup_to_email'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_brute_time
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'minutes',
        'placeholder' => '5',
        'name' => 'email_popup_brute_time',
        'label' => 'Time Between Emails',
        'tooltip' => 'The length of time (in minutes) between which an individual IP can send an email.',
        'value' => $sfbp_settings['email_popup_brute_time'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_alert_success
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Thanks, your email has been sent',
        'name' => 'email_popup_alert_success',
        'label' => 'Success Alert Text',
        'tooltip' => 'The alert displayed upon successfully sending an email',
        'value' => $sfbp_settings['email_popup_alert_success'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_alert_warning
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Please check the fields and try again',
        'name' => 'email_popup_alert_warning',
        'label' => 'Warning Alert Text',
        'tooltip' => 'Add some text included in the email when people follow that way',
        'value' => $sfbp_settings['email_popup_alert_warning'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // email_popup_alert_brute
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Sorry, the time between sending emails is limited',
        'name' => 'email_popup_alert_brute',
        'label' => 'Brute Alert Text',
        'tooltip' => 'The alert displayed if someone tries to email before their Time Between Emails has passed.',
        'value' => $sfbp_settings['email_popup_alert_brute'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close google analytics
    $htmlFollowButtonsForm .= '</div>';

    // close tab content div
    $htmlFollowButtonsForm .= '</div>';

    // close off form with save button
    $htmlFollowButtonsForm .= $sfbpForm->close();

    // sfbp footer
    $htmlFollowButtonsForm .= sfbp_admin_footer();

    echo $htmlFollowButtonsForm;
}
