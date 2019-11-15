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
quid.core.googleAnalytics = function()
{
    $(this).on('document:mount', function(event) {
        var uri = quid.base.request.relative();
        
        if(quid.base.str.isNotEmpty(uri) && typeof ga !== 'undefined' && quid.base.func.is(ga))
        {
            ga('set','page',uri);
            ga('send','pageview');
        }
    });
    
    return this;
}