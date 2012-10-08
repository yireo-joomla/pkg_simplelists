<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

// Require the parent view
require_once JPATH_COMPONENT.'/lib/view/home.php';

/**
 * HTML View class 
 */
class SimpleListsViewHome extends YireoViewHome
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        $icons = array();
        $icons[] = $this->icon( 'item', 'New Item', 'item.png');
        $icons[] = $this->icon( 'items', 'Items', 'items.png');
        $icons[] = $this->icon( 'category', 'Categories', 'categories.png');
        $this->assignRef( 'icons', $icons );

        $urls = array();
        $urls['twitter'] ='http://twitter.com/yireo';
        $urls['facebook'] ='http://www.facebook.com/yireo';
        $urls['tutorials'] = 'http://www.yireo.com/tutorials/simplelists';
        $urls['jed'] ='http://extensions.joomla.org/extensions/news-display/tables-a-lists/3650';
        $this->assignRef( 'urls', $urls );

        parent::display($tpl);
    }
}
