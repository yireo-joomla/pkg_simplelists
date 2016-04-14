<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the general helper
require_once JPATH_COMPONENT . '/helpers/helper.php';

/**
 * Simplelists Controller
 */
class SimplelistsController extends YireoController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Default view
		$this->_default_view = 'home';

		// Call the parent
		parent::__construct();

		// Check for the Yireo Library
		YireoHelperInstall::autoInstallLibrary('yireo', 'https://www.yireo.com/documents/lib_yireo_j3x.zip', 'Yireo Library');

		// Remove existing files
		YireoHelperInstall::remove(array(JPATH_COMPONENT_ADMINISTRATOR . '/lib/'));

		// Check for the System Plugin
		YireoHelperInstall::autoInstallEnablePlugin('simplelists', 'system', 'https://www.yireo.com/documents/plg_system_simplelists_j3x.zip', JText::_('COM_SIMPLELISTS_SYSTEMPLUGIN'));

		// Redirect categories
		if (JRequest::getCmd('view') == 'categories' || JRequest::getCmd('view') == 'category')
		{
			$app = JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_categories&extension=com_simplelists&section=com_simplelists', false));
			$app->close();
		}
	}

	/**
	 * Method to set the current tab within the Joomla! session
	 */
	public function cookie()
	{
		if (JRequest::getCmd('name') != 'tab')
		{
			die('Access denied');
		}

		$tab = JRequest::getString('tab');
		$session = JFactory::getSession();
		$session->set('simplelists.item.tab', $tab);
	}

	/**
	 * Method to run SQL-update queries
	 */
	public function updateQueries()
	{
		// Run the update-queries
		require_once JPATH_COMPONENT . '/helpers/update.php';
		SimplelistsUpdate::runUpdateQueries();

		// Redirect
		$link = 'index.php?option=com_simplelists&view=home';
		$msg = JText::_('Applied database upgrades');
		$this->setRedirect($link, $msg);
	}
}
