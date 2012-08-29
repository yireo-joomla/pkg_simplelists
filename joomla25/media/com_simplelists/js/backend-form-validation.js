/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011
 * @link http://www.yireo.com/
 */

function submitbutton(pressbutton) {
    if (pressbutton == 'cancel') { 
        submitform(pressbutton);
        return;
    }
        
    var f = document.adminForm;
    if (document.formvalidator.isValid(f)) {
        submitform(pressbutton);    
    }    

    /* @todo: Give focus on first missing field */
}
