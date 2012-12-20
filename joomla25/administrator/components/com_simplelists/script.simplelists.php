<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

class com_simplelistsInstallerScript
{
	public function postflight($action, $installer)
	{
		switch($action) {
			case "install":
			case "update":
                include_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'install.simplelists.php';
                if(function_exists('com_install')) {
                    com_install();
                }
				break;
			}
	}
}
