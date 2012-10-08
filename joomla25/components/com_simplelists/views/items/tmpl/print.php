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

<?php if( $this->params->get('show_category_image') || $this->params->get('show_category_description') ) : ?>
    <?php if( $this->params->get('show_category_image') ) : ?>
    <?php echo $this->category->image; ?>
    <?php endif;?>

    <?php if( $this->params->get('show_category_description') ) : ?>
        <p><?php echo $this->category->description ?></p>
    <?php endif; ?>
    <br/>
    <br/>
    <br/>
<?php endif; ?>

<?php if( !empty($this->items)) : ?>
<?php foreach($this->items as $item): ?>

<?php if($item->picture): ?>
<?php echo $item->picture; ?>
<?php endif; ?>

<?php if($item->title): ?>
<h3><?php echo $item->title; ?></h3>
<?php endif; ?>

<?php if($item->text): ?>
<?php echo $item->text; ?>
<?php endif; ?>

<?php if($item->readmore): ?>
<br/>
<?php echo $item->readmore; ?>
<?php endif; ?>
<br/>
<br/>
<br/>

<?php endforeach; ?>
<?php endif; ?>

