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

<?php if(!empty( $this->items)): ?>
<div id="simplelists-toggle" class="accordion <?php echo $this->page_class; ?>">
<?php $i = 0; ?>
<?php foreach( $this->items as $item ): ?>
    <div class="accordion-group <?php echo $item->class; ?>">
        <div class="accordion-heading heading" style="background-color:<?php echo $this->params->get('header_bgcolor', 'none'); ?>">
            <?php if($this->params->get('disable_jumplabels', 0) == 0) { ?>
            <a name="<?php echo $item->href; ?>" />
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#simplelists-toggle" href="#<?php echo $item->href; ?>" style="color:<?php echo $this->params->get('header_fgcolor'); ?>"><?php echo $item->title; ?></a>
            <?php } else { ?>
            <a class="heading" onclick="return false;" style="color:<?php echo $this->params->get('header_fgcolor', 'none'); ?>"><?php echo $item->title; ?></a>
            <?php } ?>
        </div>
        <div class="accordion-body collapse <?php if($i == 0) echo 'in'; ?>" id="<?php echo $item->href; ?>">
            <div class="accordion-inner">
                <p>
                <?php if($item->picture): ?>
                <?php echo $item->picture; ?>
                <?php endif; ?>

                <?php if($item->text): ?>
                <?php echo $item->text; ?>
                <?php endif; ?>

                <?php if($item->readmore): ?>
                <br/><?php echo $item->readmore; ?>
                <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    <?php $i++; ?>
<?php endforeach; ?>
</div>
<?php else: ?>
    <?php echo $this->empty_list; ?>
<?php endif; ?>

<?php echo $this->loadTemplate('_footer'); ?>
