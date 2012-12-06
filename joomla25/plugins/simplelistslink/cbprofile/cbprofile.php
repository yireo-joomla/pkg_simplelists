<?php
/**
 * Joomla! link-plugin for SimpleLists - CBProfile
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
if(file_exists(dirname(__FILE__).'/default.php')) {
    require_once dirname(__FILE__).'/default.php';
} else {
    require_once dirname(dirname(__FILE__)).'/default/default.php';
}

/**
 * SimpleLists Link Plugin - CBProfile
 */
class plgSimpleListsLinkCBProfile extends plgSimpleListsLinkDefault 
{
    /**
     * Load the parameters
     * 
     * @access private
     * @param null
     * @return JParameter
     */
    private function getParams()
    {
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->RELEASE, '1.5', 'eq')) {
            $plugin = JPluginHelper::getPlugin('simplelistslink', 'cbprofile');
            $params = new JParameter($plugin->params);
            return $params;
        } else {
            return $this->params;
        }
    }

    /*
     * Method to check whether this plugin can be used or not
     *
     * @access public
     * @param null
     * @return bool
     */
    public function isEnabled() {
        if(JFolder::exists(JPATH_SITE.'/components/com_comprofiler')) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Method to get the title for this plugin 
     *  
     * @access public
     * @param null
     * @return string
     */
    public function getTitle() {
        return 'CB user profile';
    }    

    /*
     * Method the friendly name of a specific item
     *
     * @access public
     * @param mixed $link
     * @return string
     */
    public function getName($link) {
        $query = "SELECT `name` FROM #__users WHERE `id`=".(int)$link;
        $db =& JFactory::getDBO();
        $db->setQuery( $query );
        $row = $db->loadObject();
        if(is_object($row)) {
            return $row->name;
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
    public function getUrl($item = null) {
        return JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.(int)$item->link);
    }

    /*
     * Method to build the HTML when editing a item-link with this plugin
     *
     * @access public
     * @param mixed $current
     * @return string
     */
    public function getInput($current = null) {
        $query = "SELECT `id`, `name` FROM #__users";
        $db =& JFactory::getDBO();
        $db->setQuery( $query );
        $users = $db->loadObjectList();
        return JHTML::_('select.genericlist', $users, 'link_cbprofile', 'class="inputbox" size="1"', 'id', 'name', intval($current));
    }
}
