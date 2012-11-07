<?php
/**
 * Joomla! plugin SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2012
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
     * Load the parameters
     * 
     * @access private
     * @param null
     * @return JParameter
     */
    private function getParams()
    {
        if(YireoHelper::isJoomla15() == false) {
            return $this->params;
        } else {
            jimport('joomla.html.parameter');
            $plugin = JPluginHelper::getPlugin('content', 'simplelists');
            $params = new JParameter($plugin->params);
            return $params;
        }
    }

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
        $component_path = JPATH_SITE.DS.'components'.DS.'com_simplelists'.DS;
        $component_admin_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS;

        // Include all the required classes
        require_once $component_admin_path.'tables'.DS.'item.php';
        require_once $component_admin_path.'tables'.DS.'category.php';

        require_once $component_admin_path.'helpers'.DS.'helper.php';
        require_once $component_admin_path.'helpers'.DS.'plugin.php';

        require_once $component_path.'helpers'.DS.'icon.php';
        require_once $component_path.'helpers'.DS.'html.php';

        require_once $component_path.'models'.DS.'items.php';
        require_once $component_path.'views'.DS.'items'.DS.'view.html.php';

        // Create and initialize a model
        $model = new SimplelistsModelItems();
        $model->setId($arguments['id']);

        // Create and initialize a view
        $view = new SimplelistsViewItems(array('name' => 'items', 'option' => 'com_simplelists'));
        $view->addTemplatePath($component_path.'views'.DS.'items'.DS.'tmpl');
        $view->setModel($model, true);

        // Prepare and load the view
        $view->prepareDisplay();
        $content = $view->loadTemplate($view->getLayout());

        // Return the template-output
        return $content;
    }

    /**
     * Event onPrepareContent
     * 
     * @access public
     * @param object $row
     * @param JParameter $params
     * @param mixed $page
     * @return null
     */
	public function onPrepareContent( &$row, &$params, $limitstart )
	{
        // Only run this plugin in the frontend
        $application = JFactory::getApplication();
        if(!$application->isSite()) return;
        if(!class_exists('YireoHelper')) return;

        // Check for a {simplelists *} tag
        if(preg_match_all('/{simplelists([^}]+)}/', $row->text, $tags)) {

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

                // Replace the tag in the article content
                $row->text = str_replace($tags[0][$tagindex], $content, $row->text);
            }

        }

        return;
	}

    /**
     * Joomla! 1.6 alias
     * 
     * @access public
     * @param string $content
     * @param object $article
     * @param JParameter $params
     * @param mixed $limitstart
     * @return null
     */
    public function onContentPrepare($content, &$article, &$params, $limitstart)
    {
        $this->onPrepareContent($article, $params, $limitstart);
    }
}
