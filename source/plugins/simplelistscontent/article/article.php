<?php
/**
 * Joomla! link-plugin for SimpleLists - Article
 *
 * @author Yireo (info@yireo.com)
 * @package SimpleLists
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Include the parent class
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/plugin/content.php';

/**
 * SimpleLists Content Plugin - Articles
 */
class plgSimpleListsContentArticle extends SimplelistsPluginContent
{
    protected $_name = 'article';

    /**
     * Constructor
     *
     * @access protected
     * @param object $subject The object to observe
     * @param array $config  An array that holds the plugin configuration
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onSimpleListsContentGetId($category_id, $plugin_name, $model_name)
    {
        $acategory_id = JRequest::getInt('acategory_id', '0');
        if($acategory_id > 0) {
            $category_id = $acategory_id;
            $plugin_name = 'article';
            $model_name = 'SimplelistsModelArticles';
            return true;
        }
            
        return false;
    }
}
