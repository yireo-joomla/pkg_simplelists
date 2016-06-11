<?php
/**
 * Joomla! content-plugin parent-class for SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
jimport('joomla.plugin.plugin');

/**
 * SimpleLists Content Plugin Abstract
 */
class SimplelistsPluginContent extends JPlugin
{
	/**
	 * Load the parameters
	 *
	 * @return \Joomla\Registry\Registry
	 */
	private function getParams()
	{
		return $this->params;
	}

	/*
	 * Method to get the plugin name
	 *
	 * @return string
	 */
	public function getPluginName()
	{
		return $this->_name;
	}

	/*
	 * Plugin event when Menu-Item form is being generated
	 *
	 * @param JForm $form
	 * @param mixed $data
	 * @return string
	 */
	public function onSimpleListsContentPrepareForm($form, $data)
	{
		// Add the plugin-form to main form
		$formFile = JPATH_SITE . '/plugins/simplelistscontent/' . $this->_name . '/form.xml';

		if (file_exists($formFile))
		{
			$form->loadFile($formFile, false);
		}
	}
}
