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

// Check to ensure this file is included in Joomla
defined('_JEXEC') or die();

class SimplelistsModelItem extends YireoModelItem
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_orderby_title = 'title';

		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables/');

		parent::__construct('item');
	}

	/**
	 * Method to get a XML-based form
	 *
	 * @param array $data
	 * @param bool  $loadData
	 *
	 * @return false|JForm
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = parent::getForm($data, $loadData);

		if (empty($form))
		{
			return false;
		}

		$data = $this->getData();

		if (empty($data->categories))
		{
			$filter = $this->getFilter('category_id', null, 'cmd', 'com_simplelists_items_');

			if (!empty($filter))
			{
				$data->categories = array($filter);
			}
		}

		// Allow third party plugins to change the form
		JPluginHelper::importPlugin('simplelistscontent');
		$this->app->triggerEvent('onSimplelistsItemPrepareForm', array(&$form, &$data));

		// Bind the form data
		$form->bind(array('item' => $data));

		return $form;
	}

	/**
	 * Method to store the model
	 *
	 * @param mixed $data
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function store($data)
	{
		$params = JComponentHelper::getParams('com_simplelists');
		$categories = array();

		// Insert $categories manually
		if (!empty($data['item']['categories']))
		{
			$categories = $data['item']['categories'];
			unset($data['item']['categories']);
		}
		elseif (!empty($data['categories']))
		{
			$categories = $data['categories'];
			unset($data['categories']);
		}

		// Insert link manually
		if (isset($data['link_type']))
		{
			$type = $data['link_type'];

			if (!empty($data['input']['link_' . $type]))
			{
				$data['link'] = $data['input']['link_' . $type];
			}

			if (!empty($data['link_' . $type]))
			{
				$data['link'] = $data['link_' . $type];
			}
		}

		// Remove the old category-relations
		if ($params->get('auto_ordering', 1) == 1 && $data['id'] == 0 && count($categories) == 1)
		{
			$db = $this->db;
			$query = $db->getQuery(true);
			$query->select('MAX(' . $db->quoteName('item.ordering') . ')');
			$query->from($db->quoteName('#__simplelists_items', 'item'));
			$query->leftJoin($db->quoteName('#__simplelists_categories', 'category') . ' ON ' . $db->quoteName('category.id') . '=' . $db->quoteName('item.id'));
			$query->where($db->quoteName('category.category_id') . '=' . (int) $categories[0]);

			$this->_db->setQuery($query);
			$data['ordering'] = $this->_db->loadResult() + 1;
		}

		// Store these data
		$rs = parent::store($data);

		if ($rs == false)
		{
			throw new Exception(JText::_('LIB_YIREO_TABLE_ERROR'));
		}

		// Handle category-relations
		if (!$this->getId() > 0)
		{
			throw new Exception(JText::_('LIB_YIREO_TABLE_UNKNOWN_ID'));
		}

		// Remove the old category-relations
		$this->removeCategories(array((int) $this->getId()));

		// Store the new category-relations
		if (!empty($categories))
		{
			foreach ($categories as $categoryId)
			{
				$categoryMapping = new stdClass();
				$categoryMapping->id = (int) $this->getId();
				$categoryMapping->category_id = (int) $categoryId;
				$this->db->insertObject('#__simplelists_categories', $categoryMapping);
			}
		}

		return true;
	}

	/**
	 * Method to remove an item
	 *
	 * @param array $cid
	 *
	 * @return boolean True on success
	 * @throws Exception
	 */
	public function delete($cid = array())
	{
		if (empty($cid))
		{
			return true;
		}

		// Convert this array
		\Joomla\Utilities\ArrayHelper::toInteger($cid);

		// Call the parent function
		if (parent::delete($cid) == false)
		{
			return false;
		}

		// Remove all item/category relations
		$this->removeCategories($cid);

		return true;
	}

	/**
	 * Remove categories
	 *
	 * @param array $ids
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected function removeCategories($ids)
	{
		if (empty($ids))
		{
			return false;
		}

		if (count($ids) == 1 && empty($ids[0]))
		{
			return false;
		}

		/** @var JDatabaseDriver $db */
		$db = $this->db;

		// Remove all item/category relations
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__simplelists_categories'));
		$query->where($db->quoteName('id') . ' IN (' . implode(',', $ids) . ')');
		$db->setQuery($query);
		$db->execute();

		return true;
	}

	/**
	 * Method to add extra data
	 *
	 * @param object $data
	 *
	 * @return object
	 */
	public function onDataLoad($data)
	{
		// If these data exist, add extra info
		if (empty($data->categories))
		{
			// Fetch the categories
			$data->categories = SimplelistsHelper::getCategories($data->id, null, 'id');

			// Fetch the extra link data
			$data = $this->appendLinkData($data);
		}

		return $data;
	}

	/**
	 * @param object $data
	 *
	 * @return object
	 */
	protected function appendLinkData($data)
	{
		if (isset($data->link_data))
		{
			return $data;
		}

		$data->link_data = array();

		if (empty($data->link_type))
		{
			return $data;
		}

		$plugin = SimplelistsPluginHelper::getPlugin('simplelistslink', $data->link_type);

		if (empty($plugin))
		{
			return $data;
		}

		$data->link_data[$data->link_type] = $plugin->getName($data->link);

		return $data;
	}

	/**
	 * Method to get the ordering query
	 *
	 * @return string
	 */
	public function getOrderingQuery()
	{
		if ($this->_orderby_default != 'ordering')
		{
			return null;
		}

		/** @var JDatabaseDriver $db */
		$db = $this->db;

		$subQuery = $db->getQuery(true);
		$subQuery->select($db->quoteName('category_id'));
		$subQuery->from($db->quoteName('#__simplelists_categories'));
		$subQuery->where($db->quoteName('id') . ' = ' . (int) $this->data->id);

		$query = $db->getQuery(true);
		$query->select($db->quoteName('item.ordering', 'value'));
		$query->select($db->quoteName('item.title', 'text'));
		$query->from($db->quoteName('#__simplelists_items', 'item'));
		$query->leftJoin($db->quoteName('#__simplelists_categories', 'category') . ' ON ' . $db->quoteName('category.id') . '=' . $db->quoteName('item.id'));
		$query->where($db->quoteName('category.category_id') . ' IN (' . (string) $subQuery . ')');
		$query->order($db->quoteName('item.ordering'));

		return $query;
	}
}
