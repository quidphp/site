"use strict";

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleAnalytics
// script containing logic for googleAnalytics component
(function ($, document, window) {
	
	// googleAnalytics
	// permet de lier le chargement d'une nouvelle page via ajax et de l'envoyer à google Analytics
	$.fn.googleAnalytics = function()
	{
		$(this).on('navigation:complete', function(event,uri) {
			if($.isStringNotEmpty(uri) && typeof ga !== 'undefined' && $.isFunction(ga))
			{
				ga('set','page',uri);
				ga('send','pageview');
			}
		});
		
		return this;
	}
	
}(jQuery, document, window));