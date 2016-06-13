jQuery(document).ready(function(){

	// toggle switch
	jQuery('.sfbp-toggle-switch').on('click', function() {
		jQuery(this).parent().toggleClass('sfbp--state-hidden');
	});

	// loop through each container so they're unique
	jQuery('.sfbp-container').each(function(i, obj) {

		// collect and compile posted data to an array
		var data = {
			    action: 'sfbp_lazy',
			    security : sfbpLazy.security,
			    sfbppostid: jQuery(this).data("sfbp-post-id"),
			    sfbptitle: jQuery(this).data("sfbp-title"),
			    sfbpurl: jQuery(this).data("sfbp-url"),
			    sfbpshorturl: jQuery(this).data("sfbp-short-url"),
			    sfbpfollowtext: jQuery(this).data("sfbp-follow-text")
			};

		// load the follow buttons via ajax
		jQuery.post(sfbpLazy.ajax_url, data, function(response) {

			// display buttons
			jQuery(obj).append(response);
			jQuery(obj).parent().removeClass('sfbp--state-hidden');
			jQuery('.sfbp-toggle-switch').fadeIn();

			// upon clicking a simple follow buttons plus button
			jQuery(obj).find('.sfbp-btn').click(function(event){

				// don't go the the href yet
				event.preventDefault();

				// these follow options don't need to have a popup
				if (jQuery(this).hasClass('sfbp-email-popup')) {
					return true;
				}
				else
				{
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
			}); // end of click event for buttons
	    }); // end of ajax post and response to get buttons
	}); // close loop of sfbp-containers
});
