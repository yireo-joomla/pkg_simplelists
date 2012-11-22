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

class SimplelistsModelItem extends YireoModel
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
        parent::__construct('item');
    }

    /**
     * Method to store the model
     *
     * @access public
     * @param mixed $data
     * @return bool
     */
    public function store($data)
    {
        $params = JComponentHelper::getParams ('com_simplelists');
        $table = $this->getTable();

        // Insert $categories manually
        if (!empty( $data['categories'] )) {
            $categories = $data['categories'] ;
            unset($data['categories']) ;
        }

        // Insert link manually
        if (isset($data['link_type'])) {
            $type = $data['link_type'];
            if(!empty($data['link_'.$type])) {
                $data['link'] = $data['link_'.$type];
            }
        }

        // Remove the old category-relations
        if ($params->get('auto_ordering', 1) == 1 && $data['id'] == 0 && count($categories) == 1) {
            $query = 'SELECT MAX(`item`.`ordering`) FROM `#__simplelists_items` AS `item`'
                . ' LEFT JOIN `#__simplelists_categories` AS `category` ON `category`.`id`=`item`.`id`'
                . ' WHERE `category`.`category_id`='.$categories[0];
            $this->_db->setQuery( $query );
            $data['ordering'] = $this->_db->loadResult() + 1;
        } else {
            $data['ordering'] = 0;
        }

        // Store these data
        $rs = parent::store($data);
        if($rs == false) {
            return false;
        }

        // Handle category-relations
        if($this->getId() > 0) {

            // Remove the old category-relations
            $query = 'DELETE FROM `#__simplelists_categories` WHERE `id`='.(int)$this->getId();
            $this->_db->setQuery($query);
            $this->_db->query();

            // Store the new category-relations
            if(!empty($categories)) {
                foreach($categories as $c) {
                    $query = 'INSERT INTO `#__simplelists_categories` SET `id`='.(int)$this->getId().',`category_id`='.(int)$c;
                    $this->_db->setQuery($query);
                    $this->_db->query();
                }
            }
        }

        return true;
    }

    /**
     * Method to remove an item
     *
     * @access public
     * @param array $cid
     * @return boolean True on success
     */
    public function delete($cid = array())
    {
        $result = false;

        if (count( $cid )) {

            // Convert this array
            JArrayHelper::toInteger($cid);
            $cids = implode( ',', $cid );

            // Call the parent function
            if(parent::delete($cid) == false) {
                return false;
            }

            // Also remove all item/category relations
            $query = 'DELETE FROM `#__simplelists_categories` WHERE `id` IN ('.$cids.')';
            $this->_db->setQuery( $query );
            if(!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    /**
     * Method to load data
     *
     * @access public
     * @param null
     * @return boolean
     */
    public function getData()
    {
        // Lets load the content if it doesn't already exist
        $this->_data = parent::getData();

        // If these data exist, add extra info 
        if(empty($this->_data)) {

            // Fetch the extra link data
            if(!isset($this->_data->link_data)) {
                $this->_data->link_data = array();
                if(!empty($this->_data->link_type)) {
                    $plugin = SimplelistsPluginHelper::getPlugin('simplelistslink', $this->_data->link_type);
                    if(!empty($plugin)) {
                        $this->_data->link_data[$this->_data->link_type] = $plugin->getName($this->_data->link);
                    }
                }
            }
        }

        return $this->_data;
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
        if($this->_orderby_default == 'ordering') {
            $query = 'SELECT `item`.`ordering` AS `value`, `item`.`title` AS `text`'
                . ' FROM `#__simplelists_items` AS `item`'
                . ' LEFT JOIN `#__simplelists_categories` AS `category` ON `category`.`id`=`item`.`id`'
                . ' WHERE `category`.`category_id` IN (SELECT `category_id` FROM `#__simplelists_categories` WHERE `id`='.(int)$this->_data->id.')'
                . ' ORDER BY `item`.`ordering`';
            return $query;
        }

        return null;
    }
}
