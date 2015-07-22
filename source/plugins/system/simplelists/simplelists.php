<?php
/**
 * Joomla! plugin SimpleLists
 *
 * @author    Yireo (info@yireo.com)
 * @package   SimpleLists
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Import the parent class
jimport('joomla.plugin.plugin');

/**
 * SimpleLists System Plugin
 */
class PlgSystemSimplelists extends JPlugin
{
	/**
	 * Plugin event when this form is being prepared
	 *
	 * @param JForm $form
	 * @param array $data
	 *
	 * @return null
	 */
	public function onContentPrepareForm($form, $data)
	{
		// Check we have a form
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return;
		}

		// Check for the backend
		$app = JFactory::getApplication();
		
		if ($app->isAdmin() == false)
		{
			return;
		}
		
		$this->app = JFactory::getApplication();
		$this->input = $app->input;

		// Modify the form for Menu-Items
		$this->modifyMenuItemForm($form, $data);

		// Modify the form for Menu-Items
		$this->modifyCategoryForm($form, $data);

		return true;
	}

	/**
	 * Method to modify the Menu-Item form
	 * 
	 * @param $form
	 * @param $data
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function modifyMenuItemForm($form, $data)
	{
		// Skip this for non-Menu-Item pages
		if ($this->input->getCmd('option') != 'com_menus')
		{
			return false;
		}

		// Skip this for non-Menu-Item pages
		$allowedTasks = array('apply', 'item.apply', 'save', 'item.save');

		if ($this->input->getCmd('view') != 'item' && !in_array($this->input->getCmd('task'), $allowedTasks))
		{
			return false;
		}

		// Make sure this only works for SimpleLists Items Menu-Items
		if (is_array($data))
		{
			$data = (object) $data;
		}

		if (!isset($data->link) || strstr($data->link, 'index.php?option=com_simplelists&view=items') == false)
		{
			return false;
		}

		// Add the plugin-form to main form
		$formFile = dirname(__FILE__) . '/form/menuitem.xml';

		if (file_exists($formFile))
		{
			$form->loadFile($formFile, false);
		}

		// Add additional JS
		JHtml::_('jquery.framework');
		JHtml::script(JUri::root() . 'media/com_simplelists/js/backend-menuitem.js');

		// Allow for additional plugins to change the form
		JPluginHelper::importPlugin('simplelistscontent');
		JFactory::getApplication()->triggerEvent('onSimpleListsContentPrepareForm', array(&$form, $data));

		return true;
	}

	/**
	 * Method to modify the category form
	 * 
	 * @param $form
	 * @param $data
	 *
	 * @return bool
	 */
	public function modifyCategoryForm($form, $data)
	{
		// Skip this for non-category pages
		if ($this->input->getCmd('option') != 'com_categories')
		{
			return false;
		}

		// Skip this for non-SL pages
		if ($this->input->getCmd('extension') != 'com_simplelists')
		{
			return false;
		}

		// Skip this for non-category pages
		$allowedTasks = array('apply', 'category.apply', 'save', 'category.save');

		if ($this->input->getCmd('view') != 'category' && !in_array($this->input->getCmd('task'), $allowedTasks))
		{
			return false;
		}

		// Add the plugin-form to main form
		$formFile = dirname(__FILE__) . '/form/category.xml';

		if (file_exists($formFile))
		{
			$form->loadFile($formFile, false);
		}

		return true;
	}
}
