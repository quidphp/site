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
        element.refreshIds();
        $(document).trigger('document:mountNodeCommon',[element]);
    })
    .on('jsonForm:showChoices', function(event,parent) {
        parent.find(".choices").addClass("visible");
    })
    .on('jsonForm:hideChoices', function(event,parent) {
        parent.find(".choices").removeClass("visible");
    })
    .on('jsonForm:trigger', function(event) {
        $(this).find(".type input[data-fakeselect='1']").each(function(index, el) {
            var type = $(this).parents(".type").first();
            var choices = type.data('choices');
            var val = $(this).inputValue(true);
            var parent = $(this).parents(".ele").first();
            
            if(quid.base.arr.in(val,choices))
            $(this).trigger('jsonForm:showChoices',[parent]);
            else
            $(this).trigger('jsonForm:hideChoices',[parent]);
        });
    })
    
    // component:setup
    .on('component:setup', function(event) {
        var $this = $(this);
        $(this).on('change', ".type input[data-fakeselect='1']", function(event) {
            $this.trigger('jsonForm:trigger');
        });
        
        $(this).trigger('jsonForm:trigger');
    });
    
    return this;
}