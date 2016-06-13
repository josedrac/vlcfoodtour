<?php

// add email div to footer
add_action('wp_footer', function() use ($sfbp_settings) {
    // open email div
    echo '<div id="sfbp-email-div"><span class="sfbp-x sfbp-close-email-div"></span>';

    // alert
    echo '<div class="sfbp-email-alert" id="sfbp-email-alert"></div>';

    // output form
    echo '<form id="js-sfbp-email-form" method="post" action=""
                data-success-alert-text="'.$sfbp_settings['email_popup_alert_success'].'"
                data-warning-alert-text="'.$sfbp_settings['email_popup_alert_warning'].'"
                data-brute-alert-text="'.$sfbp_settings['email_popup_alert_brute'].'">
                <input type="hidden" id="fill_me" name="fill_me" value="" />'.
                wp_nonce_field('sfbp-email-nonce').'
                <div class="sfbp-form-group">
                    <label for="email" class="sfbp-required">Your Email</label>
                    <input type="email" class="sfbp-form-control sfbp-required" id="email" name="email" placeholder="your@email.com" required>
                </div>
                <div class="sfbp-form-group">
                    <label for="message" class="sfbp-required">Message</label>
                    <textarea maxlength="250" class="sfbp-form-control sfbp-required" rows="6" id="message" name="message" required></textarea>
                </div>
                <div class="sfbp-form-group sfbp-text-align-right">
                    <button id="sfbp-email-send" type="submit" class="sfbp-btn-primary">Send</button>
                </div>
             </form>';

    // add powered by link
    echo '<a href="https://simplefollowbuttons.com/plus/?utm_source=plus&utm_medium=plugin_powered_by&utm_campaign=powered_by&utm_content=plus_email" target="_blank"><img class="sfbp-email-powered-by" src="'.plugins_url().'/simple-follow-buttons-plus/images/simple-follow-buttons-logo-white.png" alt="Simple Follow Buttons" /></a>';

    // close email div
    echo '</div>';
});

function sfbp_email_enqueue()
{
    // ajax
    wp_enqueue_script('sfbp_email_send', plugins_url('simple-follow-buttons-plus/js/email.min.js'), array('jquery'), false, true);
    wp_localize_script('sfbp_email_send', 'sfbpEmail', array(

        // URL to wp-admin/admin-ajax.php to process the request
        'ajax_url' => admin_url('admin-ajax.php'),

        // generate a nonce with a unique ID
        'security' => wp_create_nonce('sfbp_email_send_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'sfbp_email_enqueue');

add_action('wp_ajax_sfbp_email_send', 'sfbp_email_send');
add_action('wp_ajax_nopriv_sfbp_email_send', 'sfbp_email_send');

function sfbp_email_send()
{
    // check honeypot
    if (! empty($_POST['fill_me'])) {
        echo 'bot';
        die;
    }

    // not a bot, include the class file
    include_once 'SFBP_Send_Email.php';

    // initiate class
    $sfbpEmail = new SFBP_Send_Email();

    // potential brute
    if ($sfbpEmail->is_brute()) {
        echo 'brute';
        die;
    }

    // invalid inputs
    if (! $sfbpEmail->valid_inputs($_POST['email'], $_POST['message'])) {
        echo 'check';
        die;
    }

    // send the email
    $sfbpEmail->send_email($_POST['email'], $_POST['message']);

    // add to email log
    $sfbpEmail->log_email($_POST['email']);

    // show success
    echo 'success';

    // die so no zero gets returned
    die;
}
