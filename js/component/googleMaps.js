/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

let isGoogleMapLoaded = false
window.setGoogleMapLoaded = function() {
    isGoogleMapLoaded = true
}

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
        scrollwheel: false,
        click: false,
        param: {},
        // note, for next update
        // using advancedMarker requires a mapId, having a mapId prevents having custom style injected as object
        mapId: undefined,
        style: undefined
    },option);
    
    // handler
    setHdlrs(this,'googleMaps:',{
        
        has: function() {
            return isGoogleMapLoaded;
        },
        
        getType: function() {
            return $option.style == null ? 'advanced':'simple';
        },

        get: function() {
            return Obj.typecheck(google);
        },
        
        getTarget: function() {
            let r = $option.target;
            
            if(r === true)
            r = this;
            
            if(Str.isNotEmpty(r))
            r = qs(this,r,true);

            return r;
        },
        
        getMap: function() {
            const map = getData(this,'google-map');
            return Obj.typecheck(map);
        },
        
        getUri: function() {
            const target = trigHdlr(this,'googleMaps:getTarget');
            return getAttr(target,'data-uri');
        }
    });
    
    
    // handlerSetup
    const handlersSetup = {
        
        getLatLng: function() {
            const target = trigHdlr(this,'googleMaps:getTarget');
            const lat = getAttr(target,'data-lat');
            const lng = getAttr(target,'data-lng');
            
            return {
                lat: lat,
                lng: lng
            }
        },
        
        getOptions: function() {
            const type = trigHdlr(this,'googleMaps:getType');
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const target = trigHdlr(this,'googleMaps:getTarget');
            const latLng = makeLatLng.call(this,true);

            let mapId
            if(type === 'advanced')
            mapId = $option.mapId || "google_maps_"+Integer.unique();
            
            const r = Pojo.replace({
                zoom: getAttr(target,'data-zoom','int') || $option.zoom,
                center: latLng,
                scrollwheel: $option.scrollwheel,
                disableDefaultUI: ($option.ui === true)? false:true,
                mapTypeId: googleMaps.maps.MapTypeId.ROADMAP,
                styles: option.style,
                mapId
            },$option.param);
            
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
            const type = trigHdlr(this,'googleMaps:getType');
            const target = trigHdlr(this,'googleMaps:getTarget');
            const icon = getAttr(target,'data-icon');
            const iconSize = getAttr(target,'data-icon-size','int');
            
            if(Str.isNotEmpty(icon) && Str.isNotEmpty(type))
            {
                r = { url: icon };
                
                if(Integer.isPositive(iconSize))
                {
                    if(type === 'advanced')
                    r.iconSize = iconSize;

                    if(type === 'simple') {
                        const anchor = (iconSize / 2);
                        r.scaledSize = new google.maps.Size(iconSize,iconSize);
                        r.anchor = new google.maps.Point(anchor,anchor);
                    }
                }
            }
            
            return r;
        },

        handleClick: function() {
            const map = trigHdlr(this,'googleMaps:getMap');
            const uri = trigHdlr(this,'googleMaps:getUri');

            if(Str.isNotEmpty(uri)) {
                map.addListener("click", function(arg) {
                    Evt.preventStop(arg.domEvent);
                    window.open(uri,'_blank');
                });
            }
        },

        makeMarker: function(latLng,uri,icon) {
            let r = null;
            const type = trigHdlr(this,'googleMaps:getType');
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const map = trigHdlr(this,'googleMaps:getMap');
            latLng = makeLatLng.call(this,latLng);
                
            if(uri === true)
            uri = trigHdlr(this,'googleMaps:getUri');
            
            if(icon === true)
            icon = trigHdlr(this,'googleMaps:getIcon');
            
            if(type === 'simple')
            {
                const param = {
                    position: latLng,
                    map: map,
                    icon: icon
                };
                r = new google.maps.Marker(param);
            }

            if(type === 'advanced')
            {
                let iconImg;
                if(Pojo.isNotEmpty(icon) && Str.isNotEmpty(icon.url)) {
                    iconImg = document.createElement('img');
                    iconImg.src = icon.url;
    
                    if(Integer.is(icon.iconSize))
                    {
                        iconImg.style.width = icon.iconSize+"px";
                        iconImg.style.height = icon.iconSize+"px";
                    }
                }
    
                const param = {
                    position: latLng,
                    map: map,
                    content: iconImg ? iconImg : undefined,
                    gmpClickable: Str.isNotEmpty(uri)
                };
                
                r = new google.maps.marker.AdvancedMarkerElement(param);
            }
            
            if(Str.isNotEmpty(uri))
            {
                r.url = uri;
                googleMaps.maps.event.addListener(r, 'click',function(arg) {
                    Evt.preventStop(arg.domEvent);
                    window.open(r.url,'_blank');
                });
            }
            
            return r;
        }
    };
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        const $this = this;
        const max = 10;
        let inc = 0;

        const callback = function() {
            if(trigHdlr($this,'googleMaps:has'))
            {
                setHdlrs($this,'googleMaps:',handlersSetup);
                renderMap.call($this);
                return true;
            }
        }

        const initialResult = callback();

        if(initialResult !== true) {
            const interval = setInterval(function() {
                try {
                    const result = callback();
                    if(result === true) {
                        clearInterval(interval);
                    }
        
                    else {
                        inc++;
                        if(inc > max) {
                            clearInterval(interval);
                        }
                    }
                }
                catch {
                    clearInterval(interval);
                }
            },500)
        }
    });
    
    
    // makeLatLng
    const makeLatLng = function(value)
    {
        let r = null;
        
        if(value === true)
        value = trigHdlr(this,'googleMaps:getLatLng');
        
        if(Pojo.is(value))
        {
            const lat = Num.typecheck(value.lat);
            const lng = Num.typecheck(value.lng);
            r = new google.maps.LatLng(lat,lng);
        }
        
        return Obj.typecheck(r);
    }
    
    
    // renderMap
    const renderMap = function()
    {
        const googleMaps = trigHdlr(this,'googleMaps:get');
        const target = trigHdlr(this,'googleMaps:getTarget');
        const option = trigHdlr(this,'googleMaps:getOptions');
        const map = new google.maps.Map(target,option);
        setData(this,'google-map',map);
        
        if($option.marker)
        trigHdlr(this,'googleMaps:makeMarker',true,true,true);

        if($option.click)
        trigHdlr(this,'googleMaps:handleClick');
    }
    
    return this;
}