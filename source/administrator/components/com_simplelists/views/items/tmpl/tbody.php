<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo
 * @package YireoLib
 * @copyright Copyright 2016
 * @license GNU Public License
 * @link https://www.yireo.com/
 * @version 0.4.3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<td>
    <?php if($this->isCheckedOut($item)) { ?>
        <span class="checked_out"><?php echo $item->title; ?></span>
    <?php } else { ?>
        <a href="<?php echo $item->edit_link; ?>" title="<?php echo JText::_('LIB_YIREO_VIEW_EDIT'); ?>"><?php echo $item->title; ?></a>
    <?php } ?>
</td>
<td align="center">
    <?php if( $item->picture ) { ?>
    <a class="modal" href="<?php echo JUri::base() . '../' . $item->picture;?>">
        <img src="../media/com_simplelists/images/image.png" title="<?php echo basename( $item->picture );?>" />
    </a>
    <?php } else { ?>
    &nbsp;
    <?php } ?>
</td>
<td>
    <?php
    if(count($item->categories) > 0) {
        foreach($item->categories as $category) {
            $link = JRoute::_( 'index.php?option=com_simplelists&view=category&task=edit&cid[]='.$category->id);
            ?>
            <a href="<?php echo $link; ?>" title="<?php echo JText::_('LIB_YIREO_VIEW_EDIT'); ?>"><?php echo $category->title; ?></a><br/>
            <?php
        }
    }
    ?>
</td>
<td>
    <?php echo SimplelistsPluginHelper::getPluginLinkTitle($item); ?>
</td>
<td>
    <?php 
    if($item->link_type == 4) { 
        ?>
        <a class="modal" href="/<?php echo $item->link;?>"><?php echo basename($item->link);?></a>
        <?php
    } else {
        echo SimplelistsPluginHelper::getPluginLinkName($item);
    }
    ?>
</td>
<td align="center">
    <?php echo $item->accesslevel;?>
</td>
<td align="center">
    <?php echo $item->hits; ?>
</td>
