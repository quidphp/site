/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

// toggler
// component to toggle attributes on many elements using a trigger
Component.Toggler = function(option) {
    
    // not empty
    if (Vari.isEmpty(this)) 
    return null;


    // option
    const $option = Pojo.replace({
        target: ".target",
        toggler: ".toggler",
        attr: 'data-type',
        attrVisible: 'data-visible',
        attrSelected: 'data-selected',
        all: 'all',
        triggerSetup: null,
        keyboardArrow: "horizontalNotInput",
        limit: null,
        timeout: 0,
        loop: true
    },option);

    
    // component
    Component.IndexNode.call(this,{current: 'toggler:getCurrentToggler', targets: 'toggler:getTogglers', loop: $option.loop});
    
    
    // nodes
    const $nodes = this;


    // handler
    setHdlrs(this, 'toggler:', {
        
        getTogglers: function() {
            return qsa(this, $option.toggler);
        },

        getTargets: function() {
            return qsa(this, $option.target);
        },

        getToggler: function(value) {
            const togglers = trigHdlr(this, 'toggler:getTogglers');
            return Arr.find(togglers, function(ele) {
                return getAttr(ele, $option.attr) === value;
            });
        },
        
        getCurrentToggler: function() {
            const togglers = trigHdlr(this, 'toggler:getTogglers');
            return Arr.find(togglers, function(ele) {
                return getAttr(ele, $option.attrSelected,'int') === 1;
            });
        },
        
        findTargets: function(value) {
            const targets = trigHdlr(this, 'toggler:getTargets');
            let r = Arr.filter(targets, function(ele) {
                return getAttr(ele, $option.attr) === value || value === $option.all;
            });
            
            if(Integer.is($option.limit))
            r = Arr.slice(0,$option.limit,r);
            
            return r;
        },

        trigger: function(type) {
            if(type != null)
            triggerType.call(this, type);
        },
        
        setTimeout: function(timeout) {
            Integer.typecheck(timeout);
            $option.timeout = timeout;
        },
        
        findType: function(type) {
            const node = trigHdlr(this,'indexNode:find',type);
            return (node != null)? Ele.getAttr(node,$option.attr):null;
        }
    });

    
    // event
    ael(this,'keyboardArrow:left',function(event,keyEvent,isInput) {
        const type = trigHdlr(this,'toggler:findType','prev');
        trigHdlr(this,'toggler:trigger',type);
    });
    
    ael(this,'keyboardArrow:right',function(event,keyEvent,isInput) {
        const type = trigHdlr(this,'toggler:findType','next');
        trigHdlr(this,'toggler:trigger',type);
    });
    
    
    // setup
    aelOnce(this, 'component:setup', function() {
        bindTogglers.call(this);

        if($option.triggerSetup) 
        trigHdlr(this, 'toggler:trigger', $option.triggerSetup);
        
        if(Ele.match(this,'[tabindex]'))
        Component.KeyboardArrow.call(this,$option.keyboardArrow);
    });


    // bindTogglers
    const bindTogglers = function() 
    {
        const $this = this;
        const togglers = trigHdlr(this, 'toggler:getTogglers');

        ael(togglers, 'click', function() {
            const type = getAttr(this, 'data-type');
            trigHdlr($this, 'toggler:trigger', type);
        });
    };


    // triggerType
    const triggerType = function(value) 
    {
        Str.typecheck(value, true);
        
        const togglers = trigHdlr(this, 'toggler:getTogglers');
        const targets = trigHdlr(this, 'toggler:getTargets');
        const selectedToggler = Ele.find(togglers,"["+$option.attrSelected+"='1']");
        const toggler = trigHdlr(this, 'toggler:getToggler', value);
        const matchTargets = trigHdlr(this, 'toggler:findTargets', value);

        if(toggler != null && matchTargets != null && toggler !== selectedToggler) 
        {
            toggleAttr(togglers, $option.attrSelected, false);
            toggleAttr(targets, $option.attrVisible, false);
            toggleAttr(toggler, $option.attrSelected, true);
            trigEvt(this,'toggler:unset',targets);
            
            Func.timeout($option.timeout,function() {
                toggleAttr(matchTargets, $option.attrVisible, true);
                trigEvt(this,'toggler:set',matchTargets,targets);
            },this);
        }
    };

    return this;
}