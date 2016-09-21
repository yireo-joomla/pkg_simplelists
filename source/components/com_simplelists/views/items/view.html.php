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

/**
 * HTML View class
 */
class SimplelistsViewItems extends YireoViewList
{
	/**
	 * Method to display the content
	 *
	 * @param string $tpl
	 *
	 * @return null;
	 */
	public function display($tpl = null)
	{
		$this->prepareDisplay();

		parent::display($this->getLayout());
	}

	/**
	 * Return a common prefix for all layouts in this component
	 *
	 * @return string
	 */
	public function getLayoutPrefix()
	{
		$layout_type = $this->params->get('layout_type', 'legacy');

		return 'com_simplelists.items.' . $layout_type . '.';
	}

	/**
	 * Method to prepare for displaying (used by SimpleLists views but also SimpleLists Content Plugin)
	 */
	public function prepareDisplay()
	{
		// Get important system variables
		$uri = JUri::getInstance();

		// Determine the current layout
		if ($this->params->get('layout') != '')
		{
			$layout = $this->params->get('layout');
			$this->setLayout($layout);
		}
		elseif ($this->input->getCmd('layout') != '')
		{
			$layout = $this->input->getCmd('layout');
			$this->setLayout($layout);
		}
		else
		{
			$layout = $this->getLayout();
		}

		// Load the model
		$model = $this->getModel();

		// Get the category from our model and prepare it
		$category = $model->getCategory();
		$this->prepareCategory($category, $layout);

		// Prepare the HTML-document
		$this->prepareDocument($category);

		// Automatically fetch items, total and pagination - and assign them to the template
		$this->setAutoClean(false);
		$this->fetchItems();

		// Set the URL of this page
		$url = $uri->toString();

		// Set the to-top image
		if ((bool) $this->params->get('show_totop'))
		{
			if ($this->params->get('totop_text'))
			{
				$totop_text = $this->params->get('totop_text');
			}
			else
			{
				$totop_text = JText::_('Top');
			}

			$totop = null;

			if ($this->params->get('totop_image') && is_file(JPATH_SITE . '/images/simplelists/' . $this->params->get('totop_image')))
			{
				$totop_image = SimplelistsHTML::image('images/simplelists/' . $this->params->get('totop_image'), $totop_text);
				$totop_image = JHtml::link($url . '#top', $totop_image, 'class="totop"');
				$totop .= $totop_image;
			}

			if ($this->params->get('totop_text'))
			{
				$totop_text = '<span class="totop_text">' . $totop_text . '</span>';
				$totop_text = JHtml::link($url . '#top', $totop_text, 'class="totop"');
				$totop .= $totop_text;
			}
		}
		else
		{
			$totop = null;
		}

		// Determine whether to show the "No Items" message
		if ($this->params->get('show_empty_list'))
		{
			$empty_list = JText::_('No items found');
		}
		else
		{
			$empty_list = null;
		}

		// Check if the list is empty
		if (is_array($this->items) && !empty($this->items))
		{
			// Loop through the list to set things right
			$counter = 1;

			foreach ($this->items as $id => $item)
			{
				// Append category-data
				$item->category_id    = $category->id;
				$item->category_alias = $category->alias;

				// Prepare each item
				$item = $this->prepareItem($item, $layout, $counter, count($this->items));

				// Remove items that are empty
				if ($item === false)
				{
					unset($this->items[$id]);
					break;
				}

				// Save the item in the array
				$this->items[$id] = $item;
				$counter++;
			}
		}

		$this->loadCssJs($layout);

		// Assign all variables to this layout
		$this->category   = $category;
		$this->totop      = $totop;
		$this->empty_list = $empty_list;
		$this->url        = $url;
		$this->page_class = $this->getPageClass($layout);

		// Call the parent method
		parent::prepareDisplay();

		$this->prepare_display = false;
	}

	/**
	 * @param $layout
	 *
	 * @return string
	 */
	protected function getPageClass($layout)
	{
		// Construct the page class
		$page_class = 'simplelists simplelists-' . $layout;

		if ($this->params->get('pageclass_sfx'))
		{
			$page_class .= ' simplelists' . $this->params->get('pageclass_sfx');
		}

		return $page_class;
	}

	/**
	 * @return bool
	 */
	protected function loadFeed()
	{
		if (!is_array($this->items) || empty($this->items))
		{
			return false;
		}

		// Add feeds to the document
		if ($this->params->get('show_feed') == 1)
		{
			$link = '&format=feed&limitstart=';
			$this->doc->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', array(
				'type'  => 'application/rss+xml',
				'title' => 'RSS 2.0'
			));

			$this->doc->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', array(
				'type'  => 'application/atom+xml',
				'title' => 'Atom 1.0'
			));
		}
	}

	/**
	 * @param $layout
	 */
	protected function loadCssJs($layout)
	{
		// Load the default CSS only, if set through the parameters
		if ($this->params->get('load_css', 1))
		{
			$this->loadCSS($layout);
		}

		// Load the default JavaScript only, if set through the parameters
		if ($this->params->get('load_js', 1))
		{
			$this->loadJS($layout);
		}

		// Load the lightbox only, if set through the parameters
		if ($this->params->get('load_lightbox'))
		{
			JHtml::_('behavior.modal', 'a.lightbox');
		}
	}

	/**
	 * Method to prepare the category
	 *
	 * @param object $category
	 * @param string $layout
	 *
	 * @return null
	 */
	public function prepareCategory($category, $layout)
	{
		// Sanity check
		if (!is_object($category))
		{
			return null;
		}

		// Convert the parameters to an object
		$category->params = YireoHelper::toParameter($category->params);
		$params           = clone($this->params);

		// Override the default parameters with the category parameters
		foreach ($category->params->toArray() as $name => $value)
		{
			if ($value != '')
			{
				$params->set($name, $value);
			}
		}

		// Override the layout
		$layout = $category->params->get('layout');

		if (!empty($layout))
		{
			$this->setLayout($layout);
		}

		$this->prepareCategoryParent($category);
		$this->prepareCategoryChilds($category, $layout);
		$this->prepareCategoryTitle($category, $params);
		$this->prepareCategoryText($category, $params);
		$this->prepareCategoryImage($category, $params);
	}

	/**
	 * @param $category
	 */
	protected function prepareCategoryParent(&$category)
	{
		// Prepare the category URL
		if ((bool) $this->params->get('show_category_parent') && !empty($category->parent))
		{
			if ($category->parent->id > 1)
			{
				$needles = array('category_id' => $category->parent->id);

				if (isset($category->parent->alias))
				{
					$needles['category_alias'] = $category->parent->alias;
				}

				$category->parent->link = SimplelistsHelper::getUrl($needles);
			}
			else
			{
				$category->parent = null;
			}
		}
	}

	/**
	 * @param $category
	 * @param $layout
	 * @param $needles
	 */
	protected function prepareCategoryChilds(&$category, $layout)
	{
		// Loop through the child-categories
		if ((bool) $this->params->get('show_category_childs') && !empty($category->childs))
		{
			foreach ($category->childs as &$child)
			{
				$child->params = YireoHelper::toParameter($child->params);
				$child_layout  = $child->params->get('layout', $layout);

				$needles       = array(
					'category_id'    => $child->id,
					'category_alias' => $child->alias,
					'layout'         => $child_layout
				);

				$child->link   = SimplelistsHelper::getUrl($needles);
			}
		}
	}

	/**
	 * @param $category
	 * @param $params \Joomla\Registry\Registry
	 */
	protected function prepareCategoryTitle(&$category, $params)
	{
		// Set the correct page-title
		if ($params->get('show_page_title') == 1 && $params->get('page_title') != '')
		{
			$category->title = $params->get('page_title');
		}
	}

	/**
	 * @param $category
	 * @param $params \Joomla\Registry\Registry
	 */
	protected function prepareCategoryImage(&$category, $params)
	{
		// Prepare the category image
		if ($params->get('show_category_image') && isset($category->image) && !empty($category->image))
		{
			if (file_exists(JPATH_SITE . '/images/stories/' . $category->image))
			{
				$category->image = 'images/stories/' . $category->image;
			}

			$category->picture = SimplelistsHTML::image($category->picture, $category->title);
		}
		else
		{
			$params->set('show_category_image', 0);
		}
	}

	/**
	 * @param $category
	 * @param $params \Joomla\Registry\Registry
	 */
	protected function prepareCategoryText(&$category, $params)
	{
		// Run the category content through Content Plugins
		if ($params->get('show_category_description') && !empty($category->description))
		{
			$category->text = $category->description;
			$this->firePlugins($category, array());
			$category->description = $category->text;
			$category->text        = null;
		}
	}

	/**
	 * Method to prepare a specific item
	 *
	 * @param object $item
	 * @param string $layout
	 * @param int    $counter
	 * @param int    $total
	 *
	 * @return object
	 */
	public function prepareItem($item, $layout, $counter = 1, $total = 0)
	{
		$this->prepareItemParams($item);

		// Disable the text when needed
		if ((bool) $item->params->get('show_item_text', 1) === false)
		{
			$item->text = null;
		}

		// Run the content through Content Plugins
		if (!empty($item->text) && (bool) $item->params->get('enable_content_plugins', 1))
		{
			$item->text = JHtml::_('content.prepare', $item->text);
		}

		// Prepare the URL
		$item->url = JRoute::_(SimplelistsPluginHelper::getPluginLinkUrl($item));

		if ($item->alias)
		{
			$item->href = $item->alias;
		}
		else
		{
			$item->href = 'item' . $item->id;
		}

		// Construct the CSS class
		$classes     = $this->buildCssClasses($item, $counter, $total);
		$item->class = implode(' ', $classes);

		// Prepare the item
		$this->prepareItemTarget($item);
		$this->prepareItemReadmore($item);
		$this->prepareItemImage($item, $layout, $counter);
		$this->prepareItemTitle($item);
		$this->prepareItemStyle($item, $layout, $counter);
		$this->runPluginsOnItem($item);

		return $item;
	}

	/**
	 * @param $item
	 */
	protected function prepareItemParams(&$item)
	{
		// Initialize the parameters
		if ($item->params)
		{
			$p = clone($this->params);
			$p->merge($item->params);
			$item->params = $p;
		}
	}

	/**
	 * @param $item
	 */
	protected function prepareItemTarget(&$item)
	{
		switch ($item->params->get('target'))
		{
			case 1:
				$item->target = ' target="_blank"';
				break;
			case 2:
				$jsOptions    = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550';
				$item->target = ' onclick="javascript: window.open(\'' . $item->url . '\', \'\', \'' . $jsOptions . '\'); return false"';
				break;
			default:
				$item->target = false;
				break;
		}
	}

	/**
	 * @param $item
	 */
	protected function prepareItemReadmore(&$item)
	{
		if ($item->params->get('readmore') == 1 && $item->url)
		{
			$readmore_text  = $item->params->get('readmore_text', JText::sprintf('Read more', $item->title));
			$readmore_css   = trim('readon ' . $item->params->get('readmore_class', ''));
			$item->readmore = JHtml::link($item->url, $readmore_text, 'title="' . $item->title . '" class="' . $readmore_css . '"' . $item->target);
		}
		else
		{
			$item->readmore = false;
		}
	}

	/**
	 * @param $item
	 * @param $layout
	 * @param $counter
	 */
	protected function prepareItemImage(&$item, $layout, $counter)
	{
		if ($item->params->get('picture_alignment') != '' && $layout != 'picture')
		{
			$picture_alignment = $item->params->get('picture_alignment');

			if ($picture_alignment == 'toggle')
			{
				$picture_alignment = ($counter % 2 == 0) ? 'right' : 'left';
			}

			$item->picture_alignment = $picture_alignment;
		}
		else
		{
			$item->picture_alignment = false;
		}

		if ((bool) $item->params->get('show_item_image', 1) === false)
		{
			$item->picture = null;

			return;
		}

		if (empty($item->picture))
		{
			return;
		}

		// @todo: Add a class "img-polaroid"
		$attributes = 'title="' . $item->title . '" class="simplelists"';

		if ($item->picture_alignment)
		{
			$attributes .= ' align="' . $item->picture_alignment . '"';
		}

		$item->picture = SimplelistsHTML::image($item->picture, $item->title, $attributes);

		if ($item->picture && $item->params->get('image_link') && !empty($item->url))
		{
			if ($item->params->get('link_class') != '')
			{
				$item_link_class = ' class="' . $item->params->get('link_class') . '"';
			}
			else
			{
				$item_link_class = '';
			}

			if ($item->params->get('link_rel') != '')
			{
				$item_link_rel = ' rel="' . $item->params->get('link_rel') . '"';
			}
			else
			{
				$item_link_rel = '';
			}

			if (!empty($item->title))
			{
				$title = $item->title;

				if (!empty($item->text))
				{
					//$title .= ' :: ' . $item->text;
				}
			}
			else
			{
				$title = $item->target;
			}

			$title = htmlentities($title);

			$item->picture = JHtml::link($item->url, $item->picture, 'title="' . $title . '"' . $item->target . $item_link_class . $item_link_rel);
		}
	}

	/**
	 * @param $item
	 */
	protected function prepareItemTitle(&$item)
	{
		if (!$item->params->get('show_item_title', 1))
		{
			$item->title = null;

			return;
		}

		if ($item->params->get('title_link') && !empty($item->url))
		{
			$item->title = JHtml::link($item->url, $item->title, $item->target);
		}
	}

	/**
	 * @param $item
	 */
	protected function runPluginsOnItem(&$item)
	{
		// Enable parsing the content
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$results    = $dispatcher->trigger('onBeforeDisplayContent', array(&$item, &$item->params, 0));

		foreach ($results as $result)
		{
			if (!empty($result))
			{
				$item->text .= $result;
			}
		}
	}

	/**
	 * Prepare the items style attribute
	 *
	 * @param $item
	 * @param $layout
	 * @param $counter
	 */
	protected function prepareItemStyle(&$item, $layout, $counter)
	{
		// Set specific layout settings
		$item->style = '';

		if ($layout == 'select' || $layout == 'hover')
		{
			if ($counter == 1)
			{
				$item->style = 'display:block';
			}
		}
	}

	/**
	 * @param $item
	 * @param $counter
	 *
	 * @return array
	 */
	protected function buildCssClasses($item, $counter, $total)
	{
		$classes   = array('simplelists-item');
		$classes[] = 'simplelists-item-' . $counter;
		$classes[] = ($counter % 2 == 0) ? 'simplelists-item-even' : 'simplelists-item-odd';

		if ($item->params->get('new') == 1)
		{
			$classes[] = 'simplelists-item-new';
		}

		if ($item->params->get('featured') == 1)
		{
			$classes[] = 'simplelists-item-featured';
		}

		if ($item->params->get('popular') == 1)
		{
			$classes[] = 'simplelists-item-popular';
		}

		if ($item->params->get('approved') == 1)
		{
			$classes[] = 'simplelists-item-approved';
		}

		if ($counter == 1)
		{
			$classes[] = 'simplelists-item-first';
		}

		if ($counter == $total)
		{
			$classes[] = 'simplelists-item-last';
		}

		return $classes;
	}

	/**
	 * Method to load CSS depending on the layout
	 *
	 * @param string $layout
	 *
	 * @return null
	 */
	protected function loadCSS($layout)
	{
		$sheet = 'layout-' . $layout . '.css';
		$this->addCss($sheet);
	}

	/**
	 * Method to load JavaScript depending on the layout
	 *
	 * @param string $layout
	 *
	 * @return null
	 */
	protected function loadJS($layout)
	{
		switch ($layout)
		{
			case 'hover':
			case 'select':
				YireoHelper::jquery();

				if (YireoHelper::isJoomla25())
				{
					$this->addJs('bootstrap.min.js');
				}

				$script = 'layout-' . $layout . '.js';
				break;

			case 'toggle':
				YireoHelper::jquery();

				if (YireoHelper::isJoomla25())
				{
					$this->addJs('bootstrap.min.js');
				}

				if (YireoHelper::isJoomla25())
				{
					$script = 'layout-' . $layout . '.js';
				}
				break;

			default:
				$script = 'layout-default.js';
				break;
		}

		if (!empty($script))
		{
			$this->addJs($script);
		}
	}

	/**
	 * Method to fire plugins on a certain item
	 *
	 * @param object $row
	 *
	 * @return null
	 */
	protected function firePlugins(&$row = null, $params = array())
	{
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('content');
		$dispatcher->trigger('onPrepareContent', array(&$row, &$params, 0));
	}

	/**
	 * Method to prepare the HTML-document for display
	 *
	 * @param object $category
	 *
	 * @return void
	 */
	protected function prepareDocument($category)
	{
		// Set the page title
		if ($this->input->getCmd('option') == 'com_simplelists')
		{
			$page_title = $this->params->get('page_title');

			if (!empty($page_title))
			{
				$this->doc->setTitle($page_title);
			}
			elseif (!empty($category->title))
			{
				$this->doc->setTitle($category->title);
			}
		}

		// Set META information
		$this->addMetaTags($category);
		$this->addPathway($category);
	}

	/**
	 * Method to load META-tags in the HTML header
	 *
	 * @param object $category
	 *
	 * @return null
	 */
	protected function addMetaTags($category)
	{
		// Sanity check
		if (!is_object($category))
		{
			return null;
		}

		// Define the parameters
		/** @var \Joomla\Registry\Registry $params */
		$params = $category->params;

		$meta_description = $params->get('description');

		if (!empty($meta_description))
		{
			$this->doc->setDescription($meta_description);
		}

		$meta_keywords = $params->get('keywords');

		if (!empty($meta_keywords))
		{
			$this->doc->setMetadata('keywords', $meta_keywords);
		}

		$meta_author = $params->get('author');

		if (!empty($meta_author))
		{
			$this->doc->setMetadata('author', $meta_author);
		}
	}

	/**
	 * Method to add items to the breadcrumbs (pathway)
	 *
	 * @param object|array $category
	 */
	protected function addPathway($category)
	{
		if (!is_object($category))
		{
			return;
		}

		$pathway = $this->app->getPathway();

		if ($category->parent_id > 1)
		{
			$pathway->addItem($category->title);
			$parent = SimplelistsHelper::getCategory($category->parent_id);
			$this->addPathway($parent);
		}
	}

	/**
	 * Method to determine how many items starting with the letter X
	 *
	 * @param string $character
	 *
	 * @return boolean
	 */
	public function getCharacterCount($character = null)
	{
		static $characters = null;

		if (!is_array($characters))
		{
			$characters = array();

			$model = new SimplelistsModelItems;
			$model->setLimitQuery(false);
			$model->setState('no_char_filter', 1);
			$model->initLimit(1000);
			$model->initLimitstart(0);
			$rows = $model->getData();

			if (!empty($rows))
			{
				foreach ($rows as $row)
				{
					$c = substr(strtolower(trim($row->title)), 0, 1);

					if (isset($characters[$c]))
					{
						$characters[$c]++;
					}
					else
					{
						$characters[$c] = 1;
					}
				}
			}
		}

		if (isset($characters[$character]))
		{
			return $characters[$character];
		}

		return 0;
	}

	/**
	 * @param $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

	/**
	 * @return \Joomla\Registry\Registry
	 */
	public function getParams()
	{
		return $this->params;
	}
}
