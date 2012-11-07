<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the general helper
require_once( JPATH_COMPONENT.'/helpers/helper.php' );

/**
 * Simplelists Controller
 */
class SimplelistsController extends YireoController
{
    /**
     * Constructor
     * @access public
     * @subpackage SimpleLists
     */
    public function __construct()
    {
        $this->_default_view = 'items';
        parent::__construct();
    }
}
