<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Item Table class
*/
class SimpleListsTableItem extends YireoTable
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
            'title' => null,
            'alias' => null,
            'link' => null,
            'link_type' => null,
            'text' => null,
            'picture' => null,
            'hits' => null,
        );

        parent::__construct('#__simplelists_items', 'id', $db);
    }
}
