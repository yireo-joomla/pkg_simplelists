<?php
/**
 * Joomla! link-plugin for SimpleLists - Vimeo Link
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
if(file_exists(dirname(__FILE__).DS.'default.php')) {
    require_once dirname(__FILE__).DS.'default.php';
} else {
    require_once dirname(dirname(__FILE__)).DS.'default'.DS.'default.php';
}

/**
 * Plugin class
 */
class plgSimpleListsLinkVimeo extends plgSimpleListsLinkDefault
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
            $plugin = JPluginHelper::getPlugin('simplelistslink', 'vimeo');
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
    public function getTitle() {
        return 'Vimeo video';
    }    

    /*
     * Method to build the item URL 
     *
     * @access public
     * @param object $item
     * @return string
     */
    public function getUrl($item = null) {
        return $item->link;
    }

    /*
     * Method to build the HTML when editing a item-link with this plugin
     *
     * @access public
     * @param mixed $current
     * @return string
     */
    public function getInput($current = null) {
        return '<input class="text_area" type="text" name="link_vimeo" id="link_vimeo" value="'.$this->getName($current).'" size="48" maxlength="250" />';
    }

    /*
     * Method to display the hidden-context of this item
     *
     * @access public
     * @param object $item
     * @return mixed
     */
    public function getHidden($item = null) {
        if($item == null) {
            return null;
        }

        // Allow an item-parameter "link_plugin_params" to override these settings
        $width = 600;
        $height = 500;
        $params = array(
            'clip_id' => $item->link,
            'server' => 'vimeo.com',
            'show_title' => 1,
            'show_byline' => 1,
            'show_portrait' => 0,
            'fullscreen' => '1',
        );
        $array = array();
        foreach($params as $name => $value) $array[] = $name.'='.$value;
        $params = implode('&amp;', $array);

        ob_start();
        include dirname(__FILE__).DS.'vimeo'.DS.'default.php';
        $text .= ob_get_contents();
        ob_end_clean();

        return $text;
    }
}
