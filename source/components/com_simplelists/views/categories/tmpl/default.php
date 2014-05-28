<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->category->title) { ?><h1 class="componentheading"><?php echo $this->category->title ?></h1><?php } ?>

<?php if ($this->category->image || $this->category->description) { ?>
<div class="simplelists-category">
    <?php if ($this->category->image) { echo $this->category->image; } ?>
    <?php if ($this->category->description) { ?><span id="simplelists-description"><?php echo $this->category->description ?></span><?php } ?>
</div>
<?php } ?>


<?php if ($this->pagination) { ?>
    <div class="simplelists-pagecounter">
    <?php echo $this->pagination->getPagesCounter(); ?>
    </div>
<?php } ?>

<div class="simplelists-categories">
<?php if (!empty($this->items)) { ?>
    <?php foreach ($this->items as $item) { ?>
        <div class="simplelists-category">
        <h2 class="componentheading"><a href="<?php echo $item->link; ?>"><?php echo $item->title ?></a></h2>
        <?php if ($item->description) { ?>
            <span id="simplelists-description"><?php echo $item->description; ?></span>
        <?php } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <?php echo $this->message ?>
<?php } ?>
</div>

<?php if ($this->pagination) echo $this->pagination->getPagesLinks(); ?>
