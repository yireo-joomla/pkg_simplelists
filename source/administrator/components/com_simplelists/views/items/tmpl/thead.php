<?php
/**
 * Joomla! Yireo Library
 *
 * @author    Yireo
 * @package   YireoLib
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 * @version   0.4.3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<th width="300" class="title">
	<?php echo JHtml::_('grid.sort', 'LIB_YIREO_TABLE_FIELDNAME_TITLE', 'title', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="40" class="title">
	<?php echo JHtml::_('grid.sort', 'COM_SIMPLELISTS_ITEM_IMAGE', 'item.picture', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="15%" class="title">
	<?php echo JText::_('COM_SIMPLELISTS_CATEGORIES'); ?>
</th>
<th width="100" class="title">
	<?php echo JHtml::_('grid.sort', 'COM_SIMPLELISTS_ITEM_LINKTYPE', 'link_type', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="12%" class="title">
	<?php echo JHtml::_('grid.sort', 'COM_SIMPLELISTS_ITEM_LINK', 'link', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="9%">
	<?php echo JHtml::_('grid.sort', 'LIB_YIREO_TABLE_FIELDNAME_ACCESS', 'access', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="1%" nowrap="nowrap">
	<?php echo JHtml::_('grid.sort', 'LIB_YIREO_TABLE_FIELDNAME_HITS', 'item.hits', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
