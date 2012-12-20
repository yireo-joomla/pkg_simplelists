<?php
/*
 * Joomla! component SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * SimpleLists Structure
 */
class HelperAbstract
{
    /**
     * Structural data of this component
     */
    public function getStructure()
    {
        return array(
            'title' => 'SimpleLists',
            'menu' => array(
                'home' => 'Home',
                'items' => 'Items',
                'categories' => 'Categories',
            ),
            'views' => array(
                'home' => 'Home',
                'items' => 'Items',
                'item' => 'Item',
                'categories' => 'Categories',
                'category' => 'Category',
            ),
            'obsolete_files' => array(
                JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/default.php',
                JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/default_ads.php',
                JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/default_cpanel.php',
                JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/feeds.php',
                JPATH_ADMINISTRATOR.'/components/com_simplelists/views/items/tmpl/default.php',
                JPATH_ADMINISTRATOR.'/components/com_simplelists/views/categories/tmpl/default.php',
                JPATH_SITE.'/media/com_simplelists/js/mootools-cookie.js',
            ),
        );
    }
}
