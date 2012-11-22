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

/**
 * HTML View class 
 */
class SimplelistsViewItems extends YireoView
{
    /*
     * Method to display the content 
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        $this->prepareDisplay();
        parent::display($this->getLayout(), false);
    }

    /*
     * Method to prepare for displaying (used by SimpleLists views but also SimpleLists Content Plugin)
     *
     * @param null
     * @return null
     */
    public function prepareDisplay()
    {
        // Get important system variables
        $document =& JFactory::getDocument();
        $uri = &JFactory::getURI();

        // Determine the current layout
        if($this->params->get('layout') != '') {
            $layout = $this->params->get('layout');
            $this->setLayout($layout) ;
        } else {
            $layout = $this->getLayout() ;
        }

        // Load the model
        $model =& $this->getModel();

        // Get the category from our model and prepare it
        $category = $model->getCategory() ;
        $this->prepareCategory($category, $layout);

        // Prepare the HTML-document
        $this->prepareDocument($category);

        // Automatically fetch items, total and pagination - and assign them to the template
        $this->setAutoClean(false);
        $this->fetchItems();

        // Set the URL of this page
        $url = $uri->toString();

        // Set the to-top image
        if( $this->params->get('show_totop') == 1 ) {

            if( $this->params->get('totop_text') ) {
                $totop_text = $this->params->get('totop_text');
            } else {
                $totop_text = JText::_( 'Top' );
            }

            $totop = null;

            if( $this->params->get('totop_image') && is_file( JPATH_SITE.'/images/simplelists/'.$this->params->get('totop_image'))) {
                $totop_image = JHTML::image( 'images/simplelists/'.$this->params->get('totop_image'), $totop_text );
                $totop_image = JHTML::link( $url.'#top', $totop_image, 'class="totop"' );
                $totop .= $totop_image ;
            }

            if( $this->params->get('totop_text') ) {
                $totop_text = '<span class="totop_text">' . $totop_text . '</span>';
                $totop_text = JHTML::link( $url.'#top', $totop_text, 'class="totop"' );
                $totop .= $totop_text ;
            }

        } else {
            $totop = null ;
        }

        // Determine whether to show the "No Items" message
        if( $this->params->get('show_empty_list') ) {
            $empty_list = JText::_( 'No items found' );
        } else {
            $empty_list = null;
        }

        // Check if the list is empty
        if(is_array($this->items) && !empty($this->items)) {

            // Loop through the list to set things right
            $counter = 0;
            foreach($this->items as $id => $item) {

                // Append category-data
                $item->category_id = $category->id;
                $item->category_alias = $category->alias;

                // Prepare each item
                $item = $this->prepareItem($item, $layout, $counter);

                // Remove items that are empty
                if($item == false) {
                    unset($this->items[$id]);
                    break;
                }
                    
                // Save the item in the array
                $this->items[$id] = $item ;
                $counter++;
            }

            // Add feeds to the document
            if( $this->params->get('show_feed') == 1 ) {
                $link = '&format=feed&limitstart=';
                $document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', array('type' => 'application/rss+xml', 'title' => 'RSS 2.0'));
                $document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', array('type' => 'application/atom+xml', 'title' => 'Atom 1.0'));
            }
        }

        // Load the default CSS only, if set through the parameters
        if( $this->params->get('load_css', 1) ) {
            $this->loadCSS( $layout );
        }

        // Load the default JavaScript only, if set through the parameters
        if( $this->params->get('load_js', 1) ) {
            $this->loadJS( $layout );
        }

        // Load the lightbox only, if set through the parameters
        if( $this->params->get('load_lightbox') ) {
            JHTML::_('behavior.modal', 'a.lightbox');
        }

        // Construct the page class
        $page_class = 'simplelists simplelists-'.$layout;
        if( $this->params->get('pageclass_sfx') ) {
            $page_class .= ' simplelists'.$this->params->get('pageclass_sfx');
        }

        // Assign all variables to this layout
        $this->assignRef( 'category', $category );
        $this->assignRef( 'totop', $totop );
        $this->assignRef( 'empty_list', $empty_list );
        $this->assignRef( 'url', $url);
        $this->assignRef( 'page_class', $page_class);

        // Call the parent method
        parent::prepareDisplay();
    }

    /*
     * Method to prepare the category
     *
     * @param object $category
     * @param string $layout
     * @return null
     */
    public function prepareCategory($category, $layout) 
    {
        // Sanity check
        if(!is_object($category)) {
            return null;
        }

        // Convert the parameters to an object
        $category->params = YireoHelper::toParameter($category->params);
        $params = $this->params;

        // Override the default parameters with the category parameters
        foreach($category->params->toArray() as $name => $value) {
            if($value != '') $params->set($name, $value);
        }

        // Override the layout
        $layout = $category->params->get('layout');
        if(!empty($layout)) {
            $this->setLayout($layout);
        }

        // Prepare the category URL
        if( $params->get('show_category_parent') && !empty( $category->parent)) {
            $needles = array('category_id' => $category->parent->id);
            if(isset($category->parent->alias)) $needles['category_alias'] = $category->parent->alias;
            $category->parent->link = SimplelistsHelper::getUrl($needles);
        }

        // Loop through the child-categories
        if( $params->get('show_category_childs') && !empty( $category->childs )) {
            foreach( $category->childs as $child ) {
                $child->params = YireoHelper::toParameter($child->params);
                $child_layout = $child->params->get('layout', $layout);
                $needles = array('category_id' => $child->id, 'category_alias' => $child->alias, 'layout' => $child_layout);
                $child->link = SimplelistsHelper::getUrl($needles);
            }
        }

        // Set the correct page-title
        if( $params->get('show_page_title') == 1 && $params->get('page_title') != '' ) {
            $category->title = $params->get('page_title') ;
        }

        // Run the category content through Content Plugins
        if( $params->get('show_category_description') && !empty($category->description)) {
            $category->text = $category->description;
            $this->firePlugins($category, array());
            $category->description = $category->text; 
            $category->text = null ;
        }

        // Prepare the category image
        if( $params->get('show_category_image') && isset($category->image) && !empty($category->image)) {
            $category->image = JHTML::image($category->image, $category->title, array( 'align' => $category->image_position));
        } else {
            $params->set('show_category_image', 0);
        }
    }

    /*
     * Method to prepare a specific item
     *
     * @param object $item
     * @param string $layout
     * @param int $counter
     * @return object
     */
    public function prepareItem($item, $layout, $counter = 0) 
    {
        $user = &JFactory::getUser();
        $dispatcher =& JDispatcher::getInstance();
        $params = $this->params;

        // Initialize the parameters
        if( $item->params ) {
            $p = clone($params);
            $p->merge($item->params);
            $item->params = $p;
        } else {
            $item->params = $params;
        }

        // Run the content through Content Plugins
        if( $item->params->get('enable_content_plugins', 1) == 1 ) {
            JPluginHelper::importPlugin( 'content' );
            $iparams = array();
            $results = $dispatcher->trigger('onPrepareContent', array ( &$item, &$iparams, 0));
        }

        // Disable the text when needed
        if($item->params->get('show_item_text', 1) == 0) {
            $item->text = null;
        }

        // Prepare the URL
        $item->url = JRoute::_(SimplelistsPluginHelper::getPluginLinkUrl($item));
        if($item->alias) {
            $item->href = $item->alias;
        } else {
            $item->href = 'item'.$item->id;
        }

        // Create a simple target-string
        switch( $item->params->get('target') ) {
            case 1:
                $item->target = ' target="_blank"' ;
                break;
            case 2:
                $item->target = ' onclick="javascript: window.open(\''. $item->url .'\', \'\', \'toolbar=no,location=no,status=no,' 
                    . 'menubar=no,scrollbars=yes,resizable=yes,width=780,height=550\'); return false"' ;
                break;
            default:
                $item->target = false;
                break;
        }

        // Set the readmore link for this item
        if( $item->params->get('readmore') == 1 && $item->url ) {
            $readmore_text = $item->params->get( 'readmore_text', JText::sprintf( 'Read more', $item->title )) ;
            $readmore_css = trim('readon '.$item->params->get('readmore_class', ''));
            $item->readmore = JHTML::link( $item->url, $readmore_text, 'title="'.$item->title.'" class="'.$readmore_css.'"'.$item->target );
        } else {
            $item->readmore = false;
        }

        // Set the image-alignment for this item
        if( $item->params->get('picture_alignment') != '' && $layout != 'picture' ) {
            $item->picture_alignment = $item->params->get('picture_alignment');
        } else {
            $item->picture_alignment = false;
        }

        // Prepare the image
        if( $item->params->get('show_item_image', 1) && !empty($item->picture)) {

            $attributes = 'title="'.$item->title.'" class="simplelists"';
            if($item->picture_alignment) $attributes .= ' align="'.$item->picture_alignment.'"';
            $item->picture = SimplelistsHTML::image( $item->picture, $item->title, $attributes);

            if($item->picture && $item->params->get('image_link') && !empty( $item->url )) {

                if($item->params->get( 'link_class') != '') {
                    $item_link_class = ' class="'.$item->params->get('link_class').'"' ;
                } else {
                    $item_link_class = '';
                }

                if($item->params->get('link_rel') != '') {
                    $item_link_rel = ' rel="'.$item->params->get('link_rel').'"' ;
                } else {
                    $item_link_rel = '';
                }

                if(!empty($item->title)) {
                    $title = $item->title;
                    if(!empty($item->text)) {
                        $title .= ' :: ' . $item->text;
                    }
                } else {
                    $title = $item->target;
                }
                $title = htmlentities($title);

                $item->picture = JHTML::link( $item->url, $item->picture, 
                    'title="'.$title.'"'.$item->target.$item_link_class.$item_link_rel );
            }
        } else {
            $item->picture = null ;
        }

        // Construct the class
        $classes = array('simplelists-item');
        if($item->params->get('new') == 1) $classes[] = 'simplelists-item-new';
        if($item->params->get('featured') == 1) $classes[] = 'simplelists-item-featured';
        if($item->params->get('popular') == 1) $classes[] = 'simplelists-item-popular';
        if($item->params->get('approved') == 1) $classes[] = 'simplelists-item-approved';
        $item->class = implode(' ', $classes);

        // Prepare the title
        if( $item->params->get('show_item_title', 1) ) {
            if( $item->params->get('title_link') && !empty( $item->url )) {
                $item->title = JHTML::link( $item->url, $item->title, $item->target );
            }
        } else {
            $item->title = null ;
        }

        // Set specific layout settings
        $item->style = '';
        if($layout == 'select' || $layout == 'hover') {
            if($counter == 0) {
                $item->style = 'display:block; visibility:visible;';
            }
        }

        // Enable parsing the content
        JPluginHelper::importPlugin('content');
        $results = $dispatcher->trigger('onBeforeDisplayContent', array ( &$item, &$item->params, 0));
        foreach($results as $result) {
            if(!empty($result)) $item->text .= $result;
        }

        return $item;
    }

    /*
     * Method to load CSS depending on the layout
     * 
     * @param string $layout
     * @return null
     */
    protected function loadCSS($layout) 
    {
        $sheet = 'layout-'.$layout.'.css';
        $this->addCss($sheet);
    }

    /*
     * Method to load JavaScript depending on the layout
     * 
     * @param string $layout
     * @return null
     */
    protected function loadJS($layout) 
    {
        // load javascript depending on the layout
        switch( $layout ) {
            case 'hover':
            case 'select':
            case 'toggle':
                $script = 'layout-'.$layout.'.js';
                JHTML::_('behavior.mootools');
                break;

            default:
                $script = 'layout-default.js';
                break;
        }

        $this->addJs($script);
    }

    /*
     * Method to fire plugins on a certain item
     *
     * @param object $row
     * @return null
     */
    protected function firePlugins(&$row = null, $params = array()) 
    {
        $dispatcher =& JDispatcher::getInstance();
        JPluginHelper::importPlugin( 'content' );
        $results = $dispatcher->trigger('onPrepareContent', array ( &$row, &$params, 0));
    }

    /*
     * Method to prepare the HTML-document for display
     *
     * @param object $category
     * @return null
     */
    protected function prepareDocument($category) 
    {
        // Get the document object
        $document =& JFactory::getDocument();

        // Set the page title
        if(JRequest::getCmd('option') == 'com_simplelists') {
            $page_title = $this->params->get('page_title');
            if($this->params->get('show_page_title') == 1 && !empty($page_title)) {
                $document->setTitle($page_title);
            } elseif(!empty($category->title)) {
                $document->setTitle($category->title);
            }
        }

        // Set META information
        $this->addMetaTags($category);
        $this->addPathway($category);
    }

    /*
     * Method to load META-tags in the HTML header
     * 
     * @param object $category
     * @return null
     */
    protected function addMetaTags($category) 
    {
        // Sanity check
        if(!is_object($category)) {
            return null;
        }

        // Define the parameters
        $params = $category->params;
        $document =& JFactory::getDocument();

        $meta_description = $params->get('description');
        if( !empty( $meta_description )) {
            $document->setDescription( $meta_description );
        }

        $meta_keywords = $params->get('keywords');
        if( !empty( $meta_keywords )) {
            $document->setMetadata( 'keywords', $meta_keywords );
        }

        $meta_author = $params->get('author');
        if( !empty( $meta_author )) {
            $document->setMetadata( 'author', $meta_author );
        }
    }

    /*
     * Method to add items to the breadcrumbs (pathway)
     *
     * @param object $category
     * @return null
     */
    protected function addPathway($category) 
    {
        // Sanity check
        if(!is_object($category)) {
            return null;
        }

        $application = JFactory::getApplication();
        $pathway = $application->getPathway();

        if($category->parent_id > 0) {
            $pathway->addItem($category->title);
            $parent = SimplelistsHelper::getCategory($category->parent_id);
            $this->addPathway($parent);
        }
    }

    /*
     * Method to determine how many items starting with the letter X
     *
     * @param string $character
     * @return boolean
     */
    public function getCharacterCount($character = null)
    {
        static $characters = null;
        if(!is_array($characters)) {
            $characters = array();

            $model = new SimplelistsModelItems();
            $model->setLimitQuery(false);
            $model->setState('no_char_filter', 1);
            $model->initLimit(1000);
            $model->initLimitstart(0);
            $rows = $model->getData();

            if(!empty($rows)) {
                foreach($rows as $row) {
                    $c = substr(strtolower(trim($row->title)), 0, 1);
                    if(isset($characters[$c])) {
                        $characters[$c]++;
                    } else {
                        $characters[$c] = 1;
                    }
                }
            }
        }

        if(isset($characters[$character])) {
            return $characters[$character];
        }

        return 0;
    }
}
