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

// Import the libraries
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/helpers/html.php';
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/helpers/helper.php';

class JElementSlcategory extends JElement
{
    var $_name = 'SimpleLists category';

	function fetchElement($name, $value, &$node, $control_name)
	{
        if(!empty($control_name)) {
            $fieldName  = $control_name.'['.$name.']';
        } else {
            $fieldName = $name;
        }   

        $categories_params = array( 'current' => $value, 'nullvalue' => 1 );
		return SimplelistsHTML::selectCategories( $fieldName, $categories_params );
    }
}
