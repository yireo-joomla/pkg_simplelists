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

// Check to ensure this file is included in Joomla! 
defined('_JEXEC') or die();

// Import the needed helpers
require_once JPATH_COMPONENT . '/helpers/html.php';

/**
 * HTML View class for the Simplelists component
 *
 * @static
 * @package    Simplelists
 */
class SimplelistsViewItem extends YireoViewForm
{
	/*
	 * Method to prepare the content for display
	 *
	 * @param string $tpl
	 */
	public function display($tpl = null)
	{
		// Give a warning if no categories are configured
		SimplelistsHelper::checkCategories();

		// Load jQuery and bootstrap
		YireoHelper::jquery();
		YireoHelper::bootstrap();

		// Fetch the item automatically
		$this->fetchItem();

		// Modify the item a bit
		$this->item->image_default_folder = COM_SIMPLELISTS_DIR;
		$this->item->image_default_uri = COM_SIMPLELISTS_BASEURL;

		$this->item->picture_folder = $this->item->image_default_folder;
		$this->item->picture_uri = $this->item->image_default_uri;
		$this->item->picture_path = null;

		if (!empty($this->item->picture))
		{
			$this->item->picture_path = JPATH_SITE . '/' . $this->item->picture;
			$this->item->picture_uri = $this->item->picture;
			$this->item->picture_folder = dirname($this->item->picture_uri);
		}

		// Add extra filtering lists
		$defaultCategory = ($this->item->id == 0) ? (int) $this->getFilter('category_id', null, null, 'com_simplelists_items_') : null;
		$categories_params = array('item_id' => $this->item->id, 'multiple' => 1, 'current' => $defaultCategory);
		$this->lists['categories'] = SimplelistsHTML::selectCategories('categories[]', $categories_params);

		// Construct the modal boxes
		$this->modal = array();
		$this->modal['picture'] = 'index.php?option=com_simplelists&amp;view=files&amp;tmpl=component&amp;type=picture&amp;current=' . $this->item->picture;

		if ($this->item->picture)
		{
			$this->modal['picture'] .= '&amp;folder=' . $this->item->picture_folder;
		}

		// Construct the slider-panel
		jimport('joomla.html.pane');

		if (class_exists('JPane'))
		{
			$this->pane = JPane::getInstance('sliders');
		}
		else
		{
			$this->pane = null;
		}

		// Fetch the selected tab
		$session = JFactory::getSession();
		$this->activeTab = $session->set('simplelists.item.tab');

		if (empty($this->activeTab))
		{
			$this->activeTab = 'basic';
		}

		// Load the plugins
		$this->link_plugins = SimplelistsPluginHelper::getPlugins('simplelistslink');

		// Add extra stuff
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.modal', 'a.modal-button');

		parent::display($tpl);
	}
}
