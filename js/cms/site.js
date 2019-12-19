/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// site
// script of additional behaviours for the specific form page of the CMS

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