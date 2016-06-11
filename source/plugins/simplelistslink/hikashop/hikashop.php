<?php
/**
 * Joomla! link-plugin for SimpleLists - Hikashop
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/libraries/plugin/link.php';

/**
 * SimpleLists Link Plugin - Hikashop
 */
class PlgSimpleListsLinkHikashop extends SimplelistsPluginLink
{
	protected $autoloadLanguage = true;

	/*
	 * Method to check whether this plugin can be used or not
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		if (JFolder::exists(JPATH_SITE . '/components/com_hikashop'))
		{
			return true;
		}

		return false;

	}

	/*
	 * Method to get the title for this plugin
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return JText::_('PLG_SIMPLELISTSLINK_HIKASHOP_ITEM_TITLE');
	}

	/*
	 * Method the friendly name of a specific item
	 *
	 * @param mixed $link
	 * @return string
	 */
	public function getName($link = null)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('product_name'));
		$query->from($db->quoteName('#__hikashop_product'));
		$query->where($db->quoteName('product_id') . '=' . (int) $link);

		$db->setQuery($query);
		$row = $db->loadObject();

		if (is_object($row))
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
		require_once JPATH_SITE . '/components/com_hikashop/helpers/router.php';

		return HikashopRouter::_('index.php?option=com_hikashop&view=entry&id=' . (int) $item->link);
	}

	/*
	 * Method to build the HTML when editing a item-link with this plugin
	 *
	 * @param mixed $current
	 * @return string
	 */
	public function getInput($current = null)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->quoteName(['product_id', 'product_name']));
		$query->from($db->quoteName('#__hikashop_product'));

		$db->setQuery($query);
		$users = $db->loadObjectList();

		return JHTML::_('select.genericlist', $users, 'link_hikashop', 'class="inputbox" size="1"', 'product_id', 'product_name', intval($current));
	}
}
