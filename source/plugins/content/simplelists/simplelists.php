<?php
/**
 * Joomla! plugin SimpleLists
 *
 * @author    Yireo (info@yireo.com)
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Load the Yireo library
jimport('yireo.loader');

/**
 * SimpleLists Content Plugin
 *
 * @since 1.6
 */
class PlgContentSimplelists extends JPlugin
{
	/**
	 * @var JApplicationCms
	 *
	 * @since 1.8
	 */
	protected $app;

	/**
	 * Function to load all necessary paths
	 *
	 * @since 1.8
	 */
	private function loadPaths()
	{
		// Construct the paths to SimpleLists
		$component_path       = JPATH_SITE . '/components/com_simplelists/';
		$component_admin_path = JPATH_ADMINISTRATOR . '/components/com_simplelists/';

		// Include all the required classes
		require_once $component_admin_path . 'tables/item.php';
		require_once $component_admin_path . 'tables/category.php';

		require_once $component_admin_path . 'helpers/helper.php';
		require_once $component_admin_path . 'helpers/plugin.php';

		require_once $component_path . 'helpers/icon.php';
		require_once $component_path . 'helpers/html.php';

		require_once $component_path . 'models/items.php';
		require_once $component_path . 'views/items/view.html.php';
	}

	/**
	 * Construct a SimpleLists output
	 *
	 * @param array $arguments
	 *
	 * @return string
	 * @since 1.6
	 */
	private function getSimpleLists($arguments)
	{
		$this->loadPaths();

		// Create and initialize a model
		$model = new SimplelistsModelItems();
		$model->setId($arguments['id']);

		// Create and initialize a view
		$view = new SimplelistsViewItems(array('name' => 'items', 'option' => 'com_simplelists'));
		$view->addTemplatePath(JPATH_SITE . '/components/com_simplelists/views/items/tmpl');
		$view->setModel($model, true);

		// Merge the category parameters
		$category = $model->getCategory();
		$viewParams = new \Joomla\Registry\Registry;

		if (isset($category->params))
		{
			$viewParams = $view->getParams();
			$viewParams->merge(YireoHelper::toRegistry($category->params));
		}

		// Prepare and load the view
		$viewParams->set('show_category_title', 0);
		$viewParams->set('load_css', 0);
		$view->setParams($viewParams);
		$view->prepareDisplay();
		$content = $view->loadTemplate($view->getLayout());

		// Return the template-output
		return $content;
	}

	/**
	 * Event onContentPrepare
	 *
	 * @param string                    $context
	 * @param object                    $item
	 * @param \Joomla\Registry\Registry $params
	 * @param mixed                     $page
	 *
	 * @return null
	 * @since 1.6
	 */
	public function onContentPrepare($context, &$item, $params, $page)
	{
		if (!$this->app->isSite())
		{
			return null;
		}

		if (!class_exists('YireoHelper'))
		{
			return null;
		}

		// Check for a {simplelists *} tag
		if (preg_match_all('/{simplelists([^}]+)}/', $item->text, $tags))
		{

			foreach ($tags[1] as $tagindex => $tag)
			{

				$content = null;

				if (preg_match_all('/([^\ ]+)=([^\ ]+)/', $tag, $matches))
				{

					$arguments = array();

					foreach ($matches[1] as $index => $match)
					{
						$name             = $match;
						$value            = preg_replace('/([\"\']+)/', '', $matches[2][$index]);
						$arguments[$name] = $value;
					}

					$content = $this->getSimpleLists($arguments);
				}

				// Replace the tag in the item content
				$item->text      = str_replace($tags[0][$tagindex], $content, $item->text);
				$item->fulltext  = str_replace($tags[0][$tagindex], $content, $item->fulltext);
				$item->introtext = str_replace($tags[0][$tagindex], $content, $item->introtext);
			}
		}

		return true;
	}
}
