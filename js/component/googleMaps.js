/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleMaps
// script containing logic for a simple googleMaps component
Component.googleMaps = function(styles)
{
    if(typeof(google) != 'undefined')
    {
        DomChange.addId('googleMaps-',this);
        $(this).each(function(index) 
        {
            const id = $(this).prop("id");
            const lat = $(this).data("lat");
            const lng = $(this).data("lng");
            const zoom = $(this).data("zoom") || 10;
            const icon = $(this).data('icon');
            const iconSize = $(this).data("iconSize");
            const uri = $(this).data('url');
            
            if(Str.isNotEmpty(id) && Num.is(lat) && Num.is(lng))
            {
                const myLatLng = new google.maps.LatLng(lat,lng);
                const mapOptions = {
                    zoom: zoom,
                    center: myLatLng,
                    scrollwheel: false,
                    styles: styles,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                
                if(Str.isNotEmpty(icon))
                {
                    icon = { url: icon };
                    if(Num.is(iconSize) && iconSize > 0)
                    {
                        const anchor = (iconSize / 2);
                        icon.scaledSize = new google.maps.Size(iconSize,iconSize);
                        icon.anchor = new google.maps.Point(anchor,anchor);
                    };
                }
                
                const map = new google.maps.Map(document.getElementById(id), mapOptions);
                const marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    url: uri,
                    icon: icon
                });
                
                if(Str.isNotEmpty(uri))
                {
                    google.maps.event.addListener(marker, 'click',function() {
                        window.open(marker.url, '_blank');
                    });
                }
            }
        });
    }
    
    return this;
}