/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// hoverSlide
// component to change height of target with mouseenter/mouseleave
Component.HoverSlide = function(option) 
{   
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        target: ".target",
        targetHeight: true,
        transitionTimeout: 500,
        clickOutside: false,
        background: false
    },option);
    
    
    // components
    Component.ClickOpen.call(this,$option);
    
    
    // event
    ael(this,'mouseenter',function() {
        const timeout = getData(this,'hoverSlide-timeout');
        if(timeout != null)
        clearTimeout(timeout);
        
        trigEvt(this,'clickOpen:open');
    });
    
    ael(this,'mouseleave',function() {
        const timeout = (trigHdlr(this,'clickOpen:canClose'))? 0:$option.transitionTimeout;
        const func = Func.timeout(timeout,function() {
            trigEvt(this,'clickOpen:close');
        },this);
        
        setData(this,'hoverSlide-timeout',func);
    });
    
    return this;
}