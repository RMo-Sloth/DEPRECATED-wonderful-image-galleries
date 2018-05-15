<?php 
/**
 * @package Module Wonderful Image Galleries
 * @author RMo
 * @copyright (C) 2018 - RMo
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access
defined('_JEXEC') or die; 
?>
<style type="text/css">
/*css styling of svg for easy theming*/

#background-rect<?php echo $module->id; ?>, #new-rect<?php echo $module->id; ?>{
	fill: <?php echo $params->get('images-background-color'); ?>;
}

#wonderful-image-gallery-<?php echo $module->id; ?> .slides>rect{/*background and border of slides and the main image*/
  fill: <?php echo $params->get('images-background-color'); ?>;
  stroke: <?php echo $params->get('background-color'); ?>;
}
/*default svg styling (should not change)*/
#wonderful-image-gallery-<?php echo $module->id; ?> .slides>rect, #more-pictures-left<?php echo $module->id; ?>, #more-pictures-right<?php echo $module->id; ?>, #wonderful-image-gallery-<?php echo $module->id; ?> .play-button, #wonderful-image-gallery-<?php echo $module->id; ?> .main-images{
  cursor: pointer;
}
#wonderful-image-gallery-<?php echo $module->id; ?> .seen-slide{
  filter:url(#sepia);
}
#wonderful-image-gallery-<?php echo $module->id; ?> .slides>rect.active-slide{
	stroke: <?php echo $params->get('buttons-background-color'); ?>;
}
#wonderful-image-gallery-<?php echo $module->id; ?> .slides>image{
  pointer-events: none;
}
#wonderful-image-gallery-<?php echo $module->id; ?> .leftNav{
	display: none;
}
</style>
