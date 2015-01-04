<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); 
?>

<div class="simplelists-item">

    <div class="image">
        <?php if($this->item->picture): ?>
        <?php echo $this->item->picture; ?>
        <?php endif; ?>
    </div>

    <div class="body">
        <?php if($this->item->title): ?>
        <h3 class="contentheading"><?php echo $this->item->title ?></h3>
        <?php endif; ?>
        
        <?php if($this->item->text): ?>
        <?php echo $this->item->text; ?>
        <?php endif; ?>

        <?php if($this->item->extra): ?>
        <?php echo $this->item->extra; ?>
        <?php endif; ?>

    </div>

</div>
