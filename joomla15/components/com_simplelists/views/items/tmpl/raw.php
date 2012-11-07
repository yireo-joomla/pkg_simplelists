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

<div class="simplelists simplelists-raw">
<?php if( !empty($this->items)): ?>
    <?php foreach($this->items as $item): ?>
        <?php
        print_r( $item );
        ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php echo $this->empty_list; ?>
<?php endif; ?>
</div>

<?php echo $this->loadTemplate('_footer'); ?>
