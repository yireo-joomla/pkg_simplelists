<?php
/**
 * Joomla! link-plugin for SimpleLists - Default
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2013
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/plugin/link.php';

/**
 * SimpleLists Link Plugin - Default
 */ 
class plgSimpleListsLinkDefault extends SimplelistsPluginLink
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
            $plugin = JPluginHelper::getPlugin('simplelistslink', 'default');
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
        return 'None';
    }
}
