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

        // Modify the form for Menu-Items
        $this->modifyMenuItemForm($form, $data);

        // Modify the form for Menu-Items
        $this->modifyCategoryForm($form, $data);

        return true;
    }

    public function modifyMenuItemForm($form, $data)
    {
        // Skip this for non-Menu-Item pages
        if(JRequest::getCmd('option') != 'com_menus') { 
            return;
        }

        // Skip this for non-Menu-Item pages
        $allowedTasks = array('apply', 'item.apply', 'save', 'item.save');
        if(JRequest::getCmd('view') != 'item' && !in_array(JRequest::getCmd('task'), $allowedTasks)) {
            return;
        }

        // Make sure this only works for SimpleLists Items Menu-Items
        if (is_array($data)) $data = (object)$data;
        if (!isset($data->link) || strstr($data->link, 'index.php?option=com_simplelists&view=items') == false) {
            return;
        }
        
        // Add the plugin-form to main form
        $formFile = dirname(__FILE__).'/form/menuitem.xml';
        if(file_exists($formFile)) {
            $form->loadFile($formFile, false);
        }

        // Allow for additional plugins to change the form
        JPluginHelper::importPlugin('simplelistscontent');
        JFactory::getApplication()->triggerEvent('onSimpleListsContentPrepareForm', array(&$form, $data));

        return true;
    }

    public function modifyCategoryForm($form, $data)
    {
        // Skip this for non-category pages
        if(JRequest::getCmd('option') != 'com_categories') { 
            return;
        }

        // Skip this for non-SL pages
        if(JRequest::getCmd('extension') != 'com_simplelists') { 
            return;
        }

        // Skip this for non-category pages
        $allowedTasks = array('apply', 'category.apply', 'save', 'category.save');
        if(JRequest::getCmd('view') != 'category' && !in_array(JRequest::getCmd('task'), $allowedTasks)) {
            return;
        }

        // Add the plugin-form to main form
        $formFile = dirname(__FILE__).'/form/category.xml';
        if(file_exists($formFile)) {
            $form->loadFile($formFile, false);
        }

        return true;
    }
}
