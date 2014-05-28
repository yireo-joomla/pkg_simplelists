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

function com_install() {

    // Initialize system variables
    $db = JFactory::getDBO();
    $application = JFactory::getApplication() ;

    // Do a simple PHP-check
    if(version_compare(phpversion(), '5.2.0', '>=') == false) {
        $application->enqueueMessage( JText::_( 'PHP 5.2.0 or higher is required.' ), 'error' ) ;
        return false;
    }

    // Do a simple Joomla! version-check
    $jversion = new JVersion();
    if(version_compare($jversion->getShortVersion(), '1.5.9', '>=') == false) {
        $application->enqueueMessage( JText::_( 'Joomla! 1.5.9 or higher is required.' ), 'error' ) ;
        return false ;
    }

    // Collection of queries were going to try
    $update_queries = array (
        'INSERT INTO `#__simplelists_categories` (`id`, `category_id` ) SELECT `id`,`catid` FROM `#__simplelists`',
        'ALTER TABLE `#__simplelists` DROP COLUMN `catid`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `link_type` INT(11) NOT NULL DEFAULT 0 AFTER `title`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `hits` INT(11) NOT NULL DEFAULT 0 AFTER `ordering`',
        'ALTER TABLE `#__simplelists` CHANGE `url` `link` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL',
        'ALTER TABLE `#__simplelists` ADD COLUMN `created` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `published`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `created_by` INT(11) NOT NULL DEFAULT 0 AFTER `created`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `created_by_alias` TEXT AFTER `created_by`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `modified` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `created_by_alias`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `modified_by` INT(11) NOT NULL DEFAULT 0 AFTER `modified`',
        'ALTER TABLE `#__simplelists` ADD COLUMN `modified_by_alias` TEXT AFTER `modified_by`',
        'UPDATE `#__components` SET `link`="option=com_simplelists", `admin_menu_link`="option=com_simplelists&view=categories" WHERE `admin_menu_link`="option=com_categories&section=com_simplelists"',
        'UPDATE `#__components` SET `link`="option=com_simplelists", `admin_menu_link`="option=com_simplelists&view=items" WHERE `admin_menu_link`="option=com_simplelists"',
        'UPDATE `#__simplelists` SET `picture`=CONCAT("images/simplelists/", picture) WHERE `picture` != "" AND `picture` NOT LIKE "%images/simplelists%"',
        'DELETE FROM #__simplelists_categories WHERE id NOT IN ( SELECT id FROM #__simplelists )',
        'ALTER TABLE `#__simplelists` CHANGE `link_type` `link_type` VARCHAR( 20 ) NOT NULL',
        'UPDATE `#__simplelists` SET `link_type`="" WHERE `link_type`="0"',
        'UPDATE `#__simplelists` SET `link_type`="custom" WHERE `link_type`="1"',
        'UPDATE `#__simplelists` SET `link_type`="menuitem" WHERE `link_type`="2"',
        'UPDATE `#__simplelists` SET `link_type`="article" WHERE `link_type`="3"',
        'UPDATE `#__simplelists` SET `link_type`="image" WHERE `link_type`="4"',
        'UPDATE `#__simplelists` SET `link_type`="file" WHERE `link_type`="5"',
        'UPDATE `#__categories` SET `image`=CONCAT("images/simplelists/", image) WHERE `image` != "" AND `image` NOT LIKE "%images/simplelists%" AND `section`="com_simplelists"',
        'ALTER TABLE `#__simplelists` ADD `alias` VARCHAR(255) NOT NULL AFTER `title`',
        'ALTER TABLE `#__simplelists` ADD `access` INT( 11 ) NOT NULL AFTER `published`',
        'RENAME TABLE  `#__simplelists` TO  `#__simplelists_items`',
        'UPDATE #__menu SET link = REPLACE(link,"option=com_simplelists&view=simplelist","option=com_simplelists&view=items")',
        'ALTER TABLE `#__simplelists_items` ADD `asset_id` INT( 11 ) NOT NULL AFTER  `id`',
        'ALTER TABLE `#__simplelists_items` ADD `article_id` INT( 11 ) NOT NULL AFTER  `asset_id`',
        'ALTER TABLE `#__simplelists_items` CHANGE `link_type` `link_type` VARCHAR(255) NOT NULL DEFAULT "0"',
        'ALTER TABLE `#__simplelists_items` ADD `flags` VARCHAR(10) NOT NULL AFTER `picture`',
        'DROP TABLE  `#__simplelists_plugins`',
        'UPDATE `#__categories` SET `parent_id`=1 WHERE `extension`="com_simplelists" AND `parent_id`=0',
    );

    // Perform all queries - we don't care if it fails
    foreach( $update_queries as $query ) {
        $db->debug(0);
        $db->setQuery( $query );
        $db->query();
    }
}
