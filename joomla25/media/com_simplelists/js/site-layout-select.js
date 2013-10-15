/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2013 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

jQuery(document).ready(function() {

    var trigger = jQuery('#simplelist-select');
    var blocks = jQuery('.simplelists-item');

    trigger.change(function(event) {
        blocks.hide();
        selected = jQuery('#item' + trigger.val());
        selected.show();
    });
    
    if( window.location.hash != '' ) {
    	
        blocks.hide();
        
    	hash = window.location.hash.replace('#','');
    	id = window.location.hash.replace('#item','');
        jQuery('#' + hash).show();
        trigger.val(id);
    }
    
});
