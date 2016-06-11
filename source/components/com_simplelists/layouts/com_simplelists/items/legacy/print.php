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
<?php if ($params->get('show_category_image') || $params->get('show_category_description')) : ?>
	<?php if ($params->get('show_category_image')) : ?>
		<?php echo $category->image; ?>
	<?php endif; ?>
	<?php if ($params->get('show_category_description')) : ?>
		<p><?php echo $category->description ?></p>
	<?php endif; ?>
	<br/>
	<br/>
	<br/>
<?php endif; ?>

<?php if (!empty($items)) : ?>
	<?php foreach ($items as $item): ?>

		<?php if ($item->picture): ?>
			<?php echo $item->picture; ?>
		<?php endif; ?>

		<?php if ($item->title): ?>
			<h3><?php echo $item->title; ?></h3>
		<?php endif; ?>

		<?php if ($item->text): ?>
			<?php echo $item->text; ?>
		<?php endif; ?>

		<?php if ($item->readmore): ?>
			<br/>
			<?php echo $item->readmore; ?>
		<?php endif; ?>
		<br/>
		<br/>
		<br/>

	<?php endforeach; ?>
<?php endif; ?>

