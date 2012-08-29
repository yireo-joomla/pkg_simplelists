/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

window.addEvent('domready', function() {
	
    var triggers = $$( "#simplelists-navigator a.simplelist-hover" );
    var blocks = $$( ".simplelists-item" );
    var fx = new Fx.Elements(triggers, {wait: false, duration: 300});
    
    triggers.each(function(trigger, i) {
    	
        trigger.addEvent("mouseenter", function(event) {
            blocks.each(function(block, j) {
                block.setStyle( 'display', 'none' );
                block.setStyle( 'visibility', 'hidden' );
            });
            thisblock = trigger.id.replace( 'simplelist-hover', 'item' ) ;
            
            $(thisblock).setStyle( 'display', 'block' );
            $(thisblock).setStyle( 'visibility', 'visible' );
            
        });
    });
    
    if( window.location.hash != '' ) {
    	
        blocks.each(function(block, j) {
            block.setStyle( 'display', 'none' );
            block.setStyle( 'visibility', 'hidden' );
        });
    	
    	hash = window.location.hash.replace('#','');
    	blocks.each(function(item, index) {
    		if( hash == item.id) {
    			item.setStyle( 'display', 'block' );
    			item.setStyle( 'visibility', 'visible' );
    		}
    	}); 
    }
});
