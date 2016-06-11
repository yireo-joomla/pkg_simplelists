<?php
/**
 * Joomla! link-plugin for SimpleLists - Image Links
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
 * SimpleLists Link Plugin - Image Links
 */
class plgSimpleListsLinkImage extends SimplelistsPluginLink
{
	/*
	 * Method to get the title for this plugin
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return 'Internal image';
	}

	/*
	 * Method to build the item URL
	 *
	 * @param object $item
	 * @return string
	 */
	public function getUrl($item = null)
	{
		return JUri::base() . $item->link;
	}

	/*
	 * Method to build the HTML when editing a item-link with this plugin
	 *
	 * @param mixed $current
	 * @return string
	 */
	public function getInput($current = null)
	{
		$link = 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;fieldid=link_image';

		if ($current != null)
		{
			$link .= '&amp;folder=/' . preg_replace('/^images\//', '', dirname($current));
		}

		return $this->getModal('image', $link, $current);
	}
}
