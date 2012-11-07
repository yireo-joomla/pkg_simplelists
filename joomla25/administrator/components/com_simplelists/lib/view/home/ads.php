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
<h2 class="promotion_header"><?php echo JText::_('LIB_YIREO_VIEW_HOME_ADS'); ?></h2>
<div id="promotion">
    <?php if ($this->backend_feed == 1) { ?>
    <div class="loader" />
    <?php } else { ?>
    <?php echo JText::_('LIB_YIREO_VIEW_HOME_ADS_DISABLED'); ?>
    <?php } ?>
    </div>
</div>
<h2 class="latest_news_header"><?php echo JText::_('LIB_YIREO_VIEW_HOME_BLOG'); ?></h2>
<div id="latest_news">
    <?php if ($this->backend_feed == 1) { ?>
    <div class="loader" />
    <?php } else { ?>
    <?php echo JText::_('LIB_YIREO_VIEW_HOME_BLOG_DISABLED'); ?>
    <?php } ?>
</div>

</td>
