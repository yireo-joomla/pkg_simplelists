<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2011
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<form method="post" name="adminForm">
<table>
<tr>
    <td align="left" width="100%">
        <?php echo JText::_( 'Filter' ); ?>:
        <input type="text" name="<?php echo $this->lists['search_name']; ?>" id="search" value="<?php echo $this->lists['search'];?>" class="text_area"
onchange="document.adminForm.submit();" />
        <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
    </td>
    <td nowrap="nowrap">
        <?php echo $this->lists['listview']; ?>
    </td>
</tr>
</table>
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="5">
                #
            </th>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort',  'Title', 'category.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
            <th class="title">
                <?php echo JText::_( 'Parent' ); ?>
            </th>
            <th width="5%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  'Status', 'category.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
            <th width="8%" nowrap="nowrap">
                <?php echo $this->getGridHeader('orderby', 'Order'); ?>
            </th>
            <th width="7%">
                <?php echo JHTML::_('grid.sort',   'Access', 'accesslevel', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
            <th width="5%">
                <?php echo JText::_( 'Items' ); ?>
            </th>
            <th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  'ID', 'category.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="10">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    if( count( $this->items ) > 0 ) {
        for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
            $item = &$this->items[$i];
            $link = JRoute::_( 'index.php?option=com_simplelists&view=category&task=edit&cid[]='. $item->id );
            $parent_link = JRoute::_( 'index.php?option=com_simplelists&view=category&task=edit&cid[]='. $item->parent_id );
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset( $i ); ?>
                </td>
                <td>
                    <?php echo $this->getGridCell('checked', $item, $i); ?>
                </td>
                <td>
                    <?php
                    if(isset( $item->level )) {
                        echo SimplelistsCategoryTree::getIndent( $item->level );
                    }
                    if (  JTable::isCheckedOut($this->user->get ('id'), $item->checked_out ) ) {
                        echo $item->title;
                    } else {
                    ?>
                        <a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit category' ); ?>">
                            <?php echo $item->title; ?></a>
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php echo $item->parent_title; ?>
                </td>
                <td align="center">
                    <?php echo $this->getGridCell('published', $item, $i); ?>
                </td>
                <td class="order">
                    <?php echo $this->getGridCell('reorder', $item, $i, $n); ?>
                </td>
                <td align="center">
                    <?php echo $item->accesslevel;?>
                </td>
                <td align="center">
                    <?php echo SimpleListsHelper::getNumItems( $item->id ); ?>
                </td>
                <td align="center">
                    <?php echo $item->id; ?>
                </td>
            </tr>
            <?php
            $k = 1 - $k;
        }
    } else {
        ?>
        <tr>
        <td colspan="10">
            <?php echo JText::_( 'No categories' ); ?>
        </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
</div>

<input type="hidden" name="option" value="com_simplelists" />
<input type="hidden" name="view" value="categories" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->getFilter('order'); ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->getFilter('order_Dir'); ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
