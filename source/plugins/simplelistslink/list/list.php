<?php
/**
 * Joomla! link-plugin for SimpleLists - List Link
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
class plgSimpleListsLinkList extends SimplelistsPluginLink
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
        return 'Another SimpleList';
    }    

    /*
     * Method the friendly name of a specific item
     *
     * @access public
     * @param mixed $link
     * @return string
     */
    public function getName($link = null) 
    {
        $query = "SELECT `title` FROM #__categories WHERE `id`=".(int)$link;
        $db = JFactory::getDBO();
        $db->setQuery( $query );
        $row = $db->loadObject() ;
        if(is_object($row)) {
            return $row->title;
        } else {
            return '';
        }
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
        return SimplelistsHelper::getUrl($item->link);
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
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->RELEASE, '1.5', 'eq')) {
            return JHTML::_('list.category', 'link_simplelist', 'com_simplelists', (int)$current);
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__categories')->where('extension = "com_simplelists"');
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        if(empty($rows)) {
            return JText::_('No categories found');
        }

        $options = array();
        foreach($rows as $row) {
            $options[] = array('value' => $row->id, 'text' => $row->title);
        }
        return JHTML::_('select.genericlist', $options, 'link_simplelist', null, 'value', 'text', (int)$current);
    }
}
