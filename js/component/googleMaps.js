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
        scrollwheel: false,
        param: {},
        style: []
    },option);
    
    
    // handler
    setHdlrs(this,'googleMaps:',{
        
        has: function() {
            return typeof(google) !== 'undefined';
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
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const target = trigHdlr(this,'googleMaps:getTarget');
            const latLng = makeLatLng.call(this,true);
            
            const r = Pojo.replace({
                zoom: getAttr(target,'data-zoom','int') || $option.zoom,
                center: latLng,
                scrollwheel: $option.scrollwheel,
                styles: $option.style,
                disableDefaultUI: ($option.ui === true)? false:true,
                mapTypeId: googleMaps.maps.MapTypeId.ROADMAP
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

        makeMarker: function(latLng,uri,icon) {
            let r = null;
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const map = trigHdlr(this,'googleMaps:getMap');
            latLng = makeLatLng.call(this,latLng);
                
            if(uri === true)
            uri = trigHdlr(this,'googleMaps:getUri');
            
            if(icon === true)
            icon = trigHdlr(this,'googleMaps:getIcon');
            
            const param = {
                position: latLng,
                map: map,
                icon: icon
            };
            
            r = new googleMaps.maps.Marker(param);
            
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
        if(trigHdlr(this,'googleMaps:has'))
        {
            setHdlrs(this,'googleMaps:',handlersSetup);
            renderMap.call(this);
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
            const googleMaps = trigHdlr(this,'googleMaps:get');
            const lat = Num.typecheck(value.lat);
            const lng = Num.typecheck(value.lng);
            r = new googleMaps.maps.LatLng(lat,lng);
        }
        
        return Obj.typecheck(r);
    }
    
    
    // renderMap
    const renderMap = function()
    {
        const googleMaps = trigHdlr(this,'googleMaps:get');
        const target = trigHdlr(this,'googleMaps:getTarget');
        const option = trigHdlr(this,'googleMaps:getOptions');
        const map = new googleMaps.maps.Map(target,option);
        setData(this,'google-map',map);
        
        if($option.marker)
        trigHdlr(this,'googleMaps:makeMarker',true,true,true);
    }
    
    return this;
}