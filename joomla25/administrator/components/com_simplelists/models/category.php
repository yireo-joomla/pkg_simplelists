<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class SimplelistsModelCategory extends YireoModel
{
    /**
     * Indicator whether to debug this model or not
     */
    protected $_debug = false;

    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->_orderby_title = 'title';
        parent::__construct('category');
    }

    /**
     * Method to remove a category
     *
     * @access public
     * @param array $cid
     * @return boolean
     */
    public function delete($cid = array())
    {
        if(count($cid)) {
            JArrayHelper::toInteger($cid);
            $cids = implode(',', $cid);

            // Check if the category is empty
            $query = 'SELECT `id` FROM `#__simplelists_categories` WHERE `category_id` IN ( '.$cids.' )' ;
            $this->_db->setQuery( $query ) ;
            $rows = $this->_db->loadAssocList() ;
            if(!empty($rows)) {
                $this->setError( JText::_( 'Category not empty' ));
                return false;
            }

            // Check if this category serves as parent for others
            $query = 'SELECT `id` FROM `#__categories` WHERE `parent_id` IN ( '.$cids.' )' ;
            $this->_db->setQuery( $query ) ;
            $rows = $this->_db->loadAssocList() ;
            if(!empty($rows)) {
                $this->setError( JText::_( 'Category is still parent' ));
                return false;
            }

            // Call the parent function
            if(parent::delete($cid) == false) {
                return false;
            }

            // Also remove all item/category relations
            $query = 'DELETE FROM `#__simplelists_categories` WHERE `category_id` IN ('.$cids.')';
            $this->_db->setQuery( $query );
            if(!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    /**
     * Method to get the ordering query
     *
     * @access public 
     * @param null
     * @return string
     */
    public function getOrderingQuery()
    {
    	$query = 'SELECT `lft` AS `value`, `title` AS `text` FROM `#__categories` WHERE `extension`="com_simplelists" ORDER BY lft';
        return $query;
    }
}
