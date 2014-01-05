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

// Set toolbar items for the page
JToolBarHelper::title( JText::_( 'SimpleLists' ).' - '.JText::_( 'Update' ));
JToolBarHelper::cancel();
?>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="50%" valign="top">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Current version' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <?php echo JText::_( 'Component' ); ?>
            </td>
            <td>
                <?php echo $this->data['name']; ?>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <?php echo JText::_( 'Version' ); ?>
            </td>
            <td>
                <?php echo $this->data['version']; ?>
            </td>
        </tr>
        </table>
    </fieldset>
</td>
</tr>
<tr>
<td width="50%" valign="top">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Available version' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <?php echo JText::_( 'Version' ); ?>
            </td>
            <td>
                <?php echo $this->update['version']; ?>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <?php echo JText::_( 'Update' ); ?>
            </td>
            <td>
                <?php if(version_compare($this->update['version'], $this->data['version'], '>')) { ?>
                <form method="post" name="adminForm" id="adminForm">
                    <input type="hidden" name="option" value="com_installer" />
                    <input type="hidden" name="task" value="doInstall" />
                    <input type="hidden" name="installtype" value="url" />
                    <input type="hidden" name="install_url" value="<?php echo $this->update['install']; ?>">
                    <input type="submit" name="submit" value="<?php echo JText::_( 'Update Now' ); ?>" />
                    <?php echo JHTML::_( 'form.token' ); ?>
                </form>
                <?php } elseif(!empty($this->update['version'])) { ?>
                    <?php echo JText::_( 'No update needed' ); ?>
                <?php } else { ?>
                    <?php echo JText::_( 'Failed to retrieve update' ); ?>
                <?php } ?>
            </td>
        </tr>
        </table>
    </fieldset>
</td>
</tr>
</table>
                    
