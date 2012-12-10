<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo (http://www.yireo.com/)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.5.1
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the parent view
require_once dirname(dirname(__FILE__)).'/loader.php';

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
     * Identifier of the library-view
     */
    protected $_viewParent = 'form';

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
        // Initialize tooltips
        JHTML::_('behavior.tooltip');

        // Automatically fetch the item and assign it to the layout
        $this->fetchItem();

        // Automatically load the parameters form
        $this->loadParametersForm();

        parent::display($tpl);
    }

    /*
     * Load the parameters form
     *
     * @access public
     * @param null
     * @return null
     */
    public function loadParametersForm()
    {
        // Initialize parameters
        $view = JRequest::getCmd('view');
        $file = JPATH_COMPONENT.'/models/'.$view.'.xml';
        if(file_exists($file) == false) {
            return false;
        }

        if(YireoHelper::isJoomla15()) {
            $params = YireoHelper::toRegistry($this->item->params, $file);
            $this->assignRef('params', $params);
        } else {
            $paramsForm = JForm::getInstance('params', $file);
            $this->assignRef('paramsForm', $paramsForm);
        }
        return true;
    }
}
