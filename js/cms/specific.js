/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// specific
// script of additional behaviours for the specific form page of the CMS
$(document).ready(function() {
	
	// route:specificPrepare
	$(this).on('route:specificPrepare', function(event) {
		var formWrapper = $(".specific .container > .form");
		var preparable = formWrapper.triggerHandler('form:getPreparable');
		
		// jsonForm
		var jsonForm = formWrapper.find(".element.jsonForm");
		if(jsonForm.length)
		jsonForm.jsonForm();
		
		preparable.on('specificForm:prepare', function(event) {
			$(this).find(".element .googleMaps").googleMaps();
			$(this).find(".element.tinyMce .tinymce, .element.tinyMceAdvanced .tinymce").each(function(index, el) {
				var parent = $(this).parents(".element").first();
				var hasTableRelation = parent.hasClass('tableRelation');
				var data = $(this).data('tinymce') || { };
				var textarea = $(this).parent().find("textarea");
				
				if(hasTableRelation === true)
				{
					var filters = parent.find(".relations .filter");
					data.setup = function(editor) {
						editor.on('click', function(e) {
					      filters.trigger('clickOpen:close');
					    });
					};
					
					textarea.on('textarea:insert', function(event,html) {
						var r = false;
						var editor = $(this).data('editor');
						
						if($.isStringNotEmpty(html) && editor)
						editor.execCommand('mceInsertContent',false,html);
						
						return r;
					});
				}
				
				var editor = $(this).tinymce(data);
				textarea.data('editor',editor);
			});
		});
	});
});