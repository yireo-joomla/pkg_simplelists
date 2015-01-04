/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @link http://www.yireo.com/
 */

var current_image = '' ;

function setImage( path, file ) {

    if( image_base_path ) {
        current_image = image_base_path + '/' + file;
    } else {
        current_image = file;
    }

    jQuery('div.item a').css('background-color', '#ffffff' );
    jQuery('div.item a').css('border-color', '#eeeeee' );
    jQuery(path).css('background-color', '#eeeeee'); 
    jQuery(path).css('border-color', '#5a5a5a'); 
    jQuery('folder-indicator').html(current_image);
}

