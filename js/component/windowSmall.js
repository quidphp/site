/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// windowSmall
// component to open a small window from an anchor link
Component.WindowSmall = function(option) 
{    
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        width: 1000,
        height: 1000,
        left: null,
        top: null
    },option);
    
    
    // handler
    setHdlrs(this,'windowSmall:',{
        
        getOptions: function() {
            const width = getAttr(this,'data-width','int') || $option.width;
            const height = getAttr(this,'data-height','int') || $option.height;
            const defaultLeft = (window.screen.width / 2) - (width / 2)
            const defaultTop = (window.screen.height / 2) - (height / 2);
            
            return {
                width: width,
                height: height,
                left: getAttr(this,'data-left','int') || $option.left || defaultLeft,
                top: getAttr(this,'data-top','int') || $option.top || defaultTop
            };
        },
        
        getParam: function() {
            const opt = trigHdlr(this,'windowSmall:getOptions');
            return "toolbar=no,left="+opt.left+",top="+opt.top+",width="+opt.width+",height="+opt.height+",location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no";
        }
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        bindClick.call(this);
    });
    
    
    // bindClick
    const bindClick = function()
    {
        Ele.addId(this,'window-small-');
        
        ael(this,'click',function(event) {
            const href = getAttr(this,'href');
            const id = getProp(this,'id');
            const param = trigHdlr(this,'windowSmall:getParam');
            const small = window.open(href,id,param);
            small.focus();
            window.blur();
            
            Evt.preventStop(event);
        });
    }
    
    return this;
}