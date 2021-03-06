/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// tabsSlider
// component that adds timeout and iframe support to the tabsNav component
Component.TabsSlider = function(option) 
{   
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        iframe: true,
        timeoutEvent: 'tabs:afterChange',
        timeout: null,
        targetClick: false
    },option);
    
    
    // components
    Component.TabsNav.call(this,$option);
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        if($option.iframe === true)
        bindIframe.call(this);
        
        if(Integer.isPositive($option.timeout))
        bindTimeout.call(this);
        
        if($option.targetClick === true)
        bindTargetClick.call(this);
    });
    
    
    // bindIframe
    const bindIframe = function()
    {
        ael(this,'tabs:beforeChange',function(event,tab,oldTab) {
            if(oldTab != null)
            {
                const iframe = qs(oldTab,'iframe');
                if(iframe != null)
                {
                    const src = getAttr(iframe,'src');
                    setAttr(iframe,'src',src);
                }
            }
        });
    }
    
    
    // bindTimeout
    const bindTimeout = function()
    {
        Component.Timeout.call(this,$option.timeoutEvent,$option.timeout);
        
        ael(this,'timeout:'+$option.timeoutEvent,function() {
            trigHdlr(this,'tabs:goNext');
        });
        
        ael(this,'mouseover',function() {
            trigHdlr(this,'timeout:clear',$option.timeoutEvent)
        });
        
        ael(this,'mouseleave',function() {
            trigHdlr(this,'timeout:set',$option.timeoutEvent);
        });
    }
    
    // bindTargetClick
    const bindTargetClick = function()
    {
        const $this = this;
        const targets = trigHdlr(this,'tabs:getTargets');
        
        ael(targets,'click',function(event) {
            const triggerTarget = Evt.getTriggerTarget(event);
            
            if(!Ele.closest(triggerTarget,'a'))
            trigHdlr($this,'tabs:goNext');
        });
    }
    
    return this;
}