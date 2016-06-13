<?php
defined('ABSPATH') or die('No direct access permitted');

// get and show follow buttons
function sfbp_show_follow_buttons($content, $booShortCode = false, $atts = '')
{
    // globals
    global $post;

    // get sfbp settings
    $sfbp_settings = get_sfbp_settings();

    // variables
    $pattern = get_shortcode_regex();

    // sfbp_hide shortcode is in the post content and instance is not called by shortcode sfbp
    if (preg_match_all('/' . $pattern . '/s', $post->post_content, $matches)
        && array_key_exists(2, $matches)
        && in_array('sfbp_hide', $matches[2])
        && $booShortCode == false
    ) {
        // exit the function returning the content without the buttons
        return $content;
    }

    // if option is set to use free version shortcode
    if ($sfbp_settings['use_sfb'] == 'Y') {
        // sfb_hide shortcode is in the post content and instance is not called by shortcode sfb
        if (preg_match_all('/' . $pattern . '/s', $post->post_content, $matches)
            && array_key_exists(2, $matches)
            && in_array('sfb_hide', $matches[2])
            && $booShortCode == false
        ) {
            // exit the function returning the content without the buttons
            return $content;
        }
    }

    // check if the current post type is not wanted
    if (in_array(get_post_type($post->ID), explode(',', $sfbp_settings['disabled_types']))) {
        return $content;
    }

    // placement on pages/posts
    if ((!is_home() && !is_front_page() && is_page() && $sfbp_settings['pages'] == 'Y') || (is_single() && $sfbp_settings['posts'] == 'Y') || $booShortCode == TRUE) {
        // get license status
        $status = get_option('sfbp_license_status');

        // sfbp comment
        $wrap = '<!-- Simple Follow Buttons Plus (v' . SFBP_VERSION . ') simplefollowbuttons.com/plus ' . ($status === false || $status != 'valid' ? ' License Inactive ' : null) . '-->';

        // if widget use widget follow text
        if (isset($atts['widget']) && $atts['widget'] == 'Y') {
            $strFollowText = $sfbp_settings['widget_text'];
        } else {// use normal follow text
            $strFollowText = $sfbp_settings['follow_text'];
        }

        // get wrap
        $wrap .= '<div class="sfbp-set--one sfbp--state-hidden sfbp-wrap sfbp--' . $sfbp_settings['set_one_position'] . ' sfbp--theme-' . $sfbp_settings['default_style'] . '"'.($sfbp_settings['one_toggle'] == 'Y' ? ' data-sfbp-toggle="true"' : null) . ($sfbp_settings['one_responsive'] == 'Y' ? ' data-sfbp-responsive="true"' : null) . '>';

        // start to prepare everything that goes within the wrap
        $innards = '<button class="sfbp-toggle-switch sfbp-toggle-close"><span></span></button>';

        // sfbp div
        $innards .= '<div class="sfbp-container">';

        // if lazy load is not enabled
        if ($sfbp_settings['lazy_load'] != 'Y') {
            // initiate sfbp button class
            $sfbpButtons = new sfbpFollowButtons();

            // the buttons!
            $innards .= $sfbpButtons->get_sfbp_buttons();

//            // iffollow counts are enabled add the total follow count if it's high enough
//            if ($sfbp_settings['counters_enabled'] == 'Y' && $sfbpButtons->sfbpFollowCountData['total'] >= intval($sfbpButtons->sfbp_settings['min_follows'])) {
//
//                // if not using custom images
//                if ($sfbpButtons->sfbp_settings['custom_images'] != 'Y') {
//                    // add total follow count
//                    $innards .= '<span class="sfbp-total-follows"><b>' . sfbp_format_number($sfbpButtons->sfbpFollowCountData['total']) . '</b></span>';
//                }
//            }
        }

        // close container div
        $innards .= '</div>';

        // innards are ready, add to set one wrap
        $wrap .= $innards;

        // close wrap div
        $wrap .= '</div>';

        // adding shortcode buttons
        if ($booShortCode == true) {
            return $wrap;
        } else {
            return sfbp_add_to_content($sfbp_settings['before_or_after'], $content, $wrap);
        }
    } else {
        return $content;
    }
}

// add the buttons to the content where needed
function sfbp_add_to_content($beforeOrAfter, $content, $htmlFollowButtons)
{
    // switch for placement of sfbp
    switch ($beforeOrAfter) {
        case 'before': // before the content
            return $htmlFollowButtons . $content;
            break;

        case 'after': // after the content
        default:
            return $content . $htmlFollowButtons;
            break;

        case 'both': // before and after the content
            return $htmlFollowButtons . $content . $htmlFollowButtons;
            break;
    }
}

// shortcode for adding buttons
function sfbp_buttons($atts)
{
    // get buttons - NULL for $content, TRUE for shortcode flag
    return sfbp_show_follow_buttons(null, true, $atts);
}

// shortcode for hiding buttons
function sfbp_hide($content)
{
    // nothing to do here
}
