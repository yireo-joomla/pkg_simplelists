<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Define constants for all pages 
define('COM_SIMPLELISTS_DIR', 'images'.DS.'simplelists'.DS);
define('COM_SIMPLELISTS_BASE', JPATH_ROOT.DS.COM_SIMPLELISTS_DIR);
define('COM_SIMPLELISTS_BASEURL', JURI::root().str_replace(DS, '/', COM_SIMPLELISTS_DIR));

// Manage common includes
require_once JPATH_COMPONENT.DS.'helpers'.DS.'acl.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'plugin.php';

// Make sure the user is authorized to view this page
if(SimpleListsHelperAcl::isAuthorized() == false) {
    $application = JFactory::getApplication();
    $application->redirect('index.php', JText::_('COM_SIMPLELISTS_NOT_AUTHORIZED'));
}

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// General checks
SimplelistsHelper::checkDirectory();
SimplelistsHelper::checkVersions();

// Initialize the controller
$controller = new SimplelistsController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();

