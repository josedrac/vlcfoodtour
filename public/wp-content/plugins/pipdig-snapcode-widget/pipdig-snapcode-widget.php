<?php
/*
Plugin Name: Snapchat Snapcode Widget
Plugin URI: http://wordpress.org/extend/plugins/pipdig-snapcode-widget/
Version: 1.0.1
Author: pipdig
Description: Add your Snapchat Snapcode to your site. Gain more followers!
Text Domain: pipdig-snapcode-widget
Author URI: http://www.pipdig.co/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
GNU General Public License for more details.

*/

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}


function pipdig_snapcode_widget_textdomain() {
	load_plugin_textdomain( 'pipdig-snapcode-widget', false, 'pipdig-snapcode-widget/languages' );
}
add_action( 'init', 'pipdig_snapcode_widget_textdomain' );

// add admin scripts
function pipdig_snapcode_widget_scripts() {
	wp_enqueue_media();
	wp_enqueue_script('pipdig-snapcode-widget-media-upload', plugin_dir_url( __FILE__ ) . 'pipdig-snapcode-widget.js', false, '1.0', true);
}
add_action('admin_enqueue_scripts', 'pipdig_snapcode_widget_scripts');



//The widget
if (!class_exists('pipdig_snapcode_widget')) {
	class pipdig_snapcode_widget extends WP_Widget {

		public function __construct() {
				$widget_ops = array('classname' => 'pipdig_snapcode_widget', 'description' => __("Display your Snapchat Snapcode.", 'pipdig-snapcode-widget') );
				parent::__construct('pipdig_snapcode_widget', 'Snapchat Snapcode', $widget_ops);
		}

		function widget($args, $instance) {
			// PART 1: Extracting the arguments + getting the values
			extract($args, EXTR_SKIP);
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$snapchat_account = empty($instance['snapchat_account']) ? '' : $instance['snapchat_account'];
			$snapcode = empty($instance['snapcode']) ? '' : $instance['snapcode'];

			// Before widget code, if any
			echo (isset($before_widget)?$before_widget:'');

			// PART 2: The title and the text output
			if (!empty($title)) {
				echo $before_title . $title . $after_title;
			}

			if (!empty($snapcode)) { ?>
			<!--<div style="text-align:center; margin: 0 auto">-->
			<div style="margin-left: 5px">
			<img src="<?php echo esc_url($snapcode); ?>" style="min-width: 1in; max-width: 1.2in; height: auto;" alt="Snapchat" />
			<?php if (!empty($snapchat_account)) { ?>
				<p style="margin-top:5px"><?php printf( __('Follow %s on Snapchat!', 'pipdig-snapcode-widget'), '<a href="https://www.snapchat.com/add/'.$snapchat_account.'" target="_blank">'.$snapchat_account.'</a>' ); ?></p>
			<?php } ?>
			</div>
			<?php } else {
				_e("Setup not complete. Please check you have entered all information in the widget options.", 'pipdig-snapcode-widget');
			}
			// After widget code, if any
			echo (isset($after_widget)?$after_widget:'');
		}

		public function form( $instance ) {

			 // PART 1: Extract the data from the instance variable
			 $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			 $title = sanitize_text_field($instance['title']);

		 if (isset($instance['snapchat_account'])) {
			$snapchat_account = sanitize_text_field($instance['snapchat_account']);
		 } else {
			 $snapchat_account = '';
		 }

			 // PART 2-3: Display the fields
			 ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'pipdig-snapcode-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

			<p style="font-weight:bold"><?php _e('Upload your <a href="https://accounts.snapchat.com/accounts/snapcodes?type=png" target="_blank">Snapcode</a> PNG file:', 'pipdig-snapcode-widget'); ?></p>
			<?php
				if (!empty($instance['snapcode'])) {
					echo '<img src="'.esc_url($instance['snapcode']).'" style="margin:5px 0;padding:0;max-width:150px;height:auto" alt="" /><br />';
				}
			?>

			<input type="text" style="display:none" class="widefat custom_media_url" name="<?php echo $this->get_field_name('snapcode'); ?>" id="<?php echo $this->get_field_id('snapcode'); ?>" value="<?php if (isset($instance['snapcode'])) { echo esc_url($instance['snapcode']); } ?>" style="margin-top:5px;">

			<input type="button" class="button button-primary custom_media_button" id="custom_media_button" name="<?php echo $this->get_field_name('snapcode'); ?>" value="<?php _e('Upload Snapcode', 'pipdig-snapcode-widget'); ?>" style="margin-top:8px;" />

		<p>
			<label for="<?php echo $this->get_field_id('snapchat_account'); ?>"><?php _e("Snapchat Account Name:", 'pipdig-snapcode-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('snapchat_account'); ?>" name="<?php echo $this->get_field_name('snapchat_account'); ?>" type="text" value="<?php echo esc_attr($snapchat_account); ?>" placeholder="<?php _e("For example: mileyxxcyrus or rhianna", 'pipdig-snapcode-widget'); ?>" />
		</p>

		<p><?php printf( __( 'Has this free plugin helped you? Please consider leaving a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating on wordpress.org. It means we can keep adding new features :)', 'pipdig-snapcode-widget' ), '<a href="https://goo.gl/FiDvY9" target="_blank" style="text-decoration:none">', '</a>' ); ?></p>


			 <?php

		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = sanitize_text_field($new_instance['title']);
			$instance['snapcode'] = esc_url($new_instance['snapcode']);
			$instance['snapchat_account'] = sanitize_text_field($new_instance['snapchat_account']);
			update_option('pipdig_snapcode_widget_account', sanitize_text_field($instance['snapchat_account'])); // might be useful for extra features in future
			return $instance;
		}

	}
	add_action( 'widgets_init', create_function('', 'return register_widget("pipdig_snapcode_widget");') );
}
