/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// jsonForm
// script containing logic for the jsonForm component
Component.jsonForm = function()
{
    // trigger
    $(this).on('addRemove:inserted',function(event,element) {
        $(document).trigger('document:mountNodeCommon',[element]);
        bindElement.call(this,element);
    })
    
    // component:setup
    .one('component:setup',function(event) {
        const $this = $(this);
        
        triggerFunc(this,'addRemove:getAll').each(function() {
            bindElement.call($this,$(this));
        });
    });
    
    // bindElement
    const bindElement = function(element) {
        const $this = $(this);
        const typeSelect = element.find(".type input[data-fakeselect='1']");
        
        element.on('jsonForm:getTypeElement',function(event) {
            return $(this).find(".type");
        })
        .on('jsonForm:getTypeSelect',function(event) {
            return triggerFunc(this,'jsonForm:getTypeElement').find("input[data-fakeselect='1']");
        })
        .on('jsonForm:getTypeChoices',function(event) {
            return triggerFunc(this,'jsonForm:getTypeElement').data('choices');
        })
        .on('jsonForm:getChoices',function(event) {
            return $(this).find(".choices");
        })
        .on('jsonForm:showChoices',function(event) {
            triggerFunc(this,'jsonForm:getChoices').addClass("visible");
        })
        .on('jsonForm:hideChoices',function(event,parent) {
            triggerFunc(this,'jsonForm:getChoices').removeClass("visible");
        })
        .on('jsonForm:refresh',function(event) {
            const typeSelect = triggerFunc(this,'jsonForm:getTypeSelect');
            const val = typeSelect.val();
            const choices = triggerFunc(this,'jsonForm:getTypeChoices');
            triggerCustom(this,(Arr.in(val,choices))? 'jsonForm:showChoices':'jsonForm:hideChoices');
        })
        .on('jsonForm:setup',function(event) {
            const $this = $(this);
            const type = triggerFunc(this,'jsonForm:getTypeSelect');
            
            type.on('change',function(event) {
                $this.trigger('jsonForm:refresh');
            });
            
            triggerCustom(this,'jsonForm:refresh');
        })
        .trigger('jsonForm:setup');
    };
    
    return this;
}