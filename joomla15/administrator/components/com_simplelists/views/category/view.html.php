<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

// Import the needed libraries
jimport('joomla.filesystem.file');

// Import the needed helpers
require_once JPATH_COMPONENT.'/helpers/html.php';

/**
 * HTML View class
 */
class SimplelistsViewCategory extends YireoView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Automatically fetch the item and assign it to the layout
        $this->fetchItem();

        // Common lists
        $parent_id_params = array('nullvalue' => 1, 'nulltitle' => JText::_('No parent'), 'current' => $this->item->parent_id, 'self' => $this->item->id);
        $this->lists['parent_id'] = SimplelistsHTML::selectCategories( 'parent_id', $parent_id_params );
        
        // Construct the modal boxes
        $modal = array() ;
        //$modal['image'] = 'index.php?option=com_simplelists&amp;view=files&amp;tmpl=component&amp;type=picture' ;
        //$modal['image'] .= ($this->item->image) ? '&amp;folder=/'.dirname($this->item->image).'&amp;current='.$this->item->image : '&amp;current=';
        $this->assignRef('modal', $modal);

        // Construct the slider-panel
        jimport('joomla.html.pane');
        if(class_exists('JPane')) {
            $pane = & JPane::getInstance('sliders');
            $this->assignRef('pane', $pane);
        } else {
            $pane = false;
            $this->assignRef('pane', $pane);
        }

        // Load jQuery 
        YireoHelper::jquery();

        // Add extra JavaScript
        JHTML::_('behavior.formvalidation');
        $this->addJs('form-validation.js');

        parent::display($tpl);
    }
}
