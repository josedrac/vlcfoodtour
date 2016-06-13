jQuery(document).ready(function() {


	// DEFAULT STYLES
	// default style for set one is changed
	jQuery( "#default_style" ).change(function() {
		jQuery('#sfbp-preview--one').removeClass('sfbp--theme-1 sfbp--theme-2 sfbp--theme-3 sfbp--theme-4 sfbp--theme-5 sfbp--theme-6 sfbp--theme-7 sfbp--theme-8 sfbp--theme-9 sfbp--theme-10');
		jQuery('#sfbp-preview--one').addClass('sfbp--theme-'+jQuery(this).val());
	});
	// default style for set two is changed
	jQuery( "#two_style" ).change(function() {
		if(jQuery(this).val() == '') {
			jQuery('#set-two-col').addClass('hidden');
			jQuery('#sfbp-preview--two').removeClass('sfbp--theme-1 sfbp--theme-2 sfbp--theme-3 sfbp--theme-4 sfbp--theme-5 sfbp--theme-6 sfbp--theme-7 sfbp--theme-8 sfbp--theme-9 sfbp--theme-10');
		}
		else {
			jQuery('#set-two-col').removeClass('hidden');
			jQuery('#sfbp-preview--two').removeClass('sfbp--theme-1 sfbp--theme-2 sfbp--theme-3 sfbp--theme-4 sfbp--theme-5 sfbp--theme-6 sfbp--theme-7 sfbp--theme-8 sfbp--theme-9 sfbp--theme-10');
			jQuery('#sfbp-preview--two').addClass('sfbp--theme-'+jQuery(this).val());
		}
	});

	// FOLLOW COUNTS
	// show/hide follow counts set one
	jQuery('input[name="one_follow_counts"]').on('switchChange.bootstrapSwitch', function(event, state) {
		if(state === true) {
			jQuery('#sfbp-preview--one').data('sfbp-counts', true).attr('data-sfbp-counts', true);
		}
		else {
			jQuery('#sfbp-preview--one').removeData('sfbp-counts').removeAttr('data-sfbp-counts');
		}
	});
	// show/hide follow counts set two
	jQuery('input[name="two_follow_counts"]').on('switchChange.bootstrapSwitch', function(event, state) {
		if(state === true) {
			jQuery('#sfbp-preview--two').data('sfbp-counts', true).attr('data-sfbp-counts', true);
		}
		else {
			jQuery('#sfbp-preview--two').removeData('sfbp-counts').removeAttr('data-sfbp-counts');
		}
	});

	// COLOURS
	// set one button colour change
	jQuery('#color_main').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('#sfbp-preview--one .sfbp-btn').css("background-color", '#'+hex);
		}
	});
	// set tw0 button colour change
	jQuery('#color_main_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('#sfbp-preview--two .sfbp-btn').css("background-color", '#'+hex);
		}
	});

	// HOVER COLOURS
	jQuery('#color_hover').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#sfbp-preview--one .sfbp-btn:hover {background-color : #'+hex+'!important;}</style>');
		}
	});
	jQuery('#color_hover_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#sfbp-preview--two .sfbp-btn:hover {background-color : #'+hex+'!important;}</style>');
		}
	});

	jQuery('#icon_color').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#sfbp-preview--one .sfbp-btn:before {color : #'+hex+';}</style>');
		}
	});
	jQuery('#icon_color_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#sfbp-preview--two .sfbp-btn:before {color : #'+hex+';}</style>');
		}
	});

	jQuery('#icon_color_hover').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#sfbp-preview--one .sfbp-btn:hover:before {color : #'+hex+'!important;}</style>');
		}
	});
	jQuery('#icon_color_hover_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#sfbp-preview--two .sfbp-btn:hover:before {color : #'+hex+'!important;}</style>');
		}
	});

	// show/hide previews
	jQuery('input[name="custom_images"]').on('switchChange.bootstrapSwitch', function(event, state) {
		if(state === true) {
			jQuery('#sfbp-previews').addClass('hidden');
		}
		else {
			jQuery('#sfbp-previews').removeClass('hidden');
		}
	});

	// SIZES
	jQuery( "#button_height" ).change(function() {
		jQuery('#sfbp-preview--one .sfbp-btn').css('height', jQuery(this).val()+'em');
		jQuery('#sfbp-preview--one .sfbp-btn').css('line-height', jQuery(this).val()+'em');
	});
	jQuery( "#button_two_height" ).change(function() {
		jQuery('#sfbp-preview--two .sfbp-btn').css('height', jQuery(this).val()+'em');
		jQuery('#sfbp-preview--two .sfbp-btn').css('line-height', jQuery(this).val()+'em');
	});

	jQuery( "#button_width" ).change(function() {
		jQuery('#sfbp-preview--one .sfbp-btn').css('width', jQuery(this).val()+'em');
	});
	jQuery( "#button_two_width" ).change(function() {
		jQuery('#sfbp-preview--two .sfbp-btn').css('width', jQuery(this).val()+'em');
	});

	jQuery( "#icon_size" ).change(function() {
		jQuery('head').append('<style>#sfbp-preview--one .sfbp-btn:before {font-size : '+jQuery(this).val()+'px}</style>');
	});
	jQuery( "#icon_two_size" ).change(function() {
		jQuery('head').append('<style>#sfbp-preview--two .sfbp-btn:before {font-size : '+jQuery(this).val()+'px}</style>');
	});

	jQuery( "#button_margin" ).change(function() {
		jQuery('#sfbp-preview--one .sfbp-btn').css('margin', jQuery(this).val()+'px');
	});
	jQuery( "#button_two_margin" ).change(function() {
		jQuery('#sfbp-preview--two .sfbp-btn').css('margin', jQuery(this).val()+'px');
	});


});
