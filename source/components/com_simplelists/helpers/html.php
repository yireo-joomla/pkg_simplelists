<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
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
	 * @param string $file
	 * @param string $alt
	 * @param mixed  $attribs
	 *
	 * @return string HTML output
	 */
	static public function image($file, $alt = null, $attribs = null)
	{
		if (!file_exists(JPATH_SITE . '/' . $file))
		{
			return null;
		}

		$info = getimagesize(JPATH_SITE . '/' . $file);

		if (empty($alt))
		{
			$alt = basename($file);
		}

		if (is_array($attribs))
		{
			$attribs = JArrayHelper::toString($attribs);
		}

		$picture = JUri::base() . '/' . $file;

		return '<img src="' . $picture . '" alt="' . $alt . '" ' . $attribs . ' ' . $info[3] . ' />';
	}
}
