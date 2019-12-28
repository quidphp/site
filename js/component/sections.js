/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// sections
// script containing logic for a node containing multiple sections linked to a hash
Component.Sections = function(option) 
{
    // option
    const $option = Pojo.replace({
        persistent: true,
        target: null,
        skipFirst: true,
        attr: 'data-section',
        loop: false,
        child: 'section',
        childActive: 'isOpen',
        type: 'sections',
        speed: 1000,
        anchorClass: "selected",
        hashPush: true
    },option);
    
    
    // components
    Component.NavIndex.call(this,$option);
    Component.NavHash.call(this,$option);
    Component.KeyboardArrow.call(this,true);
    Component.ScrollChange.call(this,$option.persistent);
    
    
    // handler
    setHdlrs(this,'sections:',{
        
        canChange: function() {
            let r = false;
            
            if(!trigHdlr(document,'history:isLoading'))
            {
                if(getData(this,'sections-active') === true && trigHdlr(this,'sections:isScrolling') === false)
                r = true;
            }
            
            return r;
        },
        
        isScrolling: function() {
            return getData(this,'sections-scrolling') === true;
        },
        
        getScroller: function() {
            let r = this;
            const htmlBody = trigHdlr(document,'doc:getHtmlBody');

            if(Arr.in(this,htmlBody))
            r = htmlBody;
            
            return r;
        },
        
        updateAnchors: function() {
            const hash = Uri.makeHash(trigHdlr(this,'navHash:getCurrentHash'),true);
            const all = qsa(this,"a[href*='#']");
            const anchors = Arr.filter(all,function() {
                return this.hash === hash;
            });
            
            toggleClass(all,$option.anchorClass,false);
            toggleClass(anchors,$option.anchorClass,true);
        },
        
        enable: function(value,context) {
            enableSections.call(this);
            trigHdlr(this,'sections:go',value,context);
        },
        
        disable: function() {
            disableSections.call(this);
        },
        
        getPromise: function(target,old,context,targets) {
            let r = null;
            
            if(shouldAnimate(target,context,targets) === true)
            {
                const $this = this;
                const scroller = trigHdlr(this,'sections:getScroller');
                setData(this,'sections-scrolling',true);
                
                const top = Dom.getOffset(target).top;
                const promise = DomChange.animate(scroller,{scrollTop: top},$option.speed);
                
                r = promise.done(function() {
                    setData($this,'sections-scrolling',false);
                });
            }
            
            return r;
        }
    });

    
    // event
    ael(this,'sections:afterChange',function(event,target,old,context,targets) {
        const isFirst = Arr.valueFirst(targets) === target;
        let current = trigHdlr(this,'navHash:getCurrentHash');
        let hdlr = 'history:replaceHash';
        
        if($option.hashPush === true && Arr.in(context,['keyboard','scroll']))
        hdlr = 'history:pushHash';
        
        if(isFirst === true && $option.skipFirst === true)
        trigHdlr(document,hdlr,'');
        else
        trigHdlr(document,hdlr,current);
        
        trigHdlr(this,'sections:updateAnchors');
    });

    
    // shouldAnimate
    const shouldAnimate = function(target,context,targets)
    {
        let r = Arr.in(context,['keyboard','hashchange']);
        const isFirst = Arr.valueFirst(targets) === target;
        
        if(Arr.in(context,['ready','mountPage']))
        r = (isFirst === false || $option.skipFirst !== true);
        
        return r;
    }
    
    
    // enableSections
    const enableSections = function()
    {
        const $this = this;
        disableSections.call(this);
        
        setData(this,'sections-active',true);
        
        ael(this,'keyboardArrow:up',function(event,keyEvent,isInput) {
            if(isInput === false)
            trigHdlr(this,'sections:goPrev','keyboard');
        },'sections-keyboardUp');
        
        ael(this,'keyboardArrow:down',function(event,keyEvent,isInput) {
            if(isInput === false)
            trigHdlr(this,'sections:goNext','keyboard');
        },'sections-keyboardDown');
        
        ael(this,'scroll:change',function() {
            const target = getScrollTarget.call(this);
            
            if(target != null)
            trigHdlr(this,'sections:go',target,'scroll');
        },'sections-scroll');
        
        aelOnce(document,'doc:unmountPage',function() {
            disableSections.call($this);
        });
    }
    
    
    // disableSections
    const disableSections = function()
    {
        setData(this,'sections-active',false);
        const $this = this;
        const arr = ['sections-keyboardUp','sections-keyboardDown','sections-scroll'];
        
        Arr.each(arr,function(value) {
            rel($this,value);
        });
    }
    
    
    // getScrollTarget
    const getScrollTarget = function()
    {
        let r = null;
        const scrollTop = Dom.getScroll(this).top;
        const windowHeight = Dom.getHeight(window);
        const documentHeight = Dom.getHeight(document);
        const windowHeightRatio = (windowHeight / 2);
        const targets = trigHdlr(this,'sections:getTargets');
        
        if(Arr.isNotEmpty(targets))
        {
            if(scrollTop <= windowHeightRatio)
            r = Arr.valueFirst(targets);
            
            else
            {
                Arr.each(targets,function() {
                    let keep = false;
                    const offset = Dom.getOffset(this).top;
                    const height = Dom.getHeight(this,true);
                    
                    if(scrollTop >= (offset - windowHeightRatio))
                    {
                        if(scrollTop < ((offset + height) - windowHeightRatio))
                        keep = true;
                    }
                    
                    if(keep === true)
                    {
                        r = this;
                        return false;
                    }
                });
            }
            
            if(r == null && scrollTop >= (documentHeight - windowHeight))
            r = Arr.valueLast(targets);
        }
        
        return r;
    }
    
    return this;
}