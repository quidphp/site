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
	$(this).on('specific:formPrepare', function(event,parent) {
        parent.find("[data-col='jsonForm']").jsonForm();
        parent.find("[data-col='googleMaps'] .bind").googleMaps();
	});
});