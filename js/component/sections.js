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
        firstTop: true,
        attr: 'data-section',
        loop: false,
        child: 'section',
        childActive: 'isOpen',
        type: 'sections',
        smooth: true,
        anchorClass: "selected",
        hashPush: true,
        keyboardArrow: "notInput"
    },option);
    
    
    // components
    Component.NavIndex.call(this,$option);
    Component.NavHash.call(this,$option);
    Component.KeyboardArrow.call(this,$option.keyboardArrow);
    Component.ScrollAnimate.call(this,$option);
    
    
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
        
        updateAnchors: function() {
            const hashes = [];
            const isFirst = trigHdlr(this,'sections:isFirst');
            hashes.push(Uri.makeHash(trigHdlr(this,'navHash:getCurrentHash'),true));
            
            if(isFirst === true && $option.skipFirst === true)
            hashes.push('');
            
            const all = qsa(this,"a[href*='#']");
            const anchors = Arr.filter(all,function() {
                return Arr.in(this.hash,hashes);
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
        
        manageHashChange: function(target,old,context,targets) {
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
        },
        
        getPromise: function(target,old,context,targets) {
            let r = null;
            
            if(shouldAnimate(target,context,targets) === true)
            {
                const $this = this;
                const isFirst = (Arr.valueFirst(targets) === target);
                let top;
                
                if(isFirst === true && $option.firstTop === true)
                top = 0;
                
                else
                top = Ele.getOffset(target).top;
                
                const promise = trigHdlr(this,'scrollAnimate:go',top,null,$option.smooth);
                
                if(promise != null)
                {
                    setData(this,'sections-scrolling',true);
                    r = promise.then(function() {
                        setData($this,'sections-scrolling',false);
                    });
                }
            }
            
            return r;
        }
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
            d(keyEvent);
            trigHdlr(this,'sections:goPrev','keyboard');
        },'sections-keyboardUp');
        
        ael(this,'keyboardArrow:down',function(event,keyEvent,isInput) {
            d(keyEvent);
            trigHdlr(this,'sections:goNext','keyboard');
        },'sections-keyboardDown');
        
        ael(this,'scroll:stop',function() {
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
        const scrollTop = Ele.getScroll(this).top;
        const windowHeight = Win.getDimension().height;
        const documentHeight = Doc.getDimension(document).height;
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
                    const offset = Ele.getOffset(this).top;
                    const height = Ele.getDimension(this).height;
                    
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