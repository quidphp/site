/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleMaps
// script containing logic for a simple googleMaps component
Quid.Component.googleMaps = function(styles)
{
    if(typeof(google) != 'undefined')
    {
        Quid.Dom.addId('googleMaps-',this);
        $(this).each(function(index) 
        {
            var id = $(this).prop("id");
            var lat = $(this).data("lat");
            var lng = $(this).data("lng");
            var zoom = $(this).data("zoom") || 10;
            var icon = $(this).data('icon');
            var iconSize = $(this).data("iconSize");
            var uri = $(this).data('url');
            
            if(Quid.Str.isNotEmpty(id) && Quid.Number.is(lat) && Quid.Number.is(lng))
            {
                var myLatLng = new google.maps.LatLng(lat,lng);
                var mapOptions = {
                    zoom: zoom,
                    center: myLatLng,
                    scrollwheel: false,
                    styles: styles,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                
                if(Quid.Str.isNotEmpty(icon))
                {
                    icon = { url: icon };
                    if(Quid.Number.is(iconSize) && iconSize > 0)
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
                
                if(Quid.Str.isNotEmpty(uri))
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