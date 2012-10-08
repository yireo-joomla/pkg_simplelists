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
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/table.php';

/**
* Category Table class
*/
class TableCategory extends YireoTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(& $db) 
    {
        // Initialize the default values
        if(YireoHelper::isJoomla15()) {
            $this->_defaults = array(
                'section' => 'com_simplelists',
            );
        } else {
            $this->_defaults = array(
                'extension' => 'com_simplelists',
                'level' => 1,
            );
        }

        // Set the required fields
        $this->_required = array('title');

        parent::__construct('#__categories', 'id', $db);
    }

    /**
     * Overloaded check method to ensure data integrity
     *
     * @access public
     * @return boolean True on success
     */
    public function check()
    {
        // Make sure the parent_id doesn't match the id 
        if ($this->id > 0 && $this->id == $this->parent_id) {
            $this->_error = JText::_('Category can not be its own parent');
            return false;
        }

        return parent::check();
    }
}
