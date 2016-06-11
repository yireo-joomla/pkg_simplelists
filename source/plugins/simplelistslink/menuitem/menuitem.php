<?php
/**
 * Joomla! link-plugin for SimpleLists - MenuItem Link
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/libraries/plugin/link.php';

/**
 * Plugin class
 */
class plgSimpleListsLinkMenuItem extends SimplelistsPluginLink
{
	/*
	 * Method to get the title for this plugin
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return 'Internal menu-link';
	}

	/*
	 * Method the friendly name of a specific item
	 *
	 * @param mixed $link
	 * @return string
	 */
	public function getName($link = null)
	{
		$query = "SELECT `title` FROM #__menu WHERE `id`=" . (int) $link;

		$db = JFactory::getDbo();
		$db->setQuery($query);
		$row = $db->loadObject();

		if (is_object($row) && isset($row->name))
		{
			return $row->name;
		}
		elseif (is_object($row) && isset($row->name))
		{
			return $row->name;
		}
	}

	/*
	 * Method to build the item URL
	 *
	 * @param object $item
	 * @return string
	 */
	public function getUrl($item = null)
	{
		$query = "SELECT `id`,`link` FROM #__menu WHERE `id`=" . (int) $item->link;
		$db = JFactory::getDbo();
		$db->setQuery($query);
		$row = $db->loadObject();

		if (is_object($row))
		{
			return JRoute::_($row->link . '&Itemid=' . (int) $row->id);
		}
	}

	/*
	 * Method to build the HTML when editing a item-link with this plugin
	 *
	 * @param mixed $current
	 * @return string
	 */
	public function getInput($current = null)
	{
		$xmlFile = JPATH_SITE . '/plugins/simplelistslink/menuitem/form/form.xml';

		if (file_exists($xmlFile))
		{
			$form = JForm::getInstance('input', $xmlFile);
			$form->bind(array('input' => array('link_menuitem' => $current)));

			foreach ($form->getFieldset('input') as $field)
			{
				echo $field->input;
			}
		}
	}
}
