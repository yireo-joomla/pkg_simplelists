<?php
/*
 * Joomla! modal colorpicker
 * Built on MooRainbow of W00fz, modified by Yireo
 *
 * @author Yireo (info@yireo.com)
 * @package Yireo
 * @copyright Copyright 2012
 * @license MIT-style license
 */

// Check to ensure this file is included in Joomla!
if(defined('_JEXEC')) define('_JEXEC', 1);
defined('_JEXEC') or die();

// Simple import from main URL (note that this bypasses Joomla! security)
$object = preg_replace( '/([^a-zA-Z0-9_-].)/', '', $_GET['object']);
$color = preg_replace( '/([^a-zA-Z0-9].)/', '', $_GET['color']);
?>
<html>
<head>
<script type="text/javascript" src="/media/system/js/mootools.js"></script>
<script type="text/javascript" src="modalrainbow.js"></script>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
<div id="modalRainbow" style="display:block; position:relative"></div>
<button id="submit" class="button">Insert</button>
<script type="text/javascript">
var rainbox = new ModalRainbow({
    'startColorHex': '<?php echo $color; ?>',
	'imgPath': './images/',
    'onComplete': function(color) {
        window.parent.jSelectColor(color.hex, '<?php echo $object; ?>');
    }
});
</script>
</body>
</html>
