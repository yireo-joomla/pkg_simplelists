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

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper
require_once dirname(dirname(__FILE__)).'/lib/helper.php';

/**
 * Simplelists HTML Helper
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsHTML
{
    /**
     * Method to parse a list of categories into a HTML selectbox
     *
     * @access public
     * @param int ID of current item 
     * @param int ID of parent category
     * @return string HTML output
     */
    public function getCategories($parent_id = null) 
    {
        // Include the SimplelistsCategoryTree helper-class
        require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/helpers/category.php';

        // Fetch the categories and parse them in a tree
        $categories = SimplelistsHelper::getCategories( null, $parent_id ) ;
        $tree = new SimplelistsCategoryTree( $categories );
        $categories = $tree->getList();

        // Add a prefix to the category-title depending on the category-level
        foreach( $categories as $cid => $category ) {

            // Add a simple prefix to the category name
            $prefix = '';
            for( $i = 1; $i < $category->level; $i++ ) {
                $prefix .= '--';
            }

            if( !empty( $prefix )) {
                $category->title = $prefix . ' ' . $category->title ;
            }

            $categories[$cid] = $category;
        }

        return $categories;
    }

    /**
     * Method to set the current category
     *
     * @access    public
     * @param    int ID of current item 
     * @param    int ID of parent category
     * @return    string HTML output
     */
    public function getCurrentCategory( $categories, $item_id = 0 ) 
    {
        // If there is no current item yet and we only have one category, select it
        if( $item_id == 0 ) {
            if( count($categories) == 1 ) {
                $current = $categories ;
            } else {
                $application = JFactory::getApplication();
                $option = JRequest::getCmd('option');
                $id = $application->getUserStateFromRequest( $option.'filter_category_id', 'filter_category_id', 0, 'int' );
                $current = SimplelistsHelper::getCategory( $id );
            }
        
        // Fetch the categories currently selected with the item
        } else {
            $current = SimplelistsHelper::getCategories( $item_id ) ;
        }

        return $current;
    }

    /**
     * Method to parse a list of categories into a HTML selectbox
     *
     * @access    public
     * @param    int ID of current item 
     * @param    int ID of parent category
     * @return    string HTML output
     */
    public function selectCategories( $name = '', $params = array()) 
    {
        // Fetch all categories
        $parent_id = (isset($params['parent_id'])) ? $params['parent_id'] : null;
        $categories = SimplelistsHTML::getCategories($parent_id);

        // Remove the current category
        if(isset($params['self'])) {
            foreach($categories as $index => $category) {
                if($category->id == $params['self']) {
                    unset($categories[$index]);
                    break;
                }
            }
        }
    
        // If the $item_id is set, find all the current categories for a SimpleLists item
        $current = (isset($params['item_id'])) ? SimplelistsHTML::getCurrentCategory($categories, $params['item_id']) : null;
        $current = (isset($params['current'])) ? $params['current'] : $current;
        
        // If no current is set, and if the count of categories is 1, select the first category list
        if(empty($current) && count($categories) == 1) {
            $current = $categories[0]->id;
        }
        
        // Define extra HTML-arguments
        $extra = '';

        // If $multiple is true, we assume we're in the Item Edit form
        if( isset($params['multiple']) && $params['multiple'] == 1 ) {
            $size = (count($categories) < 10) ? count($categories) : 10 ;
            $extra .= 'size="'.$size.'" multiple';
        }
        
        // If $none is true, we include an extra option
        if( isset($params['nullvalue']) && $params['nullvalue'] == 1) {
            $nulltitle = (isset($params['nulltitle'])) ? $params['nulltitle'] : JText::_('COM_SIMPLELISTS_SELECT_CATEGORY');
            array_unshift( $categories, JHTML::_('select.option', 0, '- '.$nulltitle.' -', 'id', 'title' ));
        }

        // If $javascript is true, we submit the form as soon as an option has been selected
        if( isset($params['javascript']) && $params['javascript'] == 1 ) {
            $extra .= 'onchange="document.adminForm.submit();"';
        }

        return JHTML::_('select.genericlist', $categories, $name, $extra, 'id', 'title', $current );
    }

    /**
     * Method to parse a list of link-types into a HTML selectbox
     *
     * @access public
     * @param int $current
     * @return string HTML output
     */
    public function selectLinkType( $current = '' ) 
    {
        if(YireoHelper::isJoomla15()) {
            $query = 'SELECT name AS title, element AS value FROM #__plugins WHERE folder="simplelistslink" ORDER BY ordering';
        } else {
            $query = 'SELECT name AS title, element AS value FROM #__extensions WHERE type="plugin" AND folder="simplelistslink" ORDER BY ordering';
        }
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $plugins = $db->loadObjectList();

        $options = array();
        $options[] = JHTML::_('select.option', '', '- '.JText::_('COM_SIMPLELISTS_SELECT_LINKTYPE').' -', 'id', 'title' );
        if(!empty($plugins)) {
            foreach($plugins as $plugin) {
                $title = trim(preg_replace('/simplelists\ \-/i', '', $plugin->title));
                $options[] = JHTML::_('select.option', $plugin->value, $title, 'id', 'title' );
            }
        }

        $javascript = 'onchange="document.adminForm.submit();"';
        return JHTML::_('select.genericlist', $options, 'filter_link_type', $javascript, 'id', 'title', $current );
    }

    /**
     * Method to parse a list of link-types into a HTML selectbox
     *
     * @access    public
     * @param    int ID of current option
     * @return    string HTML output
     */
    public function selectImagePosition($name, $default) 
    {
        $options[] = array( 'id' => 'left', 'title' => 'Left' );
        $options[] = array( 'id' => 'right', 'title' => 'Right' );
        $options[] = array( 'id' => 'center', 'title' => 'Center' );
        $options[] = array( 'id' => 'top', 'title' => 'Top' );
        $options[] = array( 'id' => 'bottom', 'title' => 'Bottom' );
        return JHTML::_('select.genericlist', $options, $name, null, 'id', 'title', $default);
    }
}
