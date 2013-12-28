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
     * Method to get a XML-based form
     *
     * @access public
     * @subpackage Yireo
     * @param array $data
     * @param bool $loadData
     * @return mixed
     */
    public function getForm($data = array(), $loadData = true)
    {   
        $form = parent::getForm($data, $loadData);
        $data = $this->getData();
        $form->bind(array('basic' => $data));
        $form->bind(array('text' => $data));
        return $form;
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
        if (!empty($data['basic']['categories'])) {
            $categories = $data['basic']['categories'] ;
            unset($data['basic']['categories']) ;
        } elseif (!empty($data['categories'])) {
            $categories = $data['categories'] ;
            unset($data['categories']) ;
        }

        // Insert link manually
        if (isset($data['link_type'])) {
            $type = $data['link_type'];
            if(!empty($data['input']['link_'.$type])) $data['link'] = $data['input']['link_'.$type];
            if(!empty($data['link_'.$type])) $data['link'] = $data['link_'.$type];
        }

        // Remove the old category-relations
        if ($params->get('auto_ordering', 1) == 1 && $data['id'] == 0 && count($categories) == 1) {
            $query = 'SELECT MAX(`item`.`ordering`) FROM `#__simplelists_items` AS `item`'
                . ' LEFT JOIN `#__simplelists_categories` AS `category` ON `category`.`id`=`item`.`id`'
                . ' WHERE `category`.`category_id`='.$categories[0];
            $this->_db->setQuery( $query );
            $data['ordering'] = $this->_db->loadResult() + 1;
        }

        // Store these data
        $rs = parent::store($data);
        if($rs == false) {
            $this->setError(JText::_('LIB_YIREO_TABLE_ERROR'));
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
        } else {
            JError::raiseWarning('LIB_YIREO_TABLE_UNKNOWN_ID');
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
     * Method to add extra data
     *
     * @access public
     * @param array $data
     * @return array
     */
    public function onDataLoad($data)
    {
        // If these data exist, add extra info 
        if (empty($_data)) {

            // Fetch the categories
            $data->categories = SimplelistsHelper::getCategories($data->id, null, 'id');

            // Fetch the extra link data
            if (!isset($data->link_data)) {
                $data->link_data = array();
                if (!empty($data->link_type)) {
                    $plugin = SimplelistsPluginHelper::getPlugin('simplelistslink', $data->link_type);
                    if (!empty($plugin)) {
                        $data->link_data[$data->link_type] = $plugin->getName($data->link);
                    }
                }
            }
        }

        return $data;
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
        if ($this->_orderby_default == 'ordering') {
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
