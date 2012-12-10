<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2012
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
			case 'install':
			case 'update':

                include_once JPATH_ADMINISTRATOR.'/components/com_simplelists/install.simplelists.php';
                if(function_exists('com_install')) {
                    // @todo: Removed in Joomla! 3.0
                    com_install();
                }

                // Remove obsolete files
                $files = array(
                    JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/default.php',
                    JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/default_ads.php',
                    JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/default_cpanel.php',
                    JPATH_ADMINISTRATOR.'/components/com_simplelists/views/home/tmpl/feeds.php',
                );
                foreach($files as $file) {
                    if(file_exists($file)) @unlink($file);
                }

				break;
			}
	}
}
