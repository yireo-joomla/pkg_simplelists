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
require_once (dirname(__FILE__).'/helper.php');

// Fetch the list of items
$list = modSimpleListsItemsHelper::getList($params);

// Determine the readmore data
$readmore_link = modSimpleListsItemsHelper::getUrl($params);
$readmore_label = $params->get('readmore_label', 'Read More');
$readmore_label = JText::_($readmore_label);

// Determine the right module-style
$style = $params->get('style', 'default');

// Add a stylesheet per style
if($style == 'advanced') {
    $template = JFactory::getApplication()->getTemplate();
    if(file_exists(JPATH_SITE.'/templates/'.$template.'/css/mod_simplelists_items/'.$style.'.css')) {
        JHTML::stylesheet('templates/'.$template.'/css/mod_simplelists_items/'.$style.'.css');
    } elseif(file_exists( JPATH_SITE.'/media/mod_simplelists_items/css/'.$style.'.css')) {
        JHTML::stylesheet('media/mod_simplelists_items/css/'.$style.'.css');
    }
}

// Display the output
require(JModuleHelper::getLayoutPath('mod_simplelists_items', $style));
