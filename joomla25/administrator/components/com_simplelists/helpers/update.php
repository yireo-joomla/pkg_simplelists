<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2011
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
    public function getRemote( $url ) {
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
     public function getUpdate( $url ) {

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
}

