/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
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
    
    
    // handler
    setHdlrs(this,'jsonForm:',{
        
        getAttr: function() {
            return getAttr(this,'data-form',true);
        }
    });
    
    
    // event
    ael(this,'addRemove:inserted',function(event,element) {
        bindElement.call(this,element);
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        const $this = this;
        const items = trigHdlr(this,'addRemove:getItems');
        
        Arr.each(items,function(ele) {
            bindElement.call($this,ele);
        });
    });
    
    
    // bindElement
    const bindElement = function(element) {
        const $this = this;
        
        // handler
        setHdlrs(element,'jsonForm:',{
            
            getComponent: function() {
                return $this;
            },
            
            getTypeElement: function() {
                return qs(this,".current > .type",true);
            },
            
            getTypeSelect: function() {
                const type = trigHdlr(this,'jsonForm:getTypeElement');
                return qs(type,"select",true);
            },
                        
            getNodeFromKey: function(key) {
                Str.typecheck(key,true);
                return qs(this,".current > ."+key,true);
            },
            
            refresh: function() {
                syncJsonForm.call(this);
            }
        });
        
        // setup
        aelOnce(element,'component:setup',function() {
            const $this = this;
            const typeSelect = trigHdlr(this,'jsonForm:getTypeSelect');
            
            ael(typeSelect,'input',function() {
                trigHdlr($this,'jsonForm:refresh');
            });
            
            trigHdlr(this,'jsonForm:refresh');
        });
        
        // syncJsonForm
        const syncJsonForm = function()
        {
            const $this = this;
            const component = trigHdlr(this,'jsonForm:getComponent');
            const attr = trigHdlr(component,'jsonForm:getAttr');
            const typeSelect = trigHdlr(this,'jsonForm:getTypeSelect');
            const typeValue = trigHdlr(typeSelect,'input:getValue');
            
            Pojo.each(attr,function(val,key) {
                const node = trigHdlr($this,'jsonForm:getNodeFromKey',key);
                toggleAttr(node,'data-visible',Arr.in(typeValue,val));
            });
        }
        
        trigSetup(element);
    };
    
    return this;
}