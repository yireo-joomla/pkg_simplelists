<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="mod_simplelists_items">
<div class="simplelists<?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach ($items as $item) : ?>
	<div class="simplelists-item">

        <?php if($params->get('show_image') && $params->get('link_image')) : ?>
            <a href="<?php echo $item->link; ?>"><?php echo $item->picture; ?></a></h3>
        <?php elseif($params->get('show_image')) : ?>
            <?php echo $item->picture; ?>
        <?php endif; ?>

        <?php if(!empty($item->link) && $params->get('show_title') && $params->get('link_title')) : ?>
            <h3>
                <a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
            </h3>
        <?php elseif($params->get('show_title')) : ?>
            <h3><?php echo $item->title; ?></h3>
        <?php endif; ?>

        <?php if($params->get('show_text')) : ?>
            <?php echo $item->text; ?>
        <?php endif; ?>
	</div>
<?php endforeach; ?>
</div>
</div>
