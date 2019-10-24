"use strict";

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// specific
// script of additional behaviours for the specific form page of the CMS
$(document).ready(function() {
	
	// route:specificPrepare
    // comportement pour la préparation de certains inputs avancés
	$(this).on('route:specificPrepare', function(event,preparable) {
		preparable.on('specificForm:prepare', function(event) {
    		$(this).find("[data-col='jsonForm']").jsonForm();
			$(this).find("[data-col='googleMaps'] .googleMaps").googleMaps();
        });
	});
});