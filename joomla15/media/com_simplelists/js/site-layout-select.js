/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

window.addEvent('domready', function() {
    var trigger = $( 'simplelist-select' );
    var blocks = $$( '.simplelists-item' );

    //var fx = new Fx.Elements(triggers, {wait: false, duration: 300});
    trigger.addEvent('change', function(event) {
    	
        blocks.each(function(block, j) {
            block.setStyle( 'display', 'none' );
            block.setStyle( 'visibility', 'hidden' );
        });
        
        thisblock = 'item' + trigger.value ;
        $(thisblock).setStyle( 'display', 'block' );
        $(thisblock).setStyle( 'visibility', 'visible' );
    });
    
    if( window.location.hash != '' ) {
    	
        blocks.each(function(block, j) {
            block.setStyle( 'display', 'none' );
            block.setStyle( 'visibility', 'hidden' );
        });
        
    	hash = window.location.hash.replace('#','');
    	id = window.location.hash.replace('#item','');
        trigger.value = id;
    	
    	blocks.each(function(item, index) {
    		if( hash == item.id) {
    			item.setStyle( 'display', 'block' );
    			item.setStyle( 'visibility', 'visible' );
    		}
    	}); 
    }
    
});
