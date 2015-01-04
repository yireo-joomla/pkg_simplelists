<?php
/**
 * Joomla! plugin SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die();

// Import the parent class
jimport( 'joomla.plugin.plugin' );

// Import the Yireo loader
include_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/loader.php';

/**
 * SimpleLists Content Plugin
 */
class plgContentSimplelists extends JPlugin
{
    /**
     * Construct a SimpleLists output
     * 
     * @access private
     * @param array $arguments
     * @return text
     */
    private function getSimpleLists($arguments)
    {
        // Construct the paths to SimpleLists
        $component_path = JPATH_SITE.'/components/com_simplelists/';
        $component_admin_path = JPATH_ADMINISTRATOR.'/components/com_simplelists/';

        // Include all the required classes
        require_once $component_admin_path.'tables/item.php';
        require_once $component_admin_path.'tables/category.php';

        require_once $component_admin_path.'helpers/helper.php';
        require_once $component_admin_path.'helpers/plugin.php';

        require_once $component_path.'helpers/icon.php';
        require_once $component_path.'helpers/html.php';

        require_once $component_path.'models/items.php';
        require_once $component_path.'views/items/view.html.php';

        // Create and initialize a model
        $model = new SimplelistsModelItems();
        $model->setId($arguments['id']);

        // Create and initialize a view
        $view = new SimplelistsViewItems(array('name' => 'items', 'option' => 'com_simplelists'));
        $view->addTemplatePath($component_path.'views/items/tmpl');
        $view->setModel($model, true);

       // Merge the category parameters
        $category = $model->getCategory();
        if(isset($category->params)) {
            $view->params->merge(YireoHelper::toRegistry($category->params));
        }

        // Prepare and load the view
        $view->params->set('show_category_title', 0);
        $view->params->set('load_css', 0);
        $view->prepareDisplay();
        $content = $view->loadTemplate($view->getLayout());

        // Return the template-output
        return $content;
    }

    /**
     * Event onContentPrepare
     * 
     * @access public
     * @param string $context
     * @param object $item
     * @param JParameter $params
     * @param mixed $page
     * @return null
     */
    public function onContentPrepare($context, &$item, $params, $page)
	{
        // Only run this plugin in the frontend
        $application = JFactory::getApplication();
        if(!$application->isSite()) return;
        if(!class_exists('YireoHelper')) return;

        // Check for a {simplelists *} tag
        if(preg_match_all('/{simplelists([^}]+)}/', $item->text, $tags)) {

            foreach($tags[1] as $tagindex => $tag) {

                $content = null;

                if(preg_match_all('/([^\ ]+)=([^\ ]+)/', $tag, $matches)) {

                    $arguments = array();
                    foreach($matches[1] as $index => $match) {
                        $name = $match;
                        $value = preg_replace('/([\"\']+)/', '', $matches[2][$index]);
                        $arguments[$name] = $value;
                    }

                    $content = $this->getSimpleLists($arguments);
                }

                // Replace the tag in the item content
                $item->text = str_replace($tags[0][$tagindex], $content, $item->text);
                $item->fulltext = str_replace($tags[0][$tagindex], $content, $item->fulltext);
                $item->introtext = str_replace($tags[0][$tagindex], $content, $item->introtext);
            }
        }

        return true;
	}
}
