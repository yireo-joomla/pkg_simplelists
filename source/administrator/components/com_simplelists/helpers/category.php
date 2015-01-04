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

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Simplelists Category Helper
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsCategoryHelper
{
    /*
     * Helper-function to get the category-alias if not set
     * 
     * @access public
     * @param int $category_id
     * @return string
     */
    static public function getAlias( $category_id = 0 ) 
    {
        $query = "SELECT `alias` FROM `#__categories` WHERE `id`='".(int)$category_id."' AND `extension`='com_simplelists' LIMIT 1";
        $db = JFactory::getDBO();
        $db->setQuery( $query );
        return $db->loadResult();
    }

    /*
     * Helper-function to get the category-ID if not set
     * 
     * @access public
     * @param string $category_alias
     * @return mixed
     */
    static public function getId( $category_alias = null ) 
    {
        $db = YireoHelper::getDBO();
        $query = "SELECT `id` FROM `#__categories` WHERE `alias`=".$db->quote($category_alias)." AND `extension`='com_simplelists' LIMIT 1";
        $db->setQuery( $query );
        return $db->loadResult();
    }
}

/**
 * Simplelists Category Tree
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsCategoryTree
{
    private $items = array();
    private $tree = array();
    private $list = array();
    private $type = '';
    private $root = null;

    public function __construct( $items = array(), $type = '' ) 
    {
        $this->items = $items;
        return null;
    }

    public function getItems() 
    {
        return $this->items;
    }

    public function setItems( $items = array() ) 
    {
        $this->items = $items;
        return null;
    }

    public function setType( $type = array() ) 
    {
        $this->type = $type;
        return null;
    }

    public function _getRoot() 
    {
        if(empty($this->root)) {
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/models/category.php';
            require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/tables/category.php';
            $model = new SimplelistsModelCategory();
            $model->setId(0, false);
            $this->root = $model->getData();
            $this->root->id = 0;
        }
        return $this->root;
    }

    public function getTree() 
    {
        if(empty( $this->tree )) {
            $this->tree = $this->items ;
            $parent = $this->_getRoot();
            $this->tree = $this->getTreeRecursive($parent);
        }
        return $this->tree;
    }

    public function getList() 
    {
        if(empty( $this->list )) {
            $this->getTree();
        }
        return $this->list;
    }

    private function getTreeRecursive( $parent, $level = 0, $counter = 0 ) 
    {
        $parent->level = $level;
        $level++;
        if(!empty( $this->tree )) {
            foreach( $this->tree as $id => $item ) {
    
                if( $item->parent_id == $parent->id ) {
                    unset( $this->tree[$id] );
                    $item->tree = $counter;
                    $this->list[] = $item;
                    $counter++;
                    $parent->children[] = $item;
                    $this->getTreeRecursive( $item, $level, $counter );
                }
            }
        }
        return $parent;
    }

    public function getIndent( $level = 0 )     
    {
        $indent = '';
        for( $i = 1 ; $i < $level ; $i++ ) {
            $indent .= '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if( $level > 1 ) $indent .= '<sup>|_</sup>&nbsp;' ;
        return $indent;
    }
}
