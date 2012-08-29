<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access'); 
?>

<?php echo $this->loadTemplate('_header'); ?>
<?php 
$width = $this->params->get('item_width');
$item_width = (!empty($width)) ? ' style="width:'.$width.'"' : '';  
?>

<div class="<?php echo $this->page_class; ?>">
<?php if(!empty( $this->items)) : ?>
    <?php foreach( $this->items as $item ) : ?>

    <div class="simplelists-item"<?php echo $item_width; ?>>
        <div class="image">

            <a name="<?php echo $item->href; ?>"></a>

            <?php if($item->picture): ?>
            <?php echo $item->picture; ?>
            <?php endif;?>

            <?php if($item->title): ?>
            <div class="caption"><?php echo $item->title; ?></div>
            <?php endif; ?>

        </div>
    </div>

    <?php endforeach; ?>
<?php else: ?>
    <?php echo $this->empty_list; ?>
<?php endif; ?>
<div style="clear:both"></div>
</div>

<?php echo $this->loadTemplate('_footer'); ?>
