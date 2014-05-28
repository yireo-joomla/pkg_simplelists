<?php
/**
 * Joomla! search plugin for Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2013 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the SimpleLists helper
include_once JPATH_SITE.'/components/com_simplelists/helpers/search.php';

/**
 * SimpleLists Search Plugin
 */
class plgSearchSimpleLists extends JPlugin
{
	/**
     * Method to return the various search-areas of this plugin
     *
     * @access public
     * @param null
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas()
    {
        static $areas = array(
            'simplelists' => 'Lists'
        );
        return $areas;
    }

	/**
	 * Method to perform the actual search
	 *
	 * @access public
	 * @param string $text
     * @param string $phrase
     * @param string $ordering
     * @param string $areas
	 * @return array
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
        // Fetch system variables
        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        // If the SimpleLists search-area is not included in this search-request, skip this plugin
        if(is_array($areas)) {
            if(!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
                return array();
            }
        }

        // Skip empty search-queries
        $text = trim($text);
        if ($text == '') {
            return array();
        }

        // Perform the search
        $limit = $this->getParams()->get('search_limit', 50);
        $list = SimplelistsHelperSearch::search($text, $phrase, $ordering, $limit);

        // Loop through the search-results, and optimize the results
        if(!empty($list)) {
            foreach($list as $key => $item) {
                $needles = array(
                    'category_id' => $item->category_id,
                    'category_alias' => $item->category_alias,
                    'item_id' => $item->id,
                    'item_alias' => $item->alias,
                );
                $list[$key]->href = SimplelistsHelper::getUrl($needles);
                $list[$key]->section = $item->catname ;
                $list[$key]->created = null ;
            }
        }

        // Right, can somebody cleanup?
        $rows[] = $list;
        $results = array();
        if(count($rows)) {
            foreach($rows as $row) {
                $results = array_merge($results, (array) $row);
            }
        }

        return $results;
    }

    /**
     * Load the parameters
     *
     * @access private
     * @param null
     * @return JParameter
     */
    private function getParams()
    {
        JLoader::import( 'joomla.version' );
        $jversion = new JVersion();
        if(version_compare( $jversion->RELEASE, '1.5', 'eq') == false) {
            return $this->params;
        } else {
            jimport('joomla.html.parameter');
            $plugin = JPluginHelper::getPlugin('search', 'simplelists');
            $params = new JParameter($plugin->params);
            return $params;
        }
    }
}
