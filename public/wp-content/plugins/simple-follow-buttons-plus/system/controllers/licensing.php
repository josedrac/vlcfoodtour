<?php
//set_site_transient( 'update_plugins', null );
include dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php';

define('SFBP_SL_STORE_URL', 'https://simplesharebuttons.com');
define('SFBP_SL_ITEM_NAME', 'Simple Follow Buttons Plus');

function sfbp_sl_plugin_updater()
{
    // retrieve our license key from the DB
    $license_key = trim(get_option('sfbp_license_key'));

    // setup the updater
    $sfbp_updater = new SFB_SL_Plugin_Updater(SFBP_SL_STORE_URL, SFBP_FILE, array(
            'version' => SFBP_VERSION,        // current version number
            'license' => $license_key,    // license key (used get_option above to retrieve from DB)
            'item_name' => SFBP_SL_ITEM_NAME,    // name of this plugin
            'author' => 'Simple Follow Buttons',  // author of this plugin
            'url' => home_url(),
        )
    );
}

add_action('admin_init', 'sfbp_sl_plugin_updater');

function sfbp_activate_license()
{
    // listen for our activate button to be clicked
    if (isset($_POST['sfbp_license_activate'])) {

        // run a quick security check
        if (!check_admin_referer('sfbp_activate_nonce', 'sfbp_activate_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim(get_option('sfbp_license_key'));

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $license,
            'item_name' => urlencode(SFBP_SL_ITEM_NAME), // the name of our product in EDD,
            'url' => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_post(SFBP_SL_STORE_URL, array('timeout' => 10, 'sslverify' => false, 'body' => $api_params));

        // make sure the response came back okay
        if (is_wp_error($response)) {
            // start a session if needed
            @session_start();

            // set error in session
            $_SESSION['sfbp_license_error'] = $response->get_error_message();

            // signify error
            return false;
        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "active" or "inactive"
        update_option('sfbp_license_status', $license_data->license);

        // signify success
        return true;
    }
}

add_action('admin_init', 'sfbp_activate_license');

function sfbp_licensing()
{
    $license = get_option('sfbp_license_key');
    $status = get_option('sfbp_license_status');

    // sfbp header
    echo sfbp_admin_header();

    // heading
    echo '<h2>Licensing</h2>';

    // intro info
    echo '<blockquote><p>A valid license is required to take advantage of WordPress dashboard updates and Developer Support. Enter your license below, save, then click activate. You can <a href="https://simplesharebuttons.com/purchases/" target="_blank">manage your licenses here</a> and <a href="https://simplefollowbuttons.com/plus/" target="_blank">buy more/renew licenses here</a>.</p>
	<p><b>If you are having problems activating your license</b>, please ensure you/your host\'s version of <b>cURL</b> is up-to-date.</p></blockquote>';

    // start a session if needed
    @session_start();

    // if a license error is present
    if (isset($_SESSION['sfbp_license_error']) && $_SESSION['sfbp_license_error'] != '') {
        // output error alert
        echo '<div class="alert alert-danger"><strong>Activation error:</strong> ' . $_SESSION['sfbp_license_error'] . '</div>';

        // unset license error, hopefully the next try will be successful
        unset($_SESSION['sfbp_license_error']);
    }

    // initiate forms helper
    $sfbpForm = new sfbpForms();

    // opening form tag
    echo $sfbpForm->open(true, 'options.php', 'sfbp-form-non-ajax');

    settings_fields('sfbp_license');

    // default follow title
    $opts = array(
        'form_group' => true,
        'type' => 'text',
        'placeholder' => '555ab175b3fd6747a7da2fc652509e56',
        'name' => 'sfbp_license_key',
        'label' => 'License Key',
        'tooltip' => 'Enter your SFBP license key',
        'value' => esc_attr($license),
    );
    echo $sfbpForm->sfbp_input($opts);

    if (false !== $license && $license != '') {
        ?>

        <div class="form-group">
            <label for="sfbp_license_activate" class="control-label" data-toggle="tooltip" data-placement="right"
                   data-original-title="Activate your license here">Activate License</label>

        </div>
        <?php if ($status !== false && $status == 'valid') {
            ?>
            <span style="color:green;"><?php _e('Active');
                ?></span>
            <?php
        } else {
            wp_nonce_field('sfbp_activate_nonce', 'sfbp_activate_nonce');
            ?>
            <button type="submit" class="btn btn-primary" name="sfbp_license_activate">Activate License</button>
            <?php
        }
        ?>
        <?php
    }
    ?>

    <?php
    // close off form with save button
    echo $sfbpForm->close();

    // sfbp footer
    echo sfbp_admin_footer();
}

function sfbp_register_option()
{
    // creates our settings in the options table
    register_setting('sfbp_license', 'sfbp_license_key', 'sfbp_sanitize_license');
}

add_action('admin_init', 'sfbp_register_option');

function sfbp_sanitize_license($new)
{
    $old = get_option('sfbp_license_key');
    if ($old && $old != $new) {
        delete_option('sfbp_license_status'); // new license has been entered, so must reactivate
    }

    return $new;
}

function sfbp_deactivate_license()
{
    // listen for our activate button to be clicked
    if (isset($_POST['edd_license_deactivate'])) {

        // run a quick security check
        if (!check_admin_referer('edd_sample_nonce', 'edd_sample_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim(get_option('edd_sample_license_key'));

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $license,
            'item_name' => urlencode(SFBP_SL_ITEM_NAME), // the name of our product in EDD
            'url' => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_get(add_query_arg($api_params, SFBP_SL_STORE_URL), array('timeout' => 10, 'sslverify' => false));

        // make sure the response came back okay
        if (is_wp_error($response)) {
            return false;
        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "deactivated" or "failed"
        if ($license_data->license == 'deactivated') {
            delete_option('edd_sample_license_status');
        }
    }
}

add_action('admin_init', 'sfbp_deactivate_license');

function sfbp_check_license()
{
    global $wp_version;

    $license = trim(get_option('edd_sample_license_key'));

    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode(SFBP_SL_ITEM_NAME),
        'url' => home_url(),
    );

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($api_params, SFBP_SL_STORE_URL), array('timeout' => 15, 'sslverify' => false));

    if (is_wp_error($response)) {
        return false;
    }

    $license_data = json_decode(wp_remote_retrieve_body($response));

    if ($license_data->license == 'valid') {
        echo 'valid';
        exit;
        // this license is still valid
    } else {
        echo 'invalid';
        exit;
        // this license is no longer valid
    }
}
