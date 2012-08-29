<?php 
/**
 * Joomla! module SimpleLists Items
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="mod_simplelists_items">
<?php if(!empty($list)) : ?>
<ul class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach ($list as $item) : ?>
	<li class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>">
        <a href="<?php echo $item->link; ?>" class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>"><?php echo $item->title; ?></a>
	</li>
<?php endforeach; ?>
</ul>
<?php if(!empty($readmore_label)): ?>
<a href="<?php echo $readmore_link; ?>"><?php echo $readmore_label; ?></a>
<?php endif; ?>
<?php else: ?>
    <p><?php echo JText::_('No items found'); ?></p>
<?php endif; ?>
</div>
