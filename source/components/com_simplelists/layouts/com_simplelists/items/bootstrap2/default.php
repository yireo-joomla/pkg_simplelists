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
<div class="<?php echo $page_class; ?>">
	<?php if (!empty($items)): ?>
		<?php foreach ($items as $item): ?>

			<a name="<?php echo $item->href; ?>"></a>

			<div class="<?php echo $item->class; ?>">

				<?php if (!empty($item->picture)) : ?>
					<div class="image" style="<?php if ($item->picture_alignment)
					{
						echo 'float:' . $item->picture_alignment;
					} ?>">
						<?php if ($item->picture): ?>
							<?php echo $item->picture; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="body">
					<?php if ($item->title): ?>
						<h3 class="contentheading"><?php echo $item->title ?></h3>
					<?php endif; ?>

					<?php if ($item->text): ?>
						<?php echo $item->text; ?>
					<?php endif; ?>

					<?php if ($item->readmore): ?>
						<br/><?php echo $item->readmore; ?>
					<?php endif; ?>

					<?php if ($totop): ?>
						<?php echo $totop; ?>
					<?php endif; ?>

				</div>

			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<?php echo $empty_list; ?>
	<?php endif; ?>
</div>