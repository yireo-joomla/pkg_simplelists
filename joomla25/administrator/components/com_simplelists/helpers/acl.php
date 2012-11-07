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
        // Joomla! 1.5 ACLs
        if(YireoHelper::isJoomla15()) {
            $auth =& JFactory::getACL();
            $auth->addACL('com_simplelists', 'manage', 'users', 'super administrator');
            $auth->addACL('com_simplelists', 'manage', 'users', 'administrator');
            $auth->addACL('com_simplelists', 'manage', 'users', 'manager');
        }
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
        $user =& JFactory::getUser();

        // Check the ACLs for Joomla! 1.5
        if(YireoHelper::isJoomla15() && !$user->authorize( 'com_simplelists', 'manage' )) {
            return false;

        // Check the ACLs for Joomla! 1.6 and later
        } elseif(YireoHelper::isJoomla15() == false && $user->authorise('core.manage', 'com_simplelists') == false) {
            return false;
        }

        return true;
    }
}

