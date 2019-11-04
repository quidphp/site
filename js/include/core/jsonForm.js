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
quid.core.jsonForm = $.fn.jsonForm = function()
{
    $(this).on('addRemove:bind', function(event,element) {
        var $this = $(this);
        $(this).find(".type select").on('change', function(event) {
            $this.trigger('jsonForm:trigger');
        });
    })
    .on('addRemove:inserted', function(event,element) {
        element.refreshIds();
    })
    .on('jsonForm:showChoices', function(event,parent) {
        parent.find(".choices").addClass("visible");
    })
    .on('jsonForm:hideChoices', function(event,parent) {
        parent.find(".choices").removeClass("visible");
    })
    .on('jsonForm:trigger', function(event) {
        $(this).find(".type select").each(function(index, el) {
            var select = $(this);
            var choices = select.data('choices');
            var val = select.inputValue(true);
            var parent = select.parents(".ele").first();
            
            if($.inArray(val,choices) !== -1)
            $(this).trigger('jsonForm:showChoices',[parent]);
            else
            $(this).trigger('jsonForm:hideChoices',[parent]);
        });
    })
    .trigger('jsonForm:trigger');
    
    return this;
}