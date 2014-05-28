/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2014
 * @link http://www.yireo.com/
 */

// Container for the current selected item (path + file)
var current_item = '' ;

/*
 * Function to set the current modal-item
 */
function setModalItem(path, element) 
{
    current_item = path;

    jQuery('div.item a').css('background-color', '#ffffff' );
    jQuery('div.item a').css('border-color', '#eeeeee' );

    if(jQuery('#' + element)) {
        jQuery('#' + element).css('background-color', '#eeeeee'); 
        jQuery('#' + element).css('border-color', '#5a5a5a'); 
    }

    if(jQuery('#folder-indicator')) {
        jQuery('#folder-indicator').html(path);
    }
}

function submitModalForm(target, value) 
{
    if(target != null) {
        target(value);
    }
}
