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

// Require the parent table
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplelists'.DS.'lib'.DS.'table.php';

/**
* Plugin Table class
*
* @package SimpleLists
*/
class TablePlugin extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(& $db) 
    {
        // Initialize the fields
        $this->_fields = array(
            'id' => null,
            'name' => null,
            'element' => null,
            'folder' => null,
        );

        // Set the required fields
        $this->_required = array('name');

        parent::__construct('#__simplelists_plugins', 'id', $db);
    }
}
