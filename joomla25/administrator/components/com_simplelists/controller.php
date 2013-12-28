<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the general helper
require_once( JPATH_COMPONENT.'/helpers/helper.php' );

/**
 * Simplelists Controller
 */
class SimplelistsController extends YireoController
{
    /**
     * Constructor
     * 
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->_default_view = 'home';
        parent::__construct();

        // Redirect categories
        if (JRequest::getCmd('view') == 'categories' || JRequest::getCmd('view') == 'category') {
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_categories&extension=com_simplelists&section=com_simplelists', false));
            $app->close();
        }
    }

    /**
     * Method to set the current tab within the Joomla! session
     *
     * @access public 
     * @param null
     * @return null
     */
    public function cookie()
    {
        if(JRequest::getCmd('name') != 'tab') {
            die('Access denied');
        }

        $tab = JRequest::getString('tab');
        $session = JFactory::getSession();
        $session->set('simplelists.item.tab', $tab);
    }

    /**
     * Method to run SQL-update queries
     *
     * @access public 
     * @param null
     * @return null
     */
    public function updateQueries()
    {
        // Run the update-queries
        require_once JPATH_COMPONENT.'/helpers/update.php';
        SimplelistsUpdate::runUpdateQueries();

        // Redirect
        $link = 'index.php?option=com_simplelists&view=home';
        $msg = JText::_('Applied database upgrades');
        $this->setRedirect($link, $msg);
    }
}
