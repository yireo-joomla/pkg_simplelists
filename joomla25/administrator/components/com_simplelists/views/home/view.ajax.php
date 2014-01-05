<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

/**
 * HTML View class 
 */
class SimpleListsViewHome extends YireoViewHomeAjax
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        switch(JRequest::getVar('layout')) {
            case 'feeds':
                ini_set('display_errors', 0);
                $feeds = $this->fetchFeeds('http://www.yireo.com/blog?format=feed&type=rss', 3);
                $this->assignRef('feeds', $feeds);
                break;
            case 'promotion':
                $html = YireoHelper::fetchRemote('http://www.yireo.com/advertizement.php', 'SimpleLists');
                print $html;
                exit;
        }

        parent::display($tpl);
    }

    /*
     * Display method
     *
     * @param string $url
     * @param int $max
     * @return array
     */
    public function fetchFeeds($url = '', $max = 3)
    {
        if(method_exists('JFactory', 'getFeedParser')) {
            $rss = JFactory::getFeedParser($url);
        } else {
            $rss = JFactory::getXMLParser('rss', array('rssUrl' => $url));
        }

        if($rss == null) {
            return false;
        }
        $result = $rss->get_items();
        $feeds = array();
        $i = 0;
        foreach($result as $r) {
            if($i == $max) break;
            $feed = array();
            $feed['link'] = $r->get_link();
            $feed['title'] = $r->get_title();
            $feed['description'] = preg_replace( '/<img([^>]+)>/', '', $r->get_description());
            $feeds[] = $feed;
            $i++;
        }
        return $feeds;
    }
}
