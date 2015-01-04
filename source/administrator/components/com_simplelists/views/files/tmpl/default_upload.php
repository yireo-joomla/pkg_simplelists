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

$user = JFactory::getUser();
$session = JFactory::getSession();
$config = JFactory::getConfig();
$token = (method_exists('JSession', 'getFormToken')) ? JSession::getFormToken() : JUtility::getToken();

$folder = JFactory::getApplication()->getUserStateFromRequest('com_simplelists.files.folder', 'folder', 'images/simplelists');
$folder = preg_replace('/^images\//', '', $folder);
?>
<div style="clear:both; height:20px;"></div>
<?php if($user->authorise('core.create', 'com_media')): ?>
	<form action="<?php echo JURI::base(); ?>index.php?option=com_media&amp;task=file.upload&amp;folder=<?php echo $folder; ?>&amp;tmpl=component&amp;<?php echo $session->getName().'='.$session->getId(); ?>&amp;<?php echo $token;?>=1&amp;asset=<?php echo JRequest::getCmd('asset');?>&amp;author=<?php echo JRequest::getCmd('author');?>&amp;format=<?php echo $config->get('enable_flash')=='1' ? 'json' : '' ?>" id="uploadForm" name="uploadForm" method="post" enctype="multipart/form-data">
		<fieldset id="uploadform">
			<legend><?php echo $config->get('upload_maxsize')=='0' ? JText::_('COM_SIMPLELISTS_UPLOAD_FILES_NOLIMIT') : JText::sprintf('COM_SIMPLELISTS_UPLOAD_FILES', $config->get('upload_maxsize')); ?></legend>
			<fieldset id="upload-noflash" class="actions">
				<label for="upload-file" class="hidelabeltxt"><?php echo JText::_('COM_SIMPLELISTS_UPLOAD_FILE'); ?></label>
				<input type="file" id="upload-file" name="Filedata" />
				<label for="upload-submit" class="hidelabeltxt"><?php echo JText::_('COM_SIMPLELISTS_START_UPLOAD'); ?></label>
				<input class="btn" type="submit" id="upload-submit" value="<?php echo JText::_('COM_SIMPLELISTS_START_UPLOAD'); ?>"/>
			</fieldset>
			<div id="upload-flash" class="hide">
				<ul>
					<li><a href="#" id="upload-browse"><?php echo JText::_('COM_SIMPLELISTS_BROWSE_FILES'); ?></a></li>
					<li><a href="#" id="upload-clear"><?php echo JText::_('COM_SIMPLELISTS_CLEAR_LIST'); ?></a></li>
					<li><a href="#" id="upload-start"><?php echo JText::_('COM_SIMPLELISTS_START_UPLOAD'); ?></a></li>
				</ul>
				<div class="clr"> </div>
				<p class="overall-title"></p>
				<?php echo JHtml::_('image','media/bar.gif', JText::_('COM_SIMPLELISTS_OVERALL_PROGRESS'), array('class' => 'progress overall-progress'), true); ?>
				<div class="clr"> </div>
				<p class="current-title"></p>
				<?php echo JHtml::_('image','media/bar.gif', JText::_('COM_SIMPLELISTS_CURRENT_PROGRESS'), array('class' => 'progress current-progress'), true); ?>
				<p class="current-text"></p>
			</div>
			<ul class="upload-queue" id="upload-queue">
				<li style="display: none"></li>
			</ul>
			<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_simplelists&view=files&tmpl=component&type=picture'); ?>" />
		</fieldset>
	</form>
<?php  endif; ?>
