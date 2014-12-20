<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Import the needed libraries
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the SimpleLists component
 *
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsViewSimplelist extends JView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        $document =& JFactory::getDocument();
        $dispatcher =& JDispatcher::getInstance();

        // Ugly way to get around PHP-notices
        ini_set('display_errors', 0);

        // load the parameters
        $params = &JComponentHelper::getParams( 'com_simplelists' );

        // load the model
        $model =& $this->getModel();

        // get the category from our model
        $category = $model->getCategory() ;
        $category->params = new JParameter( $category->params );

        // set the page title
        if( $params->get('show_page_title') == 1 && $params->get('page_title') != '' ) {
            $document->setTitle( $params->get('page_title') );
            $category->title = $params->get('page_title') ;
        } else {
            $document->setTitle( $category->title );
        }

        // get the simple list from our model
        $simplelist = $model->getData() ;

        // run the category content through Content Plugins
        if( $params->get('show_category_description') && $category->description ) {
            $category->text = $category->description;

            JPluginHelper::importPlugin( 'content' );
            $cparams = array();
            $results = $dispatcher->trigger('onPrepareContent', array ( &$category, &$cparams, 0));

            $category->description = $category->text; 
            $category->text = null ;
        }

        // prepare the category image
        if( $params->get('show_category_image') && $category->image ) {
            $category->image = SimplelistsHTML::image( JURI::base().'images/simplelists/'.$category->image, $category->title, array( 'align' => $category->image_position));
        } else {
            $params->set('show_category_image', 0);
        }

        // check if the list is empty
        if( is_array($simplelist) && !empty( $simplelist )) {

            // loop through the list to set things right
            foreach( $simplelist as $id => $item ) {

                // Merge the parameters
                if( $item->params ) {
                    $p = clone( $params );
                    $p->merge( new JParameter( $item->params ));
                    $item->params = $p;
                } else {
                    $item->params = $params;
                }

                // Override certain parameters
                $item->params->set('show_vote', 0);
                unset($item->rating_count);

                // Run the content through Content Plugins
                if( $item->params->get('show_item_text') ) {

                    JPluginHelper::importPlugin( 'content' );
                    $iparams = array();
                    $results = $dispatcher->trigger('onPrepareContent', array ( &$item, &$iparams, 0));

                } else {
                    $item->text = '' ;
                }

                // prepare the URL
                $item->url = SimplelistsPluginHelper::getPluginLinkUrl($item));

                // create a simple target-string
                switch( $item->params->get('target') ) {
                    case 1:
                        $item->target = ' target="_blank"' ;
                        break;
                    case 2:
                        $item->target = ' onclick="javascript: window.open(\''. $item->url .'\', \'\', \'toolbar=no,location=no,status=no,' 
                            . 'menubar=no,scrollbars=yes,resizable=yes,width=780,height=550\'); return false"' ;
                        break;
                    default:
                        $item->target = false;
                        break;
                }

                // set the readmore link for this item
                if( $item->params->get('readmore') == 1 && $item->url ) {
                    $readmore_text = $item->params->get( 'readmore_text', JText::sprintf( 'Read more', $item->title )) ;
                    $readmore_css = trim( 'readon ' . $item->params->get( 'readmore_class', '' ));
                    $item->readmore = JHTML::link( $item->url, $readmore_text, 'title="'.$item->title.'" class="'.$readmore_css.'"'.$item->target );
                } else {
                    $item->readmore = false;
                }

                // set the image-alignment for this item
                if( $item->params->get('picture_alignment') != '' && $layout != 'picture' ) {
                    $item->picture_alignment = ' align="' . $item->params->get('picture_alignment') . '"';
                } else {
                    $item->picture_alignment = false;
                }

                // prepare the image
                if( $item->params->get('show_item_image') && !empty( $item->picture )) {
                    $attributes = 'title="'.$item->title.'" class="simplelists"'.$item->picture_alignment;
                    $item->picture = SimplelistsHTML::image( $item->picture, $item->title, $attributes);

                    if( $item->picture && $item->params->get('image_link') && !empty( $item->url )) {

                        if($item->params->get( 'link_class') != '') {
                            $item_link_class = ' class="'.$item->params->get('link_class').'"' ;
                        } else {
                            $item_link_class = '';
                        }

                        if($item->params->get('link_rel') != '') {
                            $item_link_rel = ' rel="'.$item->params->get('link_rel').'"' ;
                        } else {
                            $item_link_rel = '';
                        }

                        $item->picture = JHTML::link( $item->url, $item->picture, 'title="'.$item->title.'"'.$item->target.$item_link_class.$item_link_rel );
                    }
                } else {
                    $item->picture = null ;
                }

                // prepare the title
                if( $item->params->get('show_item_title') ) {
                    if( $item->params->get('title_link') && !empty( $item->url )) {
                        $item->title = JHTML::link( $item->url, $item->title, $item->target );
                    }
                } else {
                    $item->title = null ;
                }

                // set specific layout settings
                $item->style = '';
                if( $layout == 'select' || $layout == 'hover' ) {

                    if(empty($firstflag)) {
                        $firstflag = 1;
                        $item->style = 'display:block; visibility:visible;';
                    }
                }

                // enable parsing the content
                JPluginHelper::importPlugin( 'content' );
                $results = $dispatcher->trigger('onBeforeDisplayContent', array ( &$item, &$item->params, 0));
                foreach($results as $result) {
                    $item->text .= $result;
                }

                // save the item in the array
                $simplelist[$id] = $item ;
            }
        }

        // assign all variables to this layout
        $this->assignRef( 'simplelist', $simplelist );
        $this->assignRef( 'category', $category );
        $this->assignRef( 'params', $params );

        parent::display($tpl);
    }
}
