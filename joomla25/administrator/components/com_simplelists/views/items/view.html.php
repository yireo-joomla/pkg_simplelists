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

// Require the parent view
require_once JPATH_COMPONENT.DS.'lib'.DS.'view'.DS.'list.php';

// Import the needed helpers
require_once JPATH_COMPONENT.DS.'helpers'.DS.'html.php';

/**
 * HTML View class 
 */
class SimplelistsViewItems extends YireoViewList
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Set extra toolbar items for the page
        JToolBarHelper::preferences('com_simplelists', '480');
        //JToolBarHelper::help( 'screen.simplelist' );

        // Preliminary check to see if any categories have been configured yet
        SimplelistsHelper::checkCategories() ;

        // Automatically fetch items, total and pagination - and assign them to the template
        $this->fetchItems();

        // Prepare data for each simplelists item
        foreach($this->items as $index => $item) {
            $item->categories = SimplelistsHelper::getCategories( $item->id );
            $item->edit_link = JRoute::_('index.php?option=com_simplelists&view=item&task=edit&cid[]='. $item->id);
            $this->items[$index] = $item;
        }

        // build list of categories
        $category_id_params = array( 'current' => $this->getFilter('category_id'), 'javascript' => 1, 'nullvalue' => 1 );
        $this->lists['category_id'] = SimplelistsHTML::selectCategories('filter_category_id', $category_id_params);
        $this->lists['link_type'] = SimplelistsHTML::selectLinkType($this->getFilter('link_type'));

        parent::display($tpl);
    }
}
