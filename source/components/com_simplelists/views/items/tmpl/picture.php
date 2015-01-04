<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access'); 
?>

<?php echo $this->loadTemplate('_header'); ?>
<?php 
$column_mode = $this->params->get('column_mode', 'css');
$columns = (int)$this->params->get('columns');
if(!$columns > 0) $columns = 4;
if(count($this->items) < $columns && count($this->items) > 0) $columns = count($this->items);

if($column_mode == 'bootstrap') {
    $this->page_class .= ' row-fluid';
    $column_span = (int)(12 / $columns);
    $item_class = ' span'.$column_span;
    $item_style = '';
} else {
    $column_width = (int)floor(100 / $columns);
    $item_class = '';
    $item_style = 'float:left; width:'.$column_width.'%';
}
?>

<div class="<?php echo $this->page_class; ?>">
<?php if(!empty( $this->items)) : ?>
    <?php foreach( $this->items as $item ) : ?>

    <div class="<?php echo $item->class; ?> <?php echo $item_class; ?>" style="<?php echo $item_style; ?>">
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
<?php if($column_mode == 'css') : ?>
<div style="clear:both"></div>
<?php endif; ?>
</div>

<?php echo $this->loadTemplate('_footer'); ?>
