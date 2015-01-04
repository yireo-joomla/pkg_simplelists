<?php 
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

defined('_JEXEC') or die('Restricted access'); 
?>

<?php if( $this->params->get('show_footer_modules') == 1 ) : ?>
    <?php $modules =& JModuleHelper::getModules( 'simplelists_footer' );
    if( !empty( $modules )) { foreach( $modules as $m ) { ?>

        <div class="simplelists-footer">
        <?php echo JModuleHelper::renderModule( $m ); ?>
        </div>

    <?php }} ?>
<?php endif; ?>

<?php if( $this->params->get('show_category_parent') == 1 && !empty($this->category->parent )): ?>
<ul class="pagination">
    <li>    
        <a href="<?php echo $this->category->parent->link; ?>"><?php echo JText::sprintf( 'Back to', $this->category->parent->title ); ?></a>
    </li>
</ul>
<?php endif; ?>

<?php if($this->params->get('use_pagination')): ?>
    <div class="simplelists-pagenavigation">
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

