<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo (http://www.yireo.com/)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.5.0
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the parent view
require_once dirname(dirname(__FILE__)).'/view.php';

// Import the needed libraries
jimport('joomla.filter.output');

/**
 * Form View class
 *
 * @package Yireo
 */
class YireoViewForm extends YireoView
{
    /*
     * Flag to determine whether this view is a single-view
     */
    protected $_single = true;

    /*
     * Main constructor method
     *
     * @access public
     * @subpackage Yireo
     * @param null
     * @return null
     */
    public function __construct()
    {
        // Template-paths
        $this->templatePaths[] = dirname(__FILE__).'/form';
    
        // Do not load the toolbar automatically
        //$this->loadToolbar = false;

        // Call the parent constructor
        return parent::__construct();
    }

    /*
     * Main display method
     *
     * @access public
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Hide the menu
        JRequest::setVar('hidemainmenu', 1);
    
        // Initialize tooltips
        JHTML::_('behavior.tooltip');

        // Automatically fetch the item and assign it to the layout
        $this->fetchItem();

        parent::display($tpl);
    }
}
