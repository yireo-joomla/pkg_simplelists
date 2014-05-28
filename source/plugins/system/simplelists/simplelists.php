<?php
/**
 * Joomla! plugin SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die();

// Import the parent class
jimport( 'joomla.plugin.plugin' );

/**
 * SimpleLists System Plugin
 */
class plgSystemSimplelists extends JPlugin
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
        return $this->params;
    }

    /**
     * Plugin event when this form is being prepared
     *
     * @param JForm $form
     * @param array $data
     * @return null
     */
    public function onContentPrepareForm($form, $data)
    {
        // Check we have a form
        if (!($form instanceof JForm)) {
            $this->_subject->setError('JERROR_NOT_A_FORM');
            return;
        }

        // Check for the backend
        $app = JFactory::getApplication();
        if($app->isAdmin() == false) {
            return;
        }

        // Skip this for non-Menu-Item pages
        if(JRequest::getCmd('option') != 'com_menus') { 
            return;
        }

        // Skip this for non-Menu-Item pages
        if(JRequest::getCmd('view') != 'item' && JRequest::getCmd('task') != 'apply') {
            return;
        }

        // Make sure this only works for SimpleLists Items Menu-Items
        if (is_array($data)) $data = (object)$data;
        if (!isset($data->link) || strstr($data->link, 'index.php?option=com_simplelists&view=items') == false) {
            return;
        }
        
        // Add the plugin-form to main form
        $formFile = dirname(__FILE__).'/form/form.xml';
        if(file_exists($formFile)) {
            $form->loadFile($formFile, false);
        }

        // Allow for additional plugins to change the form
        JPluginHelper::importPlugin('simplelistscontent');
        JFactory::getApplication()->triggerEvent('onSimpleListsContentPrepareForm', array(&$form, $data));

        return true;
    }
}
