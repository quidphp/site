"use strict";

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// jsonForm
// script containing logic for the jsonForm component

// jsonForm
// génère un input jsonForm, comme addRemove mais avec un peu plus de logique pour chaque élément
quid.component.jsonForm = function()
{
    // trigger
    $(this).on('addRemove:inserted', function(event,element) {
        $(document).trigger('document:mountNodeCommon',[element]);
        bindElement.call(this,element);
    })
    
    // component:setup
    .one('component:setup', function(event) {
        var $this = $(this);
        
        $(this).triggerHandler('addRemove:getAll').each(function() {
            bindElement.call($this,$(this));
        });
    });
    
    // bindElement
    var bindElement = function(element) {
        var $this = $(this);
        var typeSelect = element.find(".type input[data-fakeselect='1']");
        
        element.on('jsonForm:getTypeElement', function(event) {
            return $(this).find(".type");
        })
        .on('jsonForm:getTypeSelect', function(event) {
            return $(this).triggerHandler('jsonForm:getTypeElement').find("input[data-fakeselect='1']");
        })
        .on('jsonForm:getTypeChoices', function(event) {
            return $(this).triggerHandler('jsonForm:getTypeElement').data('choices');
        })
        .on('jsonForm:getChoices', function(event) {
            return $(this).find(".choices");
        })
        .on('jsonForm:showChoices', function(event) {
            $(this).triggerHandler('jsonForm:getChoices').addClass("visible");
        })
        .on('jsonForm:hideChoices', function(event,parent) {
            $(this).triggerHandler('jsonForm:getChoices').removeClass("visible");
        })
        .on('jsonForm:refresh', function(event) {
            var typeSelect = $(this).triggerHandler('jsonForm:getTypeSelect');
            var val = typeSelect.val();
            var choices = $(this).triggerHandler('jsonForm:getTypeChoices');
            $(this).trigger((quid.base.arr.in(val,choices))? 'jsonForm:showChoices':'jsonForm:hideChoices');
        })
        .on('jsonForm:setup', function(event) {
            var $this = $(this);
            var type = $(this).triggerHandler('jsonForm:getTypeSelect');
            
            type.on('change', function(event) {
                $this.trigger('jsonForm:refresh');
            });
            
            $(this).trigger('jsonForm:refresh');
        })
        .trigger('jsonForm:setup');
    };
    
    return this;
}