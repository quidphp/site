/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// site
// script of additional behaviours for the specific form page of the CMS

// generalComponents:update
// adds additional general components to the array
ael(document,'generalComponents:update',function(event,components) {
    
    // googleMaps
    components.push({
        match: "[data-col='googleMaps']",
        component: Component.GoogleMaps,
        option: {target: ".map-render"}
    });
});

// specificComponents:update
// adds additional specific components to the array
ael(document,'specificComponents:update',function(event,components) {
    
    // jsonForm
    components.push({
        match: "[data-col='jsonForm']",
        component: Component.JsonForm,
        setupOnView: true
    });
    
    // googleMaps
    components.push({
        match: "[data-col='googleMaps']",
        component: Component.GoogleMaps,
        option: {target: ".map-render"},
        setupOnView: true
    });
});