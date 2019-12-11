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
        $(document).trigger('doc:mountNodeCommon',[element]);
        bindElement.call(this,element);
    })
    
    // component:setup
    .one('component:setup',function(event) {
        const $this = $(this);
        
        trigHandler(this,'addRemove:getAll').each(function() {
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
            return trigHandler(this,'jsonForm:getTypeElement').find("input[data-fakeselect='1']");
        })
        .on('jsonForm:getTypeChoices',function(event) {
            return trigHandler(this,'jsonForm:getTypeElement').data('choices');
        })
        .on('jsonForm:getChoices',function(event) {
            return $(this).find(".choices");
        })
        .on('jsonForm:showChoices',function(event) {
            trigHandler(this,'jsonForm:getChoices').addClass("visible");
        })
        .on('jsonForm:hideChoices',function(event,parent) {
            trigHandler(this,'jsonForm:getChoices').removeClass("visible");
        })
        .on('jsonForm:refresh',function(event) {
            const typeSelect = trigHandler(this,'jsonForm:getTypeSelect');
            const val = typeSelect.val();
            const choices = trigHandler(this,'jsonForm:getTypeChoices');
            trigEvt(this,(Arr.in(val,choices))? 'jsonForm:showChoices':'jsonForm:hideChoices');
        })
        .on('jsonForm:setup',function(event) {
            const $this = $(this);
            const type = trigHandler(this,'jsonForm:getTypeSelect');
            
            type.on('change',function(event) {
                trigEvt($this,'jsonForm:refresh');
            });
            
            trigEvt(this,'jsonForm:refresh');
        })
        .trigger('jsonForm:setup');
    };
    
    return this;
}