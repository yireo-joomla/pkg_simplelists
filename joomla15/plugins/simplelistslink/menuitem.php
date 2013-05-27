<?php
/**
 * Joomla! link-plugin for SimpleLists - MenuItem Link
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
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/plugin/link.php';

/**
 * Plugin class
 */
class plgSimpleListsLinkMenuItem extends SimplelistsPluginLink
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
            $plugin = JPluginHelper::getPlugin('simplelistslink', 'menuitem');
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
        return 'Internal menu-link';
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
        if(YireoHelper::isJoomla15() && YireoHelper::isJoomla25()) {
            $query = "SELECT `name` FROM #__menu WHERE `id`=".(int)$link;
        } else {
            $query = "SELECT `title` FROM #__menu WHERE `id`=".(int)$link;
        }

        $db = JFactory::getDBO();
        $db->setQuery( $query );
        $row = $db->loadObject() ;
        if(is_object($row) && isset($row->name)) {
            return $row->name;
        } elseif(is_object($row) && isset($row->name)) {
            return $row->name;
        } else {
            return '' ;
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
        $query = "SELECT `id`,`link` FROM #__menu WHERE `id`=".(int)$item->link;
        $db = JFactory::getDBO();
        $db->setQuery( $query );
        $row = $db->loadObject() ;
        if(is_object($row)) {
            return JRoute::_( $row->link . '&Itemid='.(int)$row->id );
        } else {
            return '' ;
        }
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
        if(YireoHelper::isJoomla15()) {
            $links = JHTML::_( 'menu.linkoptions' );
            return JHTML::_('select.genericlist', $links, 'link_menuitem', 'class="inputbox" size="1"', 'value', 'text', intval($current));
        } else {
            $xmlFile = JPATH_SITE.'/plugins/simplelistslink/menuitem/form.xml';
            if(file_exists($xmlFile)) {
                $form = JForm::getInstance('input', $xmlFile);
                $form->bind(array('input' => array('link_menuitem' => $current)));
                foreach($form->getFieldset('input') as $field) {
                    echo $field->input; 
                }
            }
        }
    }
}
