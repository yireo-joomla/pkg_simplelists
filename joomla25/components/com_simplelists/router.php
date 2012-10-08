<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the router-helper
require_once JPATH_SITE.'/components/com_simplelists/helpers/router.php';

/*
 * Function to convert a system URL to a SEF URL
 */
function SimplelistsBuildRoute(&$query)
{
    // Initialize the segments
	$segments = array();

    // Temporarily extract the Itemid
    if(isset($query['Itemid'])) {
        $Itemid = $query['Itemid'];
        unset($query['Itemid']);
    } else {
        $Itemid = null;
    }

    // If this is an item view and we have to hide it
    if (!empty($query['view']) && $query['view'] == 'item' && !empty($query['task']) && $query['task'] == 'hidden') {

        $segments[] = 'id,'.$query['id'];
        unset($query['view']);
        unset($query['task']);
        unset($query['tmpl']);
        unset($query['id']);

        return $segments;
    }

    // Get the menu items for this component
    $items = SimplelistsHelperRouter::getMenuItems();
    $params = &JComponentHelper::getParams('com_simplelists');

    // Break up the slug into numeric and alias values
    if (!empty($query['category_id'])) {
        $query['slug'] = $query['category_id'];
        if( strpos($query['category_id'], ':')) {
            list($query['category_id'], $query['alias']) = explode(':', $query['category_id'], 2);
        }
    }

    // If this is an item view 
    if (!empty($query['view']) && $query['view'] == 'item') {

        $segments[] = 'item';
        $segments[] = $query['id'];

        if(isset($query['category_id'])) {
            foreach ($items as $item) {
                if(isset($item->query['view']) && isset($item->query['category_id']) && $item->query['view'] == 'items' && $query['category_id'] == $item->query['category_id']) {
                    $query['Itemid'] = $item->id;
                }
            }
        }

        unset($query['view']);
        unset($query['task']);
        unset($query['tmpl']);
        unset($query['id']);
        unset($query['slug']);
        unset($query['alias']);
        unset($query['category_id']);
        return $segments;
    }

    // Search for an appropriate menu item
    if (!empty($items) && isset($query['view'])) {
        foreach ($items as $item) {

            // Matching menu-items only makes sense if there is a "view" and an "id"
            if($params->get('use_parent_url', 0) == 1) {
                if (isset($item->query['view']) && isset($item->query['category_id']) && isset($query['view']) && isset($query['category_id'])) {

                    // Whoever knows how to rewrite the following into something readable, wins my respect
                    if ($query['view'] == $item->query['view'] && $query['category_id'] == $item->query['category_id']) {
                        if(empty($query['layout'])) {
                            $query['Itemid'] = $item->id;
                            break;
                        } elseif(empty($query['layout']) && empty($item->query['layout'])) {
                            $query['Itemid'] = $item->id;
                            break;
                        } elseif(!empty($query['layout']) && !empty($item->query['layout']) && $query['layout'] == $item->query['layout']) {
                            $query['Itemid'] = $item->id;
                            break;
                        }
                    }
                }
            }
        }
    }

    // Set the alias if it is not present
    if(!empty($query['category_id']) && empty($query['alias'])) {
        require_once JPATH_SITE.'/administrator/components/com_simplelists/helpers/category.php';
        $query['alias'] = SimplelistsCategoryHelper::getAlias($query['category_id']);
    }

    // Check if the router found an appropriate Itemid
    if(!isset($query['Itemid']) || !$query['Itemid'] > 0) {
        if($params->get('sef_url') == 'slug' && !empty($query['slug'])) {
            $segments[] = $query['slug'];
        } elseif(!empty($query['alias'])) {
            $segments[] = $query['alias'];
        }
    }

    // Re-add the router if not existing yet
    if(!isset($query['Itemid']) && !empty($Itemid)) {
        $query['Itemid'] = $Itemid;
    }

    // Set the limitstart if needed
    if(isset($query['start'])) {
        $segments[] = (int)$query['start'];
        unset($query['start']);
    }

    // Unset all unneeded query-parts because they should be now either segmented or referenced from the Itemid
    unset($query['view']);
    unset($query['category_id']);
    unset($query['alias']);
    unset($query['slug']);
    unset($query['layout']);

    // Return the segments
	return $segments;
}

/*
 * Function to convert a SEF URL back to a system URL
 */
function SimplelistsParseRoute($segments)
{
	$vars = array();

    // First do the easiest parsing 
    if(preg_match('/^id\,([0-9]+)/', $segments[0])) {
        $ids = explode(',', $segments[0]);
        $vars['view'] = 'item';
        $vars['task'] = 'hidden';
        $vars['tmpl'] = 'component';
        $vars['id'] = $ids[1];
        return $vars;
    }

    // Parse an item
    if($segments[0] == 'item') {
        $vars['view'] = 'item';
        $vars['id'] = $segments[1];
        return $vars;
    }

	// Get the active menu item
	$menu =& JSite::getMenu();
	$item =& $menu->getActive();

    // If the last segment is numeric, assume it's used pagination
    $last = count($segments) - 1;
    if(isset($segments[$last]) && is_numeric($segments[$last])) {
        $vars['limitstart'] = $segments[$last];
        unset($segments[$last]);
    }

	// Parse the segments
    if(!empty($segments[0])) {
    	$vars['alias'] = str_replace( ':', '-', preg_replace('/^([0-9]?):/', '', $segments[0]));
   	    $vars['category_id'] = (int)$segments[0];
   	    $vars['view'] = 'items';
    }

    // If the layout is specified in the URL (which is unlikely), set it in the query
    if(!empty($segments[1])) {
    	$vars['layout'] = $segments[1];
    }

    // If there is no menu-item (so no Itemid), there's nothing more to fetch
	if(!isset($item)) {
		return $vars;
	}

    // Add the menu-item elements to the query
    if(isset( $item->query['layout'] )) {
        $vars['layout'] = $item->query['layout'];
    }

    if(!isset($vars['view']) && isset( $item->query['view'] )) {
        $vars['view'] = $item->query['view'];
    }

    if(!isset($vars['category_id']) && isset( $item->query['category_id'] )) {
        $vars['category_id'] = $item->query['category_id'];
    }

	return $vars;
}
