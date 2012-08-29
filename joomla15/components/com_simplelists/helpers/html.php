<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * HTML Helper
 */
class SimplelistsHTML
{
    /**
     * Method to parse an image on the filesystem to an HMLT-tag
     *
     * @access public
     * @param string $file
     * @param string $alt
     * @param mixed $attribs 
     * @return string HTML output
     */
    public function image($file, $alt = null, $attribs = null)
    {
        if(!file_exists(JPATH_SITE.DS.$file)) {
            return null;
        }

        $info = getimagesize(JPATH_SITE.DS.$file);

        if(empty($alt)) {
            $alt = basename($file);
        }

        if (is_array($attribs)) {
            $attribs = JArrayHelper::toString( $attribs );
        }

        $picture = JURI::base().'/'.str_replace(DS, '/', $file);

        return '<img src="'.$picture.'" alt="'.$alt.'" '.$attribs.' '.$info[3].' />';
    }
}
