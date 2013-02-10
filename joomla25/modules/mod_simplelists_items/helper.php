<?php
/**
 * Joomla! module for Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2012 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the SimpleLists helper
include_once JPATH_SITE.'/administrator/components/com_simplelists/helpers/helper.php' ;

/*
 * Helper class
 */
class modSimpleListsItemsHelper
{
    /*
     * Method to get the SimpleLists category
     */
	public function getCategory(&$params)
	{
        // Get some system variables
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

        // Read the module parameters
		$category_id = (int)$params->get('category_id');
		$layout = $params->get('layout');
		$Itemid = (int)$params->get('menu_id');

        $query = 'SELECT c.* FROM #__categories AS c WHERE c.id = '.$category_id;
		$db->setQuery($query);
		$category = $db->loadObject();

        // Get the Itemid
        if($Itemid > 0) {
		    $menu_item = SimplelistsHelper::getMenuItemFromItemId($Itemid);
            if(!empty($menu_item)) $layout = (!empty($menu_item->layout)) ? $menu_item->layout : 'default';
        }

        // Load the menu-item differently
        if(empty($menu_item)) {
		    $menu_item = SimplelistsHelper::getMenuItem($category_id, $layout);
		    if($menu_item != null) {
			    if(isset($menu_item->query['layout'])) $layout = $menu_item->query['layout'] ;
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
    
    /*
     * Method to get a list of SimpleLists items
     */
	public function getList(&$params)
	{
        // Get some system variables
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

        // Read the module parameters
		$count = (int)$params->get('count', 5);
		$category_id = (int)$params->get('category_id');
		$ordering = $params->get('ordering');
		$layout = $params->get('layout');
		$Itemid = (int)$params->get('menu_id');

        // Initialize some variables
		$aid = $user->get('aid', 0);
		$now = date('Y-m-d H:i');

	    switch( $ordering ) {
            case 'alpha': 
                $o = 'w.title ASC' ;
                break ;
            case 'ralpha': 
                $o = 'w.title DESC' ;
                break ;
            case 'date': 
                $o = 'w.created DESC, w.modified DESC' ;
                break ;
            case 'rdate': 
                $o = 'w.created ASC, w.modified ASC' ;
                break ;
            case 'random': 
                $o = 'RAND()' ;
                break ;
            default:
                $o = 'w.ordering' ;
                break ;
        }
        $where = 'ORDER BY '.$o;

        $query = 'SELECT w.*, c.title AS category, c.id AS category_id, c.alias AS category_alias'.
            ' FROM #__simplelists_categories AS sc' .
            ' LEFT JOIN #__simplelists_items AS w ON w.id = sc.id' .
            ' LEFT JOIN #__categories AS c ON c.id = sc.category_id' .
            ' WHERE sc.category_id = '.$category_id .
            ' AND c.published = 1' .
            ' AND w.published = 1' .
            ' ' . $where ;

		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

        // Get the Itemid
        if($Itemid > 0) {
		    $menu_item = SimplelistsHelper::getMenuItemFromItemId($Itemid);
            if(!empty($menu_item)) $layout = (!empty($menu_item->layout)) ? $menu_item->layout : 'default';
        }

        // Load the menu-item differently
        if(empty($menu_item)) {
		    $menu_item = SimplelistsHelper::getMenuItem($category_id, $layout);
		    if($menu_item != null) {
			    if(isset($menu_item->query['layout'])) $layout = $menu_item->query['layout'] ;
			    $Itemid = $menu_item->id;
            }
		}
		
		$i = 0;
		$lists = array();
        if(!empty($rows)) {
            foreach ($rows as $row) {

                $needles = array(
                    'category_id' => $row->category_id,
                    'category_alias' => $row->category_alias,
                    'item_id' => $row->id,
                    'item_alias' => $row->alias,
                    'Itemid' => $Itemid,
                    'layout' => $layout,
                );

                $row->href = ($row->alias) ? $row->alias : 'item'.$row->id;
                $row->link = SimplelistsHelper::getUrl($needles);
                $row->title = htmlspecialchars( $row->title );
                $row->params = YireoHelper::toParameter($row->params);

                if($params->get('show_image',0) == 1) {
                    $align = $row->params->get('picture_alignment');
                    if(empty($align)) $align = $params->get('image_align', 'left');
                    $attributes = 'alt="'.$row->title.'" title="'.$row->title.'" class="simplelists" style="float:'.$align.'"';
                    $image_file = JPATH_SITE.'/'.$row->picture;
                    if(is_file($image_file)) {
                        $size = getimagesize($image_file);
                        $attributes .= 'width="'.$size[0].'" height="'.$size[1].'"';
                    }
                    $row->picture = JHTML::image( $row->picture, $row->title, $attributes );
                } else {
                    $row->picture = null;
                }

                $lists[$i] = $row;
                $i++;
            }
        }

		return $lists;
	}
}
