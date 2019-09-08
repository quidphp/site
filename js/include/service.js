/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// service
// script containing logic for third-party services
(function ($, document, window) {
	
	// googleAnalytics
	// permet de lier le chargement d'une nouvelle page via ajax et de l'envoyer à google Analytics
	$.fn.googleAnalytics = function()
	{
		$(this).on('navigation:complete', function(event,uri) {
			if($.isStringNotEmpty(uri) && typeof ga !== 'undefined' && $.isFunction(ga))
			{
				ga('set','page',uri);
				ga('send','pageview');
			}
		});
		
		return this;
	}
	
	
	// googleMaps
	// génère une carte google à partir d'élément jquery
	$.fn.googleMaps = function(styles)
	{
		if(typeof(google) != 'undefined')
		{
			$(this).addIds('googleMaps');
			$(this).each(function(index) 
			{
				var id = $(this).prop("id");
				var lat = $(this).data("lat");
				var lng = $(this).data("lng");
				var zoom = $(this).data("zoom") || 10;
				var icon = $(this).data('icon');
				var iconSize = $(this).data("iconSize");
				var uri = $(this).data('url');
				
				if($.isStringNotEmpty(id) && $.isNumeric(lat) && $.isNumeric(lng))
				{
					var myLatLng = new google.maps.LatLng(lat,lng);
					var mapOptions = {
						zoom: zoom,
						center: myLatLng,
						scrollwheel: false,
						styles: styles,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					
					if($.isStringNotEmpty(icon))
					{
						icon = { url: icon };
						if($.isNumeric(iconSize) && iconSize > 0)
						{
							var anchor = (iconSize / 2);
							icon.scaledSize = new google.maps.Size(iconSize,iconSize);
							icon.anchor = new google.maps.Point(anchor,anchor);
						};
					}
					
					var map = new google.maps.Map(document.getElementById(id), mapOptions);
					var marker = new google.maps.Marker({
						position: myLatLng,
						map: map,
						url: uri,
						icon: icon
					});
					
					if($.isStringNotEmpty(uri))
					{
						google.maps.event.addListener(marker, 'click', function() {
							window.open(marker.url, '_blank');
						});
					}
				}
			});
		}
		
		return this;
	}
	
	
	// tinymce
	// génère un éditeur de texte à partir de tinycme
	$.fn.tinymce = function(data)
	{
		var r = null;

		if($(this).length === 1)
		{
			$(this).addIds('tinycme');
			var textarea = $(this);
			var id = $(this).prop('id');
			data.selector = "#"+id;
			data.init_instance_callback = function (editor) {
				editor.on('Blur', function (e) {
					editor.save();
				});
			};
			tinymce.init(data);
			r = tinymce.get(id);
		}
		
		return r;
	}
	
}(jQuery, document, window));