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

// Require the parent model
require_once JPATH_COMPONENT.DS.'lib'.DS.'model.php';

class SimplelistsModelCategory extends YireoModel
{
    /**
     * Indicator whether to debug this model or not
     */
    protected $_debug = false;

    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->_orderby_title = 'name';
        parent::__construct('plugin', 'plugins', 'id' );
    }
}
