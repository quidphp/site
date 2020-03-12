/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// tabsScroll
// component that adds scrolling support to tabsSlider
Component.TabsScroll = function(option) 
{   
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        scroller: window,
        smooth: true,
        resize: true,
        vertical: true,
        horizontal: true
    },option);
    
    
    // components
    Component.TabsSlider.call(this,$option);
    
    
    // handler
    setHdlr(this, 'tabs:canChange', function() {
        return trigHdlr(this, 'scroller:isScrolling') === false;
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        bindScroller.call(this);
        
        if($option.resize === true)
        bindResize.call(this);
    });
    
    
    // bindResize
    const bindResize = function() 
    {
        Component.ResizeChange.call(this,$option);
        
        ael(this, 'resize:stop', function() {
            trigHdlr(this, 'tabs:goFirst');
        });
    }
    
    
    // bindScroller
    const bindScroller = function()
    {
        const $this = this;
        Component.Scroller.call(this, { scroller: $option.scroller });
        
        const tabs = trigHdlr(this, 'tabs:getTargets');
        ael(tabs, 'tab:open', function() {
            const method = ($option.smooth === true)? 'scroller:goSmooth':'scroller:go';
            const offset = Ele.getOffsetParent(this);
            const top = ($option.vertical === true)? offset.top:0;
            const left = ($option.horizontal === true)? offset.left:0;
            trigHdlr($this, method, top, left);
        });
    }
    
    return this;
}