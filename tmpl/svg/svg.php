<?php 
	/**
	 * @package Module Wonderful Image Galleries
	 * @author RMo
	 * @copyright (C) 2018 - RMo
	 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	**/
?>

<svg id="wonderful-image-gallery-<?php echo $module->id; ?>" class="wonderful-image-gallery" viewBox="0 0 3000 2500" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> 
	<defs>
    <!--grey-ish filter-->
		<!--filter id="sepia">
			<feColorMatrix type="matrix"
						values="
				 0.4 0.2 0.2 0 0
				 0.2 0.4 0.2 0 0
				 0.2 0.2 0.4 0 0
				 0     0   0 1 0"
			 /> 
		</filter-->
    
		<!--gradient around play button-->
		<radialGradient id="attention-circle<?php echo $module->id; ?>">
			<stop stop-color="<?php echo $params->get('buttons-background-color'); ?>" offset="0%" stop-opacity="1"/>
			<stop stop-color="<?php echo $params->get('buttons-background-color'); ?>" offset="75%" stop-opacity="1"/>
			<stop id="playButtonEffect" stop-color="<?php echo $params->get('buttons-background-color'); ?>" offset="80%" stop-opacity="0"/>
			<stop offset="1050" stop-color="white" stop-opacity="0"/>
		</radialGradient>
    
	
		<mask id="displayed_mask">
			<rect width="2550" height="100%" fill="white"></rect>
		</mask> 
		<?php 
			$animation = explode(", ", $params->get('animation-type-list', 'vanilla'))[0]; 
			switch($animation){
				case 'animateWidth':
					require JModuleHelper::getLayoutPath('mod_wig', 'svg/animations/animate-width');
					break;
				case 'animationCircles':
					require JModuleHelper::getLayoutPath('mod_wig', 'svg/animations/animate-circles');
					break;
				case 'vanilla':
					break;
				default: ;
			}
		?>	
	</defs>
	<!--background-->
	<rect width="3000" height="2500" fill="<?php echo $params->get('background-color'); ?>"></rect>
	<!--main slide-->
	<!--container for images-->
	<g class="main-images" transform="translate(225,50)">
	</g>
	
	<!--navigation left and right-->
	<g transform="translate(0,1975)">
		<g class="slides" transform="translate(225, 0)">
			<!--SLIDES WILL BE ADDED BY JS-->
		</g>
		<!--left button-->
		<g class="leftNav">
			<rect id="more-pictures-left<?php echo $module->id; ?>" x="0" y="0" width="200" height="500"  fill="<?php echo $params->get('background-color'); ?>"></rect>
			<path d="m140,100 l-70,150 70,150" stroke-width="15" stroke="<?php echo $params->get('buttons-background-color'); ?>" stroke-linecap="round" fill="none" pointer-events="none"/>
		</g>
		<!--right button-->
		<g class="rightNav">
			<rect id="more-pictures-right<?php echo $module->id; ?>" x="2800" y="0" width="200" height="500" fill="<?php echo $params->get('background-color'); ?>"></rect>
			<path d="m2840,100 l70,150 -70,150" stroke-width="15" stroke="<?php echo $params->get('buttons-background-color'); ?>" stroke-linecap="round" fill="none" pointer-events="none"/>
		</g>
		<!--play button-->
		<g>
			<circle class="play-button" cx="1500" cy="25" r="150" fill="<?php echo $params->get('buttons-background-color'); ?>"/>
			<path class="play-button-play-mode" class="color-1-fill" d="m1500,25 m-60,-90 l0,180 160,-90 z" fill="<?php echo $params->get('buttons-color'); ?>" pointer-events="none" stroke-linejoin="round"/>
			<path class="play-button-pause-mode" display="none" class="color-1-fill" d="m1500,25 m-60,-90 l0,180 50,0 0,-180z m70,0 l0,180 50,0 0,-180z" fill="<?php echo $params->get('buttons-color'); ?>" pointer-events="none"/>
		</g>
	</g>
	<!--frame around everything-->
	<rect stroke="<?php echo $params->get('border-color') ?>" width="3000" height="2500" fill="none" stroke-width="25"></rect>
</svg>
