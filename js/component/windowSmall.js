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
        x: 0,
        y: 0
    },option);
    
    
    // handler
    setHdlrs(this,'windowSmall:',{
        
        getOptions: function() {
            return {
                width: Dom.getAttrInt(this,'data-width') || $option.width,
                height: Dom.getAttrInt(this,'data-height') || $option.height,
                x: Dom.getAttrInt(this,'data-x') || $option.x,
                y: Dom.getAttrInt(this,'data-y') || $option.y
            };
        },
        
        getParam: function() {
            const opt = trigHdlr(this,'windowSmall:getOptions');
            return "toolbar=no,left="+opt.x+",top="+opt.y+",width="+opt.width+",height="+opt.height+",location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no";
        }
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        bindClick.call(this);
    });
    
    
    // bindClick
    const bindClick = function()
    {
        DomChange.addId(this,'window-small-');
        
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