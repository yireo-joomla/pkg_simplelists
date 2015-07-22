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
<?php if (!empty($items)): ?>
	<div id="simplelists-navigator">
		<select name="simplelist-select" id="simplelist-select">
			<?php foreach ($items as $item): ?>
				<option value="<?php echo $item->id; ?>"><?php echo $item->title ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="<?php echo $page_class; ?>">
		<?php foreach ($items as $item): ?>

			<div class="<?php echo $item->class; ?>" id="item<?php echo $item->id; ?>"
				 style="<?php echo $item->style; ?>">

				<?php if ($item->title): ?>
					<h3 class="contentheading"><?php echo $item->title ?></h3>
				<?php endif; ?>

				<?php if ($item->picture): ?>
					<?php echo $item->picture; ?>
				<?php endif; ?>

				<?php if ($item->text): ?>
					<?php echo $item->text; ?>
				<?php endif; ?>

				<?php if ($item->readmore): ?>
					<br/><?php echo $item->readmore; ?>
				<?php endif; ?>

			</div>

		<?php endforeach; ?>
	</div>

<?php else: ?>
	<?php echo $empty_list; ?>
<?php endif; ?>