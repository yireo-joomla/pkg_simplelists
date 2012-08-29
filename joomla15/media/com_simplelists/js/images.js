/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011
 * @link http://www.yireo.com/
 */

var current_image = '' ;

function setImage( path, file ) {

    if( image_base_path ) {
        current_image = image_base_path + '/' + file;
    } else {
        current_image = file;
    }

        $$('div.item a').setStyle('background-color', '#ffffff' );
        $$('div.item a').setStyle('border-color', '#eeeeee' );

    $(path).setStyle('background-color', '#eeeeee'); 
    $(path).setStyle('border-color', '#5a5a5a'); 
    $('folder-indicator').setText( current_image );
}

