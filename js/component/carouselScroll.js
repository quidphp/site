/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// carouselScroll
// script for a carousel component which scrolls
Component.CarouselScroll = function(option)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        scroller: null,
        prev: null,
        next: null,
        step: null,
        smooth: true,
        attrPrevNext: 'data-active'
    },option);
    

    // components
    Component.Scroller.call(this,{ scroller: $option.scroller });
    Component.KeyboardArrow.call(this,'horizontal');
    Component.ResizeChange.call(this);
    
    
    // handler
    setHdlrs(this,'carouselScroll:',{
        
        getScroller: function() {
            return trigHdlr(this,'scrollChange:getScroller');
        },
        
        getPrev: function() {
            return getTargetOrChild.call(this,$option.prev);
        },
        
        getNext: function() {
            return getTargetOrChild.call(this,$option.next);
        },
        
        goPrev: function() {
            doScroll.call(this,'prev');
        },
        
        goNext: function() {
            doScroll.call(this,'next');
        },
        
        getStep: function() {
            let r = $option.step;
            
            if(!Integer.is(r))
            {
                r = getTargetOrChild.call(this,r);
                if(r != null)
                {
                    const dimension = Ele.getDimension(r);
                    r = dimension.width;
                }
            }
            
            return Integer.typecheck(r);
        }
    });
    
    
    // handler
    ael(this,'keyboardArrow:left',function() {
        trigHdlr(this,'carouselScroll:goPrev');
    });
    
    ael(this,'keyboardArrow:right',function() {
        trigHdlr(this,'carouselScroll:goNext');
    });
    
    ael(this,'resize:change',function() {
        refreshPrevNext.call(this);
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        bindPrevNext.call(this);
        refreshPrevNext.call(this);
    });
    
    
    // bindPrevNext
    const bindPrevNext = function()
    {
        const $this = this;
        const prev = trigHdlr(this,'carouselScroll:getPrev');
        const next = trigHdlr(this,'carouselScroll:getNext');
        
        if(prev != null)
        {
            ael(prev,'click',function() {
                trigHdlr($this,'carouselScroll:goPrev');
            });
        }
        
        if(next != null)
        {
            ael(next,'click',function() {
                trigHdlr($this,'carouselScroll:goNext');
            });
        }
    }
    
    
    // refreshPrevNext
    const refreshPrevNext = function() 
    {
        const currentScroll = trigHdlr(this,'scroller:getCurrentScroll');
        const prev = trigHdlr(this,'carouselScroll:getPrev');
        const next = trigHdlr(this,'carouselScroll:getNext');
        const step = trigHdlr(this,'carouselScroll:getStep');
        
        if(prev != null)
        {
            const showPrev = (currentScroll.left > 1);
            setAttr(prev,$option.attrPrevNext,showPrev);
        }
        
        if(next != null)
        {
            const showNext = (currentScroll.left + currentScroll.innerWidth) != currentScroll.width;
            setAttr(next,$option.attrPrevNext,showNext);
        }
    }
    
    
    // doScroll
    const doScroll = function(type) 
    {
        let r = null;
        Str.typecheck(type,true);
        const $this = this;
        
        const currentScroll = trigHdlr(this,'scroller:getCurrentScroll');
        const step = trigHdlr(this,'carouselScroll:getStep');
        let left = currentScroll.left;
        let top = currentScroll.top;
        
        if(type === 'prev')
        {
            left -= step;
            if(left < 0)
            left = 0;
        }
        
        if(type === 'next')
        left += step;
        
        if(trigHdlr(this,'scroller:canScroll',top,left))
        {
            r = trigHdlr(this,'scroller:go',top,left,$option.smooth);
            
            if(r != null)
            {
                r = r.then(function() {
                    refreshPrevNext.call($this);
                });
            }
        }
        
        return r;
    }
    
    
    // getTargetOrChild
    const getTargetOrChild = function(value)
    {
        let r = null;
        
        if(Target.is(value))
        r = value;
        
        else if(Str.isNotEmpty(value))
        r = qs(this,value);
        
        return r;
    }
    
    return this;
}