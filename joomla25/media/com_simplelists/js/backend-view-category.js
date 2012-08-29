/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011
 * @link http://www.yireo.com/
 */

function jSelectPicture(path) {
    document.getElementById('image_name').value = path;
    document.getElementById('image').value = path;
    document.getElementById('sbox-window').close();
    if (document.adminForm.image.value !='') {
        document.adminForm.image.src= '../' + document.adminForm.image.value;
    } else {
        document.adminForm.image.src='images/blank.png';
    }
}
