<?php
/*
 * Joomla! Yireo Library
 *
 * @author Yireo (info@yireo.com)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 * @version 0.5.0
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Yireo Helper
 */
class YireoHelper
{
    /*
     * Helper-method to get the Joomla! DBO
     *
     * @param null
     * @return bool
     */
    static public function getDBO()
    {
        if (YireoHelper::isJoomla15()) {
            return JFactory::getDBO();
        }
        return JFactory::getDbo();
    }


    /*
     * Helper-method to parse the data defined in this component
     *
     * @param null
     * @return bool
     */
    static public function getData($name = null)
    {
        $file = JPATH_COMPONENT.'/helpers/abstract.php';
        if (is_file($file)) {
            require_once $file ;
            $class = 'HelperAbstract';
            if (class_exists($class)) {
                $object = new $class;
                $data = $object->getStructure();
                if (isset($data[$name])) {
                    return $data[$name];
                }
            }
        }
        return null;
    }

    /*
     * Helper-method to return the HTML-ending of a form
     *
     * @param null
     * @return bool
     */
    static public function getFormEnd($id = 0)
    {
        echo '<input type="hidden" name="option" value="'.JRequest::getCmd('option').'" />';
        echo '<input type="hidden" name="cid[]" value="'.$id.'" />';
        echo '<input type="hidden" name="task" value="" />';
        echo JHTML::_( 'form.token' );
    }

    /*
     * Helper-method to check whether the current Joomla! version equals some value
     *
     * @param null
     * @return bool
     */
    static public function isJoomla($version_string)
    {
        static $rs = array();
        if (!isset($rs[$version_string])) {
            JLoader::import( 'joomla.version' );
            $version = new JVersion();
            if (version_compare( $version->RELEASE, $version_string, 'eq')) {
                $rs[$version_string] = true;
            } else {
                $rs[$version_string] = false;
            }
        }
        return $rs[$version_string];
    }

    /*
     * Helper-method to check whether the current Joomla! version is 3.5
     *
     * @param null
     * @return bool
     */
    static public function isJoomla35()
    {
        return self::isJoomla('3.0');
    }

    /*
     * Helper-method to check whether the current Joomla! version is 2.5
     *
     * @param null
     * @return bool
     */
    static public function isJoomla25()
    {
        if(self::isJoomla('2.5') || self::isJoomla('1.7') || self::isJoomla('1.6')) {
            return true;
        }
        return false;
    }

    
    /*
     * Helper-method to check whether the current Joomla! version is 1.5
     *
     * @param null
     * @return bool
     */
    static public function isJoomla15()
    {
        return self::isJoomla('1.5');
    }

    /**
     * Method to get the current version 
     *
     * @access public
     * @param null
     * @return string
     */
    static public function getCurrentVersion()
    {
        $option = JRequest::getCmd('option');
        $name = preg_replace('/^com_/', '', $option);

        $file = JPATH_ADMINISTRATOR.'/components/'.$option.'/'.$name.'.xml';

        if(method_exists('JInstaller', 'parseXMLInstallFile')) {
            $data = JInstaller::parseXMLInstallFile($file);
            return $data['version'];
        } elseif(method_exists('JApplicationHelper', 'parseXMLInstallFile')) {
            $data = JApplicationHelper::parseXMLInstallFile($file);
            return $data['version'];
        }
        return null;
    }

    /**
     * Method to fetch a specific page
     *
     * @access public
     * @param string $url
     * @param string $useragent
     * @return bool
     */
    static public function fetchRemote($url, $useragent = null)
    {
        if (function_exists('curl_init') == true) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, (!empty($useragent)) ? $useragent : $_SERVER['HTTP_USER_AGENT']);
            $contents = curl_exec($ch);
        } else {
            $contents = file_get_contents($url);
        }
        return $contents;
    }

    /*
     * Convert an object or string to JParameter or JRegistry
     *
     * @param mixed $params
     * @param string $file
     * @return JParameter|JRegistry
     */
    static public function toRegistry($params = null, $file = null)
    {
        if ($params instanceof JParameter || $params instanceof JRegistry) {
            return $params;
        }

        if (self::isJoomla15()) {
            jimport('joomla.html.parameter');
            $params = @new JParameter($params, $file);
        } else {
            jimport('joomla.registry.registry');
            $registry = @new JRegistry();
            if(!empty($params)) $registry->loadString($params);

            $fileContents = @file_get_contents($file);
            if(preg_match('/\.xml$/', $fileContents)) {
                $registry->loadFile($file, 'XML');
            } elseif(preg_match('/\.json$/', $fileContents)) {
                $registry->loadFile($file, 'JSON');
            }

            $params = $registry;
        }
        return $params;
    }

    /*
     * Deprecated shortcut for self::toRegistry()
     *
     * @param mixed $params
     * @param string $file
     * @return JParameter|JRegistry
     * @deprecated
     */
    static public function toParameter($params = null, $file = null)
    {
        return self::toRegistry($params, $file);
    }

    /*
     * Add in jQuery
     *
     * @access public
     * @subpackage Yireo
     * @param null
     * @return null
     */
    static public function jquery()
    {
        if (JFactory::getApplication()->get('jquery') == true) return;

        $option = JRequest::getCmd('option');
        if (file_exists(JPATH_SITE.'/media/'.$option.'/js/jquery.js')) {
            $document = JFactory::getDocument();
            $document->addScript(JURI::root().'media/'.$option.'/js/jquery.js');
            $document->addCustomTag('<script type="text/javascript">jQuery.noConflict();</script>');
            JFactory::getApplication()->set('jquery', true);
        }
    }
}
