jQuery(document).ready(function(){

    jQuery('.sfbp-wrap').removeClass('sfbp--state-hidden');
    jQuery('.sfbp-toggle-switch').fadeIn();

    // toggle switch
    jQuery('.sfbp-toggle-switch').on('click', function() {
        jQuery(this).parent().toggleClass('sfbp--state-hidden');
    });

    // upon clicking a simple follow buttons plus button
	jQuery('.sfbp-btn').click(function(event){

		// don't go the the href yet
		event.preventDefault();

        // these follow options don't need to have a popup
        if (jQuery(this).hasClass('sfbp-email-popup')) {
            return true;
        } else {
            // just open email link
            if (jQuery(this).hasClass('sfbp-email')) {
                window.location.href = jQuery(this).attr('href');
                return true;
            }

			// prepare popup window
			var width  = 575,
			    height = 520,
			    left   = (jQuery(window).width()  - width)  / 2,
			    top    = (jQuery(window).height() - height) / 2,
			    opts   = 'status=1' +
			             ',width='  + width  +
			             ',height=' + height +
			             ',top='    + top    +
			             ',left='   + left;

			// open the follow url in a smaller window
		    window.open(jQuery(this).attr('href'), 'SFBP', opts);
		}
	});
});
