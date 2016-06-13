jQuery(document).ready(function(){
	
	// upon clicking the share text
	jQuery('.sfbp-share-text').click(function () {
		
		jQuery( ".sfbp-container" ).fadeToggle(500);
    	jQuery(".sfbp-share-text").toggleClass("sfbp-active");
	});

}); 