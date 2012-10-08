<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the parent view
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/view.php';

// Require the YireoHelper class
require_once JPATH_ADMINISTRATOR.'/components/com_simplelists/lib/helper.php';

/**
 * Feed View class for the SimpleLists component
 */
class SimplelistsViewItems extends YireoView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Fetch the document and set the current URL as feed-point
        $document =& JFactory::getDocument();

        // Load the model
        $model =& $this->getModel();

        // Get the category from our model
        $category = $model->getCategory() ;

        // Automatically fetch items, total and pagination - and assign them to the template
        $this->setAutoClean(false);
        $this->fetchItems();

        // Set the document properties
        $needles = array('category_id' => $category->id, 'category_alias' => $category->alias);
        $category_url = SimpleListsHelper::getUrl($needles);
        $document->set('link', $category_url );
        $document->setGenerator('');

        // Check if the list is empty
        if(is_array($this->items) && !empty($this->items)) {

            // Loop through the list to set things right
            foreach($this->items as $id => $item ) {

                // Initialize the feed-item
                $feed = new JFeedItem();
                $feed->set('title', $item->title);
                $feed->set('link', $category_url.'#item'.$item->id);
                $feed->set('description', $item->text);
                $feed->set('category', $category->title);

                // Set the date
                $modified = strtotime($item->modified);
                $created = strtotime($item->created);
                if($modified > 0) {
                    $feed->set('date', $item->modified);
                } elseif($created > 0) {
                    $feed->set('date', $item->created);
                } else {
                    $feed->set('date', date('R'));
                }
                
                $document->addItem($feed);
            }
        }
    }
}
