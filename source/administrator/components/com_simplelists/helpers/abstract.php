<?php
/*
 * Joomla! component SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com
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
				'option=com_categories&extension=com_simplelists' => 'Categories',
			),
			'views' => array(
				'home' => 'Home',
				'items' => 'Items',
				'item' => 'Item',
				'categories' => 'Categories',
				'category' => 'Category',
			),
			'obsolete_files' => array(
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/home/tmpl/default.php',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/home/tmpl/default_ads.php',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/home/tmpl/default_cpanel.php',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/home/tmpl/feeds.php',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/items/tmpl/default.php',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/categories/tmpl/default.php',
				JPATH_SITE . '/components/com_simplelists/models/simplelist.php',
				JPATH_SITE . '/media/com_simplelists/js/mootools-cookie.js',
				JPATH_SITE . '/plugins/system/simplelists/form.xml',
			),
			'obsolete_folders' => array(
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/update',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/item/tmpl/joomla15',
				JPATH_ADMINISTRATOR . '/components/com_simplelists/views/item/tmpl/joomla25',
				JPATH_SITE . '/components/com_simplelists/views/simplelist',
				JPATH_SITE . '/components/com_simplelists/css',
				JPATH_SITE . '/components/com_simplelists/js',
			),
		);
	}
}
