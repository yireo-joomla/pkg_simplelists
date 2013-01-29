<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Import Joomla! libraries
jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// Include the media-helper
require_once( JPATH_ADMINISTRATOR.'/components/com_media/helpers/media.php' );

/**
 * SimpleLists Component Files Model
 */
class SimpleListsModelFiles extends YireoAbstractModel
{
    /*
     * Method to set a specific state for an internal variable
     * 
     * @param mixed $property
     * @return bool
     */
    public function getState($property = null)
    {
        static $set;

        $option = JRequest::getCmd( 'option' );
        $application = JFactory::getApplication();

        if (!$set) {

            // current type
            $type = $application->getUserStateFromRequest( $option.'.type', 'type', '' );
            $this->setState('type', $type);

            if( $type == 'link_file' ) {
                $default_folder = 'images/';
            } else {
                $default_folder = COM_SIMPLELISTS_DIR;
            }

            // Current folder
            $folder = $application->getUserStateFromRequest( $option.'.files.folder', 'folder', $default_folder );

            // Workaround for com_media
            if(!@is_dir(JPATH_SITE.'/'.$folder) && @is_dir(JPATH_SITE.'/images/'.$folder)) {
                $folder = 'images/'.$folder;
            }

            // Save the state of this folder
            if($folder == '.') $folder = false;
            if(!empty($folder) && preg_match('/\/$/', $folder) == false) $folder = $folder.'/';
            $this->setState('folder', $folder);

            // Save the state of this folder
            $parent = dirname($folder);
            if(empty($folder)) $parent = false;
            $this->setState('parent', $parent);

            // Current item 
            $current = $application->getUserStateFromRequest( $option.'.files.current', 'current', '' );
            $this->setState('current', $current);

            $set = true;
        }
        return parent::getState($property);
    }

    /**
     * Method to return the current filelist
     *
     * @access public
     * @param null
     * @return array
     */
    public function getFiles()
    {
        $list = $this->getList();
        return $list['files'];
    }

    /**
     * Method to return the current filelist
     *
     * @access public
     * @param null
     * @return array
     */
    public function getFolders()
    {
        $list = $this->getList();
        return $list['subfolders'];
    }

    /**
     * Method to return the current filelist
     *
     * @access public
     * @param null
     * @return array
     */
    public function getDocuments()
    {
        $list = $this->getList();
        return $list['docs'];
    }

    /**
     * Method to return the current filelist
     *
     * @access public
     * @param null
     * @return array
     */
    public function getList()
    {
        // Load the component-params
        $component_params = JComponentHelper::getParams('com_simplelists');

        // Only process the list once per request
        static $list;
        if(is_array($list)) {
            return $list;
        }

        // Initialize variables
        $folder = $this->getState('folder');
        $folderPath = JPATH_SITE.'/'.$folder;
        $type = $this->getState('type');

        // Initialize the lists
        $files = array();
        $subfolders = array();
        $docs = array();

        // Get the list of files and folders from the given folder
        if(is_readable($folderPath)) {
            $fileList = JFolder::files($folderPath);
            $subfolderList = JFolder::folders($folderPath);
        } else {
            $fileList = false;
            $subfolderList = false;
        }

        // Iterate over the files if they exist
        if($fileList !== false) {
            foreach ($fileList as $file) {

                // Skip this file if it is not readable
                if(is_file($folderPath.'/'.$file) == false) {
                    continue;
                }

                // Skip files starting with a dot
                if(substr($file, 0, 1) == '.') {
                    continue;
                }

                // Skip specific files
                if(strtolower($file) == 'index.html' || preg_match('/\.(php)$/', $file)) {
                    continue;
                }

                $tmp = new JObject();
                $tmp->name = $file;
                $tmp->path = $folderPath.'/'.$file;
                $tmp->path_relative = $folder.$file;
                $tmp->path_uri = $tmp->path_relative;
                $tmp->size = filesize($tmp->path);

                $ext = strtolower(JFile::getExt($file));
                switch ($ext)
                {
                    // Image
                    case 'jpg':
                    case 'png':
                    case 'gif':
                    case 'xcf':
                    case 'odg':
                    case 'bmp':
                    case 'jpeg':
                        $info = @getimagesize($tmp->path);
                        $size = @filesize($tmp->path);
                        $tmp->width = @$info[0];
                        $tmp->height = @$info[1];
                        $tmp->src_width = $tmp->width;
                        $tmp->src_height = $tmp->height;
                        $tmp->type = @$info[2];
                        $tmp->mime = @$info['mime'];

                        $maxsize = 60;
                        if (($info[0] > $maxsize) || ($info[1] > $maxsize)) {
                            $dimensions = MediaHelper::imageResize($info[0], $info[1], $maxsize);
                            $tmp->width = $dimensions[0];
                            $tmp->height = $dimensions[1];
                        }

                        $maxbits = $component_params->get('thumbs_limit', 524288);
                        if($maxbits > 0 && $size > $maxbits) {
                            $tmp->src = JURI::root().SimplelistsHelper::createThumbnail($tmp->path, $ext, $tmp->src_width, $tmp->src_height, $tmp->width, $tmp->height); 
                        } else {
                            $tmp->src = JURI::root().$tmp->path_relative;
                        }

                        $files[] = $tmp;
                        break;

                    // Non-image document
                    default:

                        if( $type != 'link_file' ) {
                            break;
                        }

                        // First read the Media Manager parameters and check if this extension is allowed
                        $media_params = JComponentHelper::getParams( 'com_media' );
                        $allowable = explode( ',', $media_params->get( 'upload_extensions' ));
                        if( in_array( $ext, $allowable ) == false ) {
                            break;
                        }

                        if(@file_exists(JPATH_SITE.'/media/media/images/mime-icon-32/'.$ext.'.png')) {
                            $tmp->path_relative = '/media/media/images/mime-icon-32/'.$ext.'.png';
                        } elseif(@file_exists(JPATH_SITE.'/media/media/images/con_info.png')) {
                            $tmp->path_relative = '/media/media/images/con_info.png';
                        } elseif(@file_exists(JPATH_ADMINISTRATOR.'/components/com_media/images/mime-icon-32/'.$ext.'.png')) {
                            $tmp->path_relative = '/administrator/components/com_media/images/mime-icon-32/'.$ext.'.png';
                        } elseif(@file_exists(JPATH_ADMINISTRATOR.'/components/com_media/images/con_info.png')) {
                            $tmp->path_relative = '/administrator/components/com_media/images/con_info.png';
                        }
                        $tmp->src = $tmp->path_relative;

                        $info = @getimagesize(JPATH_SITE.'/'.$tmp->path_relative);
                        $tmp->width = @$info[0];
                        $tmp->height = @$info[1];

                        $files[] = $tmp;
                        break;
                }
            }
        }

        // Iterate over the folders if they exist
        if($subfolderList !== false) {
            foreach($subfolderList as $subfolder) {
    
                $tmp = new JObject();
                $tmp->name = basename($subfolder);
                $tmp->path = JPath::clean($folderPath.'/'.$subfolder);
                $tmp->path_relative = str_replace(JPATH_ROOT.'/', '', $tmp->path);
                $tmp->path_uri = $tmp->path_relative;
                $count = MediaHelper::countFiles($tmp->path);
                $tmp->files = $count[0];
                $tmp->subfolders = $count[1];

                $subfolders[] = $tmp;
            }
        }

        $list = array('subfolders' => $subfolders, 'docs' => $docs, 'files' => $files);

        return $list;
    }
}
