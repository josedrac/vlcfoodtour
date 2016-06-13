<?php

defined('ABSPATH') or die('No direct access permitted');

function sfbp_admin_styling()
{
    // make sure we have settings ready
    $sfbp_settings = get_sfbp_settings();

    // prepare array of buttons
    $arrButtons = json_decode(get_option('sfbp_buttons'), true);

    // array of button set positions
    $arrButtonPositions = array(
        'Left-aligned' => '',
        'Centred' => 'centred',
        'Right-aligned' => 'aligned-right',
        'Stacked' => 'stacked',
        'Fixed Left' => 'fixed-left',
        'Fixed Right' => 'fixed-right',
        'Fixed Bottom' => 'fixed-bottom',
    );

    // sfbp header
    $htmlFollowButtonsForm = sfbp_admin_header();

    // get the font family needed
    $htmlFollowButtonsForm .= '<style>' . sfbp_get_font_family() . '</style>';

    $htmlFollowButtonsForm .= '<h2 class="sfbp-heading-styling">Style Settings</h2>';

    // initiate forms helper
    $sfbpForm = new sfbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-follow-buttons-styling');

    // opening form tag
    $htmlFollowButtonsForm .= $sfbpForm->open(false, $action);

    // tabs
    $htmlFollowButtonsForm .= '<ul class="nav nav-tabs">
							  <li class="active"><a href="#button_sets" data-toggle="tab">Button Sets</a></li>
							  <li><a href="#colours" data-toggle="tab">Button Colours</a></li>
							  <li><a href="#sizes" data-toggle="tab">Sizes</a></li>
							  <li><a href="#follow_text" data-toggle="tab">Follow Text</a></li>
							  <li><a href="#images" data-toggle="tab">Images</a></li>
							  <li class="dropdown">
							    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
							      CSS <span class="caret"></span>
							    </a>
							    <ul class="dropdown-menu">
							      <li><a href="#css_additional" data-toggle="tab">Additional</a></li>
							      <li><a href="#css_custom" data-toggle="tab">Custom</a></li>
							    </ul>
							  </li>
							</ul>';
    // tab content div
    $htmlFollowButtonsForm .= '<div id="sfbpTabContent" class="tab-content">';

    //======================================================================
    // 		BUTTON SETS
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade active in" id="button_sets">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>Use the options below to choose your favourite button set(s) and how it/they should appear. If you wish to use a button set from Simple Follow Buttons Adder please use the \'Images\' tab and upload them as required.</p></blockquote>';

    // SET ONE COLUMN --------------------------------
    $htmlFollowButtonsForm .= '<div class="col-md-12">';

    // open well
    $htmlFollowButtonsForm .= '<div class="well">';

    // array of button sets available for set 1
    $arrButtonSets = array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
    );

    // button set
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'default_style',
        'label' => 'Button Set',
        'tooltip' => 'Choose the style of buttons you want',
        'selected' => $sfbp_settings['default_style'],
        'options' => $arrButtonSets,
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // button positioning
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'set_one_position',
        'label' => 'Button Positioning',
        'tooltip' => 'Set the way your follow buttons should position themselves',
        'selected' => $sfbp_settings['set_one_position'],
        'options' => $arrButtonPositions,
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // show toggle
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'one_toggle',
        'label' => 'Show Toggle',
        'tooltip' => 'Switch on to show toggle switch to show hide the button set',
        'value' => 'Y',
        'checked' => ($sfbp_settings['one_toggle'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // make responsive
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'one_responsive',
        'label' => 'Responsive',
        'tooltip' => 'Make button set responsive when viewing on smaller devices',
        'value' => 'Y',
        'checked' => ($sfbp_settings['one_responsive'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // breakpoint
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '480',
        'name' => 'one_breakpoint',
        'label' => 'Mobile Breakpoint',
        'tooltip' => 'Set the screenwidth that buttons should switch to mobile-view',
        'value' => $sfbp_settings['one_breakpoint'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close well
    $htmlFollowButtonsForm .= '</div>';

    // close column one
    $htmlFollowButtonsForm .= '</div>';

    // close follow buttons tab
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		COLOURS
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="colours">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>All of these colour options are <b>optional</b>. If not set the default colours of your selected button sets will be used. You can set as few or as many of these colour options as you wish.</p></blockquote>';

    // SET ONE COLUMN --------------------------------
    $htmlFollowButtonsForm .= '<div class="col-md-12">';

    // open well
    $htmlFollowButtonsForm .= '<div class="well">';

    // button colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'color_main',
        'label' => 'Button Colour',
        'tooltip' => 'Choose a colour for your buttons or leave blank',
        'value' => $sfbp_settings['color_main'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // hover colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'color_hover',
        'label' => 'Button Hover Colour',
        'tooltip' => 'Choose a colour for your buttons when hovering or leave blank',
        'value' => $sfbp_settings['color_hover'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // icon colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'icon_color',
        'label' => 'Icon Colour',
        'tooltip' => 'Choose a colour for your icons or leave blank',
        'value' => $sfbp_settings['icon_color'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // icon hover colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'icon_color_hover',
        'label' => 'Icon Hover Colour',
        'tooltip' => 'Choose a colour for your buttons when hovering or leave blank',
        'value' => $sfbp_settings['icon_color_hover'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close well
    $htmlFollowButtonsForm .= '</div>';

    // close column one
    $htmlFollowButtonsForm .= '</div>';

    // close colours
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		SIZES
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="sizes">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>Use the size options below to tweak the follow button sizes to best suit your website.</p></blockquote>';

    // SET ONE COLUMN --------------------------------
    $htmlFollowButtonsForm .= '<div class="col-md-12">';

    // open well
    $htmlFollowButtonsForm .= '<div class="well">';

    // button height
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'em',
        'placeholder' => '3',
        'name' => 'button_height',
        'label' => 'Button Height',
        'tooltip' => 'Set the height for your buttons',
        'value' => $sfbp_settings['button_height'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // button width
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'em',
        'placeholder' => '3',
        'name' => 'button_width',
        'label' => 'Button Width',
        'tooltip' => 'Set the width for your buttons',
        'value' => $sfbp_settings['button_width'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // icon size
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '24',
        'name' => 'icon_size',
        'label' => 'Icon Size',
        'tooltip' => 'Set the icon size for your buttons',
        'value' => $sfbp_settings['icon_size'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // button margin
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '12',
        'name' => 'button_margin',
        'label' => 'Button Margin',
        'tooltip' => 'Set the margin/spacing around your buttons',
        'value' => $sfbp_settings['button_margin'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close well
    $htmlFollowButtonsForm .= '</div>';

    // close column one
    $htmlFollowButtonsForm .= '</div>';

    // close colours
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		FOLLOW TEXT
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="follow_text">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>The follow text options below relate to the follow text that you set on the main setup page. <b>Note</b> that \'Inherit\' will simply use the same font family as your theme.</p></blockquote>';

    // column for padding
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';

    // follow text placement
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'text_placement',
        'label' => 'Text Placement',
        'tooltip' => 'Choose where in relation to your buttons you wish your follow text to appear - may differ with each style',
        'selected' => $sfbp_settings['text_placement'],
        'options' => array(
            'Above' => 'above',
            'Below' => 'below',
            'Left' => 'left',
            'Right' => 'right',
        ),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // follow text size
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '15',
        'name' => 'font_size',
        'label' => 'Font Size',
        'tooltip' => 'Set the font size for your follow text',
        'value' => $sfbp_settings['font_size'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // font colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'font_color',
        'label' => 'Font Colour',
        'tooltip' => 'Choose a colour for your follow text or leave blank',
        'value' => $sfbp_settings['font_color'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // follow text font family
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'font_family',
        'label' => 'Font Family',
        'tooltip' => 'Choose a font available or inherit the font from your website',
        'selected' => $sfbp_settings['font_family'],
        'options' => array(
            'Inherit' => '',
            'Indie Flower' => 'Indie+Flower',
            'Lato' => 'Lato',
            'Merriweather' => 'Merriweather',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open+Sans',
            'Raleway' => 'Raleway',
            'Reenie Beanie' => 'Reenie+Beanie',
            'Shadows Into Light' => 'Shadows+Into+Light',
        ),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // follow text font weight
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'font_weight',
        'label' => 'Font Weight',
        'tooltip' => 'Choose the weight of your follow text',
        'selected' => $sfbp_settings['font_weight'],
        'options' => array(
            'Light' => 'light',
            'Normal' => 'normal',
            'Bold' => 'bold',
        ),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close follow text
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		IMAGES
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="images">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>If you wish to use your own custom images, simply enable them via the switch below, set the sizing and padding and upload/select the images for each social network. <b>Previews are unavailable for this option</b>.</p></blockquote>';

    // column for padding
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';

    // enable custom images
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'custom_images',
        'label' => 'Custom Images',
        'tooltip' => 'Switch on to use your own images',
        'value' => 'Y',
        'checked' => ($sfbp_settings['custom_images'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // image width
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '40',
        'name' => 'image_width',
        'label' => 'Image Width',
        'tooltip' => 'Set the width to display your images',
        'value' => $sfbp_settings['image_width'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // image height
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '40',
        'name' => 'image_height',
        'label' => 'Image Height',
        'tooltip' => 'Set the height to display your images',
        'value' => $sfbp_settings['image_height'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // image padding
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '20',
        'name' => 'image_padding',
        'label' => 'Image Padding',
        'tooltip' => 'Set the padding size around your images',
        'value' => $sfbp_settings['image_padding'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // well
    $htmlFollowButtonsForm .= '<div class="well">';

    // loop through each button
    foreach ($arrButtons as $button => $arrButton) {
        // enable custom images
        $opts = array(
            'form_group' => false,
            'type' => 'image_upload',
            'name' => 'custom_' . $button,
            'label' => $arrButton['full_name'],
            'tooltip' => 'Upload a custom ' . $arrButton['full_name'] . ' image',
            'value' => (isset($sfbp_settings['custom_' . $button]) ? $sfbp_settings['custom_' . $button] : null),
        );
        $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);
    }

    // close well
    $htmlFollowButtonsForm .= '</div>';

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close images
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		ADDITIONAL CSS
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="css_additional">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>The contents of the text area below will be minified then appended to a unique CSS file.</p></blockquote>';

    // column for padding
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';

    // additional css
    $opts = array(
        'form_group' => false,
        'type' => 'textarea',
        'rows' => '15',
        'class' => 'code-font',
        'name' => 'additional_css',
        'label' => 'Additional CSS',
        'tooltip' => 'Add your own additional CSS if you wish',
        'value' => $sfbp_settings['additional_css'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close additional css
    $htmlFollowButtonsForm .= '</div>';

    //======================================================================
    // 		CUSTOM CSS
    //======================================================================
    $htmlFollowButtonsForm .= '<div class="tab-pane fade" id="css_custom">';

    // intro info
    $htmlFollowButtonsForm .= '<blockquote><p>If you want to take over control of your follow buttons\' CSS entirely, turn on the switch below and enter your custom CSS. <strong>ALL of Simple Follow Buttons Plus\' CSS will be disabled</strong>. The contents of the text area below will be minified and added to a unique CSS file.</p></blockquote>';

    // column for padding
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';

    // enable custom css
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'custom_styles_enabled',
        'label' => 'Enable Custom CSS',
        'tooltip' => 'Switch on to disable all SFBP styles and use your own custom CSS',
        'value' => 'Y',
        'checked' => ($sfbp_settings['custom_styles_enabled'] == 'Y' ? 'checked' : null),
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // custom css
    $opts = array(
        'form_group' => false,
        'type' => 'textarea',
        'rows' => '15',
        'class' => 'code-font',
        'name' => 'custom_styles',
        'label' => 'Custom CSS',
        'tooltip' => 'Enter in your own custom CSS for your follow buttons',
        'value' => $sfbp_settings['custom_styles'],
    );
    $htmlFollowButtonsForm .= $sfbpForm->sfbp_input($opts);

    // close column
    $htmlFollowButtonsForm .= '</div>';

    // close custom css
    $htmlFollowButtonsForm .= '</div>';

    // close tab content div
    $htmlFollowButtonsForm .= '</div>';

    // close off form with save button
    $htmlFollowButtonsForm .= $sfbpForm->close();

    // PREVIEWS -------------------------------------------
    $htmlFollowButtonsForm .= '<div id="sfbp-previews" class="row container ' . ($sfbp_settings['custom_images'] == 'Y' ? 'hidden' : null) . '">';
    $htmlFollowButtonsForm .= '<div class="col-sm-12">';
    $htmlFollowButtonsForm .= '<blockquote><p>These previews are in place to give you a good idea of how your buttons will look, but are not necessarily an exact representation of how they will look with your theme. <b>Note that the icons in place are just for demonstration purposes and not all style setting changes will be reflected here.</b></p></blockquote>';
    $htmlFollowButtonsForm .= '</div>';
    $htmlFollowButtonsForm .= '<div id="set-one-col" class="col-md-6 text-center">';

    $htmlFollowButtonsForm .= '<h3>Set One Preview</h3>';

    $htmlFollowButtonsForm .= '<div id="sfbp-preview--one" class="sfbp-set--one sfbp-wrap sfbp--theme-' . $sfbp_settings['default_style'] . '" ' . ($sfbp_settings['one_follow_counts'] == 'Y' ? 'data-sfbp-counts="true"' : null) . '>
										<div class="sfbp-container">
											<ul class="sfbp-list">
												<li class="sfbp-li--facebook"><a href="#" class="sfbp-btn sfbp-facebook"><span class="sfbp-text">Facebook</span></a><span class="sfbp-total-facebook-follows sfbp-each-follow">1.8k</span></li>
												<li class="sfbp-li--twitter"><a href="#" class="sfbp-btn sfbp-twitter"><span class="sfbp-text">Twitter</span></a><span class="sfbp-total-twitter-follows sfbp-each-follow">1.8k</span></li>
												<li class="sfbp-li--google"><a href="#" class="sfbp-btn sfbp-google"><span class="sfbp-text">Google+</span></a><span class="sfbp-total-google-follows sfbp-each-follow">1.8k</span></li>
												<li class="sfbp-li--linkedin"><a href="#" class="sfbp-btn sfbp-linkedin"><span class="sfbp-text">LinkedIn</span></a><span class="sfbp-total-linkedin-follows sfbp-each-follow">1.8k</span></li>
											</ul>
										</div>
									</div>';

    $htmlFollowButtonsForm .= '</div>';

    $htmlFollowButtonsForm .= '</div>';
    // CLOSE PREVIEWS -------------------------------------

    // sfbp footer
    $htmlFollowButtonsForm .= sfbp_admin_footer();

    echo $htmlFollowButtonsForm;
}
