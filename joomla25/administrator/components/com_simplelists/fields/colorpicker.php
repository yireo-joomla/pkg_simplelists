<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @package SimpleLists
 * @copyright Copyright (C) 2011
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

define( 'COLOR_PICKER_URL', 'administrator/components/com_simplelists/elements/colorpicker/' );

class JElementColorpicker extends JElement
{
    /**
     * Element name
     *
     * @access  protected
     * @var     string
     */
    var $_name = 'Colorpicker';

    public function fetchElement($name, $value, &$node, $control_name)
    {
        $application = JFactory::getApplication();
        $document = JFactory::getDocument();
        $fieldName = $control_name.'['.$name.']';

        $link = JURI::root().COLOR_PICKER_URL.'index.php?object='.$name.'&amp;color='.preg_replace('/([^a-zA-Z0-9]?)/', '', $value);

        JHTML::script(COLOR_PICKER_URL.'colorpicker.js');
        JHTML::_('behavior.modal', 'a.modal');

        $title = JText::_('Select a Color');
        $short_title = JText::_('Select');
        $name_value = (!empty($value)) ? $value : $title;
        $background_color = (!empty($value)) ? $value : '#ffffff';
        $html = <<<EOF
            <div style="float:left;">
                <input style="background-color:#ffffff;" type="text" id="${name}_name" value="${name_value}" disabled="disabled" size="12" />
            </div>
            <div style="float:left;">
                <div style="background-color: $background_color; width:15px; height:15px; border: 1px solid #a3a3a3; margin-left:2px" id="${name}_preview"></div>
            </div>
            <div class="button2-left">
                <div class="blank">
                    <a class="modal" title="${title}"  href="${link}" rel="{handler:'iframe', size: {x: 450, y: 375}}">$short_title</a>
                </div>
            </div>
            <input type="hidden" id="${name}_id" name="$fieldName" value="$value" />
EOF;

        return $html;
    }
}

