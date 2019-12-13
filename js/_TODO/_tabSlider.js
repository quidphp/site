/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// tabSlider
Component.TabSlider = function() 
{    
    /*
    // slider
    // script for a tab slider component with next and previous buttons
    Component.slider = function(timeout,navs,className,showIfOne)
    {
        className = (Str.isNotEmpty(className))? className:".slide";
        
        const func = function() {
            const tab = $(this);
            const prev = $(this).find(".prev");
            const next = $(this).find(".next");
            const target = $(this).find(className);
            
            target.on('tab:open',function(event) {
                $(this).addClass("active");
            })
            .on('tab:close',function(event) {
                $(this).removeClass("active");
            });
            
            if(target.length > 1 || showIfOne === true)
            {
                if(next.length)
                {
                    next.on('click',function(event) {
                        tab.trigger('tab:loopNext');
                    });
                }
                
                if(prev.length)
                {
                    prev.on('click',function(event) {
                        tab.trigger('tab:loopPrev');
                    });
                }
                
                if(navs instanceof jQuery && navs.length)
                {
                    Component.tabNav.call(target,navs);
                    target.on('tab:open',function(event) {
                        const nav = trigHdlr(this,'link:getNav');
                        navs.removeClass('active');
                        nav.addClass('active');
                    });
                    navs.on('click',function(event) {
                        const target = trigHdlr(this,'link:getTarget');
                        target.trigger('tab:change');
                    });
                }
                
                if(Num.is(timeout))
                {
                    Component.Timeout.call(this,'tab:change',timeout);
                    
                    $(this).on('tab:change:onTimeout',function(event) {
                        trigEvt(this,'tab:loopNext');
                    })
                    .on('mouseover',function(event) {
                        trigEvt(this,'tab:change:clearTimeout');
                    })
                    .on('mouseleave',function(event) {
                        trigEvt(this,'tab:change:setTimeout');
                    });
                }
            }
            
            else
            {
                if(next.length)
                next.hide();
                
                if(prev.length)
                prev.hide();
                
                if(navs instanceof jQuery && navs.length)
                navs.hide();
            }
            
            $(this).on('tab:getTarget',function(event) {
                return target;
            });
            Component.tab.call(this);
            trigEvt(this,'tab:changeOrFirst');
        };
        
        $(this).each(function(index, el) {
            func.call(this);
        });
        
        $(this).find(className).on('tab:close',function(event) {
            const iframe = $(this).find("iframe");
            if(iframe.length)
            setAttr(iframe,'src',getAttr(iframe,'src'));
        });
        
        return this;
    }
    */
    
    return this;
}