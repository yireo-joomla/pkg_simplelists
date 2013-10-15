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
defined('_JEXEC') or die( 'Restricted access' );

/**
 */
class SimpleListsViewFiles extends YireoView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
		JResponse::allowCache(false);

        YireoHelper::jquery();
        if(YireoHelper::isJoomla15()) {
    		JHTML::_('stylesheet', 'popup-imagelist.css', 'administrator/components/com_media/assets/');
        } else {
    		JHTML::_('stylesheet', 'popup-imagelist.css', 'media/media/css/');
        }

        $files = $this->get('files');
        $folders = $this->get('folders');
        $state = $this->get('state');

		$this->assignRef('files', $files);
		$this->assignRef('folders', $folders);
		$this->assignRef('state', $state);

		parent::display($tpl);
	}

    /*
     * Method to prepare the content for display
     *
     * @param int $index
     * @return null
     */
	public function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

    /*
     * Method to prepare the content for display
     *
     * @param int $index
     * @return null
     */
	public function setFile($index = 0)
	{
		if (isset($this->files[$index])) {
			$this->_tmp_file = &$this->files[$index];
		} else {
			$this->_tmp_file = new JObject;
		}
	}
}
