<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'icon.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'html.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'helper.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugin.php';

// Initialize the controller
$controller	= new SimplelistsController( );
$controller->execute( null );

// Redirect if set by the controller
$controller->redirect();

