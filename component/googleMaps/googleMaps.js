"use strict";

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleMaps
// script containing logic for a simple googleMaps component

// googleMaps
// génère une carte google à partir d'élément jquery
quid.component.googleMaps = function(styles)
{
    if(typeof(google) != 'undefined')
    {
        $(this).addId('googleMaps');
        $(this).each(function(index) 
        {
            var id = $(this).prop("id");
            var lat = $(this).data("lat");
            var lng = $(this).data("lng");
            var zoom = $(this).data("zoom") || 10;
            var icon = $(this).data('icon');
            var iconSize = $(this).data("iconSize");
            var uri = $(this).data('url');
            
            if(quid.base.str.isNotEmpty(id) && quid.base.number.is(lat) && quid.base.number.is(lng))
            {
                var myLatLng = new google.maps.LatLng(lat,lng);
                var mapOptions = {
                    zoom: zoom,
                    center: myLatLng,
                    scrollwheel: false,
                    styles: styles,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                
                if(quid.base.str.isNotEmpty(icon))
                {
                    icon = { url: icon };
                    if(quid.base.number.is(iconSize) && iconSize > 0)
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
                
                if(quid.base.str.isNotEmpty(uri))
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