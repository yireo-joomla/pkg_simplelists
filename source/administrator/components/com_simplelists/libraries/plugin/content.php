<?php
/**
 * Joomla! content-plugin parent-class for SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
jimport( 'joomla.plugin.plugin' );

/**
 * SimpleLists Content Plugin Abstract
 */ 
class SimplelistsPluginContent extends JPlugin
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

    /*
     * Method to get the plugin name
     *
     * @access public
     * @param null
     * @return string
     */
    public function getPluginName() 
    {
        return $this->_name;
    }

    /*
     * Plugin event when Menu-Item form is being generated
     *
     * @access public
     * @param JForm $form
     * @param mixed $data
     * @return string
     */
    public function onSimpleListsContentPrepareForm($form, $data)
    {
        // Add the plugin-form to main form
        $formFile = JPATH_SITE.'/plugins/simplelistscontent/'.$this->_name.'/form.xml';
        if(file_exists($formFile)) {
            $form->loadFile($formFile, false);
        }
    }
}
