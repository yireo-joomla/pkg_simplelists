<?php
/**
 * Joomla! component SimpleLists
 *
 * @author Yireo
 * @copyright Copyright 2011
 * @license GNU General Public License
 * @link https://www.yireo.com/
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// build the alphabet
$alphabet = array();

// include an ALL-option
$uri = clone( JURI::getInstance() );
$uri->delVar( 'char' );
$url = $uri->toString();
$alphabet[] = '<span class="char"><a href="'.$url.'">' . JText::_('All') . '</a></span>';

// gather all characters
for( $i = 97 ; $i < 123 ; $i++ ) { 
    $character = chr($i);
    $uri = clone( JURI::getInstance() );
    $uri->setVar( 'char', $character );
    $url = JRoute::_($uri->toString(), true);
    $characterCount = $this->getCharacterCount($character);

    if(strtolower(JRequest::getCmd('char')) == $character || $characterCount == 0) {
        $alphabet[] = '<span class="char">'.strtoupper($character).'</span>';
    } else {
        $alphabet[] = '<span class="char"><a href="'.$url.'">'.strtoupper($character).'</a></span>';
    }
}

echo implode( ' | ', $alphabet );
