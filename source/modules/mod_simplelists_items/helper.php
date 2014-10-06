<?php
/**
 * Joomla! module for Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2013 Yireo
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
	static public function getCategory($params)
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
    static public function getItems($params)
    {
        // Get some system variables
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $category = self::getCategory($params);

        // Read the module parameters
        $ordering = $params->get('ordering', 5);
        $count = (int)$params->get('count', 5);
        $category_id = (int)$params->get('category_id');
        $layout = $params->get('layout');
        $Itemid = (int)$params->get('menu_id');

        // Include the model
        $modelFile = JPATH_SITE.'/components/com_simplelists/models/items.php';
        $tableFile = JPATH_ADMINISTRATOR.'/components/com_simplelists/tables/item.php';

        if(file_exists($modelFile) == false)
        {
            return false;
        }

        // Include the files
        include_once $tableFile;
        include_once $modelFile;

        // Instantiate the model
        $model = new SimplelistsModelItems();
        $model->setId($category_id);
        $model->initLimit($count);
        $model->initLimitstart(0);
        $model->params->set('orderby', $ordering);
        $items = $model->getData();

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

        $result = array();
        if(!empty($items)) {
            foreach ($items as $item) {

                $needles = array(
                    'category_id' => $category->id,
                    'category_alias' => $category->alias,
                    'item_id' => $item->id,
                    'item_alias' => $item->alias,
                    'Itemid' => $Itemid,
                    'layout' => $layout,
                );

                $item->href = ($item->alias) ? $item->alias : 'item'.$item->id;
                $item->link = SimplelistsHelper::getUrl($needles);
                $item->title = htmlspecialchars( $item->title );
                $item->params = YireoHelper::toParameter($item->params);

                if($params->get('show_image',0) == 1) {
                    $align = $item->params->get('picture_alignment');
                    if(empty($align)) $align = $params->get('image_align', 'left');
                    $attributes = 'alt="'.$item->title.'" title="'.$item->title.'" class="simplelists" style="float:'.$align.'"';
                    $image_file = JPATH_SITE.'/'.$item->picture;
                    if(is_file($image_file)) {
                        $size = getimagesize($image_file);
                        $attributes .= 'width="'.$size[0].'" height="'.$size[1].'"';
                    }
                    $item->picture = JHTML::image( $item->picture, $item->title, $attributes );
                } else {
                    $item->picture = null;
                }

                $result[] = $item;
            }
        }

        return $result;
    }
}
