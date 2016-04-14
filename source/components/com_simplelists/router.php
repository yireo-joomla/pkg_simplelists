<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the router-helper
require_once JPATH_SITE . '/components/com_simplelists/helpers/router.php';

// Library loader
jimport('yireo.loader');

/*
 * Function to convert a system URL to a SEF URL
 */
function SimplelistsBuildRoute(&$query)
{
	$routeQuery = new SimplelistsHelperRouter;
	$routeQuery->setData($query);
	$Itemid = $routeQuery->getItemid();

	// Get the menu items for this component
	$params = JComponentHelper::getParams('com_simplelists');

	// Break up the slug into numeric and alias values
	$routeQuery->prepareCategorySlug();

	// If this is an item view and we have to hide it
	if ($routeQuery->isView('item') && $routeQuery->isTask('hidden'))
	{
		$routeQuery->handleHiddenItem();
		$query = $routeQuery->getData();

		return $routeQuery->getSegments();
	}

	// If this is an item view
	if ($routeQuery->isView('item'))
	{
		$routeQuery->handleItem();
		$query = $routeQuery->getData();

		return $routeQuery->getSegments();
	}

	// Search for an appropriate menu item
	if ($params->get('use_parent_url', 0) == 1)
	{
		$this->copyItemidFromItems();
	}

	// Set the alias if it is not present
	if ($routeQuery->hasValue('category_id') && !$routeQuery->hasValue('alias'))
	{
		$routeQuery->setAliasFromCategoryId();
	}

	// Check if the router found an appropriate Itemid
	if (!$routeQuery->hasValue('Itemid') || $routeQuery->hasValue('category_id'))
	{
		if ($params->get('sef_url') == 'slug' && $routeQuery->hasValue('slug'))
		{
			echo 'test';
			$routeQuery->addSegmentFromData('slug');
		}
		elseif ($routeQuery->hasValue('alias'))
		{
			$routeQuery->addSegmentFromData('alias');
		}
	}

	// Re-add the router if not existing yet
	if (!$routeQuery->hasValue('Itemid') && !empty($Itemid))
	{
		$routeQuery->setValue('Itemid', $Itemid);
	}

	// Set the limitstart if needed
	if ($routeQuery->hasValue('start'))
	{
		$routeQuery->addSegment((int) $routeQuery->getValue('start'));
	}

	// Unset all unneeded query-parts because they should be now either segmented or referenced from the Itemid
	$routeQuery->unsetVars(array('view', 'layout', 'task', 'tmpl', 'id', 'slug', 'alias', 'category_id', 'category_slug', 'start', 'limitstart'));
	$query = $routeQuery->getData();

	// Return the segments
	return $routeQuery->getSegments();
}

/*
 * Function to convert a SEF URL back to a system URL
 */
function SimplelistsParseRoute($segments)
{
	$vars = array();

	// First do the easiest parsing
	if (preg_match('/^id\,([0-9]+)/', $segments[0]))
	{
		$ids = explode(',', $segments[0]);
		$vars['view'] = 'item';
		$vars['task'] = 'hidden';
		$vars['tmpl'] = 'component';
		$vars['id'] = $ids[1];

		return $vars;
	}

	// Parse an item
	if ($segments[0] == 'item' && count($segments) > 1)
	{
		$vars['view'] = 'item';
		$vars['id'] = $segments[1];

		return $vars;
	}

	// Get the active menu item
	$menu = JFactory::getApplication()
		->getMenu();
	$item = $menu->getActive();

	// If the last segment is numeric, assume it's used pagination
	$last = count($segments) - 1;
	if (isset($segments[$last]) && is_numeric($segments[$last]))
	{
		$vars['limitstart'] = $segments[$last];
		unset($segments[$last]);
	}

	// Parse the segments
	if (!empty($segments[0]))
	{
		$vars['alias'] = str_replace(':', '-', preg_replace('/^([0-9]?):/', '', $segments[0]));
		$vars['category_id'] = (int) $segments[0];
		$vars['view'] = 'items';
	}

	// If the layout is specified in the URL (which is unlikely), set it in the query
	if (!empty($segments[1]))
	{
		$vars['layout'] = $segments[1];
	}

	// If there is no menu-item (so no Itemid), there's nothing more to fetch
	if (!isset($item))
	{
		return $vars;
	}

	// Add the menu-item elements to the query
	if (isset($item->query['layout']))
	{
		$vars['layout'] = $item->query['layout'];
	}

	if (!isset($vars['view']) && isset($item->query['view']))
	{
		$vars['view'] = $item->query['view'];
	}

	if (!isset($vars['category_id']) && isset($item->query['category_id']))
	{
		$vars['category_id'] = $item->query['category_id'];
	}

	return $vars;
}