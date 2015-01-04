<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Initialize the ACLs
SimpleListsHelperAcl::init();

/*
 * Helper for ACL-permissions
 */
class SimpleListsHelperAcl
{
    /*
     * Initialize the helper-class
     *
     * @param mixed $string
     * @return string
     */
    public static function init()
    {
    }

    /*
     * Check whether a certain person is authorised
     *
     * @param mixed $string
     * @return string
     */
    public static function isAuthorized()
    {
        // Initialize system variables
        $user = JFactory::getUser();

        // Check the ACLs for Joomla! 1.6 and later
        if($user->authorise('core.manage', 'com_simplelists') == false) {
            return false;
        }

        return true;
    }
}

