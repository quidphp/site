/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// jsonForm
// script containing logic for the jsonForm component which is based on the addRemove input
Component.JsonForm = function(option)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // components
    Component.AddRemove.call(this,option);
    
    
    // event
    ael(this,'addRemove:inserted',function(event,element) {
        bindElement.call(this,element);
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        const $this = this;
        const items = trigHdlr(this,'addRemove:getItems');
        
        Arr.each(items,function() {
            bindElement.call($this,this);
        });
    });
    
    
    // bindElement
    const bindElement = function(element) {
        const $this = this;
        
        // handler
        setHdlrs(element,'jsonForm:',{
            
            getTypeElement: function() {
                return qs(this,".type");
            },
            
            getTypeSelect: function() {
                const type = trigHdlr(this,'jsonForm:getTypeElement');
                return qs(type,"select");
            },
            
            getTypeChoices: function() {
                const type = trigHdlr(this,'jsonForm:getTypeElement');
                return getAttr(type,'data-choices',true);
            },
            
            getChoices: function() {
                return qs(this,".choices");
            },
            
            showChoices: function() {
                const choices = trigHdlr(this,'jsonForm:getChoices');
                toggleAttr(choices,'data-visible',true);
            },
            
            hideChoices: function() {
                const choices = trigHdlr(this,'jsonForm:getChoices');
                toggleAttr(choices,'data-visible',false);
            },
            
            refresh: function() {
                const typeSelect = trigHdlr(this,'jsonForm:getTypeSelect');
                const val = trigHdlr(typeSelect,'input:getValue');
                const choices = trigHdlr(this,'jsonForm:getTypeChoices');
                trigHdlr(this,(Arr.in(val,choices))? 'jsonForm:showChoices':'jsonForm:hideChoices');
            }
        });
        
        // setup
        aelOnce(element,'component:setup',function() {
            const $this = this;
            const typeSelect = trigHdlr(this,'jsonForm:getTypeSelect');
            
            ael(typeSelect,'change',function() {
                trigHdlr($this,'jsonForm:refresh');
            });
            
            trigHdlr(this,'jsonForm:refresh');
        });
        
        trigSetup(element);
    };
    
    return this;
}