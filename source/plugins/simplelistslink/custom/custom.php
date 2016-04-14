<?php
/**
 * Joomla! link-plugin for SimpleLists - Custom Links
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
 * SimpleLists Link Plugin - Custom Links
 */
class plgSimpleListsLinkCustom extends SimplelistsPluginLink
{
	/*
	 * Method to get the title for this plugin
	 *
	 * @access public
	 * @param null
	 * @return string
	 */
	public function getTitle()
	{
		return 'Custom link';
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
		return $item->link;
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
		return '<input class="text_area" type="text" name="link_custom" id="link_custom" value="' . $this->getName($current) . '" size="48" maxlength="250" />';
	}
}
