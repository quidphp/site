/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */

// toggler
// component to toggle attributes on many elements using a trigger
Component.Toggler = function(option) {
    
    // not empty
    if (Vari.isEmpty(this)) 
    return null;


    // option
    const $option = Pojo.replace({
        attr: 'data-type',
        attrVisible: 'data-visible',
        attrSelected: 'data-selected',
        attrOddEven: 'data-odd-even',
        all: 'all',
        triggerFirst: null,
        timeout: 0
    },option);


    // nodes
    const $nodes = this;


    // handler
    setHdlrs(this, 'toggler:', {
        
        getTogglers: function() {
            return qsa(this, '.type-toggler > button');
        },

        getTargets: function() {
            return qsa(this, '.elements > *');
        },

        getToggler: function(value) {
            const togglers = trigHdlr(this, 'toggler:getTogglers');
            return Arr.find(togglers, function() {
                return getAttr(this, $option.attr) === value;
            });
        },

        findTargets: function(value) {
            const targets = trigHdlr(this, 'toggler:getTargets');
            return Arr.filter(targets, function() {
                return getAttr(this, $option.attr) === value || value === $option.all;
            });
        },

        trigger: function(type) {
            triggerType.call(this, type);
        },
        
        setTimeout: function(timeout) {
            Integer.check(timeout);
            $option.timeout = timeout;
        }
    });


    // setup
    aelOnce(this, 'component:setup', function() {
        bindTogglers.call(this);

        if($option.triggerFirst) 
        trigHdlr(this, 'toggler:trigger', $option.triggerFirst);
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
        Str.check(value, true);
        const togglers = trigHdlr(this, 'toggler:getTogglers');
        const targets = trigHdlr(this, 'toggler:getTargets');
        const toggler = trigHdlr(this, 'toggler:getToggler', value);
        const matchTargets = trigHdlr(this, 'toggler:findTargets', value);

        if(toggler != null && matchTargets != null) 
        {
            setAttr(togglers, $option.attrSelected, 0);
            setAttr(targets, $option.attrVisible, 0);
            setAttr(targets, $option.attrOddEven, 0);
            setAttr(toggler, $option.attrSelected, 1);
            
            Func.timeout($option.timeout,function() {
                setAttr(matchTargets, $option.attrVisible, 1);
                
                Arr.each(matchTargets, function(value, index) {
                    const key = index + 1;
                    const oddEven = Num.isOdd(key) ? 'odd' : 'even';
                    setAttr(this, $option.attrOddEven, oddEven);
                });
            });
        }
    };

    return this;
}