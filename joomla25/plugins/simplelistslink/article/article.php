<?php
/**
 * Joomla! link-plugin for SimpleLists - Article
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Include the parent class
if(file_exists(dirname(__FILE__).'/default.php')) {
    require_once dirname(__FILE__).'/default.php';
} else {
    require_once dirname(dirname(__FILE__)).'/default/default.php';
}

/**
 * SimpleLists Link Plugin - Articles
 */
class plgSimpleListsLinkArticle extends plgSimpleListsLinkDefault
{
    /**
     * Load the parameters
     * 
     * @access private
     * @param null
     * @return JParameter
     */
    private function getParams()
    {
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->RELEASE, '1.5', 'eq')) {
            $plugin = JPluginHelper::getPlugin('simplelistslink', 'article');
            $params = new JParameter($plugin->params);
            return $params;
        } else {
            return $this->params;
        }
    }

    /*
     * Method to get the title for this plugin 
     *  
     * @access public
     * @param null
     * @return string
     */
    public function getTitle() 
    {
        return 'Internal article';
    }    

    /*
     * Method the friendly name of a specific item
     *
     * @access public
     * @param mixed $link
     * @return string
     */
    public function getName($link) 
    {
        $query = "SELECT `title` FROM #__content WHERE `id`=".(int)$link;
        $db =& JFactory::getDBO();
        $db->setQuery( $query );
        $row = $db->loadObject() ;
        if(is_object($row)) {
            return $row->title ;
        } else {
            return '' ;
        }
    }

    /*
     * Method to build the item URL 
     *
     * @access public
     * @param object $item
     * @return string
     */
    public function getUrl($item = null) 
    {
        require_once JPATH_SITE.'/components/com_content/helpers/route.php' ;
        $link = $item->link;
        $url = ContentHelperRoute::getArticleRoute((int)$link);

        if(!strstr($url,'Itemid=')) {

            $query = "SELECT a.*, c.alias AS catalias, s.alias AS sectionalias FROM #__content AS a "
                . " LEFT JOIN #__categories AS c ON c.id = a.catid "
                . " LEFT JOIN #__sections AS s ON s.id = a.sectionid "
                . " WHERE a.`id`=".(int)$link
            ;

            $db =& JFactory::getDBO();
            $db->setQuery( $query );
            $article = $db->loadObject();

            if(!empty($article)) {
                $url = ContentHelperRoute::getArticleRoute($article->id.':'.$article->alias, $article->catid.':'.$article->catalias, $article->sectionid.':'.$article->sectionalias );
            }
        }

        /*if(!strstr($url,'Itemid=')) {
            $url .= '&Itemid='.JRequest::getInt('Itemid');
        }*/

        return $url;
    }

    /*
     * Method to build the HTML when editing a item-link with this plugin
     *
     * @access public
     * @param mixed $current
     * @return string
     */
    public function getInput($current = null) 
    {
        if(YireoHelper::isJoomla15()) {
            $modal_link = 'index.php?option=com_content&amp;task=element&amp;tmpl=component';
        } else {
            $modal_link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=slSelectArticle';
        }
        return $this->getModal('article', $modal_link, $current);
    }
}
