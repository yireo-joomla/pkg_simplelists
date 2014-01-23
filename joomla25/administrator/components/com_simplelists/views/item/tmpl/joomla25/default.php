<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Set the right image directory for JavaScipt
jimport('joomla.utilities.utility');
?>
<script language="javascript" type="text/javascript">
    <!--
    var image_directory = '<?php echo $this->item->image_default_uri; ?>';
    var form_no_title = '<?php echo JText::_('COM_SIMPLELISTS_ITEM_EMPTY_TITLE'); ?>' ;
    -->
</script>

<form method="post" name="adminForm" id="adminForm">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td width="50%" valign="top">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_BASIC'); ?></legend>
        <table class="admintable" width="100%">
        <tbody>
        <?php foreach($this->form->getFieldset('basic') as $field) : ?>
        <tr>
            <td class="name"><?php echo $field->label; ?></td>
            <td class="value"><?php echo $field->input; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </fieldset>
    <fieldset class="adminform">
        <legend><?php echo JText::_('LIB_YIREO_TABLE_FIELDNAME_TEXT'); ?></legend>
        <table class="admintable" width="100%">
        <tbody>
        <tr>
            <td class="value">
                <?php
                $editor = JFactory::getEditor();
                echo @$editor->display( 'text', $this->item->text, '100%', '300', '44', '9', array('pagebreak', 'readmore' )) ;
                ?>
            </td>
        </tr>
        </tbody>
        </table>
    </fieldset>
</td>
<td width="50%" valign="top">
    <?php echo $this->pane->startPane('content-pane'); ?>
    <?php echo $this->pane->startPanel(JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_IMAGE'), 'image'); ?>
    <table class="admintable">
    <tbody>
        <tr>
            <td>
                <input type="text" id="picture_name" value="<?php echo $this->item->picture; ?>" />
                <input type="hidden" id="picture" name="picture" value="<?php echo $this->item->picture; ?>" />
            </td>
            <td>
                <div class="button2-left">
                    <div class="blank">
                        <a class="modal-button" title="<?php echo JText::_('COM_SIMPLELISTS_SELECT_IMAGE') ?>" href="<?php echo $this->modal['picture']; 
                        ?>" rel="{handler: 'iframe', size: {x: 770, y: 500}}">
                        <?php echo JText::_('COM_SIMPLELISTS_SELECT_IMAGE'); ?>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px;">
                <?php 
                if(!empty($this->item->picture_path) && JFile::exists($this->item->picture_path)) {
                    echo '<img width="80" id="picture-preview" src="../'.$this->item->picture_uri.'" name="item_picture" />' ;
                } else {
                    echo '<img width="80" id="picture-preview" src="../media/com_simplelists/images/blank.png" alt="' . JText::_( 'No image' ) . '" name="item_picture" width="1" height="1" />' ;
                } ?>
            </td>
        </tr>
    </tbody>
    </table>
    <?php echo $this->pane->endPanel(); ?>
    <?php echo $this->pane->startPanel(JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_LINK'), 'link'); ?>
    <?php if(!empty($this->link_plugins)) { ?>
    <table class="paramlist admintable" width="100%">
    <tbody>
        <?php foreach($this->link_plugins as $plugin) { ?>
            <?php if($plugin->isEnabled() == false) continue; ?>
            <?php $link_type = $plugin->getPluginName(); ?>
            <?php $active = ($link_type == $this->item->link_type) ? true : false; ?>
            <tr>
                <td valign="top" align="right" class="key">
                    <?php $checked = ($active) ? 'checked="checked"' : '' ; ?>
                    <input type="radio" class="simplelists_link" id="link_type_<?php echo $link_type; ?>" name="link_type" value="<?php echo $link_type; ?>" <?php echo $checked; ?> />
                </td>
                <td>
                    <?php $classes = array('simplelists_link_inner', 'simplelists_link_inner_type_'.$link_type); ?>
                    <?php if($active) $classes[] = 'simplelists_link_inner_active'; ?>
                    <div class="<?php echo implode(' ', $classes); ?>" id="simplelists_link_inner<?php echo $link_type; ?>">
                    <label class="simplelists_link" for="link_type_<?php echo $link_type; ?>" id="link_type_<?php echo $link_type; ?>_label">
                        <?php echo $plugin->getTitle(); ?>
                    </label>
                    <?php $current = ($link_type == $this->item->link_type) ? $this->item->link : null; ?>
                    <?php echo $plugin->getInput($current); ?>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
    </table>
    <?php } else { ?>
        <p style="padding:10px;"><?php echo JText::_('You have not published any SimpleListsLink plugins'); ?></p>
    <?php } ?>
    <?php echo $this->pane->endPanel(); ?>
    <?php if(YireoHelper::isJoomla15()) { ?>
        <?php echo $this->pane->startPanel('Parameters', 'params'); ?>
        <table class="paramlist admintable" cellspacing="1">
        <tbody>
            <tr>
                <td colspan="2" class="params">
                    <?php echo $this->params->render();?>
                </td>
            </tr>
        </tbody>
        </table>
        <?php echo $this->pane->endPanel(); ?>
    <?php } elseif($this->form) { ?>
        <?php foreach($this->form->getFieldsets() as $fieldset) { ?>
            <?php if(in_array($fieldset->name, array('text','basic'))) continue; ?>
            <?php echo $this->pane->startPanel(JText::_($fieldset->label), $fieldset->name); ?>
            <table class="paramlist admintable" cellspacing="1">
                <tbody>
                <tr>
                     <td colspan="2" class="params">
                        <?php foreach($this->form->getFieldset($fieldset->name) as $field) { ?>
                        <dl>
                            <dt><?php echo $field->label; ?></dt>
                            <dd><?php echo $field->input; ?></dd>
                        <dl>
                        <?php } ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php echo $this->pane->endPanel(); ?>
        <?php } ?>
    <?php } ?>
    <?php echo $this->pane->endPane(); ?>
</td>
</tr>
</tbody>
</table>

<input type="hidden" name="option" value="com_simplelists" />
<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
