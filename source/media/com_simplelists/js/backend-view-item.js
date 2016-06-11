/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright 2016
 * @link https://www.yireo.com/
 */

/*
 * Form validation function
 */
function submitbutton(pressbutton) 
{
    var form = document.adminForm;
    if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
    }

    // do field validation
    if (form.title.value == ""){
        alert( form_no_title );
    } else {
        submitform( pressbutton );
    }
}

/*
 * Generic function to close a modal-box
 */
function slModalClose()
{
    if(SqueezeBox) {
        SqueezeBox.close();
    }

    var box = document.getElementById('sbox-window')
    if(box) {
        box.close();
    }
}

/*
 * Function (J1.5) to select an article through modal popup
 */
function jSelectArticle(id, title) 
{
    document.getElementById('link_article').value = id;
    document.getElementById('link_name_article').value = title;
    document.getElementById('link_type_article').checked = true;
    slModalClose();
}

/*
 * Function (J1.6+) to select an article through modal popup
 */
function slSelectArticle(id, title) 
{
    document.getElementById('link_article').value = id;
    document.getElementById('link_name_article').value = title;
    document.getElementById('link_type_article').checked = true;
    slModalClose();
}

/*
 * Function (J1.x) to select a linkimage through modal popup
 */
function slSelectLinkImage(path) 
{
    document.getElementById('link_image').value = path;
    document.getElementById('link_name_image').value = path;
    document.getElementById('link_type_image').checked = true;
    slModalClose();
}

/*
 * Function (J1.x) to select ... nothing
 */
function slSelectNothing() 
{
    slModalClose();
}


/*
 * Function (J1.x) to select a linkfile through modal popup
 */
function slSelectLinkFile(path) 
{
    document.getElementById('link_file').value = path;
    document.getElementById('link_name_file').value = path;
    document.getElementById('link_type_file').checked = true;
    slModalClose();
}

/*
 * Function (J1.x) to select a picture through modal popup
 */
function slSelectPicture(path) 
{
    document.getElementById('picture_name').value = path;
    document.getElementById('picture').value = path;
    slModalClose();
        
    if(document.getElementById('picture').value !='') {
        document.getElementById('picture-preview').src= '../' + document.getElementById('picture').value;
    } else {
        document.getElementById('picture-preview').src='images/blank.png';
    }
}

/*
 * Wrapper for SimpleLists-functions called by core Media Manager
 */
function jInsertFieldValue(value, id)
{
    if(id == 'picture_name') { slSelectPicture(value); }
    if(id == 'link_image') { slSelectLinkImage(value); }
    if(id == 'link_file') { slSelectLinkFile(value); }
}

/*
 * Some extra GUI tricks
 */
jQuery(document).ready(function(){

    // Autofocus the title input-field
    if(jQuery('#title')) {
        jQuery('#title').focus();
    }

    // Fetch the selected tab if it is available
    jQuery('a[data-toggle="tab"]').on('shown', function (e) {
        url = '' + e.target;
        tab = url.split('#')[1];
        jQuery.ajax({
            type: 'POST',
            url: 'index.php?option=com_simplelists&task=cookie&name=tab', 
            data: {tab: tab}
        });
    });
});

