<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2013
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

switch( $this->state->type ) {
    case 'picture':
        $jSelect = 'slSelectPicture';
        break;
    case 'link_image':
        $jSelect = 'slSelectLinkImage';
        break;
    case 'link_file':
        $jSelect = 'slSelectLinkFile';
        break;
    case 'default':
        $jSelect = 'slSelectLinkImage';
        break;
}
$base_path = $this->state->folder;
$indicator = $this->state->folder;
?>
<script type="text/javascript">
var base_path = '<?php echo $base_path; ?>';
</script>
<form action="index.php" id="fileForm" method="post" enctype="multipart/form-data">
	<fieldset>
		<div style="float:left" id="folder-indicator"><?php echo $indicator; ?></div>
		<div style="float:right">
			<button type="button" class="button btn" onclick="javascript:submitModalForm(window.parent.<?php echo $jSelect; ?>, current_item);"><?php echo JText::_('Select') ?></button>
			<button type="button" class="button btn" onclick="javascript:submitModalForm(window.parent.<?php echo $jSelect; ?>, '');"><?php echo JText::_('Reset') ?></button>
			<button type="button" class="button btn" onclick="javascript:submitModalForm(window.parent.slSelectNothing());"><?php echo JText::_('Cancel') ?></button>
		</div>
	</fieldset>
    <p><?php echo count($this->files); ?> <?php echo JText::_('Files'); ?>, <?php echo count($this->folders); ?> <?php echo JText::_('Subfolders'); ?></p>
    <div class="manager">
    <?php 
    echo $this->loadTemplate('default_parent');

    if( count( $this->folders ) > 0 ) {
        for ($i=0,$n=count($this->folders); $i<$n; $i++) {
            $this->setFolder($i);
            echo $this->loadTemplate('default_folder');
        } 
    }

    if( count( $this->files ) > 0 ) {
        for ($i=0,$n=count($this->files); $i<$n; $i++) {
            $this->setFile($i);
            echo $this->loadTemplate('default_item');
        }
    }

    if(!is_readable( JPATH_SITE.'/'.$base_path )) {
        $message = JText::_( 'Folder not readable' );
    } elseif( empty( $this->files ) && empty( $this->folders )) {
        $message = JText::_( 'No files or folders found' );
    } elseif( empty( $this->files )) {
        $message = JText::_( 'No files found' );
    }

    if(!empty($message)) {
        echo '<div id="files-message">'.$message.'</div>';
    }
    ?>
    </div>

	<input type="hidden" id="dirPath" name="dirPath" />
	<input type="hidden" id="f_file" name="f_file" />
	<input type="hidden" id="tmpl" name="component" />
</form>
<?php echo $this->loadTemplate('default_upload'); ?>
