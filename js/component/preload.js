/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// preload
// component to preload assets, currently only images
Component.Preload = function(option) 
{   
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        attr: null
    },option);
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        preload.call(this);
    });
    
    
    // preload
    const preload = function()
    {
        let src;
        const tag = Ele.tag(this);
        
        if(tag === 'img')
        src = getAttr(this,'src');
        
        if(Vari.isEmpty(src) && $option.attr)
        src = getAttr(this,$option.attr);
        
        if(Str.isNotEmpty(src))
        {
            const img = new Image();
            img.src = src;
        }
    }
    
    return this;
}