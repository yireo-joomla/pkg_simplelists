<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

/**
 * Simplelists Controller
 */
class SimplelistsController extends YireoController
{
    /**
     * Constructor
     * @access public
     * @package SimpleLists
     */
    public function __construct()
    {
        // Get URL-parameters
        $view = JRequest::getCmd('view');
        $Itemid = JRequest::getInt('Itemid');

        // Redirect to the base-URL if a view without Menu-Item is set
        if (empty($view) && empty($Itemid)) {
            $app = JFactory::getApplication();
            $url = JUri::base();
            $app->redirect($url);
            $app->close();
            exit;
        }

        // Set a default view
        $this->_default_view = 'items';

        // Parent constructor
        parent::__construct();
    }

	public function getModel($name = '', $prefix = '', $config = array())
    {
        // Deterine the ID for SimpleLists content
        $category_id = JRequest::getInt('category_id', '0');
        $plugin_name = null;
        $model_name = 'items';

        // Allow for additional plugins to determine the ID
        JPluginHelper::importPlugin('simplelistscontent');
        JFactory::getApplication()->triggerEvent('onSimpleListsContentGetId', array(&$category_id, &$plugin_name, &$model_name));

        // Override the model
        if(!empty($plugin_name)) {
            $modelFile = JPATH_SITE.'/plugins/simplelistscontent/'.$plugin_name.'/model.php';
            if(file_exists($modelFile)) include_once $modelFile;
            $tableFile = JPATH_SITE.'/plugins/simplelistscontent/'.$plugin_name.'/table.php';
            if(file_exists($tableFile)) include_once $tableFile;

            if(class_exists($model_name)) {
                $model = new $model_name();
                return $model;
            }
        }

        return parent::getModel($name, $prefix, $config);
    }
}
