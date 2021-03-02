/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// scrollSections
// script containing logic for scrolling multiple sections linked to a hash
Component.ScrollSections = function(option) 
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
        type: 'scrollSections',
        smooth: true,
        anchorClass: "selected",
        hashPush: true,
        keyboardArrow: "notInput"
    },option);
    
    
    // components
    Component.NavIndex.call(this,$option);
    Component.NavHash.call(this,$option);
    Component.KeyboardArrow.call(this,$option.keyboardArrow);
    Component.Scroller.call(this,$option);
    
    
    // handler
    setHdlrs(this,'scrollSections:',{
        
        canChange: function() {
            let r = false;
            
            if(!trigHdlr(document,'history:isLoading'))
            {
                if(getData(this,'sections-active') === true && trigHdlr(this,'scroller:isScrolling') === false)
                r = true;
            }
            
            return r;
        },
        
        updateAnchors: function() {
            const hashes = [];
            const isFirst = trigHdlr(this,'scrollSections:isFirst');
            hashes.push(Uri.makeHash(trigHdlr(this,'navHash:getCurrentHash'),true));
            
            if(isFirst === true && $option.skipFirst === true)
            hashes.push('');
            
            const all = qsa(this,"a[href*='#']");
            const anchors = Arr.filter(all,function(ele) {
                return Arr.in(ele.hash,hashes);
            });
            
            toggleClass(all,$option.anchorClass,false);
            toggleClass(anchors,$option.anchorClass,true);
        },
        
        enable: function(value,context) {
            enableSections.call(this);
            trigHdlr(this,'scrollSections:go',value,context);
        },
        
        disable: function() {
            disableSections.call(this);
        },
        
        manageHashChange: function(target,old,context,targets) {
            const isFirst = Arr.valueFirst(targets) === target;
            let current = trigHdlr(this,'navHash:getCurrentHash');
            let hdlr = 'history:replaceHash';
            
            if($option.hashPush === true && Arr.in(context,['keyboard','scroll','click']))
            hdlr = 'history:pushHash';
            
            if(isFirst === true && $option.skipFirst === true)
            trigHdlr(document,hdlr,'');
            else
            trigHdlr(document,hdlr,current);
            
            trigHdlr(this,'scrollSections:updateAnchors');
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
                top = Ele.getOffsetParent(target).top;
                
                r = trigHdlr(this,'scroller:go',top,null,$option.smooth);
            }
            
            return r;
        },
        
        goCurrent: function(context) {
            const targets = trigHdlr(this,'scrollSections:getTargets');
            const target = trigHdlr(this,'scroller:getCurrentVerticalTarget',targets);
            context = (Str.isNotEmpty(context))? context:'scroll';
            
            if(target != null)
            trigHdlr(this,'scrollSections:go',target,context);
        }
    });

    
    // event
    ael(this,'scrollSections:afterChange',function(event,target) {
        if(Ele.isFocusable(target))
        Ele.focus(target);
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        disableSections.call(this);
    });
    
    
    // shouldAnimate
    const shouldAnimate = function(target,context,targets)
    {
        let r = Arr.in(context,['keyboard','hashchange','click']);
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
        
        setHdlr(this,'keyboardArrow:prevent',function(keyEvent,isInput,keyCode) {
            return trigHdlr(this,'keyboardArrow:preventBool',keyEvent,isInput,keyCode);
        });
        
        ael(this,'keyboardArrow:up',function(event,keyEvent,isInput) {
            trigHdlr(this,'scrollSections:goPrev','keyboard');
        },'sections-keyboardUp');
        
        ael(this,'keyboardArrow:down',function(event,keyEvent,isInput) {
            trigHdlr(this,'scrollSections:goNext','keyboard');
        },'sections-keyboardDown');
        
        ael(this,'scroll:stop',function() {
            trigHdlr(this,'scrollSections:goCurrent');
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
        
        setHdlr(this,'keyboardArrow:prevent',function(keyEvent,isInput,keyCode) {
            return false;
        });
        
        Arr.each(arr,function(value) {
            rel($this,value);
        });
    }
    
    return this;
}