/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2015 Yireo
 * @license GNU/GPL
 * @link http://www.yireo.com/
 */

jQuery(document).ready(function() {

    // Automatically select category ID if it is still empty
    var categorySelectId = 'jform_request_category_id';
    var categorySelect = jQuery('#' + categorySelectId);

    if (categorySelect) {
        categoryId = categorySelect.val();

        if (categoryId == 0) {
            jQuery('#' + categorySelectId + ' > option').each(function() {
               if (this.value != 0) {
                   categorySelect.val(this.value);
                   return;
               }
            });
        }
    }
});