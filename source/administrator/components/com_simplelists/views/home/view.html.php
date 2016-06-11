<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo (info@yireo.com)
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class
 */
class SimpleListsViewHome extends YireoViewHome
{
	/*
	 * Display method
	 *
	 * @param string $tpl
	 * @return null
	 */
	public function display($tpl = null)
	{
		$icons = array();
		$icons[] = $this->icon('item', 'New Item', 'item.png');
		$icons[] = $this->icon('items', 'Items', 'items.png');
		$icons[] = $this->icon('category', 'Categories', 'categories.png');
		$this->icons = $icons;

		$urls = array();
		$urls['twitter'] = 'http://twitter.com/yireo';
		$urls['facebook'] = 'http://www.facebook.com/yireo';
		$urls['tutorials'] = 'https://www.yireo.com/tutorials/simplelists';
		$urls['jed'] = 'http://extensions.joomla.org/extensions/news-display/tables-a-lists/3650';
		$this->urls = $urls;

		$user = JFactory::getUser();

		if ($user->authorise('core.admin'))
		{
			JToolBarHelper::custom('updateQueries', 'archive', '', 'DB Upgrade', false);
		}

		parent::display($tpl);
	}
}
