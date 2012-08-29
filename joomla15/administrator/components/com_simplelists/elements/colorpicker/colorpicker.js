/*
 * Joomla! 1.5 modal colorpicker
 * Built on MooRainbow of W00fz, modified by Yireo
 *
 * @author Yireo (info@yireo.com)
 * @package Yireo
 * @copyright Copyright 2010
 * @license MIT-style license
 */

/*
 * Function called when the color is selected in the modal box
 *
 * @param string color - Value of the new selected color
 * @param string name - Name of HTML element
 */
function jSelectColor(color, name) {
    document.getElementById(name + '_id').value = color;
    document.getElementById(name + '_preview').style.backgroundColor = color;
    document.getElementById(name + '_name').value = color;
    document.getElementById('sbox-window').close();
}
