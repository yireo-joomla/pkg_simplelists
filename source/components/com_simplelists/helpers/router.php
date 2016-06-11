<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Load the Yireo library
jimport('yireo.loader');

/**
 * Class SimplelistsRouteQuery
 */
class SimplelistsHelperRouter extends YireoRouteQuery
{
	/**
	 * @return array|null
	 */
	public function getMenuItems()
	{
		return $this->getMenuItemsByComponent('com_simplelists');
	}

	/**
	 * Handle the query in case of a hidden item
	 */
	public function handleHiddenItem()
	{
		$this->addSegment('id, ' . $this->getValue('id'));
		$this->unsetVars(array('view', 'layout', 'task', 'tmpl', 'id', 'slug', 'alias', 'category_id'));
	}

	public function setAliasFromCategoryId()
	{
		require_once JPATH_SITE . '/administrator/components/com_simplelists/helpers/category.php';
		$this->setValue('alias', SimplelistsCategoryHelper::getAlias($this->getValue('category_id')));
	}

	/**
	 * Handle the query in case of an item
	 */
	public function handleItem()
	{
		$this->addSegment('item');
		$this->addSegment($this->getValue('id'));

		// Match the category-ID with an existing Menu-Item
		if ($this->hasValue('category_id'))
		{
			foreach ($this->getMenuItems() as $item)
			{
				if ($this->isView('items', $item) && $this->getValue('category_id', $item) == $this->getValue('category_id'))
				{
					$this->setValue('Itemid', $item->id);
					$this->unsetVar('category_id');
				}
			}
		}

		$this->unsetVars(array('view', 'layout', 'task', 'tmpl', 'id', 'slug', 'alias', 'category_id', 'limitstart'));
	}

	/**
	 * Parse the category ID into a workable slug and alias
	 */
	public function prepareCategorySlug()
	{
		if ($categoryId = $this->hasValue('category_id'))
		{
			if (!$this->hasValue('category_slug'))
			{
				$this->setValue('category_slug', $categoryId);
			}

			if (strpos($categoryId, ':'))
			{
				list($categoryId, $categoryAlias) = explode(':', $categoryId, 2);
				$this->setValue('category_id', $categoryId);
				$this->setValue('category_alias', $categoryAlias);
			}
		}
	}

	/**
	 * @return bool
	 */
	public function copyItemidFromItems()
	{
		$items = $this->getMenuItems();

		if (empty($items))
		{
			return false;
		}

		foreach ($items as $item)
		{
			if (!$itemView = $this->getValue('view', $item))
			{
				continue;
			}

			if (!$itemCategoryId = $this->getValue('category_id', $item))
			{
				continue;
			}

			if ($this->matchValue('view', $item) == false || $this->matchValue('category_id', $item) == false)
			{
				continue;
			}

			if ($this->hasValue('layout') && $this->matchValue('layout', $item))
			{
				$this->setValue('Itemid', $item->id);
				break;
			}

			if ($this->hasValue('layout') == false)
			{
				$this->setValue('Itemid', $item->id);
				break;
			}
		}
	}
}
