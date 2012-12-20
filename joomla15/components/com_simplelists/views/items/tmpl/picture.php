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
<?php 
$columns = $this->params->get('columns');
if(!$columns > 0) $columns = 4;
if(count($this->items) < $columns) $columns = count($this->items);
$column_span = (int)(12 / $columns);
?>

<div class="<?php echo $this->page_class; ?> row-fluid">
<?php if(!empty( $this->items)) : ?>
    <?php foreach( $this->items as $item ) : ?>

    <div class="<?php echo $item->class; ?> span<?php echo $column_span; ?>">
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
