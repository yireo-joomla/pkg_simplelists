<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$cparams = JComponentHelper::getParams ('com_media');
?>
<table width="100%">
    <tr>
        <td align="left" valign="top">
            <form action="index.php?option=com_simplelists&amp;view=item" method="post" name="adminForm" id="adminForm">
                <fieldset class="adminform">
                    <legend><?php echo JText::_( 'Details' ); ?></legend>
                    <table class="admintable">
                    <tr>
                        <td width="100" align="right" class="key">
                            <label for="title">
                                <?php echo JText::_( 'Name' ); ?>:
                            </label>
                        </td>
                        <td class="value">
                            <input class="required" type="text" name="title" id="title" size="48" maxlength="250" value="<?php echo $this->item->title;?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="100" align="right" class="key">
                            <label for="alias">
                                <?php echo JText::_( 'Alias' ); ?>:
                            </label>
                        </td>
                        <td class="value">
                            <input class="" type="text" name="alias" id="alias" size="48" maxlength="250" value="<?php echo $this->item->alias;?>" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right" class="key">
                            <?php echo JText::_( 'Published' ); ?>:
                        </td>
                        <td class="value">
                            <?php echo $this->lists['published']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right" class="key">
                            <label for="parent_id">
                                <?php echo JText::_( 'Parent category' ); ?>:
                            </label>
                        </td>
                        <td class="value">
                            <?php echo $this->lists['parent_id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="key">
                            <label for="access">
                                <?php echo JText::_( 'Access Level' ); ?>:
                            </label>
                        </td>
                        <td class="value">
                            <?php echo $this->lists['access']; ?>
                        </td>
                    </tr>
                    <?php /*
                    <tr>
                        <td valign="top" class="key">
                            <label for="image">
                                <?php echo JText::_( 'Image' ); ?>
                            </label>
                        </td>
                        <td class="value">
                            <div class="button2-left">
                                <div class="blank">
                                    <a class="modal-button" title="<?php echo JText::_('Select an Image') ?>" href="<?php echo $this->modal['image']; 
                                    ?>" rel="{handler: 'iframe', size: {x: 770, y: 500}}">
                                    <?php echo JText::_('Select an Image'); ?>
                                    </a>
                                </div>
                            </div>
                            &nbsp;
                            <input type="text" id="image_name" value="<?php echo $this->item->image; ?>" disabled="disabled" />
                            <input type="hidden" id="image" name="image" value="<?php echo $this->item->image; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="key">
                            <label for="image_position">
                                <?php echo JText::_( 'Image Position' ); ?>:
                            </label>
                        </td>
                        <td class="value">
                            <?php echo $this->lists['image_position']; ?>
                        </td>
                    </tr>
                    */ ?>
                </table>
            </fieldset>
            <fieldset class="adminform">
                <legend><?php echo JText::_( 'Text' ); ?></legend>
                <table class="admintable" width="100%">
                    <tr>
                        <td class="value">
                            <?php
                            $editor = JFactory::getEditor();
                            echo $editor->display( 'description', $this->item->description, '100%', '300', '44', '9' ) ;
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td>
        <td width="480" valign="top" class="params">
            <?php 
            $groups = array(
                array('params-category', 'Category', 'details'),
                array('params-layout', 'Layout', 'layout'),
                array('metadata-category', 'Metadata', 'metadata'),
            ); 

            echo $this->pane->startPane('content-pane');
                
            foreach($groups as $group) {
                $title = JText::_('Parameters').' ('.JText::_($group[1]).')';
                echo $this->pane->startPanel($title, $group[0]);
                foreach($this->form->getFieldset($group[2]) as $field) {
                    ?>
                    <dl>
                        <dt><?php echo $field->label; ?></dt>
                        <dd><?php echo $field->input; ?></dd>
                    <dl>
                    <?php
                }
                echo $this->pane->endPanel();
            }
            echo $this->pane->endPane();
            ?>
        </td>
    </tr>
</table>
<input type="hidden" name="option" value="com_simplelists" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
