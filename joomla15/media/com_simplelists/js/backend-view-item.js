/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011
 * @link http://www.yireo.com/
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
    } else {
        document.getElementById('sbox-window').close();
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
 * Some extra GUI tricks
 */
window.addEvent('domready', function() {

    // Autofocus the title input-field
    if($('title')) {
        $('title').focus();
    }
});

/*
 * Re-create the Accordion-effect including support for a cookie
 */
window.addEvent('domready', function() {

    var currentPane = SLCookie.read('simplelists-item-pane');
    if(!currentPane > 0) {
        currentPane = 0;
    }

    /*
    jpanes = new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {
        onActive: function(toggler, i) {
            toggler.addClass('jpane-toggler-down'); 
            toggler.removeClass('jpane-toggler'); 
        },
        onBackground: function(toggler, i) { 
            toggler.addClass('jpane-toggler');
            toggler.removeClass('jpane-toggler-down'); 
        },
        onComplete: function() {
            SLCookie.write('simplelists-item-pane', this.previous );  
        },
        duration: 300,
        display: currentPane,
        opacity: false 
    });
    */
});

