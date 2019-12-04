/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleAnalytics
// script containing logic for googleAnalytics component
Component.googleAnalytics = function()
{
    $(this).on('doc:mount',function(event) {
        const uri = Request.relative();
        
        if(Str.isNotEmpty(uri) && typeof ga !== 'undefined' && Func.is(ga))
        {
            ga('set','page',uri);
            ga('send','pageview');
        }
    });
    
    return this;
}