<?php
defined('ABSPATH') or die('No direct access permitted');

// get (or generate then get) SFBP_CSS file
function sfbp_stylesheets()
{
    // if css file doesn't exist
    if (!@file_exists(SFBP_CSS)) {
        // create and save settings.json file
        $fp = @fopen(SFBP_CSS, 'wb');

        // unable to create the file
        if ($fp === false) {
            // add CSS to the head
            add_action('wp_head', 'sfbp_style_head');
        } // file created successfully
        else {
            // get, collate and minify all needed CSS
            $css = sfbp_get_css();
            @fwrite($fp, $css);
            @fclose($fp);

            // make sure we can delete the file when changes are made
            chmod(SFBP_CSS, 0755);

            // enqueue the created stylesheet
            wp_enqueue_style('sfbp_styles', plugins_url('sfbp.min.css', SFBP_FILE));
        }
    } // file exists
    else {
        // enqueue the created stylesheet
        wp_enqueue_style('sfbp_styles', plugins_url('sfbp.min.css', SFBP_FILE));
    }
}

// add sfbp font in head
function sfbp_font_in_head()
{

    // css style
    $htmlStyle = '<style type="text/css">';

    // get sfbp font
    $htmlStyle .= sfbp_get_font_family();

    // close style tag
    $htmlStyle .= '</style>';

    // output to head
    echo $htmlStyle;
}

// add css to head
function sfbp_style_head()
{

    // css style
    $htmlStyle = '<style type="text/css">';

    // get, collate and minify all needed CSS
    $htmlStyle .= sfbp_get_css();

    // close style tag
    $htmlStyle .= '</style>';

    // output to head
    echo $htmlStyle;
}

// get, collate and minify all needed css
function sfbp_get_css()
{
    // define css variables
    $css = '';

    // get sfbp settings
    $sfbp_settings = get_sfbp_settings();

    // if some custom styles have been set
    if ($sfbp_settings['custom_styles_enabled'] == 'Y') {
        // use custom css only
        return sfbp_minify_css($sfbp_settings['custom_styles']);
    }

    // if we haven't set to load the font in the head
    if ($sfbp_settings['load_font_in_head'] != 'Y') {
        // add it to the custom CSS file
        $css .= sfbp_get_font_family();
    }

    // set default breakpoint in case
    if ($sfbp_settings['one_breakpoint'] == '') {
        $sfbp_settings['one_breakpoint'] = 480;
    }

    // if custom images are not enabled
    if ($sfbp_settings['custom_images'] != 'Y') {
        // base style file
        $base = SFBP_ROOT . '/followbuttons/assets/css/sfbp-base.css';

        // read css contents
        $fileCSS = fopen($base, 'r');
        $temp = fread($fileCSS, filesize($base));
        fclose($fileCSS);

        // if the first set has been set as responsive
        if ($sfbp_settings['one_responsive'] == 'Y') {

            // replace breakpoint with set value
            $css .= str_replace('481px', (intval($sfbp_settings['one_breakpoint']) + 1) . 'px', $temp);
        } else {
            $css .= $temp;
        }

        // theme style file
        $theme = SFBP_ROOT . '/followbuttons/assets/css/themes/' . $sfbp_settings['default_style'] . '.css';

        // read css contents and add to css
        $fileCSS = fopen($theme, 'r');
        $css .= fread($fileCSS, filesize($theme));
        fclose($fileCSS);

        // if the first set has been set as responsive
        if ($sfbp_settings['one_responsive'] == 'Y') {
            // mobile style file
            $mobile = SFBP_ROOT . '/followbuttons/assets/css/sfbp-mobile.css';

            // read css contents
            $fileCSS = fopen($mobile, 'r');
            $temp = fread($fileCSS, filesize($mobile));
            fclose($fileCSS);

            // replace breakpoint with set value
            $css .= str_replace('480px', $sfbp_settings['one_breakpoint'] . 'px', $temp);
        }    }


    // if email popup option is set
    if ($sfbp_settings['email_popup'] == 'Y') {
        // Important - Flattr should be hidden if not set in options
        if (strpos($sfbp_settings['selected_buttons'], 'flattr') === false) {
            $css .= '#sfbp-email-div .sfbp-li--flattr {
                        display: none !important
                    }';
        }

        // email div style file
        $emailPath = SFBP_ROOT . '/followbuttons/assets/css/sfbp-email-div.css';

        // read css contents and add to css
        $fileCSS = fopen($emailPath, 'r');
        $css .= fread($fileCSS, filesize($emailPath));
        fclose($fileCSS);
    }

    // get extra style
    $css .= get_sfbp_settings_styles();

    // add additional css last to take priority over all other css
    $css .= $sfbp_settings['additional_css'];

    // return all the css
    return sfbp_minify_css($css);
}

// add scripts for page/post use
function sfbp_page_scripts()
{
    // get sfbp settings
    $sfbp_settings = get_sfbp_settings();

    // if lazy load is enabled
    if ($sfbp_settings['lazy_load'] == 'Y') {
        // just lazy load and toggle
        wp_enqueue_script('sfbp_lazy', plugins_url('js/lazy.min.js', SFBP_FILE), array('jquery'), false, true);
        wp_localize_script('sfbp_lazy', 'sfbpLazy', array(

            // URL to wp-admin/admin-ajax.php to process the request
            'ajax_url' => admin_url('admin-ajax.php'),

            // generate a nonce with a unique ID
            'security' => wp_create_nonce('sfbp-lazy-nonce'),
        ));
    } // not lazy loading
    else {
        // just show buttons and allow toggle
        wp_register_script('sfbp_standard', plugins_url('js/standard.min.js', SFBP_FILE), array('jquery'), false, true);
        wp_enqueue_script('sfbp_standard');
    }

    // if a font family is set
    if ($sfbp_settings['font_family'] != '') {
        // register and enqueue the font family
        wp_register_style('sfbpFont', '//fonts.googleapis.com/css?family=' . $sfbp_settings['font_family']);
        wp_enqueue_style('sfbpFont');
    }
}

// generate style
function get_sfbp_settings_styles()
{
    // get sfbp settings
    $sfbp_settings = get_sfbp_settings();

    // define empty css variable
    $css = '';

    // if there's some follow text
    if ($sfbp_settings['follow_text'] != '') {
        // follow text margin depending on its placement
        switch ($sfbp_settings['text_placement']) {
            // above
            case 'above':
                $sfbpTextMargin = 'margin:0 0 10px 0;';
                break;

            // below
            case 'below':
                $sfbpTextMargin = 'margin:10px 0 0 0;';
                break;

            // right
            case 'right':
                $sfbpTextMargin = 'margin:0 0 10px 0;';
                break;

            // left
            case 'left':
                $sfbpTextMargin = 'margin:0 15px 0 0;';
                break;
        }

        // follow text weight as chosen
        switch ($sfbp_settings['font_weight']) {
            // light
            case 'light':
                $sfbpFontWeight = 'font-weight:light;';
                break;

            // normal
            case 'normal':
                $sfbpFontWeight = 'font-weight:normal;';
                break;

            // bold
            case 'bold':
                $sfbpFontWeight = 'font-weight:bold;';
                break;
        }

        $font_family = (str_replace('+', ' ', $sfbp_settings['font_family']));

        // add follow text css
        $css .= '.sfbp-follow-text{' . $sfbpFontWeight . $sfbpTextMargin . ($sfbp_settings['font_family'] != '' ? 'font-family:' . $font_family . ';' : null) . 'font-size:' . ($sfbp_settings['font_size'] != '' ? $sfbp_settings['font_size'] : 20) . 'px;color:' . $sfbp_settings['font_color'] . '}';
    }

    // if custom images are not enabled
    if ($sfbp_settings['custom_images'] != 'Y') {
        // SET ONE
        // if style one is fixed
        if (in_array($sfbp_settings['set_one_position'], array(
            'fixed-left',
            'fixed-right',
            'fixed-bottom',
        ))) {
            // hide the follow text
            $css .= '.sfbp-set--one .sfbp-follow-text{display:none!important;}';
        }

        // if any custom colours are set for set one
        if (
            ($sfbp_settings['icon_color'] != '') ||
            ($sfbp_settings['color_main'] != '')
        ) {
            // open button class
            $css .= '.sfbp-set--one .sfbp-btn{';

            // if an icon colour is set, use it
            if ($sfbp_settings['icon_color'] != '') {
                $css .= 'color:' . $sfbp_settings['icon_color'] . ';';
            }

            // if a button colour is set, use it
            if ($sfbp_settings['color_main'] != '') {
                $css .= 'background-color:' . $sfbp_settings['color_main'] . ';';
            }

            // close button class
            $css .= '}';
        }

        // if any custom hover colours are set for set one
        if (
            ($sfbp_settings['icon_color_hover'] != '') ||
            ($sfbp_settings['color_hover'] != '')
        ) {
            // open button hover class
            $css .= '.sfbp-set--one .sfbp-btn:hover{';

            // if a button hover colour is set, use it
            if ($sfbp_settings['color_hover'] != '') {
                $css .= 'background-color:' . $sfbp_settings['color_hover'] . '!important;';
            }

            // if an icon hover colour is set, use it
            if ($sfbp_settings['icon_color_hover'] != '') {
                $css .= 'color:' . $sfbp_settings['icon_color_hover'] . '!important;';
            }

            // close button hover class
            $css .= '}';
        }

        // if any custom sizes are set for set one
        if (
            ($sfbp_settings['button_width'] != '') ||
            ($sfbp_settings['button_height'] != '') ||
            ($sfbp_settings['icon_size'] != '') ||
            ($sfbp_settings['button_margin'] != '')
        ) {
            // open button hover class
            $css .= '.sfbp-set--one .sfbp-btn{';

            // if a button margin is set, use it
            if ($sfbp_settings['button_margin'] != '') {
                $css .= 'margin:' . $sfbp_settings['button_margin'] . 'px!important;';
            }

            // if a button width is set, use it
            if ($sfbp_settings['button_width'] != '') {
                $css .= 'width:' . $sfbp_settings['button_width'] . 'em!important;';
            }

            // if a button height is set, use it
            if ($sfbp_settings['button_height'] != '') {
                // add btn height
                $css .= 'height:' . $sfbp_settings['button_height'] . 'em!important;';

                // close btn class
                $css .= '}';

                // add line height
                $css .= '.sfbp-set--one .sfbp-btn{';
                $css .= 'line-height:' . $sfbp_settings['button_height'] . 'em!important;';
                $css .= '}';
            } else {
                // close btn class - sizes
                $css .= '}';
            }

            // if an icon size is set, use it
            if ($sfbp_settings['icon_size'] != '') {
                $css .= '.sfbp-set--one .sfbp-btn:before{';
            }
            $css .= 'font-size:' . $sfbp_settings['icon_size'] . 'px!important;';
            $css .= '}';
        }

        // return the css
        return $css;
    }// end set two enabled
    // custom images are enabled

    // important overrides of base
    $css .= '.sfbp-toggle-switch{display:none!important}';
    $css .= '.sfbp-input-url{display:none!important}';
    $css .= '.sfbp-list{list-style:none!important;}';
    $css .= '.sfbp-list li{display: inline !important;}';

//    // if follow counts are not set to display
//    if ($sfbp_settings['one_follow_counts'] != 'Y') {
//        // hide them
//        $css .= '.sfbp-each-follow{display:none!important;}';
//    }
//
//    // if total follow counts are not set to display
//    if ($sfbp_settings['one_total_follow_counts'] != 'Y') {
//        // hide them
//        $css .= '.sfbp-total-follows{display:none!important;}';
//    }

    // open sfbp-list
    $css .= '.sfbp-list {';

    // switch on positioning
    switch ($sfbp_settings['set_one_position']) {

        // centred
        case 'centred':
            $css .= 'text-align:center;';
            break;

        // aligned right
        case 'aligned-right':
            $css .= 'text-align:right;';
            break;

        // align left for all others
        default:
            $css .= 'text-align:left;';
            break;
    }

    // close sfbp-list
    $css .= '}';

    // image css
    $css .= '.sfbp-container img
					{
						width: ' . $sfbp_settings['image_width'] . 'px !important;
						height: ' . $sfbp_settings['image_height'] . 'px !important;
						margin: ' . $sfbp_settings['image_padding'] . 'px;
						border:  0;
						box-shadow: none !important;
						vertical-align: middle;
						display:inline!important;
					}
					.sfbp-container a
					{
						border:0!important;
					}
					.sfbp-container img:after {
					    content:"\A";
					    position:absolute;
					    width:100%; height:100%;
					    top:0; left:0;
					    background:rgba(0,0,0,0.6);
					    opacity:0;
					    transition: all 0.5s;
					    -webkit-transition: all 0.5s;
					}
					.sfbp-container img:hover:after {
					    opacity:1;
					}';

    $css .= '.sfbp-container {margin-bottom:10px;}';

    // strip out spaces to keep tidy
    $css = sfbp_minify_css($css);

    // return
    return $css;
}

// get sfbp font family
function sfbp_get_font_family()
{
    $plugins_url = plugins_url();
    $plugins_url = str_replace('https:', '', $plugins_url);
    $plugins_url = str_replace('http:', '', $plugins_url);

    return "@font-face {
					font-family: 'sfbp';
					src:url('".$plugins_url."/simple-follow-buttons-plus/followbuttons/assets/fonts/sfbp.eot?5276i0');
					src:url('".$plugins_url."/simple-follow-buttons-plus/followbuttons/assets/fonts/sfbp.eot?#iefix5276i0') format('embedded-opentype'),
						url('".$plugins_url."/simple-follow-buttons-plus/followbuttons/assets/fonts/sfbp.woff2?5276i0') format('woff2'),
						url('".$plugins_url."/simple-follow-buttons-plus/followbuttons/assets/fonts/sfbp.woff?5276i0') format('woff'),
						url('".$plugins_url."/simple-follow-buttons-plus/followbuttons/assets/fonts/sfbp.ttf?5276i0') format('truetype'),
						url('".$plugins_url."/simple-follow-buttons-plus/followbuttons/assets/fonts/sfbp.svg?5276i0#sfbp') format('svg');
					font-weight: normal;
					font-style: normal;

					/* Better Font Rendering =========== */
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
				}";
}

// minify css on the fly
function sfbp_minify_css($sfbpCSS)
{
    // Remove comments
    $sfbpCSS = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $sfbpCSS);

    // search and replace arrays
    $sfbpSearch = array(': ', ', ', ' {', '; ', ' ;');
    $sfbpReplace = array(':', ',', '{', ';', ';');

    // Remove space after colons
    $sfbpCSS = str_replace($sfbpSearch, $sfbpReplace, $sfbpCSS);

    // Remove whitespace
    $sfbpCSS = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $sfbpCSS);

    // return it
    return $sfbpCSS;
}
