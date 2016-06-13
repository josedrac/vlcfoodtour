jQuery(document).ready(function() {

	// switch for checkboxes
	jQuery(".sfbp-admin-wrap input:checkbox:not('.sfbp-post-type')").bootstrapSwitch({
		onColor: 	'primary',
		size:		'normal'
	});

	// switch for post type checkboxes
	jQuery("input.sfbp-post-type").bootstrapSwitch({
		onColor: 	'default',
		onText:		'OFF',
		offColor:	'primary',
		offText:	'ON',
		inverse:	true,
		size:		'normal'
	});

	jQuery('[data-toggle="tooltip"]').tooltip();

	// simple file input
	jQuery(".filestyle").filestyle({icon: false});

	jQuery('.sfbp-updated').fadeIn('fast');
	jQuery('.sfbp-updated').delay(1000).fadeOut('slow');

	//------- INCLUDE LIST ----------//

	// add drag and sort functions to include table
	jQuery(function() {
		jQuery( "#sfbpsort1, #sfbpsort2" ).sortable({
			connectWith: ".sfbpSortable"
		}).disableSelection();
	  });


	// extract and add include list to hidden field
	jQuery('#sfbp_selected_buttons').val(jQuery('#sfbpsort2 li').map(function() {
	// For each <li> in the list, return its inner text and let .map()
	//  build an array of those values.
	return jQuery(this).attr('id');
	}).get());

	// after a change, extract and add include list to hidden field
	jQuery('.sfbp-wrap').mouseout(function() {
		jQuery('#selected_buttons').val(jQuery('#sfbpsort2 li').map(function() {
		// For each <li> in the list, return its inner text and let .map()
		//  build an array of those values.
		return jQuery(this).attr('id');
		}).get());
	});

	// when support details textarea is clicked
	jQuery('#sfbp-support-textarea,.support-details-btn').click(function(){
		// select text in support details textarea
		document.getElementById("sfbp-support-textarea").select();
	});

	jQuery("#ssb-official-import").click(function(){
		if(confirm("Are you sure? All your current settings will be overwritten!")) {
	        return true;
	    }
	    return false;
	});

	//---------------------------------------------------------------------------------------//
    //
    // SFBP ADMIN FORM
    //
    jQuery( "#sfbp-admin-form:not('.sfbp-form-non-ajax')" ).on( 'submit', function(e) {

        // don't submit the form
        e.preventDefault();

        // show spinner to show save in progress
        jQuery("button.sfbp-btn-save").html('<i class="fa fa-spinner fa-spin"></i>');

        // get posted data and serialise
        var sfbpData = jQuery("#sfbp-admin-form").serialize();

        // disable all inputs
        jQuery(':input').prop('disabled', true);
		jQuery(".sfbp-admin-wrap input:checkbox").bootstrapSwitch('disabled', true);


        jQuery.post(
            jQuery( this ).prop( 'action' ),
            {
                sfbpData: sfbpData
            },
            function() {

				// show success
                jQuery('button.sfbp-btn-save-success').fadeIn(100).delay(2500).fadeOut(200);

	            // re-enable inputs and reset save button
	            jQuery(':input').prop('disabled', false);
				jQuery(".sfbp-admin-wrap input:checkbox").bootstrapSwitch('disabled', false);
                jQuery("button.sfbp-btn-save").html('<i class="fa fa-floppy-o"></i>');
            }
        ); // end post
    } ); // end form submit

});
