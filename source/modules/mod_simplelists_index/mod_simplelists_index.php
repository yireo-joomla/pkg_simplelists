<?php
/**
 * @author    Yireo
 * @copyright Copyright 2015 Yireo
 * @license   GNU/GPL
 * @link      http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$input = $app->input;

// Deny output
if ($input->getCmd('option') != 'com_simplelists')
{
	return;
}
if ($input->getCmd('view') != 'items')
{
	return;
}

// Include the helper
require_once(dirname(__FILE__) . DS . 'helper.php');

// Fetch the list of items
$items = modSimpleListsIndexHelper::getItems($params);

if (empty($items))
{
	return null;
}

// Display the output
require(JModuleHelper::getLayoutPath('mod_simplelists_index'));
