<?php
/*
 * Joomla! Yireo Library
 *
 * @author Yireo (http://www.yireo.com/)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.5.1
 */

defined('_JEXEC') or die('Restricted access');
?>
<form method="post" name="adminForm" id="adminForm">
<table id="adminform" width="100%">
<tr>
<td width="60%" valign="top">

<div id="cpanel">
<?php echo $this->loadTemplate('cpanel'); ?>
<?php echo $this->loadTemplate('version'); ?>
<?php echo $this->loadTemplate('review', array('extension' => YireoHelper::getData('title'))); ?>
<?php echo $this->loadTemplate('twitter'); ?>
<?php echo $this->loadTemplate('facebook'); ?>
</div>

</td>
<td width="40%" valign="top" style="margin-top:0; padding:0">
<?php echo $this->loadTemplate('ads'); ?>
</td>
</tr>
</table>
<input type="hidden" name="option" value="com_simplelists" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
