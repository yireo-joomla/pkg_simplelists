<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

$parent = $this->state->parent;
?>
<?php if($parent) { ?>
<div class="item">
	<a href="index.php?option=com_simplelists&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $parent; ?>&amp;type=<?php echo $this->state->type ?>">
		<img src="<?php echo JURI::base() ?>../media/com_simplelists/images/button_up.png" width="32" height="32" />
		<span><?php echo JText::_('Up'); ?></span></a>
</div>
<?php } ?>
