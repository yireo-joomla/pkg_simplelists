<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access');

foreach ($displayData as $name => $value)
{
	$$name = $value;
}
?>
<?php if (!empty($items)) : ?>
	<?php $columns = $params->get('columns', 1); ?>
	<?php $width = floor(100 / $columns) . '%'; ?>
	<?php $i = 1; ?>
	<table class="table table-striped <?php echo $page_class; ?>">
		<?php foreach ($items as $item): ?>
			<?php if ($i % $columns == 1 || count($items) == 1 || $columns == 1): ?>
				<tr>
			<?php endif; ?>
			<td width="<?php echo $width; ?>" class="<?php echo $item->class; ?>">
				<?php if (!empty($item->picture) && $item->picture_alignment != 'right'): ?>
					<?php if ($item->picture): ?>
						<?php echo $item->picture; ?>
					<?php endif; ?>
				<?php endif; ?>

				<a name="<?php echo $item->href; ?>"></a>
				<?php if ($item->title): ?>
					<h3 class="contentheading"><?php echo $item->title; ?></h3>
				<?php endif; ?>

				<?php if ($item->text): ?>
					<?php echo $item->text; ?>
				<?php endif; ?>

				<?php if ($item->readmore): ?>
					<br/><?php echo $item->readmore; ?>
				<?php endif; ?>

				<?php if ($item->picture_alignment == 'right'): ?>
					<?php if ($item->picture): ?>
						<?php echo $item->picture; ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ($totop): ?>
					<?php echo $totop; ?>
				<?php endif; ?>
			</td>

			<?php if ($i % $columns == 0 || count($items) == 1): ?>
				</tr>
			<?php endif; ?>

			<?php $i++; ?>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<?php echo $empty_list; ?>
<?php endif; ?>