/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// clickRemove
// component that removes itself on click
Component.ClickRemove = function()
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // event
    ael(this,'click',function() {
        Ele.remove(this);
    });
    
    return this;
}