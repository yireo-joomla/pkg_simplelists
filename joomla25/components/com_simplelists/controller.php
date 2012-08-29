<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

// Include the parent controller
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'lib'.DS.'controller.php';

/**
 * Simplelists Controller
 */
class SimplelistsController extends YireoController
{
    /**
     * Constructor
     * @access public
     * @package SimpleLists
     */
    public function __construct()
    {
        $this->_default_view = 'items';
        parent::__construct();
    }
}
