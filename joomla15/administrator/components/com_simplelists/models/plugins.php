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

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

class SimplelistsModelPlugins extends YireoModel
{
    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->_search = array('title','name');
        $this->_debug = true;
        $this->_limit_query = true;
        parent::__construct('plugin');
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery()
    {
        if(YireoHelper::isJoomla15()) {
            $query = "SELECT `plugin`.*, {access}, {editor} FROM `#__plugins` AS `plugin`\n";
        } else {
            $query = "SELECT `plugin`.*, {access}, {editor} FROM `#__extensions` AS `plugin`\n";
        }
        return parent::buildQuery($query);
    }

    /**
     * Method to build the query WHERE segment
     *
     * @access protected
     * @param null
     * @return string
     */
    protected function buildQueryWhere()
    {
        $this->addWhere('plugin.type = "plugin"');
        $this->addWhere('plugin.folder = "simplelistslink"');
        return parent::buildQueryWhere();
    }
}
