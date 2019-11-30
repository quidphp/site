/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleAnalytics
// script containing logic for googleAnalytics component
quid.component.googleAnalytics = function()
{
    $(this).on('document:mount', function(event) {
        var uri = quid.request.relative();
        
        if(quid.str.isNotEmpty(uri) && typeof ga !== 'undefined' && quid.func.is(ga))
        {
            ga('set','page',uri);
            ga('send','pageview');
        }
    });
    
    return this;
}