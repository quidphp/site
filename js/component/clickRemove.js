/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// clickRemove
// component that fades out and removes itself on click
Component.ClickRemove = function(speed)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // speed
    speed = speed || 1000;
    
    
    // event
    ael(this,'click',function() {
        const $this = this;
        const promise = Ele.animate(this,{opacity: 0},speed)
        
        promise.done(function() {
            Ele.remove($this);
        });
    });
    
    return this;
}