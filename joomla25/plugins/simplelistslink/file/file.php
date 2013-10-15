<?php
/**
 * Joomla! link-plugin for SimpleLists - File Links
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
 * SimpleLists Link Plugin - File Links
 */ 
class plgSimpleListsLinkFile extends SimplelistsPluginLink
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
        return 'Internal file';
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
        return JURI::base().$item->link;
    }

    /*
     * Method to display the hidden-context of this item
     *
     * @access public
     * @param object $item
     * @return mixed
     */
    public function getHidden($item) 
    {
        $file = JPATH_SITE.'/'.$item->link;
        header('Content-type: application/force-download');
        header('Content-Transfer-Encoding: Binary');
        header('Content-length: '.filesize($file));
        header('Content-disposition: attachment; filename="'.basename($file).'"');
        readfile($file);
        exit;
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
        $link = 'index.php?option=com_simplelists&amp;view=files&amp;tmpl=component&amp;type=link_file';
        if($current!=null) $link .= '&amp;folder=/'.dirname($current);

        return $this->getModal('file', $link, $current);
    }
}
