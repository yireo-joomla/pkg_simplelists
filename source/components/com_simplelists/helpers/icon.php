<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * HTML Icon Helper
 * 
 * @package Joomla
 * @subpackage Simplelists
 */
if (!class_exists('JHTMLIcon')) {
    class JHTMLIcon
    {
        /**
         * Method to display a print-icon
         *
         * @access public
         * @param null
         * @return string HTML output
         */
        public function print_popup()
        {
            // Construct the URL
            $url = 'index.php?option=com_simplelists&view=items';
            if (JRequest::getInt('category_id') > 0) $url .= '&category_id='.JRequest::getInt('category_id');
            if (JRequest::getInt('Itemid') > 0) $url .= '&Itemid='.JRequest::getInt('Itemid');
            $url .= '&tmpl=component';

            // Add JavaScript variables
            $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

            // Checks template image directory for image, if non found default are loaded
            $image_folder = '/media/system/images/';
            $text = JHTML::_('image.site',  'printButton.png', $image_folder, NULL, NULL, JText::_( 'Print' ) );

            // Construct the link-attributes
            $attribs['title']    = JText::_( 'Print' );
            $attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
            $attribs['rel']     = 'nofollow';

            return JHTML::_('link', JRoute::_($url), $text);
        }

        /**
         * Dummy method for the pdf-button
         *
         * @access public
         * @param null
         * @return null
         */
        public function pdf()
        {
        }

        /**
         * Dummy method for the email-button
         *
         * @access public
         * @param null
         * @return null
         */
        public function email()
        {
        }

        /**
         * Dummy method for the edit-button
         *
         * @access public
         * @param null
         * @return null
         */
        public function edit()
        {
        }
    }
}
