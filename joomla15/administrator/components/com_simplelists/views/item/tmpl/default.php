<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2012
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
jQuery(document).ready(function(){
    jQuery('ul.nav-tabs a[href="#<?php echo $this->activeTab; ?>"]').tab('show');
});
-->
</script>

<form method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li><a href="#basic" data-toggle="tab"><?php echo JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_BASIC');?></a></li>
                <li><a href="#text" data-toggle="tab"><?php echo JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_TEXT');?></a></li>
                <li><a href="#image" data-toggle="tab"><?php echo JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_IMAGE');?></a></li>
                <li><a href="#link" data-toggle="tab"><?php echo JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_LINK');?></a></li>
                <li><a href="#params" data-toggle="tab"><?php echo JText::_('COM_SIMPLELISTS_ITEM_FIELDSET_PARAMS');?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="basic">
                    <fieldset class="adminform">
                        <?php foreach($this->form->getFieldsets('basic') as $fieldset) : ?>
                        <div class="control-group form-inline">
                            <?php foreach($this->form->getFieldset($fieldset->name) as $field) : ?>
                                <?php echo $field->label; ?>
                                <div class="controls">
                                    <?php echo $field->input; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </fieldset>
                </div>
                <div class="tab-pane" id="text">
                    <fieldset class="form-vertical">
                        <?php foreach($this->form->getFieldsets('text') as $fieldset) : ?>
                        <div class="control-group form-inline">
                            <?php foreach($this->form->getFieldset($fieldset->name) as $field) : ?>
                                <div class="controls">
                                    <?php echo $field->input; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </fieldset>
                </div>
                <div class="tab-pane" id="image">
                    <fieldset class="form-vertical">
                        <div class="control-group">
                            <div class="controls">
                                <input type="text" id="picture_name" value="<?php echo $this->item->picture; ?>" />
                                <input type="hidden" id="picture" name="picture" value="<?php echo $this->item->picture; ?>" />
                            </div>
                            <div class="blank">
                                <a class="btn modal-button" title="<?php echo JText::_('COM_SIMPLELISTS_SELECT_IMAGE') ?>" href="<?php echo $this->modal['picture']; 
                                    ?>" onclick="IeCursorFix(); return false;" rel="{handler: 'iframe', size: {x: 800, y: 550}}">
                                    <i class="icon-picture"></i>
                                    <?php echo JText::_('COM_SIMPLELISTS_SELECT_IMAGE'); ?>
                                </a>
                            </div>
                            <div class="image-preview">
                            <?php if(JFile::exists($this->item->picture_path)): ?>
                                <img width="380" id="picture-preview" src="../<?php echo $this->item->picture_uri; ?>" name="item_picture" />
                            <?php else: ?>
                                <img width="380" id="picture-preview" src="../media/com_simplelists/images/blank.png" alt="<?php echo JText::_('No image'); ?>" name="item_picture" width="1" height="1" />' ;
                            <?php endif; ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="tab-pane tab-pane-plugins" id="link">
                    <fieldset class="form-vertical">
                        <?php if(!empty($this->link_plugins)) : ?>
                        <div class="control-group">
                        <?php foreach($this->link_plugins as $plugin) : ?>
                            <?php if($plugin->isEnabled() == false) continue; ?>
                            <?php $link_type = $plugin->getPluginName(); ?>
                            <?php $active = ($link_type == $this->item->link_type) ? true : false; ?>
                            <label class="simplelists_link" for="link_type_<?php echo $link_type; ?>" id="link_type_<?php echo $link_type; ?>_label">
                                <?php $checked = ($active) ? 'checked="checked"' : '' ; ?>
                                <input type="radio" class="simplelists_link" id="link_type_<?php echo $link_type; ?>" name="link_type" value="<?php echo $link_type; ?>" <?php echo $checked; ?> />
                                <?php echo $plugin->getTitle(); ?>
                            </label>
                            <?php $classes = array('simplelists_link_inner', 'simplelists_link_inner_type_'.$link_type); ?>
                            <?php if($active) $classes[] = 'simplelists_link_inner_active'; ?>
                            <div class="<?php echo implode(' ', $classes); ?>" id="simplelists_link_inner<?php echo $link_type; ?>">
                                <?php $current = ($link_type == $this->item->link_type) ? $this->item->link : null; ?>
                                <?php echo $plugin->getInput($current); ?>
                            </div>
                        <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <?php echo JText::_('You have not published any SimpleListsLink plugins'); ?>
                        <?php endif; ?>
                    </fieldset>
                </div>
                <div class="tab-pane" id="params">
                    <fieldset class="adminform">
                        <?php foreach($this->form->getFieldsets('params') as $fieldset) : ?>
                        <div class="control-group">
                            <?php foreach($this->form->getFieldset($fieldset->name) as $field) : ?>
                                <?php echo $field->label; ?>
                                <div class="controls">
                                    <?php echo $field->input; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="option" value="com_simplelists" />
<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
