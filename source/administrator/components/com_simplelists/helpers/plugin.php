<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Load the plugin helper        
jimport('joomla.plugin.helper');

/**
 * Simplelists Plugin Helper
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsPluginHelper
{
    /**
     * Method to load a specific plugin
     */
    static public function getPlugin($type = null, $name = null)
    {
        $plugin = JPluginHelper::getPlugin($type, $name);
        if(empty($plugin) || !is_object($plugin)) {
            return null;
        }

        $path = JPATH_PLUGINS.'/'.$plugin->type.'/'.$plugin->name.'/'.$plugin->name.'.php';
        if(!file_exists($path)) {
            return null;
        }

        // Include the plugin-file
        require_once($path);

        // Determine the class-name and return an instance
        $class = 'plg'.$plugin->type.$plugin->name;
        if(class_exists($class)) {
		    $dispatcher = JDispatcher::getInstance();
            $plugin = new $class($dispatcher, (array)$plugin);
            return $plugin;
        }

        return null;
    }

    /**
     * Method to load plugins of a specific type
     */
    static public function getPlugins($type = null)
    {
        // Load the plugins
        $plugins = JPluginHelper::getPlugin($type);
        foreach($plugins as $index => $plugin) {
            $plugin = self::getPlugin($plugin->type, $plugin->name);
            if($plugin == null) {
                unset($plugins[$index]); 
            } else {
                $plugins[$index] = $plugin;
            }
        }

        return $plugins;
    }

    /**
     * Method to return the title of a specific plugin
     */
    static public function getPluginLinkTitle($item)
    {
        $plugin = self::getPlugin('simplelistslink', $item->link_type);
        if(!empty($plugin)) {
            return $plugin->getTitle();
        }
    }

    /**
     * Method to return the link-name of a specific plugin
     */
    static public function getPluginLinkName($item)
    {
        $plugin = self::getPlugin('simplelistslink', $item->link_type);
        if(!empty($plugin)) {
            return $plugin->getName($item->link);
        }
    }

    /**
     * Method to return the hidden-value of a specific plugin
     */
    static public function getPluginLinkHidden($item)
    {
        $plugin = self::getPlugin('simplelistslink', $item->link_type);
        if(!empty($plugin)) {
            return $plugin->getHidden($item);
        }
    }

    /**
     * Method to return the URL-value of a specific plugin
     */
    static public function getPluginLinkUrl($item)
    {
        $item->params = YireoHelper::toParameter($item->params);
        $return = null;

        if($item->params->get('link_show', 1) == 0 && !empty($item->link)) {
            $url = 'index.php?option=com_simplelists&view=item&task=hidden&tmpl=component&id='.$item->id;
            if($item->alias) $url .= ':'.$item->alias;
            $return = JRoute::_( $url );

        } else {
            $plugin = self::getPlugin('simplelistslink', $item->link_type);
            if(!empty($plugin)) {
                $return = $plugin->getUrl($item);
            }
        }
        return $return;
    }
}
