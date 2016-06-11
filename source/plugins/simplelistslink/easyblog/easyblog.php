<?php
/**
 * Joomla! link-plugin for SimpleLists - EasyBlog
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
 * SimpleLists Link Plugin - EasyBlog
 */
class plgSimpleListsLinkEasyBlog extends SimplelistsPluginLink
{
	/*
	 * Method to check whether this plugin can be used or not
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		if (JFolder::exists(JPATH_SITE . '/components/com_easyblog'))
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
		return 'EasyBlog post';
	}

	/*
	 * Method the friendly name of a specific item
	 *
	 * @param mixed $link
	 * @return string
	 */
	public function getName($link = null)
	{
		$query = "SELECT `title` FROM #__easyblog_post WHERE `id`=" . (int) $link;
		$db = JFactory::getDbo();
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
		require_once JPATH_SITE . '/components/com_easyblog/helpers/router.php';

		return EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id=' . (int) $item->link);
	}

	/*
	 * Method to build the HTML when editing a item-link with this plugin
	 *
	 * @param mixed $current
	 * @return string
	 */
	public function getInput($current = null)
	{
		$query = "SELECT `id`, `title` FROM #__easyblog_post";
		$db = JFactory::getDbo();
		$db->setQuery($query);
		$users = $db->loadObjectList();

		return JHtml::_('select.genericlist', $users, 'link_easyblog', 'class="inputbox" size="1"', 'id', 'title', intval($current));
	}
}
