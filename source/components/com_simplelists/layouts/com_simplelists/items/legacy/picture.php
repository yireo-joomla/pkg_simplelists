<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access');

foreach ($displayData as $name => $value)
{
	$$name = $value;
}
?>
<?php
$column_mode = $params->get('column_mode', 'css');
$columns = (int) $params->get('columns');

if (!$columns > 0)
{
	$columns = 4;
}

if (count($items) < $columns && count($items) > 0)
{
	$columns = count($items);
}

if ($column_mode == 'bootstrap')
{
	$page_class .= ' row-fluid';
	$column_span = (int) (12 / $columns);
	$item_class = ' span' . $column_span;
	$item_style = '';
}
else
{
	$column_width = (int) floor(100 / $columns);
	$item_class = '';
	$item_style = 'float:left; width:' . $column_width . '%';
}
?>
<div class="<?php echo $page_class; ?>">
	<?php if (!empty($items)) : ?>
		<?php foreach ($items as $item) : ?>

			<div class="<?php echo $item->class; ?> <?php echo $item_class; ?>" style="<?php echo $item_style; ?>">
				<div class="image">

					<a name="<?php echo $item->href; ?>"></a>

					<?php if ($item->picture): ?>
						<?php echo $item->picture; ?>
					<?php endif; ?>

					<?php if ($item->title): ?>
						<div class="caption"><?php echo $item->title; ?></div>
					<?php endif; ?>

				</div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<?php echo $empty_list; ?>
	<?php endif; ?>
	<?php if ($column_mode == 'css') : ?>
		<div style="clear:both"></div>
	<?php endif; ?>
</div>