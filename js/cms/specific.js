"use strict";

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// specific
// script of additional behaviours for the specific form page of the CMS
$(document).ready(function() {
	
	// specific:formPrepareViewable
    // comportement pour la préparation de certains inputs avancés
    // se bind à l'ouverture du panneau
	$(this).on('specific:formPrepareViewable', function(event,parent) {
        parent.find("[data-col='jsonForm'] .specific-component").callThis(quid.core.jsonForm);
        parent.find("[data-col='googleMaps'] .specific-component").find(".map-render").callThis(quid.core.googleMaps);
	});
});