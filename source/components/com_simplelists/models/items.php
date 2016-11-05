<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
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
	 * Data for the category containing these items
	 *
	 * @var object
	 */
	protected $category;

	/**
	 * @var JTable
	 */
	protected $table;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Construct the item
		parent::__construct('item');

		// Deterine the ID for SimpleLists content
		$categoryId = $this->input->getInt('category_id', '0');
		$this->setId($categoryId);
		$this->setIdByAlias($this->input->getString('alias', ''));

		//$this->setConfig('debug', true);

		// Set pagination
		if ($this->params->get('use_pagination'))
		{
			$this->setLimitQuery(true);

			if ($this->params->get('limit') > 0)
			{
				$this->initLimit($this->params->get('limit'));
			}
		}
		else
		{
			$this->setLimitQuery(false);
		}
	}

	/**
	 * @return JPagination
	 */
	public function getPagination()
	{
		$pagination = parent::getPagination();
		$pagination->setAdditionalUrlParam('category_id', $this->getId());

		return $pagination;
	}

	/**
	 * Method to get data
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function onDataLoadAfter($items)
	{
		// Remove unwanted items
		foreach ($items as $index => $item)
		{
			$event_date_from = $item->params->get('event_date_from');
			$event_date_to   = $item->params->get('event_date_to');

			if (!empty($event_date_to) && strtotime($event_date_to) + (60 * 60 * 24) < time())
			{
				unset($items[$index]);
				continue;
			}
			elseif (empty($event_date_to) && !empty($event_date_from) && strtotime($event_date_from) + (60 * 60 * 24) < time())
			{
				unset($items[$index]);
				continue;
			}
		}

		$items = $this->sortItems($items, $this->params->get('order_by'));

		return $items;
	}

	/**
	 * @param array $items
	 * @param string $ordering
	 *
	 * @return array
	 */
	protected function sortItems($items, $ordering)
	{
		if ($ordering == 'published')
		{
			usort($items, 'SimplelistsModelItems::sortByPublishUp');
			return $items;
		}

		if ($ordering == 'rpublished')
		{
			usort($items, 'SimplelistsModelItems::sortByPublishUp');
			$items = array_reverse($items);
			return $items;
		}

		if ($ordering == 'event')
		{
			usort($items, 'SimplelistsModelItems::sortByEventDateFrom');
			return $items;
		}

		return $items;
	}

	/**
	 * Static method used for sorting data
	 *
	 * @param object $item1
	 * @param object $item2
	 *
	 * @return int
	 */
	static public function sortByPublishUp($item1, $item2)
	{
		$item1_date = @strtotime($item1->params->get('publish_up'));
		$item2_date = @strtotime($item2->params->get('publish_up'));

		if (empty($item1_date))
		{
			return -1;
		}

		if ($item1_date > $item2_date)
		{
			return 1;
		}

		if ($item1_date < $item2_date)
		{
			return -1;
		}

		return 0;
	}

	/**
	 * Static method used for sorting data by event_date_from
	 *
	 * @param object $item1
	 * @param object $item2
	 *
	 * @return int
	 */
	static public function sortByEventDateFrom($item1, $item2)
	{
		$item1_date = @strtotime($item1->params->get('event_date_from'));
		$item2_date = @strtotime($item2->params->get('event_date_from'));

		if (empty($item1_date))
		{
			return -1;
		}

		if ($item1_date > $item2_date)
		{
			return 1;
		}

		if ($item1_date < $item2_date)
		{
			return -1;
		}

		return 0;
	}

	/**
	 * Method to set the simplelist alias
	 *
	 * @param string $alias Simplelist category-alias
	 */
	public function setIdByAlias($alias)
	{
		if (empty($this->id))
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/helpers/category.php';
			$this->setId(SimplelistsCategoryHelper::getId($alias));
		}
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
		$query->leftJoin($this->db->quoteName('#__simplelists_categories', 'relation') . ' ON ' . $this->db->quoteName('item.id') . '=' . $this->db->quoteName('relation.id'));
		$query->leftJoin($this->db->quoteName('#__categories', 'category') . ' ON ' . $this->db->quoteName('category.id') . '=' . $this->db->quoteName('relation.category_id'));

		$query->where($this->db->quoteName('category.published') . ' = 1');

		$categoryId = (int) $this->getId();

		if ($categoryId > 0)
		{
			$query->where($this->db->quoteName('relation.category_id') . ' = ' . $categoryId);
		}

		if ($this->getState('no_char_filter') != 1)
		{
			$character = $this->input->getCmd('char');

			if (!empty($character) && preg_match('/^([a-z]{1})$/', $character))
			{
				$query->where($this->db->quoteName('item.title') . ' LIKE ' . $this->db->quote($character . '%'));
			}
		}

		$ordering = $this->params->get('orderby');

		switch ($ordering)
		{
			case 'alpha':
				$orderby = $this->db->quoteName('item.title') . ' ASC';
				break;
			case 'ralpha':
				$orderby = $this->db->quoteName('item.title') . ' DESC';
				break;
			case 'date':
				$orderby = $this->db->quoteName('item.created') . ' DESC, ' . $this->db->quoteName('item.modified') . ' DESC';
				break;
			case 'rdate':
				$orderby = $this->db->quoteName('item.created') . ' ASC, ' . $this->db->quoteName('item.modified') . ' ASC';
				break;
			case 'random':
				$orderby = 'RAND()';
				break;
			case 'rorder':
				$orderby = $this->db->quoteName('item.ordering') . ' DESC';
				break;
			default:
				$orderby = $this->db->quoteName('item.ordering');
				break;
		}

		$query->order($orderby);

		return $query;
	}

	/**
	 * Method to get a category
	 * 
	 * @param int $categoryId
	 * 
	 * @return object|null
	 */
	public function getCategory($categoryId = null)
	{
		// Only run this once
		if (empty($this->category))
		{
			// Set the ID
			if (empty($categoryId))
			{
				$categoryId = $this->getId();
			}

			// Fetch the category of these items
			require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/models/category.php';
			$model = new SimplelistsModelCategory;
			$model->setId($categoryId);
			$category = $model->getData();

			// Fetch the related categories (parent and children) of this category
			require_once JPATH_ADMINISTRATOR . '/components/com_simplelists/models/categories.php';
			$model = new SimplelistsModelCategories;
			$model->resetFilters();

			$where   = array();
			$where[] = 'category.id = ' . (int) $category->parent_id;
			$where[] = 'category.parent_id = ' . (int) $category->id;
			//$where[] = 'category.parent_id IN (SELECT id from `#__categories` where parent_id = '.(int)$category->id.')';

			$model->addWhere('(' . implode(' OR ', $where) . ')');

			$related = $model->getData(true);

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

			$category->childs = $related;

			// Insert this category in the model
			$this->category = $category;
		}

		return $this->category;
	}
}
