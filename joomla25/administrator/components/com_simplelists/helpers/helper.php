<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright (C) 2012 Yireo.com
 * @license GNU General Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the Yireo helper
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'lib'.DS.'helper.php';

/**
 * Simplelists Helper
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsHelper
{
    /**
     * Method to fetch a single category from the database
     *
     * @access public
     * @param int ID of category
     * @return array List with this category
     */
    public function getCategory( $id = null ) 
    {
        if(YireoHelper::isJoomla15()) {
            $query = "SELECT * FROM `#__categories` WHERE `id` = ".(int)$id." AND `section`='com_simplelists' LIMIT 1";
        } else {
            $query = "SELECT * FROM `#__categories` WHERE `id` = ".(int)$id." AND `extension`='com_simplelists' LIMIT 1";
        }
        $db =& JFactory::getDBO();
        $db->setQuery( $query ) ;
        $rows = $db->loadObjectList() ;
        return $rows ;
    }

    /**
     * Method to fetch a list of categories from the database
     *
     * @access public
     * @param int ID of current item
     * @param int ID of parent category
     * @return array List of categories
     */
    public function getCategories( $id = null, $parent_id = null ) 
    {
        // Convert to integers
        if(!empty($id)) $id = (int)$id;
        if(!empty($parent_id)) $parent_id = (int)$parent_id;

        // Fetch categories with a specific ID
        if($id > 0) {
            if(YireoHelper::isJoomla15()) {
                $query = 'SELECT c.* FROM `#__simplelists_categories` AS s LEFT JOIN `#__categories` AS c ON c.`id` = s.`category_id` WHERE s.`id`='.(int)$id.' AND c.`section` = "com_simplelists"';
            } else {
                $query = 'SELECT c.* FROM `#__simplelists_categories` AS s LEFT JOIN `#__categories` AS c ON c.`id` = s.`category_id` WHERE s.`id`='.(int)$id.' AND c.`extension` = "com_simplelists"';
            }

        // Fetch categories with a specific parent
        } elseif($parent_id > 0) {
            if(YireoHelper::isJoomla15()) {
                $query = 'SELECT c.* FROM `#__categories` AS c WHERE c.`section`="com_simplelists" AND c.`parent_id`='.(int)$parent_id;
            } else {
                $query = 'SELECT c.* FROM `#__categories` AS c WHERE c.`extension`="com_simplelists" AND c.`parent_id`='.(int)$parent_id;
            }

        // Fetch all categories
        } else {
            if(YireoHelper::isJoomla15()) {
                $query = 'SELECT c.* FROM `#__categories` AS c WHERE c.`section`="com_simplelists"';
            } else {
                $query = 'SELECT c.* FROM `#__categories` AS c WHERE c.`extension`="com_simplelists"';
            }
        }

        $db =& JFactory::getDBO();
        $db->setQuery($query);

        $rows = $db->loadObjectList();
        return $rows ;
    }

    /**
     * Method to get the number of items within a category
     *
     * @access public
     * @param int ID of category
     * @return int Number of items
     */
    public function getNumItems( $category_id ) 
    {
        $query = 'SELECT id FROM `#__simplelists_categories` WHERE `category_id`='.(int)$category_id ;

        $db =& JFactory::getDBO();
        $db->setQuery( $query ) ;

        $rows = $db->loadAssocList() ;
        return count($rows);
    }

    /**
     * Method to create the SimpleLists image directory
     *
     * @access public
     * @param folder-name (default 'images/simplelists')
     * @return boolean True if the folder has been created successfully
     */
    public function checkDirectory( $folder = '' ) 
    {
        $application = JFactory::getApplication() ;
        if( $folder == '' ) {
            $folder = COM_SIMPLELISTS_BASE;
        }
        if( JFolder::exists( $folder )) {
            return true ;
        } else {
            if( JFolder::create( $folder )) {
                $application->enqueueMessage( JText::sprintf( 'Created image directory', $folder ) , 'notice' ) ;
                return true ;
            } else {
                $application->enqueueMessage( JText::sprintf( 'Failed to create directory', $folder ), 'error' ) ;
                return false ;
            }
        }
    }

    /**
     * Method to check if there is at least 1 category
     *
     * @access public
     * @param null
     * @return boolean True if there is at least 1 category
     */
    public function checkCategories() 
    {
        if(YireoHelper::isJoomla15()) {
            $query = "SELECT * FROM #__categories WHERE `section`='com_simplelists'";
        } else {
            $query = "SELECT * FROM #__categories WHERE `extension`='com_simplelists'";
        }

        $application = JFactory::getApplication() ;
        $db =& JFactory::getDBO();
        $db->setQuery($query);
        $rows = $db->loadAssocList() ;
        if(empty($rows)) {
            $application->enqueueMessage( JText::_( 'No categories' ), 'notice' ) ;
            return false ;
        }
        return true ;
    }

    /**
     * Method to check the PHP-version and Joomla! version
     *
     * @access public
     * @param null
     * @return boolean True if all versions are acceptable
     */
    public function checkVersions() 
    {
        $application = JFactory::getApplication() ;

        // Check the PHP-version
        if(version_compare(phpversion(), '5.2.0', '>=') == false) {
            $application->enqueueMessage( JText::_( 'PHP 5.2.0 or higher is required.' ), 'error' ) ;
            return false ;
        }

        // Check the Joomla!-version
        $jversion = new JVersion();
        if(version_compare($jversion->getShortVersion(), '1.5.9', '>=') == false) {
            $application->enqueueMessage( JText::_( 'Joomla! 1.5.9 or higher is required.' ), 'error' ) ;
            return false ;
        }

        return true ;
    }

    /*
     * Helper-function to return a specific Menu-Item
     *  
     * @access public
     * @param int $Itemid
     * @return object
     */
    public function getMenuItemFromItemid($Itemid = 0) 
    {
        static $menu_items;
        if(empty($menu_items)) {
            $component = &JComponentHelper::getComponent('com_simplelists');
            $menu = &JSite::getMenu();
            if(YireoHelper::isJoomla15()) {
                $menu_items = $menu->getItems('componentid', $component->id);
            } else {
                $menu_items = $menu->getItems('component_id', $component->id);
            }
        }

        if(!empty($menu_items)) {
            foreach($menu_items as $menu_item) {
                if($menu_item->id == $Itemid) {
                    return $menu_item;
                }
            }
        }

        return null;
    }

    /*
     * Helper-function that tries to match the given category-settings with an existing Menu-Item
     *
     * @access public
     * @param int $category_id
     * @param string $layout
     * @return object
     */
    public function getMenuItem($category_id = 0, $layout = 'default') {

        static $menu_items;
        if (empty($menu_items)) {
            $component = &JComponentHelper::getComponent('com_simplelists');
            $menu = &JSite::getMenu();
            if(YireoHelper::isJoomla15()) {
                $menu_items = $menu->getItems('componentid', $component->id);
            } else {
                $menu_items = $menu->getItems('component_id', $component->id);
            }
        }
           
        $near_match = null;

        if(!empty($menu_items)) {
            foreach($menu_items as $menu_item) {
                if(!empty($menu_item->query['category_id']) && !empty($menu_item->query['layout'])
                    && $menu_item->query['category_id'] == $category_id && $menu_item->query['layout'] == $layout ){
                    return $menu_item;
                } elseif( $near_match == null && !empty( $menu_item->query['category_id'] ) 
                    && $menu_item->query['category_id'] == $category_id ) {
                    $near_match = $menu_item;                  
                }
            }
        }
        return $near_match;
    }

    /*
     * Helper-function to build a proper SimpleLists system-URL
     * 
     * @access public
     * @param array $needles
     * @return string
     */
    public function getUrl($needles = array())
    {
        // Construct the base-URL
        if(empty($needles['view'])) $needles['view'] = 'items';
        $url = 'index.php?option=com_simplelists&view='.$needles['view'];

        // Determine the layout
        if(!empty($needles['layout'])) {
            $layout = $needles['layout'];
        } else {
            $layout = JRequest::getCmd('layout');
        }

        // Add the layout to the URL
        if(!empty($layout)) {
            $url .= '&layout='.$layout ;
        }

        // Determine the category alias
        if(!empty($needles['category_id'])) {
            if(!empty($needles['category_alias'])) {
                $category_alias = $needles['category_alias'];
            } else {
                require_once (dirname(__FILE__).DS.'category.php');
                $category_alias = SimplelistsCategoryHelper::getAlias($needles['category_id']);
            }

            // Append the category ID (and optionally the category alias) to the URL
            if(!empty($category_alias)) {
                $url .= '&category_id='.(int)$needles['category_id'].':'.$category_alias;
            } else {
                $url .= '&category_id='.(int)$needles['category_id'];
            }
        }

        // Append the Itemid to the URL
        $Itemid = null;
        if(!empty($needles['menu_id']) && $needles['menu_id'] > 0) $Itemid = $needles['menu_id'];
        if(!empty($needles['Itemid']) && $needles['Itemid'] > 0) $Itemid = $needles['Itemid'];
        if($Itemid > 0) {

            // Check whether this Itemid is valid
            include_once JPATH_SITE.DS.DS.'components'.DS.'com_simplelists'.DS.'helpers'.DS.'router.php' ;
            $menu_items = SimplelistsHelperRouter::getMenuItems();
            $match = false;
            foreach($menu_items as $menu_item) {
                if($menu_item->id == $Itemid
                    && $menu_item->query['view'] == $needles['view']) {
                    $match = true;
                    break;
                }
            }

            // Only if the Itemid is valid, add it to the URL
            if($match == true) $url .= '&Itemid='.$Itemid;
        }
        
        
        // Convert the system URL into a SEF URL
        $url = JRoute::_($url);

        // Append the anchor-name for the item
        if(!empty($needles['item_alias'])) {
            $url .= '#'.$needles['item_alias'];
        } elseif(!empty($needles['item_id']) && $needles['item_id'] > 0) {
            $url .= '#item'.(int)$needles['item_id'];
        }

        return $url;
    }

    /*
     * Helper-method to create a backend thumbnail for an image
     * 
     * @access public
     * @param string $script
     * @return string
     */
    public function createThumbnail($image, $ext, $src_width, $src_height, $dest_width, $dest_height)
    {
        // Check for the caching folder
        $folder = JPATH_ADMINISTRATOR.DS.'cache'.DS.'com_simplelists';
        if(!JFolder::exists($folder)) {
            JFolder::create($folder);
        }

        // Check again for the caching folder
        if(!JFolder::exists($folder)) {
            // @todo: Create a warning
            return null;
        }

        // Check for an existing thumbnail image
        $thumb_file = md5($image).'.'.$ext;
        $thumb_path = $folder.DS.$thumb_file;
        if(is_file($thumb_path) && is_readable($thumb_path)) {
            return 'administrator/cache/com_simplelists/'.$thumb_file;
        }

        // Create an image depending on the image-type
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'gif':
                $source = imagecreatefromgif($image);
                break;
            case 'png':
                $source = imagecreatefrompng($image);
                break;
            default:
                return $image;
        }

        // Initialize the thumbnail
        $thumb = imagecreatetruecolor($dest_width, $dest_height);

        // Resize the thumbnail
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);

        // Save the thumbnail
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg( $thumb, $thumb_path, 50 );
                break;
            case 'png':
                imagepng( $thumb, $thumb_path, 50 );
                break;
            case 'gif':
                imagegif( $thumb, $thumb_path );
                break;
            default:
                return $image;
        }

        // Return the thumbnail
        return 'administrator/cache/com_simplelists/'.$thumb_file;
    }
}
