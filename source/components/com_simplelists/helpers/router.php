<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the Yireo loader
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/loader.php';

/**
 * Router Helper
 */
class SimplelistsHelperRouter
{
    static public function getMenuItems()
    {
        static $items = null;
        if (empty($items)) {
            $component = JComponentHelper::getComponent('com_simplelists');
            $menu = JFactory::getApplication()->getMenu();
            $items = $menu->getItems('component_id', $component->id);
        }
        return $items;
    }
}
