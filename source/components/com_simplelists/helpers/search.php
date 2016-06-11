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

// Import the parent-class
jimport('joomla.plugin.plugin');

// Include the SimpleLists helper
include_once JPATH_SITE . '/administrator/components/com_simplelists/helpers/helper.php';

/**
 * Search Helper
 */
class SimplelistsHelperSearch
{
	/**
	 * Method to perform the actual search
	 *
	 * @param string $text
	 * @param string $phrase
	 * @param string $ordering
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function search($text, $phrase = '', $ordering = '', $limit)
	{
		// Fetch system variables
		$db = JFactory::getDbo();

		// Construct the current date
		$nullDate = $db->getNullDate();
		jimport('joomla.utilities.date');
		$date = new JDate;
		$now = (method_exists('JDate', 'toSql')) ? $date->toSql() : $date->toMySQL();

		// Construct the WHERE-segments
		$wheres = array();
		switch ($phrase)
		{
			case 'exact':
				$text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'LOWER(s.title) LIKE ' . $text;
				$wheres2[] = 'LOWER(s.text) LIKE ' . $text;
				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = 'LOWER(s.title) LIKE ' . $word;
					$wheres2[] = 'LOWER(s.text) LIKE ' . $word;
					$wheres[] = implode(' OR ', $wheres2);
				}

				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		$morder = '';

		switch ($ordering)
		{
			/*
			case 'oldest':
				$order = 'a.created ASC';
				break;
			case 'popular':
				$order = 'a.hits DESC';
				break;
			case 'alpha':
				$order = 'a.title ASC';
				break;
			case 'category':
				$order = 'b.title ASC, a.title ASC';
				$morder = 'a.title ASC';
				break;
			case 'newest':
				default:
				$order = 'a.created DESC';
				break;
			*/
			default:
				$order = 's.ordering';
				break;
		}

		$rows = array();

		// Search simplelists item
		// @todo: Replace this with a Model-based procedure
		$query = 'SELECT s.id, s.title, s.alias, s.text AS text, b.category_id, c.alias AS category_alias, c.title AS catname,' . ' "2" AS browsernav' . ' FROM #__simplelists_items AS s' . ' LEFT JOIN #__simplelists_categories AS b ON b.id = s.id' . ' LEFT JOIN #__categories AS c ON c.id = b.category_id' . ' WHERE (' . $where . ')' . ' AND c.published = 1' . ' AND s.published = 1' . ' GROUP BY s.id' . ' ORDER BY ' . $order;
		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();
		$limit -= count($list);

		return $list;
	}
}
