<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the parent view
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'lib'.DS.'view.php';

// Require the YireoHelper class
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'lib'.DS.'helper.php';

/**
 * HTML View class 
 */
class SimplelistsViewItem extends YireoView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Get system variables
        $application = JFactory::getApplication();
        $document = JFactory::getDocument();

        // Get the item from our model
        $this->fetchItem();

        // Set the meta-data
        $document->setTitle( $this->item->title );
        $document->setMetaData('title', $this->item->title);

        // Initialize the parameters
        if($this->item->params) {
            $p = clone($this->params);
            $this->item->params = YireoHelper::toParameter($this->item->params);
            $p->merge($this->item->params);
            $this->item->params = $p;
        } else {
            $this->item->params = $this->params;
        }

        if($this->item->params->get('meta_author') != '') {
            $document->setMetaData('author', $this->item->params->get('meta_author'));
        }

        if($this->item->params->get('meta_description') != '') {
            $document->setDescription( $this->item->params->get('meta_description'));
        }

        if($this->item->params->get('meta_keywords') != '') {
            $document->setMetadata('keywords', $this->item->params->get('meta_keywords'));
        }

        // Parse important parameters
        $layout = null;
        $this->item = $this->_prepareItem($this->item, $layout);

        // Check whether the item is still here
        if(empty($this->item)) {
            JError::raiseError(404, JText::_('Resource Not Found'));
        }

        // when the link was "hidden", we call the link-plugin to decide what to do
        if(JRequest::getCmd('task') == 'hidden') {
            $this->item->params->set('show_link', 0);
            $this->item->extra = SimplelistsPluginHelper::getPluginLinkHidden($item);
        } else {
            $this->item->extra = null;
        }

        parent::display($tpl);
    }

    /*
     * Method to prepare a specific item
     *
     * @param object $item
     * @param string $layout
     * @return object
     */
    public function _prepareItem($item, $layout) 
    {
        // Get system variables
        $user = &JFactory::getUser();
        $dispatcher =& JDispatcher::getInstance();
        $params = $this->params;

        // Run the content through Content Plugins
        if( $item->params->get('enable_content_plugins', 1) == 1 ) {
            JPluginHelper::importPlugin( 'content' );
            $iparams = array();
            $results = $dispatcher->trigger('onPrepareContent', array ( &$item, &$iparams, 0));
        }

        // Disable the text when needed
        if( $item->params->get('show_item_text', 1) == 0 ) {
            $item->text = null;
        }

        // Prepare the URL
        $item->url = JRoute::_(SimplelistsPluginHelper::getPluginLinkUrl($item));
        if($item->alias) {
            $item->href = $item->alias;
        } else {
            $item->href = 'item'.$item->id;
        }

        // Create a simple target-string
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

        // Set the readmore link for this item
        if( $item->params->get('readmore') == 1 && $item->url ) {
            $readmore_text = $item->params->get( 'readmore_text', JText::sprintf( 'Read more', $item->title )) ;
            $readmore_css = trim( 'readon ' . $item->params->get( 'readmore_class', '' ));
            $item->readmore = JHTML::link( $item->url, $readmore_text, 'title="'.$item->title.'" class="'.$readmore_css.'"'.$item->target );
        } else {
            $item->readmore = null;
        }

        // Set the image-alignment for this item
        if( $item->params->get('picture_alignment') != '' && $layout != 'picture' ) {
            $item->picture_alignment = ' align="' . $item->params->get('picture_alignment') . '"';
        } else {
            $item->picture_alignment = null;
        }

        // Prepare the image
        if( $item->params->get('show_item_image', 1) && !empty( $item->picture )) {
            $attributes = 'title="'.$item->title.'" class="simplelists"'.$item->picture_alignment;
            $item->picture = SimplelistsHTML::image( $item->picture, $item->title, $attributes);
        } else {
            $item->picture = null ;
        }

        // Prepare the title
        if( $item->params->get('show_item_title', 1) ) {
            if( $item->params->get('title_link') && !empty( $item->url )) {
                $item->title = JHTML::link( $item->url, $item->title, $item->target );
            }
        } else {
            $item->title = null ;
        }

        // Set specific layout settings
        $item->style = '';
        if( $layout == 'select' || $layout == 'hover' ) {
            if(empty($firstflag)) {
                static $firstflag = 1;
                $item->style = 'display:block; visibility:visible;';
            }
        }

        // Enable parsing the content
        JPluginHelper::importPlugin( 'content' );
        $results = $dispatcher->trigger('onBeforeDisplayContent', array ( &$item, &$item->params, 0));
        foreach($results as $result) {
            if(!empty($result)) $item->text .= $result;
        }

        return $item;
    }
}
