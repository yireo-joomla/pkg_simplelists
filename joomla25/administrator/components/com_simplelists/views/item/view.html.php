<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2013
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla! 
defined('_JEXEC') or die();

// Import the needed helpers
require_once JPATH_COMPONENT.'/helpers/html.php';

/**
 * HTML View class for the Simplelists component
 *
 * @static
 * @package    Simplelists
 */
class SimplelistsViewItem extends YireoViewForm
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Give a warning if no categories are configured
        SimplelistsHelper::checkCategories() ;

        // Load jQuery
        YireoHelper::jquery();

        // Fetch the item automatically
        $this->fetchItem(true);
    
        // Modify the item a bit
        $this->item->image_default_folder = COM_SIMPLELISTS_DIR;
        $this->item->image_default_uri = COM_SIMPLELISTS_BASEURL;

        $this->item->picture_folder = $this->item->image_default_folder;
        $this->item->picture_uri = $this->item->image_default_uri;
        $this->item->picture_path = null;
        if(!empty($this->item->picture)) {
            $this->item->picture_path = JPATH_SITE.'/'.$this->item->picture;
            $this->item->picture_uri = $this->item->picture;
            $this->item->picture_folder = dirname($this->item->picture_uri);
        }

        // Add extra filtering lists
        $defaultCategory = ($this->item->id == 0) ? (int)$this->getFilter('category_id', null, null, 'com_simplelists_items_') : null;
        $categories_params = array('item_id' => $this->item->id, 'multiple' => 1, 'current' => $defaultCategory);
        $this->lists['categories'] = SimplelistsHTML::selectCategories( 'categories[]', $categories_params );
        
        // Construct the modal boxes
        $modal = array() ;
        $modal['picture'] = 'index.php?option=com_simplelists&amp;view=files&amp;tmpl=component&amp;type=picture&amp;current='.$this->item->picture;
        if($this->item->picture) $modal['picture'] .= '&amp;folder='.$this->item->picture_folder;
        $this->assignRef('modal', $modal);

        // Construct the slider-panel
        jimport('joomla.html.pane');
        if(class_exists('JPane')) {
            $pane = JPane::getInstance('sliders');
            $this->assignRef('pane', $pane);
        } else {
            $pane = null;
            $this->assignRef('pane', $pane);
        }

        if(YireoHelper::isJoomla15()) {

            // Include extra JavaScript
            $this->addJs('mootools-cookie.js');
            $this->addJs('view-browser.js');

        } else {

            // Fetch the selected tab
            $session = JFactory::getSession();
            $activeTab = $session->set('simplelists.item.tab');
            if(empty($activeTab)) $activeTab = 'basic';
            $this->assignRef('activeTab', $activeTab);
        }

        // Load the plugins
        $link_plugins = SimplelistsPluginHelper::getPlugins('simplelistslink');
        $this->assignRef('link_plugins', $link_plugins);

        // Add extra stuff
        JHTML::_('behavior.tooltip');
        JHTML::_('behavior.modal', 'a.modal-button');

        parent::display($tpl);
    }
}
