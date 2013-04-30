<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2012
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
        $view = JRequest::getCmd('view');
        $Itemid = JRequest::getInt('Itemid');
        if(empty($view) && empty($Itemid)) {
            $app = JFactory::getApplication();
            $url = JURI::base();
            $app->redirect($url);
            $app->close();
            exit;
        }

        $this->_default_view = 'items';
        parent::__construct();
    }
}
