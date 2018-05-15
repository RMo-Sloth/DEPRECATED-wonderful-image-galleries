<?php
	/**
	 * @package Module Wonderful Image Galleries
	 * @author RMo
	 * @copyright (C) 2018 - RMo
	 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	**/
	defined('_JEXEC') or die; 
?>
<script>
window.addEventListener('load', function(){
	wonderfulImageGallery<?php echo $module->id; ?>.init();
}, false);

var wonderfulImageGallery<?php echo $module->id; ?> = {
	// variables
	wonderfulImageGalleryElement: null,
	activeDia: 0,
	isCurrentlyAnimating: false,
	imgUrls: 
		<?php 
			$fileLocation = JPATH_SITE."/images/image_galleries/".$params['images'];
			$exclude = array('index.html');
			$imageArray = JFolder::files($fileLocation, '.', false, false, $exclude);
			
			$fileBaseUrl = JUri::root()."images/image_galleries/".$params['images'].'/';
			foreach($imageArray as &$imagename){
				$imagename = $fileBaseUrl.$imagename;	
			} 
			echo json_encode($imageArray); 
		?>,
	currentlyShownImageIndex: 0,
	mainImage: {
		imageContainer: null,
		mask: "<?php echo explode(", ", $params->get('animation-type-list', 'vanilla'))[1]; ?>",
		animationName: "<?php echo explode(", ", $params->get('animation-type-list', 'vanilla'))[0]; ?>",
		animationDuration: <?php echo $params->get('animation-duration')?>,
		animationInterval: <?php echo $params->get('animation-interval')?>,
		animation: <?php
			switch($animation){
			case 'vanilla':
				require JModuleHelper::getLayoutPath('mod_wig', 'js/animations/vanilla');
				break;
			case 'animateWidth':
				require JModuleHelper::getLayoutPath('mod_wig', 'js/animations/animate-width');
				break;
			case 'animationCircles':
				require JModuleHelper::getLayoutPath('mod_wig', 'js/animations/animate-circles');
				break;
			default: 
				require JModuleHelper::getLayoutPath('mod_wig', 'js/animations/vanilla');
			}
			?>,
			endAnimation: function(){
				//remove old image if one exists
				if(document.querySelector("#wonderful-image-gallery-<?php echo $module->id;?> .old-image") !== null){
					document.querySelector("#wonderful-image-gallery-<?php echo $module->id;?> .old-image").remove(); 
				}
				//remove rect
				document.getElementById("new-rect<?php echo $module->id; ?>").remove();
				//make new image the old image 
				document.querySelector("#wonderful-image-gallery-<?php echo $module->id;?> .new-image").setAttribute("class", "old-image");
				document.querySelector("#wonderful-image-gallery-<?php echo $module->id;?> .old-image").setAttribute('mask','url(#displayed_mask)');
			}
	},
	diaPreview:{
		imagePreviewContainer: null,
		imageBackgrounds: null,
		hiddenLeftDias: 0,
		previousPageButton: null,
		nextPageButton: null,
	},
	playAndPauseButton:{
		pauseAndPlayButton: null,
		playIcon: null,
		pauseIcon: null
	},
	init: function(){
		let thisWonderfulImageGalleryObject = this;
		
		// initialise references to html-elements
		this.wonderfulImageGalleryElement = document.getElementById("wonderful-image-gallery-<?php echo $module->id;?>");
		this.mainImage.imageContainer = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .main-images");
		this.diaPreview.imagePreviewContainer = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .slides");
		this.diaPreview.previousPageButton = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .leftNav");
		this.diaPreview.nextPageButton = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .rightNav");
		this.playAndPauseButton.pauseAndPlayButton = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .play-button");
		this.playAndPauseButton.playIcon = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .play-button-play-mode");
		this.playAndPauseButton.pauseIcon = document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .play-button-pause-mode");	
		
		// create the main image
		//createNewSlide image 'behind' old image without clipping / masking
		let newImage = null;
		let mainImages = this.mainImage.imageContainer;
		let imageNode = document.createElementNS('http://www.w3.org/2000/svg','image');
		imageNode.setAttributeNS('http://www.w3.org/1999/xlink','href',this.imgUrls[0]);
		imageNode.setAttributeNS(null,'width','2550'); 
		imageNode.setAttributeNS(null,'height','1925');
		imageNode.setAttributeNS(null,'class','old-image');
		// insert as first element
		if (mainImages.firstChild) {
			mainImages.insertBefore(imageNode, mainImages.firstElementChild);
		}else{
			mainImages.appendChild(imageNode)
		}
		// add a black "background" rect so transitions look good between screen sizes
		let rectNode = document.createElementNS('http://www.w3.org/2000/svg','rect');
		rectNode.setAttributeNS(null,'width','2550'); 
		rectNode.setAttributeNS(null,'height','1925');
		rectNode.setAttributeNS(null,'id','background-rect<?php echo $module->id; ?>');
		newImage = document.querySelector("#wonderful-image-gallery-<?php echo $module->id;?> .old-image");
		mainImages.insertBefore(rectNode, newImage);
		
		// create image previews
		let imgUrlsArray = this.imgUrls;
		let rectangleNode = null;
		imageNode = null;
		let slideContainer = this.diaPreview.imagePreviewContainer;
		
		for(let i=0; i < imgUrlsArray.length; i++){
			//create rect Node
			rectangleNode = document.createElementNS('http://www.w3.org/2000/svg','rect');
			rectangleNode.setAttributeNS(null,'height','400');
			rectangleNode.setAttributeNS(null,'width','600');
			rectangleNode.setAttributeNS(null,'x', i*650);
			rectangleNode.setAttributeNS(null,'y', 50);
			rectangleNode.setAttributeNS(null,'stroke-width', "5");
			rectangleNode.setAttributeNS(null,'data-dia-nr', i);
			if(i === 0){
				rectangleNode.classList.add('active-slide');
			}
			slideContainer.appendChild(rectangleNode);
			
			//create img node
			imageNode = document.createElementNS('http://www.w3.org/2000/svg','image');
			imageNode.setAttributeNS(null,'height','390');
			imageNode.setAttributeNS(null,'width','590');
			imageNode.setAttributeNS('http://www.w3.org/1999/xlink','href', imgUrlsArray[i] );
			imageNode.setAttributeNS(null,'x', (i*650)+5);
			imageNode.setAttributeNS(null,'y', 55);
			imageNode.setAttributeNS(null,'class', "slide-nr-"+i );
			slideContainer.appendChild(imageNode);
		};
		
		// set the next and previous button appropriately
		this.hideInappropriateButtons();
		
		// initialise references to html-elements that have been generated by javascript
		this.diaPreview.imageBackgrounds = document.querySelectorAll("#wonderful-image-gallery-<?php echo $module->id; ?> .slides>rect");
		
		// add eventlisteners to html-elements
		this.playAndPauseButton.pauseAndPlayButton.addEventListener("click", function(){
			if(thisWonderfulImageGalleryObject.isCurrentlyAnimating ){
				thisWonderfulImageGalleryObject.pauseSlideShow();
			 }else{
				thisWonderfulImageGalleryObject.runSlideShow();
			 }
		});
		
		this.diaPreview.nextPageButton.addEventListener('click', function(){
			thisWonderfulImageGalleryObject.showNextFourDiaPreviews();
		});

		this.diaPreview.previousPageButton.addEventListener('click', function(){
			thisWonderfulImageGalleryObject.showPreviousFourDiaPreviews();
		});
		
		this.mainImage.imageContainer.addEventListener("click", function(){
			if(thisWonderfulImageGalleryObject.isCurrentlyAnimating ){
			   thisWonderfulImageGalleryObject.pauseSlideShow();
			}else{
			   thisWonderfulImageGalleryObject.runSlideShow();
			}
		});
		
		this.diaPreview.imageBackgrounds.forEach(function(slide){
			slide.addEventListener("click", function(){
				thisWonderfulImageGalleryObject.currentlyShownImageIndex = parseInt(this.getAttribute("data-dia-nr"));
				thisWonderfulImageGalleryObject.pauseSlideShow(); 
				thisWonderfulImageGalleryObject.runSlide();
			});
		});
	},
	hideInappropriateButtons: function(){
		let hiddenLeftDias = this.diaPreview.hiddenLeftDias;
		let diaTotal = this.imgUrls.length;
		let nextButton = this.diaPreview.nextPageButton;
		let previousButton = this.diaPreview.previousPageButton; 
		
		//hide/show left button if..
		if(hiddenLeftDias == 0){
			previousButton.style.display = "none"; 
		}else{ 
			previousButton.style.display = "inline"; 
		}
		//hide/show right button if..
		if(diaTotal - hiddenLeftDias > 4){    
			nextButton.style.display = "inline";
		}else{
			nextButton.style.display = "none"; 
		}
	},
	showNextFourDiaPreviews: function(){
		let newHiddenLeftDias = this.diaPreview.hiddenLeftDias + 4;
		let slideContainer = this.diaPreview.imagePreviewContainer;
		slideContainer.setAttribute("transform", `translate(${225-newHiddenLeftDias*650}, 0)`);
		// set the new value
		this.diaPreview.hiddenLeftDias = newHiddenLeftDias;
		// hide inappropriate buttons
		this.hideInappropriateButtons();
	},
	showPreviousFourDiaPreviews: function(){
		let newHiddenLeftDias = this.diaPreview.hiddenLeftDias - 4;
		let slideContainer = this.diaPreview.imagePreviewContainer;
		slideContainer.setAttribute("transform", `translate(${225-newHiddenLeftDias*650}, 0)`);
		// set the new value
		this.diaPreview.hiddenLeftDias = newHiddenLeftDias;
		// hide inappropriate buttons
		this.hideInappropriateButtons();
	},
	runSlideShow: function(){
		// let imgUrls = this.imgUrls;
		let playIcon = this.playAndPauseButton.playIcon;
		let pauseIcon = this.playAndPauseButton.pauseIcon;
		
		//indicate that the slideshow is currently running
		this.isCurrentlyAnimating = true;
		
		//change button to "paused-icon"
		playIcon.style.display = "none";
		pauseIcon.style.display = "inline";
		
		// immidiately animate the next dia
		this.showNextDia(); 
	},
	pauseSlideShow: function(){
		let playIcon = this.playAndPauseButton.playIcon;
		let pauseIcon = this.playAndPauseButton.pauseIcon;
		
		// change button to "play-icon"
		pauseIcon.style.display = "none";
		playIcon.style.display = "inline";
		
		// stop the current animation 
		if(this.showNextDiaTimeout){
			clearTimeout(this.showNextDiaTimeout);
		}
		
		// indicate slideshow is currently not running
		this.isCurrentlyAnimating = false;
	},
	showNextDiaTimeout: null,
	showNextDia: function(){
		const animationDuration = this.mainImage.animationDuration;
		const animationInterval = this.mainImage.animationInterval;
		const animationCycleTime = animationInterval + animationDuration;
		let thisWonderfulImageGalleryObject = this;
		let imgUrls = this.imgUrls;
		let nextImageIndex = this.currentlyShownImageIndex + 1;
		if(nextImageIndex === imgUrls.length){
				nextImageIndex = 0;
		}
		// set new index for the image to animate
		this.currentlyShownImageIndex = nextImageIndex;
		
		// animate the image
		this.runSlide();
		
		// animate the next dia later, after the specified interval (infinite loop)
		this.showNextDiaTimeout = setTimeout(function(){
			thisWonderfulImageGalleryObject.showNextDia();
		}, animationCycleTime);
	},
	runSlide: function(){
		let mask = 'url(#' + this.mainImage.mask + ')'; 
		let slideIndex = this.currentlyShownImageIndex;
		let imgUrls = this.imgUrls;
		//remove .active-slide and set .active-slide on appropriate element
		document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .slides>rect.active-slide").classList.remove('active-slide');
		document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .slide-nr-"+ slideIndex ).previousSibling.classList.add("active-slide");
		//set slideDia to move if selection changes outside of the shown images
		if(slideIndex % 4 === 0){
			// set new value 
			this.diaPreview.hiddenLeftDias = slideIndex;
			document.querySelector("#wonderful-image-gallery-<?php echo $module->id; ?> .slides").setAttribute("transform", `translate(${225-slideIndex*650}, 0)`);
			
			// hide the inappropriate next or previous button 
			this.hideInappropriateButtons();
		}
		//createNewSlide image 'above' old image without clipping
		let node = document.createElementNS('http://www.w3.org/2000/svg','image');
		node.setAttributeNS('http://www.w3.org/1999/xlink','href',imgUrls[slideIndex]);
		node.setAttributeNS(null,'width','2550'); 
		node.setAttributeNS(null,'height','1925');
		node.setAttributeNS(null,'mask', mask);
		node.setAttributeNS(null,'class','new-image');
		let mainImages = this.mainImage.imageContainer;
		mainImages.appendChild(node);
		// add a rect so transitions look good between screen sizes
		node = document.createElementNS('http://www.w3.org/2000/svg','rect');
		node.setAttributeNS(null,'width','2550'); 
		node.setAttributeNS(null,'height','1925');
		node.setAttributeNS(null,'mask', mask);
		node.setAttributeNS(null,'id','new-rect<?php echo $module->id; ?>');
		mainImages.insertBefore(node, mainImages.lastElementChild);
  
		//start animating
		this.mainImage.animation();
	}
}


//ANIMATIONS 
  // return a value between start and end as l goes from 0 to 1
function lerp(start, end, progress) {
  return  (start + (end - start) *  progress);
}

</script>
