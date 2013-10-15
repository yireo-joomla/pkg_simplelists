/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2013 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

jQuery(document).ready(function() {

    panels = jQuery('div.accordion-body')
    panels.hide();
    jQuery('a.accordion-toggle').click(function() {
        panels.slideUp();
        jQuery(this).parent().next().slideDown();
        return false;
    });
    
    if( window.location.hash != '' ) {
        hash = window.location.hash.replace('#','');
        jQuery('#' + hash).parent().next().slideDown();
    } else {
        jQuery('a.accordion-toggle').first().parent().next().slideDown();
    }
});
