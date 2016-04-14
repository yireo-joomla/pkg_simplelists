<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

class SimplelistsModelPlugins extends YireoModel
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_search = array('title', 'name');
		$this->_debug = true;
		$this->_limit_query = true;
		parent::__construct('plugin');
	}

	/**
	 * Method to build the database query
	 *
	 * @return mixed
	 */
	protected function buildQuery()
	{
		$query = "SELECT `plugin`.*, {access}, {editor} FROM `#__extensions` AS `plugin`\n";

		return parent::buildQuery($query);
	}

	/**
	 * Method to build the query WHERE segment
	 *
	 * @return string
	 */
	protected function buildQueryWhere()
	{
		$this->addWhere('plugin.type = "plugin"');
		$this->addWhere('plugin.folder = "simplelistslink"');

		return parent::buildQueryWhere();
	}
}
