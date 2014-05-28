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
defined('_JEXEC') or die();

if( $this->_tmp_file->path_relative == '/'.$this->get('state')->current ) {
    $class = ' current-item';
} elseif( $this->_tmp_file->path_relative == $this->get('state')->current ) {
    $class = ' current-item';
} else {
    $class = '';
}

$max_chars = 12;
$short_name = $this->_tmp_file->name;
if( strlen( $short_name ) > $max_chars ) {
    $short_name = substr( $short_name, 0, $max_chars ) . '...';
}
?>
<div class="item<?php echo $class; ?>">
    <a 
        href="javascript:setModalItem('<?php echo $this->_tmp_file->path_uri; ?>', '<?php echo md5($this->_tmp_file->path); ?>')" 
        id="<?php echo md5($this->_tmp_file->path); ?>" 
        title="<?php echo $this->_tmp_file->name; ?>">
    <img
        src="<?php echo $this->_tmp_file->src; ?>"  
        width="<?php echo $this->_tmp_file->width; ?>" 
        height="<?php echo $this->_tmp_file->height; ?>" 
        alt="<?php echo $this->_tmp_file->name; ?>"
        title="<?php $this->_tmp_file->name; ?>" />
    <span title="<?php echo $this->_tmp_file->name; ?>"><?php echo $short_name; ?></span></a>
</div>
