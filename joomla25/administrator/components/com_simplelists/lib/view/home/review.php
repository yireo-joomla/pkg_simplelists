<?php
/*
 * Joomla! Yireo Library
 *
 * @author Yireo (http://www.yireo.com/)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.5.0
 */

defined('_JEXEC') or die('Restricted access');
?>
<div class="review">
    <?php echo JText::sprintf('LIB_YIREO_VIEW_HOME_LIKE', $extension); ?><br/>
    <?php echo JText::_('LIB_YIREO_VIEW_HOME_REVIEW'); ?><br/>
    <a href="<?php echo $this->urls['jed']; ?>"><?php echo $this->urls['jed']; ?></a>
</div>
