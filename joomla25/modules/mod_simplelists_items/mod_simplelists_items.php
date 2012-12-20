<?php
/**
 * Joomla! module SimpleLists Items
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper
require_once (dirname(__FILE__).DS.'helper.php');

// Fetch the list of items
$list = modSimpleListsItemsHelper::getList($params);

// Determine the right module-style
$style = $params->get('style', 'default');

// Add a stylesheet per style
if($style == 'advanced') {
    $template = JFactory::getApplication()->getTemplate();
    if(file_exists(JPATH_SITE.DS.'templates'.DS.$template.DS.'css'.DS.'mod_simplelists_items'.DS.$style.'.css')) {
        JHTML::stylesheet(JURI::root().'templates'.DS.$template.DS.'css'.DS.'mod_simplelists_items'.DS.$style.'.css');
    } elseif(file_exists( JPATH_SITE.DS.'media'.DS.'mod_simplelists_items'.DS.'css'.DS.$style.'.css')) {
        JHTML::stylesheet(JURI::root().'media'.DS.'mod_simplelists_items'.DS.'css'.DS.$style.'.css');
    }
}

// Display the output
require(JModuleHelper::getLayoutPath('mod_simplelists_items', $style));
