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
include_once JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_simplelists'.DS.'helpers'.DS.'helper.php' ;

/*
 * Helper class
 */
class modSimpleListsItemsHelper
{
    /*
     * Method to get a list SimpleLists items
     */
	public function getList(&$params)
	{
        // Get some system variables
        $mainframe =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();

        // Read the module parameters
		$count = (int)$params->get('count', 5);
		$category_id = (int)$params->get('category_id');
		$ordering = $params->get('ordering');
		$layout = $params->get('layout');
		$Itemid = (int)$params->get('menu_id');

        // Initialize some variables
		$aid = $user->get('aid', 0);
		$nullDate = $db->getNullDate();
		$date =& JFactory::getDate();
		$now = $date->toMySQL();

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
            if(!empty($menu_item)) {
                $layout = (!empty($menu_item->layout)) ? $menu_item->layout : 'default';
            } else {
                $layout = null;
            }
        } else {
		    $menu_item = SimplelistsHelper::getMenuItem( $category_id, $layout );
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
                    $image_file = JPATH_SITE.DS.str_replace('/', DS, $row->picture);
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
