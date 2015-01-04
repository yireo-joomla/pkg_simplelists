<?php
/**
 * Joomla! link-plugin for SimpleLists - Item Link
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/plugin/link.php';

/**
 * Plugin class
 */ 
class plgSimpleListsLinkItem extends SimplelistsPluginLink
{
    /*
     * Method to get the title for this plugin 
     *  
     * @access public
     * @param null
     * @return string
     */
    public function getTitle() 
    {
        return 'Item itself';
    }    

    /*
     * Method to build the item URL 
     *
     * @access public
     * @param object $item
     * @return string
     */
    public function getUrl($item = null) 
    {
        $slug = (int)$item->id;
        if(!empty($item->alias)) $slug .= ':'.$item->alias;
        $link = 'index.php?option=com_simplelists&view=item&id='.$slug;

        $category_slug = null;
        if(!empty($item->category_id)) $category_slug = (int)$item->category_id;
        if(!empty($item->category_alias)) $category_slug .= ':'.$item->category_alias;
        if(!empty($category_slug)) $link .= '&category_id='.$category_slug;

        return $link;
    }

    /*
     * Method to build the HTML when editing a item-link with this plugin
     *
     * @access public
     * @param mixed $current
     * @return string
     */
    public function getInput($current = null) 
    {
        return null;
    }
}
