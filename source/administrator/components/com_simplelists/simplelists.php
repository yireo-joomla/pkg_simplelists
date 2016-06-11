<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Define constants for all pages 
define('COM_SIMPLELISTS_DIR', 'images/simplelists/');
define('COM_SIMPLELISTS_BASE', JPATH_ROOT . '/' . COM_SIMPLELISTS_DIR);
define('COM_SIMPLELISTS_BASEURL', JUri::root() . COM_SIMPLELISTS_DIR);

// Load the Yireo library
jimport('yireo.loader');

// Check for helper
if (!class_exists('YireoHelperInstall'))
{
	require_once JPATH_COMPONENT . '/helpers/install.php';
	YireoHelperInstall::autoInstallLibrary('yireo', 'https://www.yireo.com/documents/lib_yireo_j3x.zip', 'Yireo Library');
	$application = JFactory::getApplication();
	$application->redirect('index.php?option=com_installer');
	$application->close();
}

// Check for function
if (!class_exists('\Yireo\System\Autoloader'))
{
	die('Yireo Library is not installed and could not be installed automatically');
}

// Manage common includes
require_once JPATH_COMPONENT . '/helpers/acl.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/plugin.php';

// Make sure the user is authorized to view this page
if (SimpleListsHelperAcl::isAuthorized() == false)
{
	$application = JFactory::getApplication();
	$application->redirect('index.php', JText::_('COM_SIMPLELISTS_NOT_AUTHORIZED'));
}

// Require the base controller
require_once JPATH_COMPONENT . '/controller.php';

// General checks
SimplelistsHelper::checkDirectory();
SimplelistsHelper::checkVersions();

// Initialize the controller
$controller = new SimplelistsController;

// Perform the Request task
$app = JFactory::getApplication();
$controller->execute($app->input->getCmd('task'));
$controller->redirect();

