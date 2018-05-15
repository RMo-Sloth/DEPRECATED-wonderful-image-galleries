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
	let duration = this.animationDuration;
	let thisWonderfulImageGalleryObjectMainImage = this;
	const startTime = performance.now();
	//get the element that will be animated
	const circle1 = document.getElementById('clip2_circle1');
	const circle2 = document.getElementById('clip2_circle2');
	const circle3 = document.getElementById('clip2_circle3');
	const circle4 = document.getElementById('clip2_circle4');
	const circle5 = document.getElementById('clip2_circle5');
	const circle6 = document.getElementById('clip2_circle6');
	const circle7 = document.getElementById('clip2_circle7');
	const circle8 = document.getElementById('clip2_circle8');
	// start animation sequence
	window.requestAnimationFrame(animateFrame);
  
	function animateFrame(){
		const timeSinceStart = (performance.now() - startTime);
		// progress goes from 0 to 1 (1 means the animation is completed);
		const progress = Math.min(timeSinceStart / duration, 1);
		//update frame
		circle1.setAttributeNS(null, 'r', lerp(0, 500, progress));
		circle2.setAttributeNS(null, 'r', lerp(0, 500, progress));
		circle3.setAttributeNS(null, 'r', lerp(0, 1000, progress));
		circle4.setAttributeNS(null, 'r', lerp(0, 750, progress));
		circle5.setAttributeNS(null, 'r', lerp(0, 700, progress));
		circle6.setAttributeNS(null, 'r', lerp(0, 150, progress));
		circle7.setAttributeNS(null, 'r', lerp(0, 650, progress));
		circle8.setAttributeNS(null, 'r', lerp(0, 500, progress));
		if(progress<1){
			window.requestAnimationFrame(animateFrame);
		}else{
			//reset radius on clipping so we can apply it on the newest slide
			circle1.setAttributeNS(null, 'r', '0');
			circle2.setAttributeNS(null, 'r', '0');
			circle3.setAttributeNS(null, 'r', '0');
			circle4.setAttributeNS(null, 'r', '0');
			circle5.setAttributeNS(null, 'r', '0');
			circle6.setAttributeNS(null, 'r', '0');
			circle7.setAttributeNS(null, 'r', '0');
			circle8.setAttributeNS(null, 'r', '0');
			
			// end animation
			thisWonderfulImageGalleryObjectMainImage.endAnimation();
		} 
	}
}
