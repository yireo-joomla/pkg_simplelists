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
        parent::__construct('item');
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery()
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
}
