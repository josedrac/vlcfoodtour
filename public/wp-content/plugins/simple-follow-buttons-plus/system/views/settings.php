<?php

defined('ABSPATH') or die('No direct access permitted');

function sfbp_admin_panel()
{
    // make sure we have settings ready
    $sfbp_settings = get_sfbp_settings();

    // prepare array of buttons
    $arrButtons = json_decode(get_option('sfbp_buttons'), true);

    // sfbp header
    $htmlFollowButtonsForm = sfbp_admin_header();

    // heading
    $htmlFollowButtonsForm .= '<h2>Follow Buttons Setup</h2>';

    // get the font family needed
    $htmlFollowButtonsForm .= '<style>' . sfbp_get_font_family() . '.sfbp-whatsapp{display: inline-block!important;}</style>';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>The <b>simple</b> options you can see below are all you need to complete to get your <b>follow buttons</b> to appear on your website. Once you\'re done here, you can further customise the follow buttons via the \'<a href="admin.php?page=simple-follow-buttons-styling">Styling</a>\' page.</p></blockquote>';

    // initiate forms helper
    $sfbpForm = new sfbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-follow-buttons-setup');

    // opening form tag
    $htmlFollowButtonsForm .= $sfbpForm->open(true, $action);

    // locations array
    $locs = array(
        'Pages' => array(
            'value' => 'pages',
            'checked' => ($sfbp_settings['pages'] == 'Y' ? true : false),
        ),
        'Posts' => array(
            'value' => 'posts',
            'checked' => ($sfbp_settings['posts'] == 'Y' ? true : false),
        ),
    );
    // locations
    $opts = array(
        'form_group' => true,
        'label' => 'Locations',
        'tooltip' => 'Enable the locations you wish for follow buttons to appear',
        'value' => 'Y',
        'checkboxes' => $locs,
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_checkboxes($opts);

    // placement
    $opts = array(
        'form_group' => true,
        'type' => 'select',
        'name' => 'before_or_after',
        'label' => 'Placement',
        'tooltip' => 'Place follow buttons before or after your content',
        'selected' => $sfbp_settings['before_or_after'],
        'options' => array(
            'After' => 'after',
            'Before' => 'before',
            'Both' => 'both',
        ),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // follow text
    $opts = array(
        'form_group' => true,
        'type' => 'text',
        'placeholder' => 'Follow Us',
        'name' => 'follow_text',
        'label' => 'Follow Text',
        'tooltip' => 'Add some custom text by your follow buttons',
        'value' => $sfbp_settings['follow_text'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // networks
    $htmlFollowButtonsForm .= '<div class="form-group">
										<label for="sfbp_choices" class="control-label" data-toggle="tooltip" data-placement="right" data-original-title="Drag, drop and reorder those buttons that you wish to include">Networks</label>
										<div class="">';

    $htmlFollowButtonsForm .= '<div class="sfbp-wrap sfbp--centred sfbp--theme-4">
												<div class="sfbp-container">
													<ul id="sfbpsort1" class="sfbp-list sfbpSortable">';
    $htmlFollowButtonsForm .= getAvailableSFBP($sfbp_settings['selected_buttons']);
    $htmlFollowButtonsForm .= '</ul>
												</div>
											</div>';
    $htmlFollowButtonsForm .= '<div class="well">';
    $htmlFollowButtonsForm .= '<div class="sfbp-well-instruction"><i class="fa fa-download"></i> Drop icons below</div>';
    $htmlFollowButtonsForm .= '<div class="sfbp-wrap sfbp--centred sfbp--theme-4">
												<div class="sfbp-container">
													<ul id="sfbpsort2" class="sfbp-include-list sfbp-list sfbpSortable">';
    $htmlFollowButtonsForm .= getSelectedSFBP($sfbp_settings['selected_buttons']);
    $htmlFollowButtonsForm .= '</ul>
											</div>';
    $htmlFollowButtonsForm .= '</div>';
    $htmlFollowButtonsForm .= '</div>';
    $htmlFollowButtonsForm .= '<input type="hidden" name="selected_buttons" id="selected_buttons" value="' . $sfbp_settings['selected_buttons'] . '"/>';

    // show URLs button
    $htmlFollowButtonsForm .= '<span class="btn btn-block btn-primary"
                                                            data-toggle="collapse"
                                                            data-target="#sfb-urls"
                                                            aria-expanded="false"
                                                            aria-controls="sfb-urls">
                                                            Set Follow URLs
                                                        </span>';

    // the URLs well
    $htmlFollowButtonsForm .= '<div class="collapse" id="sfb-urls"><div class="well">';

        // loop through each button
        foreach ($arrButtons as $button => $arrButton) {
            // empty vars for DRY
            $prefix = '';
            $suffix = '';

            // if a button has a prefix and suffix
            if (isset($arrButton['url_prefix']) && isset($arrButton['url_suffix'])) {
                // prepare vars
                $prefix = $arrButton['url_prefix'];
                $suffix = $arrButton['url_suffix'];
                $type = 'text_prefix_suffix';
            }

            // if a button has a prefix only
            if (isset($arrButton['url_prefix']) && ! isset($arrButton['url_suffix'])) {
                // prepare vars
                $prefix = $arrButton['url_prefix'];
                $type = 'text_prefix';
            }

            // if a button has a suffix only
            if (! isset($arrButton['url_prefix']) && isset($arrButton['url_suffix'])) {
                // prepare vars
                $suffix = $arrButton['url_suffix'];
                $type = 'text_suffix';
            }

            // if a button has neither a prefix nor a suffix
            if (! isset($arrButton['url_prefix']) && ! isset($arrButton['url_suffix'])) {
                // prepare vars
                $type = 'text';
            }

            // button size
            $opts = array(
                'form_group'	=> false,
                'type'          => $type,
                'prefix'        => $prefix,
                'suffix'       	=> $suffix,
                'placeholder'   => 'simplefollowbuttons',
                'name' => 'url_' . $button,
                'label' => $arrButton['full_name'],
                'tooltip' => 'Set your ' . $arrButton['full_name'] . ' URL',
                'value' => (isset($sfbp_settings['url_' . $button]) ? $sfbp_settings['url_' . $button] : null),
            );
            $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);
        }

    // the URLs well
    $htmlFollowButtonsForm .= '</div></div>';

    $htmlFollowButtonsForm .= '</div>';

    // close off form with save button
    $htmlFollowButtonsForm .= $sfbpForm->close();

    // sfbp footer
    $htmlFollowButtonsForm .= sfbp_admin_footer();

    echo $htmlFollowButtonsForm;
}

// get an html formatted of currently selected and ordered buttons
function getSelectedsfbp($strSelectedsfbp)
{
    // variables
    $htmlSelectedList = '';
    $arrSelectedsfbp = '';

    // prepare array of buttons
    $arrButtons = json_decode(get_option('sfbp_buttons'), true);

    // if there are some selected buttons
    if ($strSelectedsfbp != '') {
        // explode saved include list and add to a new array
        $arrSelectedsfbp = explode(',', $strSelectedsfbp);

        // check if array is not empty
        if ($arrSelectedsfbp != '') {
            // for each included button
            foreach ($arrSelectedsfbp as $strSelected) {
                // add a list item for each selected option
                //$htmlSelectedList .= '<li class="sfbp-option-item" id="' . $strSelected . '"><a href="javascript:;" title="'.$arrButtons[$strSelected]['full_name'].'" class="sfbp-btn sfbp-' . $strSelected . '"><span class="sfbp-text">' . $arrButtons[$strSelected]['full_name'] . '</span></a></li>';
                $htmlSelectedList .= '<li class="sfbp-option-item" id="' . $strSelected . '"><a href="javascript:;" title="'.$arrButtons[$strSelected]['full_name'].'" class="sfbp-btn sfbp-' . $strSelected . '"></a><span class="sfbp-text">' . $arrButtons[$strSelected]['full_name'] . '</span></li>';

            }
        }
    }

    return $htmlSelectedList;
}

// get an html formatted of currently selected and ordered buttons
function getAvailablesfbp($strSelectedsfbp)
{
    // variables
    $htmlAvailableList = '';
    $arrSelectedsfbp = '';

    // prepare array of buttons
    $arrButtons = json_decode(get_option('sfbp_buttons'), true);

    // explode saved include list and add to a new array
    $arrSelectedsfbp = explode(',', $strSelectedsfbp);

    // create array of all available buttons
    $arrAllAvailablesfbp = json_decode(get_option('sfbp_buttons'), true);

    // extract the available buttons
    $arrAvailablesfbp = array_diff(array_keys($arrAllAvailablesfbp), $arrSelectedsfbp);

    // check if array is not empty
    if ($arrSelectedsfbp != '') {
        // for each included button
        foreach ($arrAvailablesfbp as $strAvailable) {
            // add a list item for each available option
            $htmlAvailableList .= '<li class="sfbp-option-item" id="' . $strAvailable . '"><a href="javascript:;" title="'.$arrButtons[$strAvailable]['full_name'].'" class="sfbp-btn sfbp-' . $strAvailable . '"></a><span class="sfbp-text">' . $arrButtons[$strAvailable]['full_name'] . '</span></li>';
        }
    }

    // return html list options
    return $htmlAvailableList;
}
