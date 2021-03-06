/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// googleAnalytics
// script containing logic for googleAnalytics
Component.GoogleAnalytics = function()
{
    // document node
    Vari.check(this,document);
    
    
    // handler
    setHdlrs(this,'googleAnalytics:',{
        
        has: function() {
            return typeof(ga) !== 'undefined';
        },
        
        get: function() {
            return ga;
        }
    });
    
    
    // handlersSetup
    const handlersSetup = {
        
        sendPageView: function(value) {
            let r = false;
            const googleAnalytics = trigHdlr(this,'googleAnalytics:get');
            
            if(value === true)
            value = Request.relative();
            
            if(Str.isNotEmpty(value))
            {
                googleAnalytics('set','page',value);
                googleAnalytics('send','pageview');
                r = true;
            }
            
            return r;
        }
    }
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        if(trigHdlr(this,'googleAnalytics:has'))
        {
            setHdlrs(this,'googleAnalytics:',handlersSetup);
            bindDocument.call(this);
        }
    });
    
    
    // bindDocument
    const bindDocument = function()
    {
        ael(this,'doc:mountPage',function(event,routeWrap,isError,initial) {
            if(initial === false)
            trigHdlr(this,'googleAnalytics:sendPageView',true);
        });
    }
    
    return this;
}