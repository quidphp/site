/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// googleMaps
// script containing logic for a simple googleMaps component
Component.GoogleMaps = function(option)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // components
    Component.Base.call(this);
    
    
    // option
    const $option = Pojo.replaceRecursive({
        target: true,
        defaultZoom: 10,
        styles: {}
    },option);
    
    
    // handler
    setHdlrs(this,'googleMaps:',{
        
        has: function() {
            return typeof(google) !== 'undefined';
        },
        
        get: function() {
            return google;
        },
        
        getTarget: function() {
            let r = $option.target;
            
            if(r === true)
            r = this;
            
            if(Str.isNotEmpty(r))
            r = qs(this,r);
            
            return r;
        },
        
        getUri: function() {
            const target = trigHdlr(this,'googleMaps:getTarget');
            return getAttr(target,'data-uri');
        }
    });
    
    
    // handlerSetup
    const handlersSetup = {
        
        getLatLng: function() {
            const googleMaps = trigHdlr(this,'googleMaps:get');
            
            const target = trigHdlr(this,'googleMaps:getTarget');
            const lat = getAttr(target,'data-lat');
            const lng = getAttr(target,'data-lng');
            
            return new googleMaps.maps.LatLng(lat,lng);
        },
        
        getOptions: function(latLng) {
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const target = trigHdlr(this,'googleMaps:getTarget');
            
            return {
                zoom: getAttr(target,'data-zoom') || $option.defaultZoom,
                center: latLng,
                scrollwheel: false,
                styles: $option.styles,
                mapTypeId: googleMaps.maps.MapTypeId.ROADMAP
            }
        },
        
        getIcon: function() {
            let r = null;
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const target = trigHdlr(this,'googleMaps:getTarget');
            const icon = getAttr(target,'data-icon');
            const iconSize = getAttr(target,'data-iconSize');
            
            if(Str.isNotEmpty(icon))
            {
                r = {};
                r.url = icon;
                
                if(Integer.isPositive(iconSize))
                {
                    const anchor = (iconSize / 2);
                    r.scaledSize = new googleMaps.maps.Size(iconSize,iconSize);
                    r.anchor = new googleMaps.maps.Point(anchor,anchor);
                };
            }
            
            return r;
        },

        makeMarker: function(latLng,uri,icon,map) {
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const marker = new googleMaps.maps.Marker({
                position: latLng,
                map: map,
                icon: icon
            });
            
            if(Str.isNotEmpty(uri))
            {
                marker.url = uri;
                googleMaps.maps.event.addListener(marker, 'click',function() {
                    window.open(marker.url,'_blank');
                });
            }
            
            return marker;
        }
    };
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        if(trigHdlr(this,'googleMaps:has'))
        {
            setHdlrs(this,'googleMaps:',handlersSetup);
            renderMap.call(this);
        }
    });
    
    
    // renderMap
    const renderMap = function()
    {
        const googleMaps = trigHdlr(this,'googleMaps:get');
        const target = trigHdlr(this,'googleMaps:getTarget');
        
        if(target != null)
        {
            const latLng = trigHdlr(this,'googleMaps:getLatLng');
            const option = trigHdlr(this,'googleMaps:getOptions',latLng);
            const uri = trigHdlr(this,'googleMaps:getUri');
            const icon = trigHdlr(this,'googleMaps:getIcon');
            
            const map = new googleMaps.maps.Map(target,option);
            trigHdlr(this,'googleMaps:makeMarker',latLng,uri,icon,map);
        }
    }
    
    return this;
}