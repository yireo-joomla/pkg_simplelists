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

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

/**
 * Simplelists Items Model
 */
class SimplelistsModelItems extends YireoModelItems
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct('item');

		$this->setConfig('search_fields', ['title']);
		//$this->setConfig('debug', true);
	}

	/**
	 * Method to modify the query
	 *
	 * @param $query JDatabaseQuery
	 *
	 * @return JDatabaseQuery
	 */
	public function onBuildQuery($query)
	{
		$category_id = (int) $this->getFilter('category_id');

		if ($category_id > 0)
		{
			$subQuery = $this->db->getQuery(true);
			$subQuery->select($this->db->quoteName('id'));
			$subQuery->from($this->db->quoteName('#__simplelists_categories'));
			$subQuery->where($this->db->quoteName('category_id') . '=' . (int) $category_id);

			$query->where('item.id IN (' . (string) $subQuery . ')');
		}

		$link_type = $this->getFilter('link_type');

		if (!empty($link_type))
		{
			$query->where($this->db->quoteName('item.link_type') . '=' . $this->_db->quote($link_type));
		}

		return $query;
	}

	/**
	 * Method to get a category
	 */
	public function getCategory($category_id = null)
	{
		// Only run this once
		if (empty($this->_category))
		{
			// Set the ID
			if (empty($category_id))
			{
				$category_id = $this->getId();
			}

			// Fetch the category of these items
			$category = $this->getCategoryData($category_id);

			// Fetch the related categories (parent and children) of this category
			$related = $this->getCategoriesData(array($category->parent_id, $category_id));

			if (!empty($related))
			{
				foreach ($related as $id => $item)
				{
					// Make sure this related category is not the parent-category
					if ($item->id === $category->parent_id)
					{
						$category->parent = $item;
						unset($related[$id]);
						continue;
					}
				}
			}

			$category->childs = $related;

			// Insert this category in the model
			$this->_category = $category;
		}

		return $this->_category;
	}

	/**
	 * @param $id
	 *
	 * @return array
	 */
	protected function getCategoryData($id)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/models/category.php';

		$model = new SimplelistsModelCategory;
		$model->setId($id);

		return $model->getData();
	}

	/**
	 * @param $ids
	 *
	 * @return array
	 */
	protected function getCategoriesData($ids)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/models/categories.php';

		$model  = new SimplelistsModelCategories;
		$wheres = [];

		foreach ($ids as $id)
		{
			$wheres[] = 'category.id = ' . (int) $id;
		}

		$model->addWhere(implode(' OR ', $wheres));

		return $model->getData();
	}
}
