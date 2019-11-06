"use strict";

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleAnalytics
// script containing logic for googleAnalytics component

// googleAnalytics
// permet de lier le chargement d'une nouvelle page via ajax et de l'envoyer à google Analytics
quid.core.googleAnalytics = $.fn.googleAnalytics = function()
{
    $(this).on('document:mount', function(event) {
        var uri = quid.base.currentRelativeUri();
        
        if(quid.base.isStringNotEmpty(uri) && typeof ga !== 'undefined' && $.isFunction(ga))
        {
            ga('set','page',uri);
            ga('send','pageview');
        }
    });
    
    return this;
}