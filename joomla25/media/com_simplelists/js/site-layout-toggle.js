/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

window.addEvent('domready', function() {
	
    var accordion = new Accordion('a.heading', 'div.body', {
        opacity: false
    }, $('simplelists'));
    
    if( window.location.hash != '' ) {
    	
    	hash = window.location.hash.replace('#','');
    	accordion.elements.each(function(item, index) {
    		if( hash == item.id && index < accordion.togglers.length) {
    			accordion.display(index);
    		}
    	}); 
    }
});
