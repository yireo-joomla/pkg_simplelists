<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
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
            $url = JURI::base();
            $app->redirect($url);
            $app->close();
            exit;
        }

        // Set a default view
        $this->_default_view = 'items';

        // Parent constructor
        parent::__construct();
    }
}
