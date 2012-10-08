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

// Require the base controller
require_once JPATH_COMPONENT.'/controller.php';
require_once JPATH_COMPONENT.'/helpers/icon.php';
require_once JPATH_COMPONENT.'/helpers/html.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'helpers/plugin.php';

// Initialize the controller
$controller	= new SimplelistsController( );
$controller->execute( null );

// Redirect if set by the controller
$controller->redirect();

