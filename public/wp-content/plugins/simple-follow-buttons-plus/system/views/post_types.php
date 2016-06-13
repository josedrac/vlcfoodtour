<?php

defined('ABSPATH') or die('No direct access permitted');

function sfbp_admin_post_types()
{
    // make sure we have settings ready
    $sfbp_settings = get_sfbp_settings();

    // sfbp header
    $htmlFollowButtonsForm = sfbp_admin_header();

    // heading
    $htmlFollowButtonsForm .= '<h2>Post Types</h2>';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>Use the switches to enable/disable follow buttons for the corresponding post types.</p></blockquote>';

    // initiate forms helper
    $sfbpForm = new sfbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-follow-buttons-post_types');

    // opening form tag
    $htmlFollowButtonsForm .= $sfbpForm->open(true, $action);

    // fetch all post types
    $post_types = get_post_types('', 'names');

    // create an array of post types to ignore
    $arrIgnoreTypes = array(
        'post',
        'page',
        'attachment',
        'revision',
        'nav_menu_item',
    );

    // create a count
    $countPostTypes = 0;

    // loop through them
    foreach ($post_types as $post_type) {
        // skip those we don't want
        if (in_array($post_type, $arrIgnoreTypes)) {
            continue;
        }

        // add to counter
        $countPostTypes++;

        // post type
        $opts = array(
            'form_group' => false,
            'type' => 'checkbox',
            'class' => 'sfbp-post-type',
            'name' => 'sfbp_disabled_types[]',
            'label' => $post_type,
            'value' => $post_type,
            'checked' => (in_array($post_type, explode(',', $sfbp_settings['disabled_types'])) ? 'checked' : null),
        );
        $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);
    }

    // if there are no relevant custom post types
    if ($countPostTypes == 0) {
        $htmlFollowButtonsForm .= '<h4>No relevant custom post types found</h4>';
    }

    // close off form with save button
    $htmlFollowButtonsForm .= $sfbpForm->close();

    // sfbp footer
    $htmlFollowButtonsForm .= sfbp_admin_footer();

    echo $htmlFollowButtonsForm;
}
