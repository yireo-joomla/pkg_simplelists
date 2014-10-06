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
<?php if(!empty($items)) : ?>
    <ul class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>">
    <?php foreach ($items as $item) : ?>
	    <li class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>">
            <a href="<?php echo $item->link; ?>" class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>"><?php echo $item->title; ?></a>
    	</li>
    <?php endforeach; ?>
    </ul>
    <?php if($readmore) : ?>
        <p class="readmore"><a href="<?php echo $readmore_link; ?>"><?php echo $readmore; ?></a></p>
    <?php endif; ?>
<?php else: ?>
    <p><?php echo JText::_('MOD_SIMPLELISTS_ITEMS_NO_ITEMS'); ?></p>
<?php endif; ?>
</div>
