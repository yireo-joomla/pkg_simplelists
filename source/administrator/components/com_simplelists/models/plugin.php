<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class SimplelistsModelPlugin extends YireoModel
{
	/**
	 * Indicator whether to debug this model or not
	 */
	protected $_debug = false;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_orderby_title = 'name';
		parent::__construct('plugin', 'plugins', 'id');
	}
}
