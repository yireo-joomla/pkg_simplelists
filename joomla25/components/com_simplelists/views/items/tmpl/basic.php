<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access');
?>

<?php echo $this->loadTemplate('_header'); ?>

<div class="<?php echo $this->page_class; ?>">
<?php if(!empty( $this->items)): ?>
    <?php foreach( $this->items as $item ): ?>

        <a name="<?php echo $item->href; ?>"></a>

        <div class="simplelists-item-basic">
        <?php if($item->picture): ?>
        <?php echo $item->picture; ?>
        <?php endif; ?>

        <?php if($item->title): ?>
        <h3 class="contentheading"><?php echo $item->title; ?></h3>
        <?php endif; ?>

        <?php if($item->text): ?>
        <?php echo $item->text; ?>
        <?php endif; ?>

        <?php if($item->readmore): ?>
        <br/><?php echo $item->readmore; ?>
        <?php endif; ?>
        </div>

        <?php if($this->totop): ?>
        <?php echo $this->totop; ?>
        <?php endif; ?>

    <?php endforeach; ?>
<?php else: ?>
    <?php echo $this->empty_list; ?>
<?php endif; ?>
</div>

<?php echo $this->loadTemplate('_footer'); ?>
