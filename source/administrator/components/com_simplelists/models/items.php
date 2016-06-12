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
		$this->_search = array('title');
		//$this->_debug = true;
		$this->setConfig('table_prefix_auto', true);

		parent::__construct('item');
	}

	/**
	 * Method to build the database query
	 *
	 * @param string $query
	 *
	 * @return mixed
	 */
	protected function buildQuery($query = '')
	{
		$query = "SELECT item.*, {access}, {editor} FROM #__simplelists_items AS item \n";

		return parent::buildQuery($query);
	}

	/**
	 * Method to build the query WHERE segment
	 *
	 * @return string
	 */
	protected function buildQueryWhere()
	{
		$category_id = (int) $this->getFilter('category_id');

		if ($category_id > 0)
		{
			$this->addWhere('item.id IN (SELECT `id` FROM `#__simplelists_categories` WHERE `category_id`=' . $category_id . ')');
		}

		$link_type = $this->getFilter('link_type');

		if (!empty($link_type))
		{
			$this->addWhere('item.link_type =' . $this->_db->quote($link_type));
		}

		return parent::buildQueryWhere();
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
					if ($item->id == $category->parent_id)
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
		$model = new SimplelistsModelCategories;

		$wheres = array();

		foreach ($ids as $id)
		{
			$wheres[] = 'category.id = ' . (int) $id;
		}

		$model->addWhere(implode(' OR ', $wheres));

		return $model->getData();
	}
}
