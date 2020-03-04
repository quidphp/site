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
    },option);


    // nodes
    const $nodes = this;


    // handler
    setHdlrs(this, 'typeToggler:', {
        
        getTogglers: function() {
            return qsa(this, '.type-toggler > button');
        },

        getTargets: function() {
            return qsa(this, '.elements > *');
        },

        getToggler: function(value) {
            const togglers = trigHdlr(this, 'typeToggler:getTogglers');
            return Arr.find(togglers, function() {
                return getAttr(this, $option.attr) === value;
            });
        },

        findTargets: function(value) {
            const targets = trigHdlr(this, 'typeToggler:getTargets');
            return Arr.filter(targets, function() {
                return getAttr(this, $option.attr) === value || value === $option.all;
            });
        },

        trigger: function(type) {
            triggerType.call(this, type);
        },
    });


    // setup
    aelOnce(this, 'component:setup', function() {
        bindTogglers.call(this);

        if($option.triggerFirst) 
        trigHdlr(this, 'typeToggler:trigger', $option.triggerFirst);
    });


    // bindTogglers
    const bindTogglers = function() 
    {
        const $this = this;
        const togglers = trigHdlr(this, 'typeToggler:getTogglers');

        ael(togglers, 'click', function() {
            const type = getAttr(this, 'data-type');
            trigHdlr($this, 'typeToggler:trigger', type);
        });
    };


    // triggerType
    const triggerType = function(value) 
    {
        Str.check(value, true);
        const togglers = trigHdlr(this, 'typeToggler:getTogglers');
        const targets = trigHdlr(this, 'typeToggler:getTargets');
        const toggler = trigHdlr(this, 'typeToggler:getToggler', value);
        const matchTargets = trigHdlr(this, 'typeToggler:findTargets', value);

        if(toggler != null && matchTargets != null) 
        {
            setAttr(togglers, $option.attrSelected, 0);
            setAttr(targets, $option.attrVisible, 0);
            setAttr(targets, $option.attrOddEven, 0);
            setAttr(toggler, $option.attrSelected, 1);
            setAttr(matchTargets, $option.attrVisible, 1);

            Arr.each(matchTargets, function(value, index) {
                const key = index + 1;
                const oddEven = Num.isOdd(key) ? 'odd' : 'even';
                setAttr(this, $option.attrOddEven, oddEven);
            });
        }
    };

    return this;
}