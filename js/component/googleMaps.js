/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
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
    const $option = Pojo.replace({
        target: true,
        marker: true,
        ui: true,
        zoom: 10,
        control: true,
        style: []
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

            return Ele.typecheck(r);
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
            const r = {
                zoom: getAttr(target,'data-zoom','int') || $option.zoom,
                center: latLng,
                scrollwheel: false,
                styles: $option.style,
                disableDefaultUI: ($option.ui === true)? false:true,
                mapTypeId: googleMaps.maps.MapTypeId.ROADMAP
            };
            
            if($option.control !== true)
            {
                r.gestureHandling = 'none';
                r.zoomControl = false;
                r.draggableCursor = 'default';
            }
            
            return r;
        },
        
        getIcon: function() {
            let r = null;
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const target = trigHdlr(this,'googleMaps:getTarget');
            const icon = getAttr(target,'data-icon');
            const iconSize = getAttr(target,'data-icon-size','int');
            
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
            
            if($option.marker)
            trigHdlr(this,'googleMaps:makeMarker',latLng,uri,icon,map);
        }
    }
    
    return this;
}