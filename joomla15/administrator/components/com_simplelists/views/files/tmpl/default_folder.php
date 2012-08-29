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

$root = $this->state->folder;
$folderImage = JURI::root().'media/com_simplelists/images/folder.gif';
?>
<div class="item">
	<a href="index.php?option=com_simplelists&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_uri; ?>&amp;type=<?php echo $this->state->type; ?>">
		<img src="<?php echo $folderImage; ?>" width="80" height="80" alt="<?php echo $this->_tmp_folder->name; ?>" />
		<span><?php echo $this->_tmp_folder->name; ?></span></a>
</div>
