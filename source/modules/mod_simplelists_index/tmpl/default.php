<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access'); 
?>

<?php if(!empty($items)) : ?>
<ul>
<?php foreach( $items as $item ) : ?>
    <li><a href="#<?php echo $item->alias; ?>"><?php echo $item->title; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
