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
		<?php
			echo $this->lists['category_id'];
			echo $this->lists['link_type'];
			echo $this->lists['state'];
		?>
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
				<?php echo JHTML::_('grid.sort',  'Title', 'item.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="40" class="title">
				<?php echo JHTML::_('grid.sort',  'Image', 'item.picture', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="40" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Status', 'item.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th nowrap="nowrap">
                <?php echo $this->getGridHeader('orderby', 'Order'); ?>
			</th>
			<th width="15%"  class="title">
				<?php echo JText::_('Categories'); ?>
			</th>
			<th width="100"  class="title">
				<?php echo JHTML::_('grid.sort',  'Link type', 'link_type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="12%"  class="title">
				<?php echo JHTML::_('grid.sort',  'Link', 'link', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
            <th width="9%">
                <?php echo JHTML::_('grid.sort',   'Access', 'access', @$lists['order_Dir'], @$lists['order'] ); ?>
            </th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Hits', 'item.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 'item.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="11">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
    if(count($this->items) > 0) {
	    $k = 0;
        $i = 0;
        $n = count($this->items);
        foreach($this->items as $item) {
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset( $i ); ?>
                </td>
                <td>
                    <?php echo $this->getGridCell('checked', $item, $i); ?>
                </td>
                <td>
                    <?php if (JTable::isCheckedOut($this->user->get('id'), $item->checked_out)) { ?>
                        <?php echo $item->title; ?>
                    <?php } else { ?>
                        <a href="<?php echo $item->edit_link; ?>" title="<?php echo JText::_( 'Edit item' ); ?>">
                            <?php echo $item->title; ?></a>
                    <?php } ?>
                </td>
                <td align="center">
                    <?php if( $item->picture ) { ?>
                    <a class="modal" href="<?php echo JURI::base() . '../' . $item->picture;?>">
                        <img src="../media/com_simplelists/images/image.png" title="<?php echo basename( $item->picture );?>" />
                    </a>
                    <?php } else { ?>
                    &nbsp;
                    <?php } ?>
                </td>
                <td align="center">
                    <?php echo $this->getGridCell('published', $item, $i); ?>
                </td>
                <td class="order">
                    <?php echo $this->getGridCell('reorder', $item, $i, $n); ?>
                </td>
                <td>
                    <?php
                    if(count($item->categories) > 0) {
                        foreach($item->categories as $category) {
                            $link = JRoute::_( 'index.php?option=com_simplelists&view=category&task=edit&cid[]='.$category->id);
                            ?>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit Category' ); ?>"><?php echo $category->title; ?></a><br/>
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
                <td align="center">
                    <?php echo $item->id; ?>
                </td>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;
        }
    } else {
        ?>
        <tr>
        <td colspan="11">
            <?php echo JText::_( 'No items' ); ?>
        </td>
        </tr>
        <?php
    }
	?>
	</tbody>
	</table>
</div>

<input type="hidden" name="option" value="com_simplelists" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
