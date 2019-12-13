/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// specific
// script of additional behaviours for the specific form page of the CMS
ael(document,"DOMContentLoaded", function() {
	
	// specificForm:bindView
    // se bind à l'ouverture du panneau
	ael(this,'specificForm:bindView',function(event,node) {
        
        const jsonForm = qsa(node,"[data-col='jsonForm'] .specific-component");
        const googleMaps = qsa(node,"[data-col='googleMaps'] .specific-component");
        
        // googleMaps
        trigSetup(Component.GoogleMaps.call(googleMaps,{target: ".map-render"}));
        
        // jsonForm
        trigSetup(Component.JsonForm.call(jsonForm));
	});
});