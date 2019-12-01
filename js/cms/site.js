/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// specific
// script of additional behaviours for the specific form page of the CMS
$(document).ready(function() {
	
	// specificForm:bindView
    // se bind à l'ouverture du panneau
	$(this).on('specificForm:bindView', function(event,parent) {
        const jsonForm = parent.find("[data-col='jsonForm'] .specific-component");
        const googleMaps = parent.find("[data-col='googleMaps'] .specific-component").find(".map-render");
        
        Component.jsonForm.call(jsonForm).trigger('component:setup');
        Component.googleMaps.call(googleMaps);
	});
});