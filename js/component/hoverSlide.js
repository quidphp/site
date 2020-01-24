/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
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
        targetHeightTimeout: 500,
        clickOutside: false,
        background: false
    },option);
    
    
    // components
    Component.ClickOpen.call(this,$option);
    
    
    // event
    ael(this,'mouseenter',function() {
        trigEvt(this,'clickOpen:open');
    });
    
    ael(this,'mouseleave',function() {
        trigEvt(this,'clickOpen:close');
    });
    
    
    return this;
}