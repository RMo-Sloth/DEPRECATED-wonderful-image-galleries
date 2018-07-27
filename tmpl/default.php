<?php
/**
 * @package Module Wonderful Image Galleries
 * @author RMo
 * @copyright (C) 2018 - RMo
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
	defined('_JEXEC') or die;

	// compile the module
	require JModuleHelper::getLayoutPath('mod_wig', 'lib/animation');
	$animation = new testAnimation(
		$module->id,
		$params->get('images-background-color'),
		$params->get('background-color'),
		$params->get('buttons-color'),
		$params->get('buttons-background-color'),
		$params->get('border-color')
	);
	$animation->css();
	$animation->svg();

	require JModuleHelper::getLayoutPath('mod_wig', 'js/js');
