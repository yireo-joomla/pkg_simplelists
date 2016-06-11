<?php
/**
 * Joomla! component SimpleLists
 *
 * @author    Yireo
 * @copyright Copyright 2016
 * @license   GNU Public License
 * @link      https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access');

foreach ($displayData as $name => $value)
{
	$$name = $value;
}
?>
<div class="simplelists simplelists-raw">
	<?php if (!empty($items)): ?>
		<?php foreach ($items as $item): ?>
			<?php print_r($item); ?>
		<?php endforeach; ?>
	<?php else: ?>
		<?php echo $empty_list; ?>
	<?php endif; ?>
</div>