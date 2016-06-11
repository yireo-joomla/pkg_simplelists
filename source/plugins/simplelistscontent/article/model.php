<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Simplelists Articles Model
 */
class SimplelistsModelArticles extends YireoModel
{
    /**
     * Data for the category containing these items
     *
     * @protected int
     */
    protected $_category = null;

    /**
     * Constructor
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        // Debugging
        $this->_debug = false;

        // Deterine the ID for SimpleLists content
        $category_id = JRequest::getInt('acategory_id', '0');
        $this->setId($category_id);

        parent::__construct('content');
        $this->_tbl_alias = 'item';

        // Set pagination
        if ($this->params->get('use_pagination')) {
            $this->setLimitQuery(true);
            if ($this->params->get('limit') > 0) {
                $this->initLimit($this->params->get('limit'));
            }
        } else {
            $this->setLimitQuery(false);
        }
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery($query = '')
    {
        $query = 'SELECT item.*'
            . ' FROM #__content AS item' 
            . ' LEFT JOIN #__categories AS category ON category.id = item.catid'
        ;
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
        $this->addWhere('category.published = 1');

        // Apply the character-filter
        if ($this->getState('no_char_filter') != 1) {
            $character = JRequest::getCmd('char');
            if (!empty($character) && preg_match( '/^([a-z]{1})$/', $character)) {
                $this->addWhere('item.title LIKE '.$this->_db->Quote($character.'%'));
            }
        }

        return parent::buildQueryWhere();
    }

    /**     
     * Method to get empty fields
     *  
     * @access protected
     * @subpackage Yireo
     * @param null
     * @return array
     */
    protected function getEmptyFields()
    {
        $data = parent::getEmptyFields();
        $data['link_type'] = null;
        $data['text'] = null;
        return $data;
    }
}
