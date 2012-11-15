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

<?php if( !empty( $this->items)) : ?>

<?php $columns = $this->params->get('columns', 1); ?>
<?php $i = 1; ?>
<table class="<?php echo $this->page_class; ?>">
<?php foreach( $this->items as $item ): ?>
     <tr>
        <td>
            <table>
                <tr class="simplelists-item">

                    <?php if($item->picture_alignment != 'right'): ?>
                    <td valign="middle" align="center" class="simplelists-item-image">
                        <?php if($item->picture): ?>
                        <?php echo $item->picture; ?>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>

                    <td valign="top" class="simplelists-item-content">
                        <a name="<?php echo $item->href; ?>"></a>
                        <?php if($item->title): ?>
                        <h3 class="contentheading"><?php echo $item->title; ?></h3>
                        <?php endif; ?>
        
                        <?php if($item->text): ?>
                        <?php echo $item->text; ?>
                        <?php endif; ?>

                        <?php if($item->readmore): ?>
                        <br/><?php echo $item->readmore; ?>
                        <?php endif; ?>
                    </td>

                    <?php if($item->picture_alignment == 'right'): ?>
                    <td valign="middle" align="center" class="simplelists-item-image">
                        <?php if($item->picture): ?>
                        <?php echo $item->picture; ?>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>

                </tr>
                <?php if($this->totop): ?>
                <tr>
                    <td colspan="2">
                        <?php echo $this->totop; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </td>
    </tr>
    <?php $i++; ?>
<?php endforeach; ?>
</table>
<?php else: ?>
    <?php echo $this->empty_list; ?>
<?php endif; ?>

<?php echo $this->loadTemplate('_footer'); ?>
