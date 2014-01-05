<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla! 
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
jimport('joomla.filter.output');

/**
 * HTML View class for the Simplelists component
 *
 * @static
 * @package	Simplelists
 */
class SimplelistsViewUpdate extends JView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        // Fetch common objects from JFactory
        $application = JFactory::getApplication() ;
		$db	= JFactory::getDBO();
		$user = JFactory::getUser();
        $document = JFactory::getDocument();

        $data = JApplicationHelper::parseXMLInstallFile(JPATH_COMPONENT.'/simplelists.xml');

        require_once JPATH_COMPONENT.'/helpers/update.php';
        $update = SimplelistsUpdate::getUpdate( $data['authorUrl'] );

        $this->assignRef('data', $data);
        $this->assignRef('update', $update);

		parent::display($tpl);
	}
}
