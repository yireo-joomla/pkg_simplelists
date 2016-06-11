<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

// Require the SimpleLists helper
require_once JPATH_COMPONENT.'/helpers/category.php';

/**
 * HTML View class 
 */
class SimplelistsViewCategories extends YireoViewList
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
        JToolBarHelper::preferences('com_simplelists', '480');
        JHtml::_('behavior.tooltip');

        // Automatically fetch items, total and pagination - and assign them to the template
        $this->fetchItems();
		
        // Re-order the items by parent
        $listview = $this->getFilter('listview');
        if( $listview == 'tree' ) {
            $tree = new SimplelistsCategoryTree();
            $tree->setItems($this->items);
            $this->items = $tree->getList();
        } else {
            $listview = 'flat';
        }

        // Listview box
        $options[] = array( 'id' => 'tree', 'title' => 'Tree' );
        $options[] = array( 'id' => 'flat', 'title' => 'Flat list' );
		$extra = 'onchange="document.adminForm.submit();"';
		$this->lists['listview'] = JHtml::_('select.genericlist', $options, 'filter_listview', $extra, 'id', 'title', $listview);

		parent::display($tpl);
	}
}
