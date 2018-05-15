<?php 
/**
 * @package Module Wonderful Image Galleries
 * @author RMo
 * @copyright (C) 2018 - RMo
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die; 
?>
function(){
	let thisWonderfulImageGalleryObjectMainImage = this;
	let duration = this.animationDuration;
	const startTime = performance.now();
	//get the element that will be animated
	const rect1 = document.getElementById('clip1_rect');
	// start animation sequence
	window.requestAnimationFrame(animateFrame);
	//animate a single frame
	function animateFrame(){
		const timeSinceStart = (performance.now() - startTime);
		// progress goes from 0 to 1 (1 means the animation is completed);
		const progress = Math.min(timeSinceStart / duration, 1);
		//update frame
		rect1.setAttributeNS(null, 'width', lerp(0, 2550, progress));
		if(progress<1){
			window.requestAnimationFrame(animateFrame);
		}else{
			//reset width on clipping so we can apply it on the newest slide
			rect1.setAttributeNS(null, 'width', 0);
			
			// end animation
			thisWonderfulImageGalleryObjectMainImage.endAnimation();
		} 
	}
}
