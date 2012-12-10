<?php
/**
 * Joomla! link-plugin for SimpleLists - Custom Links
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2012
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
 * SimpleLists Link Plugin - Custom Links
 */ 
class plgSimpleListsLinkCustom extends plgSimpleListsLinkDefault
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
     * Method to get the title for this plugin 
     *  
     * @access public
     * @param null
     * @return string
     */
    public function getTitle()  
    {
        return 'Custom link';
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
        return $item->link;
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
        return '<input class="text_area" type="text" name="link_custom" id="link_custom" value="'.$this->getName($current).'" size="48" maxlength="250" />';
    }
}
