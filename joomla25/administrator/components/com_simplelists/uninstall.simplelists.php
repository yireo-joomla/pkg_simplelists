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

// Check if there are any items configured
$query = 'SELECT * FROM #__simplelists';
$db =& JFactory::getDBO();
$db->setQuery($query);
$rows = $db->loadObjectList();

if(empty($rows)) {
    $delete_queries = array(
        'DROP TABLE #__simplelists',
        'DROP TABLE #__simplelists_categories',
        'DELETE FROM #__categories WHERE section = "com_simplelists"',
    );
    foreach( $delete_queries as $query ) {
        $db->setQuery( $query );
        $db->query();
    }
    $application = JFactory::getApplication();
    $application->enqueueMessage( JText::_( 'NOTE: SimpleLists database tables have been removed' ), 'notice' ) ;

} else {
    $application = JFactory::getApplication();
    $application->enqueueMessage( JText::_( 'NOTE: Database tables were NOT removed to allow for upgrades' ), 'notice' ) ;
}

