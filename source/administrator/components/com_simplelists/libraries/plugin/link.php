<?php
/**
 * Joomla! link-plugin parent-class for SimpleLists
 *
 * @author    Yireo
 * @package   SimpleLists
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent class
jimport('joomla.plugin.plugin');

/**
 * SimpleLists Link Plugin Abstract
 */
class SimplelistsPluginLink extends JPlugin
{
	/*
	 * Method to get the plugin name
	 *
	 * @return string
	 */
	public function getPluginName()
	{
		return $this->_name;
	}

	/*
	 * Method to check whether this plugin can be used or not
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return true;
	}

	/*
	 * Method to get the title for this plugin
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return null;
	}

	/*
	 * Method the friendly name of a specific item
	 *
	 * @param mixed $link
	 *
	 * @return string
	 */
	public function getName($link = null)
	{
		return $link;
	}

	/*
	 * Method to build the item URL
	 *
	 * @param object $item
	 * @return string
	 */
	public function getUrl($item = null)
	{
		return null;
	}

	/*
	 * Method to build the HTML when editing a item-link with this plugin
	 *
	 * @param mixed $current
	 * @return string
	 */
	public function getInput($current = null)
	{
		return null;
	}

	/*
	 * Method to display the hidden-context of this item
	 *
	 * @param object $item
	 * @return mixed
	 */
	public function getHidden($item)
	{
		header('Location: ' . $this->getUrl($item->link));
		exit;
	}

	/*
	 * Method to display a modal-box
	 *
	 * @param string $type
	 * @param string $modal_link
	 * @param mixed $current
	 * @return string
	 */
	public function getModal($type, $modal_link, $current = null)
	{
		?>
		<div style="float:left;">
			<input type="text" id="link_name_<?php echo $type; ?>" value="<?php echo $this->getName($current); ?>"
				   disabled="disabled"/>
		</div>
		<div class="button2-left">
			<div class="blank">
				<?php $selectText = JText::_('COM_SIMPLELISTS_SELECT_' . strtoupper($type)); ?>
				<a class="btn modal-button" title="<?php echo $selectText; ?>" href="<?php echo $modal_link; ?>"
				   rel="{handler: 'iframe', size: {x: 770, y: 500}}">
					<?php echo $selectText; ?>
				</a>
			</div>
		</div>
		<input type="hidden" id="link_<?php echo $type; ?>" name="link_<?php echo $type; ?>"
			   value="<?php echo $current; ?>"/>
		<div style="clear:both"></div>
		<?php
	}
}
