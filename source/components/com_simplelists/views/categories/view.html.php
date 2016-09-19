<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require some extra things
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php';

/**
 * HTML View class
 */
class SimplelistsViewCategories extends YireoView
{
	/*
	 * Method to prepare the content for display
	 *
	 * @param string $tpl
	 * @return null
	 */
	public function display($tpl = null)
	{
		// Fetch URL-variables
		$document = JFactory::getDocument();

		// Get some variables
		$parent_id = $this->params->get('parent_id');
		$layout    = $this->params->get('layout', 'default');
		$clayout   = $this->params->get('clayout', 'default');

		// Load the layout-specific stylesheet
		$this->addCss('view-categories-' . $clayout . '.css');

		// Get the parent-category from our model
		$model = $this->getModel();
		if (!empty($parent_id))
		{
			$model->setParent($parent_id);
		}
		$parent = $model->getParent();

		// Automatically fetch items, total and pagination - and assign them to the template
		$this->setAutoClean(false);
		$this->fetchItems();

		// Set the page title
		$page_title = $this->params->get('page_title');

		if (!empty($page_title))
		{
			$parent->title = $page_title;
		}

		if (!empty($parent->title))
		{
			$document->setTitle($parent->title);
		}

		// Loop through the list to set things right
		if (count($this->items) > 0)
		{
			foreach ($this->items as $id => $item)
			{

				if ($this->params->get('show_category_title') == 0)
				{
					$item->title = null;
				}

				if ($this->params->get('show_category_description') == 0)
				{
					$item->description = null;
				}

				$layout     = $item->params->get('layout', $layout);
				$needles    = array('category_id' => $item->id, 'category_alias' => $item->alias, 'layout' => $layout);
				$item->link = SimplelistsHelper::getUrl($needles);

				// Reinsert this item
				$this->items[$id] = $item;
			}
		}
		else
		{
			$this->message = JText::_('No categories found');
		}

		// prepare the image
		if ($this->params->get('show_category_image') && !empty($parent->image))
		{
			$parent->image = SimplelistsHTML::image($parent->image, $parent->title, 'title="' . $parent->title . '" class="simplelists" align="' . $parent->image_position . '"');
		}
		else
		{
			$parent->image = null;
		}

		// prepare the title
		if ($this->params->get('show_category_title') == 0)
		{
			$parent->title = null;
		}

		// prepare the description
		if ($this->params->get('show_category_description') == 0)
		{
			$parent->description = null;
		}

		$this->category = $parent;
		parent::display($tpl);
	}
}
