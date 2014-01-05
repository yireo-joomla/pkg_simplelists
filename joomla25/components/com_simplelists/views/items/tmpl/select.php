<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access');
?>

<?php echo $this->loadTemplate('_header'); ?>

<?php if( !empty( $this->items)): ?>
<div id="simplelists-navigator">
<select name="simplelist-select" id="simplelist-select">
<?php foreach( $this->items as $item ): ?>
    <option value="<?php echo $item->id; ?>"><?php echo $item->title ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="<?php echo $this->page_class; ?>">
<?php foreach( $this->items as $item ): ?>

    <div class="<?php echo $item->class; ?>" id="item<?php echo $item->id; ?>" style="<?php echo $item->style; ?>">

        <?php if( $item->title ): ?>
        <h3 class="contentheading"><?php echo $item->title ?></h3>
        <?php endif; ?>

        <?php if( $item->picture ): ?>
        <?php echo $item->picture; ?>
        <?php endif; ?>

        <?php if( $item->text ): ?>
        <?php echo $item->text; ?>
        <?php endif; ?>

        <?php if( $item->readmore ): ?>
        <br/><?php echo $item->readmore; ?>
        <?php endif; ?>

    </div>

<?php endforeach; ?>
</div>

<?php else: ?>
    <?php echo $this->empty_list; ?>
<?php endif; ?>

<?php echo $this->loadTemplate('_footer'); ?>
