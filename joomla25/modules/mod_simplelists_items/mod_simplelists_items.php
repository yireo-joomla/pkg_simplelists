<?php
/**
 * Joomla! module SimpleLists Items
 *
 * @author Yireo
 * @copyright Copyright 2013
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper
require_once (dirname(__FILE__).'/helper.php');

// Fetch the category and the list of items
$list = modSimpleListsItemsHelper::getList($params);
$category = modSimpleListsItemsHelper::getCategory($params);

// Determine the right module-style
$style = $params->get('style', 'default');

// Add a stylesheet per style
if($style == 'advanced') {
    $template = JFactory::getApplication()->getTemplate();
    if(file_exists(JPATH_SITE.'/templates/'.$template.'/css/mod_simplelists_items/'.$style.'.css')) {
        JHTML::stylesheet(JURI::root().'templates/'.$template.'/css/mod_simplelists_items/'.$style.'.css');
    } elseif(file_exists( JPATH_SITE.'/media/mod_simplelists_items/css/'.$style.'.css')) {
        JHTML::stylesheet(JURI::root().'media/mod_simplelists_items/css/'.$style.'.css');
    }
}

// Construct the readmore
if($params->get('show_readmore')) {
    $readmore = $params->get('readmore_text');
    if(empty($readmore)) {
        $readmore = $category->title;
    } else {
        $readmore = str_replace('%s', $category->title, $readmore);
    }
    $readmore_link = $category->link;
} else {
    $readmore = false;
}

// Display the output
require JModuleHelper::getLayoutPath('mod_simplelists_items', $style);
