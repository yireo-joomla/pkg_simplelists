<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

/**
 * Simplelists Items Model
 */
class SimplelistsModelItems extends YireoModel
{
    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->_search = array('title');
        //$this->_debug = true;
        $this->_tbl_prefix_auto = true;
        parent::__construct('item');
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery($query = '')
    {
        $query = "SELECT item.*, {access}, {editor} FROM #__simplelists_items AS item \n";
        return parent::buildQuery($query);
    }

    /**
     * Method to build the query WHERE segment
     *
     * @access protected
     * @param null
     * @return string
     */
    protected function buildQueryWhere()
    {
        $category_id = (int)$this->getFilter('category_id');
        if($category_id > 0) {
            $this->addWhere('item.id IN (SELECT `id` FROM `#__simplelists_categories` WHERE `category_id`='.$category_id.')');
        }

        $link_type = $this->getFilter('link_type');
        if(!empty($link_type)) {
            $this->addWhere('item.link_type ='. $this->_db->Quote($link_type));
        }

        return parent::buildQueryWhere();
    }
    
    
    /**
     * Method to get a category
     */
    public function getCategory($category_id = null)
    {
        // Only run this once
        if (empty($this->_category)) {

            // Set the ID
            if (empty($category_id)) $category_id = $this->getId();

            // Fetch the category of these items
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/models/category.php';
            $model = new SimplelistsModelCategory();
            $model->setId($category_id);
            $category = $model->getData();

            // Fetch the related categories (parent and children) of this category
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/models/categories.php';
            $model = new SimplelistsModelCategories();
            $model->addWhere('category.id = '.(int)$category->parent_id.' OR category.parent_id = '.(int)$category->id);
            $related = $model->getData();

            foreach ($related as $id => $item) {

                // Make sure this related category is not the parent-category
                if ($item->id == $category->parent_id) {
                    $category->parent = $item;
                    unset( $related[$id] );
                    continue;
                }
            }

            $category->childs = $related;

            // Insert this category in the model
            $this->_category = $category;
        }

        return $this->_category;
    }    
    
}
