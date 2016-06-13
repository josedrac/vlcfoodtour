<?php

defined('ABSPATH') or die('No direct access permitted');

function sfbp_widget_init()
{
    register_sidebar_widget('Follow Buttons Widget', 'sfbp_widget');
    register_widget_control('Follow Buttons Widget', 'sfbp_widget_control');
}

// widget class
class sfbp_widget extends WP_Widget
{
    // construct the widget
    public function __construct()
    {
        parent::__construct(
            'sfbp_widget', // Base ID
            'Follow Buttons', // Name
            array('description' => __('Simple Follow Buttons Plus', 'text_domain')) // Args
        );
    }

    // extract required arguments and run the shortcode
    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $url = $instance['url'];
        $pagetitle = $instance['pagetitle'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        // make sure we have settings ready
        $sfbp_settings = get_sfbp_settings();

        // if option is set to use free version shortcode
        if ($sfbp_settings['use_sfb'] != '') {
            $shortcode = '[sfb';
        } else {// use new
            $shortcode = '[sfbp';
        }

        ($url != '' ? $shortcode .= ' url="' . $url . '"' : null);
        ($pagetitle != '' ? $shortcode .= ' title="' . $pagetitle . '"' : null);
        $shortcode .= ' widget="Y"]';
        echo do_shortcode($shortcode, 'text_domain');
        echo $after_widget;
    }

    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Follow Buttons', 'text_domain');
        }

        $url = (isset($instance['url']) ? esc_url($instance['url']) : null);
        $pagetitle = (isset($instance['pagetitle']) ? $instance['pagetitle'] : null);

        # Title
        echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
        # URL
        echo '<p><label for="' . $this->get_field_id('url') . '">' . 'URL:' . '</label><input class="widefat" id="' . $this->get_field_id('url') . '" name="' . $this->get_field_name('url') . '" type="text" value="' . $url . '" /></p>';
        echo '<p class="description">Leave this blank to follow the current page, or enter a URL to force one URL for all pages.</p>';
        # Page title
        echo '<p><label for="' . $this->get_field_id('pagetitle') . '">' . 'Page title:' . '</label><input class="widefat" id="' . $this->get_field_id('pagetitle') . '" name="' . $this->get_field_name('pagetitle') . '" type="text" value="' . $pagetitle . '" /></p>';
        echo '<p class="description">Set a page title for the page being followd, leave this blank if you have not set a URL.</p>';
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['pagetitle'] = strip_tags($new_instance['pagetitle']);

        return $instance;
    }
}
