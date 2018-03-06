<?php
/**
 * Joomla! module for Simple Lists
 *
 * @author    Yireo
 * @copyright Copyright 2016 Yireo
 * @license   GNU/GPL
 * @link      https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the SimpleLists classes
include_once JPATH_SITE . '/administrator/components/com_simplelists/helpers/helper.php';
include_once JPATH_SITE . '/administrator/components/com_simplelists/helpers/plugin.php';
include_once JPATH_SITE . '/components/com_simplelists/helpers/html.php';

/**
 * Helper class
 */
class ModSimpleListsItemsHelper
{
	/**
	 * @var \Joomla\Registry\Registry
	 */
	protected $params;
	
	/**
	 * ModSimpleListsItemsHelper constructor.
	 *
	 * @param $params
	 */
	public function __construct($params)
	{
		$this->params = $params;
	}

	/**
	 * Method to get the SimpleLists category
	 */
	public function getCategory()
	{
		// Get some system variables
		$db = JFactory::getDbo();

		// Read the module parameters
		$category_id = (int) $this->params->get('category_id');
		$layout = $this->params->get('layout');
		$Itemid = (int) $this->params->get('menu_id');

		/** @var JDatabaseQuery $query */
		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from($db->quoteName('#__categories', 'c'));
		$query->where($db->quoteName('c.id') . '=' . $category_id);
		$db->setQuery($query);
		$category = $db->loadObject();

		// Get the Itemid
		if ($Itemid > 0)
		{
			$menu_item = SimplelistsHelper::getMenuItemFromItemid($Itemid);

			if (!empty($menu_item))
			{
				$layout = (!empty($menu_item->layout)) ? $menu_item->layout : 'default';
			}
		}

		// Load the menu-item differently
		if (empty($menu_item))
		{
			$menu_item = SimplelistsHelper::getMenuItem($category_id, $layout);

			if ($menu_item != null)
			{
				if (isset($menu_item->query['layout']))
				{
					$layout = $menu_item->query['layout'];
				}
				$Itemid = $menu_item->id;
			}
		}

		// Construct the URL-needles
		$needles = array(
			'category_id' => $category->id,
			'category_alias' => $category->alias,
			'Itemid' => $Itemid,
			'layout' => $layout,
		);

		$category->link = SimplelistsHelper::getUrl($needles);
		$category->title = htmlspecialchars($category->title);
		$category->params = YireoHelper::toParameter($category->params);

		return $category;
	}

	/**
	 * Method to get a list of SimpleLists items
	 */
	public function getItems()
	{
		// Get some system variables
		$category = self::getCategory();

		// Read the module parameters
		$ordering = $this->params->get('ordering', 'order');
		$count = (int) $this->params->get('count', 5);
		$category_id = (int) $this->params->get('category_id');
		$layout = $this->params->get('layout');
		$Itemid = (int) $this->params->get('menu_id');

		// Include the model
		$modelFile = JPATH_SITE . '/components/com_simplelists/models/items.php';
		$tableFile = JPATH_ADMINISTRATOR . '/components/com_simplelists/tables/item.php';

		if (file_exists($modelFile) == false)
		{
			return false;
		}

		// Include the files
		include_once $tableFile;
		include_once $modelFile;

		// Instantiate the model
		$model = new SimplelistsModelItems();
		$model->setId($category_id);
		$model->initLimit($count);
		$model->initLimitstart(0);

		$modelParams = $model->getParams();
		$modelParams->set('orderby', $ordering);
		$model->setParams($modelParams);
		$items = $model->getData();

		// Get the Itemid
		if ($Itemid > 0)
		{
			$menu_item = SimplelistsHelper::getMenuItemFromItemid($Itemid);

			if (!empty($menu_item))
			{
				$layout = (!empty($menu_item->layout)) ? $menu_item->layout : 'default';
			}
		}

		// Load the menu-item differently
		if (empty($menu_item))
		{
			$menu_item = SimplelistsHelper::getMenuItem($category_id, $layout);

			if ($menu_item != null)
			{
				if (isset($menu_item->query['layout']))
				{
					$layout = $menu_item->query['layout'];
				}

				$Itemid = $menu_item->id;
			}
		}

		$result = array();
		$dispatcher = JEventDispatcher::getInstance();

		if (!empty($items))
		{
			foreach ($items as $item)
			{
				// Run the content through Content Plugins
				if ($item->params->get('enable_content_plugins', 1) == 1)
				{
					JPluginHelper::importPlugin('content');
					$itemParams = array();
					$dispatcher->trigger('onPrepareContent', array(&$item, &$itemParams, 0));
				}

				if ($this->params->get('link_list', 1) == 1)
				{
					$item->link = $this->getCategoryUrl($category, $item, $Itemid, $layout);
				}
				else
				{
					$item->link = $this->getItemUrl($category, $item, $Itemid, $layout);
				}

				$item->href = ($item->alias) ? $item->alias : 'item' . $item->id;
				$item->title = htmlspecialchars($item->title);
				$item->params = YireoHelper::toParameter($item->params);

				if ($this->params->get('show_image', 0) == 1)
				{
					$align = $item->params->get('picture_alignment');

					if (empty($align))
					{
						$align = $this->params->get('image_align', 'left');
					}

					$attributes = 'alt="' . $item->title . '" title="' . $item->title . '" class="simplelists" style="float:' . $align . '"';
					$image_file = JPATH_SITE . '/' . $item->picture;

					if (is_file($image_file))
					{
						$size = getimagesize($image_file);
						$attributes .= 'width="' . $size[0] . '" height="' . $size[1] . '"';
					}

					$item->picture = SimplelistsHTML::image($item->picture, $item->title, $attributes);
				}
				else
				{
					$item->picture = null;
				}

				$result[] = $item;
			}
		}

		return $result;
	}

	/**
	 * @param $category
	 * @param $item
	 * @param $Itemid
	 * @param $layout
	 *
	 * @return null|string
	 */
	public function getItemUrl($category, $item, $Itemid, $layout)
	{
		$url = SimplelistsPluginHelper::getPluginLinkUrl($item);

		if (empty($url))
		{
			return null;
		}

		$url = JRoute::_($url);

		return $url;
	}

	/**
	 * @param $category
	 * @param $item
	 * @param $Itemid
	 * @param $layout
	 *
	 * @return string
	 */
	public function getCategoryUrl($category, $item, $Itemid, $layout)
	{
		$needles = array(
			'category_id' => $category->id,
			'category_alias' => $category->alias,
			'item_id' => $item->id,
			'item_alias' => $item->alias,
			'Itemid' => $Itemid,
			'layout' => $layout,
		);

		return SimplelistsHelper::getUrl($needles);
	}
}
