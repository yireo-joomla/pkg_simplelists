<?php
/*
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('JPATH_BASE') or die();

// @bug: jimport() fails here
include_once JPATH_LIBRARIES.'/joomla/form/fields/radio.php';
include_once JPATH_LIBRARIES.'/joomla/form/fields/list.php';

/*
 * Use an intermediate class to determine the right parent-class
 */
if(YireoHelper::isJoomla25()) {
    class JFormFieldPublishedAbstract extends JFormFieldList {}
} else {
    class JFormFieldPublishedAbstract extends JFormFieldRadio {}
}

/*
 * Form Field-class for showing a published field
 */
class JFormFieldPublished extends JFormFieldPublishedAbstract
{
    /*
     * Form field type
     */
    public $type = 'Published';

    /*
     * Method to construct the HTML of this element
     *
     * @param null
     * @return string
     */
	protected function getInput()
	{
        $this->class = 'radio btn-group btn-group-yesno';
        return parent::getInput();
    }
    
	protected function getOptions()
	{
        $options = array(
            JHtml::_('select.option', '0', JText::_('JUNPUBLISHED')),
            JHtml::_('select.option', '1', JText::_('JPUBLISHED')),
        );
        return $options;
    }
}
