/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2012 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

jQuery(document).ready(function() {
	
    var trigger = jQuery('#simplelists-navigator a.simplelist-hover');
    var blocks = jQuery('.simplelists-item');
    
    triggers.each(function(event) {
    	
        trigger.addEvent("mouseenter", function(event) {

            blocks.hide();
            selected = trigger.id.replace( 'simplelist-hover', 'item' ) ;
            jQuery(selected).show();
        });
    });
    
    if( window.location.hash != '' ) {
    	hash = window.location.hash.replace('#','');
    	blocks.each(function(item, index) {
    		if( hash == item.id) {
                blocks.hide();
    			item.show();
    		}
    	}); 
    }
});
