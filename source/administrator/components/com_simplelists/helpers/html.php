<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Simplelists HTML Helper
 */
class SimplelistsHTML
{
	/**
	 * Method to parse a list of categories into a HTML selectbox
	 *
	 * @access public
	 *
	 * @param int ID of current item
	 * @param int ID of parent category
	 *
	 * @return string HTML output
	 */
	static public function getCategories($parent_id = null, $parse_tree = true)
	{
		// Include the SimplelistsCategoryTree helper-class
		require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/helpers/category.php';

		// Fetch the categories and parse them in a tree
		$categories = SimplelistsHelper::getCategories(null, $parent_id);

		if ($parse_tree)
		{
			$tree = new SimplelistsCategoryTree($categories);
			$categories = $tree->getList();
		}

		// Add a prefix to the category-title depending on the category-level
		foreach ($categories as $cid => $category)
		{
			// Add a fake-level if needed
			if (!isset($category->level))
			{
				$category->level = 1;
			}

			// Add a simple prefix to the category name
			$prefix = '';

			for ($i = 1; $i < $category->level; $i++)
			{
				$prefix .= '--';
			}

			if (!empty($prefix))
			{
				$category->title = $prefix . ' ' . $category->title;
			}

			$categories[$cid] = $category;
		}

		return $categories;
	}

	/**
	 * Method to set the current category
	 *
	 * @access    public
	 *
	 * @param    int ID of current item
	 * @param    int ID of parent category
	 *
	 * @return    string HTML output
	 */
	static public function getCurrentCategory($categories, $item_id = 0)
	{
		// If there is no current item yet and we only have one category, select it
		if ($item_id == 0)
		{
			if (count($categories) == 1)
			{
				$current = $categories;
			}
			else
			{
				$application = JFactory::getApplication();
				$option = JRequest::getCmd('option');
				$id = $application->getUserStateFromRequest($option . 'filter_category_id', 'filter_category_id', 0, 'int');
				$current = SimplelistsHelper::getCategory($id);
			}

			// Fetch the categories currently selected with the item
		}
		else
		{
			$current = SimplelistsHelper::getCategories($item_id);
		}

		return $current;
	}

	/**
	 * Method to parse a list of categories into a HTML selectbox
	 *
	 * @access    public
	 *
	 * @param    int ID of current item
	 * @param    int ID of parent category
	 *
	 * @return    string HTML output
	 */
	static public function selectCategories($name = '', $params = array())
	{
		// Fetch all categories
		$parent_id = (isset($params['parent_id'])) ? $params['parent_id'] : null;
		$categories = SimplelistsHTML::getCategories($parent_id, false);

		// Remove the current category
		if (isset($params['self']))
		{
			foreach ($categories as $index => $category)
			{
				if ($category->id == $params['self'])
				{
					unset($categories[$index]);
					break;
				}
			}
		}

		// If the $item_id is set, find all the current categories for a SimpleLists item
		$current = (isset($params['item_id'])) ? SimplelistsHTML::getCurrentCategory($categories, $params['item_id']) : null;
		$current = (isset($params['current'])) ? $params['current'] : $current;

		// If no current is set, and if the count of categories is 1, select the first category list
		if (empty($current) && count($categories) == 1)
		{
			$current = $categories[0]->id;
		}

		// Define extra HTML-arguments
		$extra = '';

		// If $multiple is true, we assume we're in the Item Edit form
		if (isset($params['multiple']) && $params['multiple'] == 1)
		{
			$size = (count($categories) < 10) ? count($categories) : 10;
			$extra .= 'size="' . $size . '" multiple';
		}

		// If $none is true, we include an extra option
		if (isset($params['nullvalue']) && $params['nullvalue'] == 1)
		{
			$nulltitle = (isset($params['nulltitle'])) ? $params['nulltitle'] : JText::_('COM_SIMPLELISTS_SELECT_CATEGORY');
			array_unshift($categories, JHtml::_('select.option', 0, '- ' . $nulltitle . ' -', 'id', 'title'));
		}

		// If $javascript is true, we submit the form as soon as an option has been selected
		if (isset($params['javascript']) && $params['javascript'] == 1)
		{
			$extra .= 'onchange="document.adminForm.submit();"';
		}

		return JHtml::_('select.genericlist', $categories, $name, $extra, 'id', 'title', $current);
	}

	/**
	 * Method to parse a list of link-types into a HTML selectbox
	 *
	 * @param int $current
	 *
	 * @return string HTML output
	 */
	static public function selectLinkType($current = '')
	{
		$query = 'SELECT name AS title, element AS value FROM #__extensions WHERE type="plugin" AND folder="simplelistslink" ORDER BY ordering';
		$db = JFactory::getDbo();
		$db->setQuery($query);
		$plugins = $db->loadObjectList();

		$options = array();
		$options[] = JHtml::_('select.option', '', '- ' . JText::_('COM_SIMPLELISTS_SELECT_LINKTYPE') . ' -', 'id', 'title');

		if (!empty($plugins))
		{
			foreach ($plugins as $plugin)
			{
				$title = trim(preg_replace('/simplelists\ \-/i', '', $plugin->title));
				$options[] = JHtml::_('select.option', $plugin->value, $title, 'id', 'title');
			}
		}

		$javascript = 'onchange="document.adminForm.submit();"';

		return JHtml::_('select.genericlist', $options, 'filter_link_type', $javascript, 'id', 'title', $current);
	}

	/**
	 * Method to parse a list of link-types into a HTML selectbox
	 *
	 * @access    public
	 *
	 * @param    int ID of current option
	 *
	 * @return    string HTML output
	 */
	static public function selectImagePosition($name, $default)
	{
		$options[] = array('id' => 'left', 'title' => 'Left');
		$options[] = array('id' => 'right', 'title' => 'Right');
		$options[] = array('id' => 'center', 'title' => 'Center');
		$options[] = array('id' => 'top', 'title' => 'Top');
		$options[] = array('id' => 'bottom', 'title' => 'Bottom');

		return JHtml::_('select.genericlist', $options, $name, null, 'id', 'title', $default);
	}
}
