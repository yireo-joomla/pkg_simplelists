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
<?php if(isset($this->urls['twitter'])) : ?>
<div class="twitter">
    <?php echo JText::_('LIB_YIREO_VIEW_HOME_TWITTER'); ?>:<br/>
    <a href="<?php echo $this->urls['twitter']; ?>"><?php echo $this->urls['twitter']; ?></a>
</div>
<?php endif; ?>
