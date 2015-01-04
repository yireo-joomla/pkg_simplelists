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

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Simplelists Update Helper
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
class SimplelistsUpdate
{
    /**
     * Method to get remote content
     *
     * @access public
     * @param string URL from remote site
     * @return string Content from remote site
     */
    public function getRemote( $url ) 
    {
        require_once JPATH_COMPONENT.'/lib/remote.class.php' ;
        $remote = new RemoteConnection();
        $remote->setUrl( $url );
        $content = $remote->getContent();
        return $content;
    }

    /**
     * Method to get the title of the specified link type
     *
     * @access public
     * @param int ID of link type
     * @return string Title of link type
     */
    public function getUpdate( $url ) 
    {

        $update = array(
            'name' => '',
            'version' => '',
            'install' => '',
        );

        if( empty( $url )) {
            $url = 'https://www.yireo.com/documents/simplelists.xml';
        }

        $content = SimplelistsUpdate::getRemote( $url );
        if( empty( $content )) {
            return $update;
        }

        if(method_exists('JFactory', 'getXML')) {
            $xml = & JFactory::getXML();
        } else {
            $xml = & JFactory::getXMLParser('Simple');
        }

        if( !$xml->loadString( $content )) {
            return $update;
        }

        $update['name'] = $xml->document->name[0]->data();
        $update['version'] = $xml->document->version[0]->data();
        $update['install'] = $xml->document->install[0]->data();

        return $update;
    }

    static public function runUpdateQueries()
    {
        // Get the database object
        $db = JFactory::getDBO();

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

        // Count the existing entries in the new table
        $query = 'SELECT COUNT(*) FROM  `#__simplelists_items`';
        $db->setQuery($query);
        try {
            $newItems = $db->loadResult();
        } catch(Exception $e) {
            $newItems = 0;
        }

        // Count the existing entries in the new table
        $query = 'SELECT COUNT(*) FROM  `#__simplelists`';
        $db->setQuery($query);
        try {
            $oldItems = $db->loadResult();
        } catch(Exception $e) {
            $oldItems = 0;
        }

        // If the old table contains data, and the new one doesn't, remove the new table
        if($oldItems > 0 && $newItems < 1) {
            array_unshift($update_queries, 'DROP TABLE `#__simplelists_items`');
        }
        
        // Perform all queries - we don't care if it fails
        foreach( $update_queries as $query ) {
            $db->debug(0);
            $db->setQuery( $query );
            try {
                $db->query();
            } catch(Exception $e) {}
        }
    }
}

