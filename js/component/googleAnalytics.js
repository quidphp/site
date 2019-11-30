/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleAnalytics
// script containing logic for googleAnalytics component
Quid.Component.googleAnalytics = function()
{
    $(this).on('document:mount', function(event) {
        var uri = Quid.Request.relative();
        
        if(Quid.Str.isNotEmpty(uri) && typeof ga !== 'undefined' && Quid.Func.is(ga))
        {
            ga('set','page',uri);
            ga('send','pageview');
        }
    });
    
    return this;
}