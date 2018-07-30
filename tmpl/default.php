<?php
/**
 * @package Module Wonderful Image Galleries
 * @author RMo
 * @copyright (C) 2018 - RMo
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
	defined('_JEXEC') or die;

	require JModuleHelper::getLayoutPath('mod_wig', 'lib/animation');
	//
	$fileLocation = JPATH_SITE."/images/image_galleries/".$params['images'];
	$exclude = array('index.html');
	$imageArray = JFolder::files($fileLocation, '.', false, false, $exclude);

	$fileBaseUrl = JUri::root()."images/image_galleries/".$params['images'].'/';
	foreach($imageArray as &$imagename){
		$imagename = $fileBaseUrl.$imagename;
	}

	// compile the module
	$animationType = $params->get('animation-type-list', 'vanilla');
	$functionName = $animationType.'Animation';
	$animation = new $functionName(
		$module->id,
		$params->get('images-background-color'),
		$params->get('background-color'),
		$params->get('buttons-color'),
		$params->get('buttons-background-color'),
		$params->get('border-color'),
		$imageArray,
		$params->get('animation-duration'),
		$params->get('animation-interval')
	);
	$animation->create();
