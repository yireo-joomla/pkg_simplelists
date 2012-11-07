<?php
/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

// Require the SimpleLists helper
require_once JPATH_COMPONENT.'/helpers/plugin.php';

/**
 * HTML View class 
 */
class SimplelistsViewPlugins extends YireoView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        // Set toolbar items for the page
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::editListX();

        // Automatically fetch items, total and pagination - and assign them to the template
        $this->fetchItems();
		
		parent::display($tpl);
	}
}
