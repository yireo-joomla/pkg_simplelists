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

/** @var $pagination JPagination */
$pagination = $this->pagination;
?>

<?php if ($this->params->get('show_category_title', 1)) : ?>
	<a name="top"></a>
	<h1 class="componentheading"><?php echo $this->category->title ?></h1>
<?php endif; ?>

<?php if ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon')): ?>
	<div class="icons">
		<?php if ($this->params->get('show_pdf_icon')): ?><?php echo JHtml::_('icon.pdf'); ?><?php endif; ?>
		<?php if ($this->params->get('show_print_icon')): ?><?php echo JHtml::_('icon.print_popup'); ?><?php endif; ?>
	</div>
	<div style="clear:both"></div>
<?php endif; ?>

<?php if (($this->params->get('show_category_image') && !empty($this->category->image)) || ($this->params->get('show_category_description') && !empty($this->category->description))) : ?>
	<div class="simplelists-category">
		<?php if ($this->params->get('show_category_image') && !empty($this->category->image)) : ?>
			<?php echo $this->category->image; ?>
		<?php endif; ?>
		<?php if ($this->params->get('show_category_description')) : ?>
			<div class="simplelists-description"><?php echo $this->category->description ?></div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($this->params->get('show_category_parent') == 1 && !empty($this->category->parent)): ?>
	<div class="simplelists-parent">
		<ul>
			<li>
				<a href="<?php echo $this->category->parent->link; ?>"><?php echo $this->category->parent->title; ?></a>
			</li>
		</ul>
	</div>
<?php endif; ?>

<?php if ($this->params->get('show_category_childs') && !empty($this->category->childs)) : ?>
	<div class="simplelists-children">
		<ul>
			<?php foreach ($this->category->childs as $child) : ?>
				<li>
					<?php if (!empty($child->image) && file_exists(JPATH_SITE . '/images/stories/' . $child->image)) : ?>
						<img src="images/stories/<?php echo $child->image; ?>"
							 align="<?php echo $child->image_position; ?>"/>
					<?php endif; ?>
					<a href="<?php echo $child->link; ?>"><?php echo $child->title; ?></a>
					<?php if ($this->params->get('show_category_childs_description') && !empty($child->description)) : ?>
						<p class="simplelists-children-description"><?php echo $child->description; ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php if ($this->params->get('show_index') && !empty($this->items)) : ?>
	<div class="simplelists-index">
		<ul>
			<?php foreach ($this->items as $item) : ?>
				<li><a href="<?php echo $this->url; ?>#<?php echo $item->href; ?>"><?php echo $item->title; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php if ($this->params->get('use_pagination') && $pagination->getPaginationPages() > 0) : ?>
	<div class="simplelists-pagecounter">
		<?php echo $pagination->getPagesCounter(); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->get('show_header_modules') == 1)
{
	$modules = JModuleHelper::getModules('simplelists-header');
	if (!empty($modules))
	{
		foreach ($modules as $m)
		{
			?>
			<div class="simplelists-header">
				<?php echo JModuleHelper::renderModule($m); ?>
			</div>
			<?php
		}
	}
} ?>

<?php if ($this->params->get('show_alpha_index') == 1) : ?>
	<?php echo $this->loadTemplate('_alphabar'); ?>
<?php endif; ?>
