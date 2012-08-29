/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2012
 * @link http://www.yireo.com/
 */

// Container for the current selected item (path + file)
var current_item = '' ;

/*
 * Function to set the current modal-item
 */
function setModalItem(path) 
{
    current_item = path;

    $$('div.item a').setStyle('background-color', '#ffffff' );
    $$('div.item a').setStyle('border-color', '#eeeeee' );

    if($(path)) {
        $(path).setStyle('background-color', '#eeeeee'); 
        $(path).setStyle('border-color', '#5a5a5a'); 
    }

    if($('folder-indicator')) {
        $('folder-indicator').innerHTML = path;
    }
}

function submitModalForm(target, value) 
{
    if(target != null) {
        target(value);
    }
}
