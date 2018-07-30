<?php
// No direct access
// defined('_JEXEC') or die;

abstract class Animation{
  protected $index;
  protected $imagesBackgroundColor;
  protected $backgroundColor;
  protected $buttonColor;
  protected $buttonBackgroundColor;
  protected $borderColor;
  protected $imageUrls;
  protected $animationDuration;
  protected $animationInterval;

  public function __construct( $id, $imagesBackgroundColor, $backgroundColor, $buttonBackgroundColor, $buttonColor, $borderColor, $imageUrls, $animationDuration, $animationInterval ){
    $this->index = $id;
    $this->imagesBackgroundColor = $imagesBackgroundColor;
    $this->backgroundColor = $backgroundColor;
    $this->buttonColor = $buttonColor;
    $this->buttonBackgroundColor = $buttonBackgroundColor;
    $this->borderColor = $borderColor;
    $this->imageUrls = $imageUrls;
    $this->animationDuration = $animationDuration;
    $this->animationInterval = $animationInterval;
  }
  final public function create(){
    $this->css();
    $this->svg();
    $this->js();
  }
  final protected function css(){
    // css styling of svg for easy theming
    echo "<style>";

    echo "#background-rect$this->index, #new-rect$this->index{";
    echo "  fill: $this->imagesBackgroundColor;";
    echo "}";

    // background and border of slides and the main image
    echo "#wonderful-image-gallery-$this->index .slides>rect{";
    echo "  fill: $this->imagesBackgroundColor;";
    echo "  stroke: $this->backgroundColor;";
    echo "}";

    // default svg styling (should not change)
    echo "#wonderful-image-gallery-$this->index .slides>rect, #more-pictures-left$this->index, #more-pictures-right$this->index, #wonderful-image-gallery-$this->index .play-button, #wonderful-image-gallery-$this->index .main-images{";
    echo "cursor: pointer;";
    echo "}";

    echo "#wonderful-image-gallery-$this->index .seen-slide{";
    echo "  filter:url(#sepia);";
    echo "}";

    echo "#wonderful-image-gallery-$this->index .slides>rect.active-slide{";
    echo "	stroke: $this->buttonBackgroundColor";
    echo "}";

    echo "#wonderful-image-gallery-$this->index .slides>image{";
    echo '  pointer-events: none;';
    echo '}';

    echo "#wonderful-image-gallery-$this->index .leftNav{";
    echo '  display: none;';
    echo '}';

    echo "</style>";
  }
  final protected function svg(){
    echo "<svg id='wonderful-image-gallery-$this->index' class='wonderful-image-gallery' viewBox='0 0 3000 2500' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'> ";
    echo '<defs>';

    // gradient around play button
    // TODO: check if this is still being used in the js
  	echo 	 "<radialGradient id='attention-circle$this->index'>";
  	echo     "<stop stop-color='$this->buttonBackgroundColor' offset='0%' stop-opacity='1' />";
  	echo     "<stop stop-color='$this->backgroundColor' offset='75%' stop-opacity='1' />";
  	echo	   "<stop id='playButtonEffect' stop-color='$this->buttonBackgroundColor' offset='80%' stop-opacity='0' />";
  	echo     "<stop offset='1050' stop-color='white' stop-opacity='0'/>";
  	echo 	 "</radialGradient>";
    // echo the mask
    echo  "<mask id='wig_mask'>";
          $this->svg_mask();
    echo  '</mask>';
    echo '</defs>';

    	// slider background
    	echo "<rect width='3000' height='2500' fill='$this->backgroundColor'></rect>";

      // main slide
    	echo "<g class='main-images' transform='translate(225,50)' >";
      echo    "<rect width='2550' height='1925' id='background-rect$this->index'></rect>";
      echo    "<image xlink:href='".$this->imageUrls[0]."' width='2550' height='1925' mask='url(#displayed_mask)' class='old-image'></image>";
      echo "</g>";

    	// navigation left and right
    	echo "<g transform='translate(0,1975)'>";
    	echo "<g class='slides' transform='translate(225, 0)'>";
      foreach ($this->imageUrls as $index => $imageUrl) {
        echo "<rect class='".( ($index === 0) ? 'active-slide' : '')."' height='400' width='600' x='".($index*650)."' y='50' stroke-width='5' data-dia-nr='$index'></rect>";
        echo "<image class='slide-nr-$index' height='390' width='590' x='".($index*650+5)."' y='55' xlink:href='$imageUrl'></image>";
      } // end foreach $this->imageUrls
    	echo '</g>';

    	// left button
    	echo "<g class='leftNav' display='none'>";
    	echo   "<rect id='more-pictures-left$this->index' x='0' y='0' width='200' height='500'  fill='$this->backgroundColor'></rect>";
    	echo   "<path d='m140,100 l-70,150 70,150' stroke-width='15' stroke='$this->buttonBackgroundColor' stroke-linecap='round' fill='none' pointer-events='none' />";
    	echo '</g>';

    	// right button
    	echo "<g class='rightNav' display='".( ( count( $this->imageUrls ) > 4 ) ? 'inline' : 'none')."'>";
    	echo   "<rect id='more-pictures-right$this->index' x='2800' y='0' width='200' height='500' fill='$this->backgroundColor'></rect>";
    	echo	  "<path d='m2840,100 l70,150 -70,150' stroke-width='15' stroke='$this->buttonBackgroundColor' stroke-linecap='round' fill='none' pointer-events='none' />";
    	echo '</g>';

      // play button
    	echo '<g>';
    	echo "<circle class='play-button' cx='1500' cy='25' r='150' fill='$this->buttonBackgroundColor' />";
    	echo "<path class='play-button-play-mode' class='color-1-fill' d='m1500,25 m-60,-90 l0,180 160,-90 z' fill='$this->buttonColor' pointer-events='none' stroke-linejoin='round' />";
    	echo "<path class='play-button-pause-mode' display='none' class='color-1-fill' d='m1500,25 m-60,-90 l0,180 50,0 0,-180z m70,0 l0,180 50,0 0,-180z' fill='$this->buttonColor' pointer-events='none' />";
    	echo '</g>';
    	echo '</g>';

    	// frame around everything
    	echo "<rect stroke='$this->borderColor' width='3000' height='2500' fill='none' stroke-width='25'></rect>";
      echo "</svg>";
  }
  protected abstract function svg_mask();
  final protected function js(){
  echo  '<script>';

  echo  "window.addEventListener('load', function(){";
  echo    "wonderfulImageGallery$this->index.init();";
  echo  "}, false);";

  echo  "let wonderfulImageGallery$this->index = {";

    // setup wonderfulImageGallery object
    echo "wonderfulImageGalleryElement: document.getElementById('wonderful-image-gallery-$this->index'),";
    echo "activeDia: 0,";
    echo "isCurrentlyAnimating: false,";
    echo "imgUrls: ".json_encode($this->imageUrls).",";
    echo "currentlyShownImageIndex: 0,";
    echo "mainImage: {";
    echo    "imageContainer: document.querySelector('#wonderful-image-gallery-$this->index .main-images'),";
    // TODO: refactor the mask into $this->svg()
    // echo    "mask: 'clip2',"; // $params->get('animation-type-list', 'vanilla'))[1] = clip2
    // TODO: can probably refactor the animation name out too
    // echo		"animationName: 'animationCircles',"; // $params->get('animation-type-list', 'vanilla'))[0] = 'animationCircles'
    echo 		"animationDuration:$this->animationDuration,";
    echo 		"animationInterval: $this->animationInterval,";
    // TODO: refactor this function output to a php method
    echo 		"animation:";
            $this->js_animateMask();
    echo    ',';
    //remove old image if one exists
    echo 			"endAnimation: function(){";
    echo      "if( document.querySelector('#wonderful-image-gallery-$this->index .old-image') !== null ){";
    echo        "document.querySelector('#wonderful-image-gallery-$this->index .old-image').remove();";
    echo    	"}";
    //remove rect
    echo      "document.getElementById('new-rect$this->index').remove();";
    //make new image the old image
    echo			"document.querySelector('#wonderful-image-gallery-$this->index .new-image').setAttribute('class', 'old-image');";
    echo      "document.querySelector('#wonderful-image-gallery-$this->index .old-image').setAttribute('mask','url(#displayed_mask)');";
    echo      "}"; // end endAnimation
    echo 	 "},"; // end mainImage
    echo 	 "diaPreview:{";
    echo      "imagePreviewContainer: document.querySelector('#wonderful-image-gallery-$this->index .slides'),";
    echo      "imageBackgrounds: document.querySelectorAll('#wonderful-image-gallery-$this->index .slides>rect'),";
    echo		  "hiddenLeftDias: 0,";
    echo		  "previousPageButton: document.querySelector('#wonderful-image-gallery-$this->index .leftNav'),";
    echo		  "nextPageButton: document.querySelector('#wonderful-image-gallery-$this->index .rightNav')";
    echo   "},"; // end diaPreview
    echo   "playAndPauseButton:{";
    echo       "pauseAndPlayButton: document.querySelector('#wonderful-image-gallery-$this->index .play-button'),";
    echo	     "playIcon: document.querySelector('#wonderful-image-gallery-$this->index .play-button-play-mode'),";
    echo		   "pauseIcon: document.querySelector('#wonderful-image-gallery-$this->index .play-button-pause-mode')";
    echo	 "},"; // end playAndPauseButton
    echo	 "init: function(){";
    echo 	    "let thisWonderfulImageGalleryObject = this;";
    // add eventlisteners pause and play button
		echo    "this.playAndPauseButton.pauseAndPlayButton.addEventListener('click', function(){";
		echo  	   "if(thisWonderfulImageGalleryObject.isCurrentlyAnimating ){";
		echo 		      "thisWonderfulImageGalleryObject.pauseSlideShow();";
		echo	     "}else{";
		echo		      "thisWonderfulImageGalleryObject.runSlideShow();";
		echo        "}";
		echo    "});"; // end click this.playAndPauseButton.pauseAndPlayButton
    // add eventlisteners next four dias button
    echo  "this.diaPreview.nextPageButton.addEventListener('click', function(){";
    echo      "thisWonderfulImageGalleryObject.showNextFourDiaPreviews();";
    echo	"});"; // end click this.diaPreview.nextPageButton
    // add eventlisteners previous four dias button
		echo  "this.diaPreview.previousPageButton.addEventListener('click', function(){";
		echo      "thisWonderfulImageGalleryObject.showPreviousFourDiaPreviews();";
		echo  "});"; // end click this.diaPreview.previousPageButton
    // add eventlisteners main image
    echo  "this.mainImage.imageContainer.addEventListener('click', function(){";
    echo  "if(thisWonderfulImageGalleryObject.isCurrentlyAnimating ){";
    echo    " thisWonderfulImageGalleryObject.pauseSlideShow();";
    echo  "}else{";
    echo    "thisWonderfulImageGalleryObject.runSlideShow();";
    echo	'}';
    echo	'});'; // end click this.mainImage.imageContainer
    // add eventlisteners to all preview images
    echo "this.diaPreview.imageBackgrounds.forEach(function(slide){";
    echo    "slide.addEventListener('click', function(){";
    echo		  "thisWonderfulImageGalleryObject.currentlyShownImageIndex = parseInt(this.getAttribute('data-dia-nr'));";
    echo		  "thisWonderfulImageGalleryObject.pauseSlideShow();";
    echo		  "thisWonderfulImageGalleryObject.runSlide();";
    echo    '});'; // end click single item in this.diaPreview.imageBackgrounds
    echo	'});'; // end forEach this.diaPreview.imageBackgrounds
    echo	'},'; // end init()
    // hideInappropriateButtons()
    echo	'hideInappropriateButtons: function(){';
    echo		'let hiddenLeftDias = this.diaPreview.hiddenLeftDias;';
    echo    'let diaTotal = this.imgUrls.length;';
    echo		'let nextButton = this.diaPreview.nextPageButton;';
    echo		'let previousButton = this.diaPreview.previousPageButton;';
            //hide/show left button if..
    echo		'if(hiddenLeftDias == 0){';
    echo			'previousButton.style.display = "none";';
    echo    '}else{';
    echo			'previousButton.style.display = "inline";';
    echo		'}';
            //hide/show right button if..
    echo		'if(diaTotal - hiddenLeftDias > 4){';
    echo			'nextButton.style.display = "inline";';
    echo		'}else{';
    echo			'nextButton.style.display = "none";';
    echo		'}';
    echo	'},'; // end hideInappropriateButtons
    // showNextFourDiaPreviews()
    echo	'showNextFourDiaPreviews: function(){';
    echo		 'let newHiddenLeftDias = this.diaPreview.hiddenLeftDias + 4;';
    echo		 'let slideContainer = this.diaPreview.imagePreviewContainer;';
    echo		 'slideContainer.setAttribute("transform", `translate(${225-newHiddenLeftDias*650}, 0)`);';
    		      // set the new value
    echo		  'this.diaPreview.hiddenLeftDias = newHiddenLeftDias;';
    		      // hide inappropriate buttons
    echo		  'this.hideInappropriateButtons();';
    echo	'},'; // end showNextFourDiaPreviews
    // showPreviousFourDiaPreviews()
    echo	'showPreviousFourDiaPreviews: function(){';
    echo	  'let newHiddenLeftDias = this.diaPreview.hiddenLeftDias - 4;';
    echo		'let slideContainer = this.diaPreview.imagePreviewContainer;';
    echo		'slideContainer.setAttribute("transform", `translate(${225-newHiddenLeftDias*650}, 0)`);';
    		    // set the new value
    echo		'this.diaPreview.hiddenLeftDias = newHiddenLeftDias;';
    		    // hide inappropriate buttons
    echo    'this.hideInappropriateButtons();';
    echo	'},'; // end showPreviousFourDiaPreviews
    // runSlideShow()
    echo	'runSlideShow: function(){';
    echo	   'let imgUrls = this.imgUrls;';
    echo	   'let playIcon = this.playAndPauseButton.playIcon;';
    echo	   'let pauseIcon = this.playAndPauseButton.pauseIcon;';
    		   //indicate that the slideshow is currently running
    echo   'this.isCurrentlyAnimating = true;';
    		   //change button to "paused-icon"
    echo		'playIcon.style.display = "none";';
    echo		'pauseIcon.style.display = "inline";';
    		    // immidiately animate the next dia
    echo		'this.showNextDia();';
    echo '},'; // end runSlideShow
    // pauseSlideShow()
    echo	'pauseSlideShow: function(){';
    echo		'let playIcon = this.playAndPauseButton.playIcon;';
    echo		'let pauseIcon = this.playAndPauseButton.pauseIcon;';
    		    // change button to "play-icon"
    echo		'pauseIcon.style.display = "none";';
    echo		'playIcon.style.display = "inline";';
    		    // stop the current animation
    echo		'if(this.showNextDiaTimeout){';
    echo		    'clearTimeout(this.showNextDiaTimeout);';
    echo 		'}';
    		    // indicate slideshow is currently not running
    echo    'this.isCurrentlyAnimating = false;';
    echo 	'},'; // end pauseSlideShow
    // showNextDiaTimeout contains a placeholder for the timeout queing animations
    echo	'showNextDiaTimeout: null,';
    // showNextDia()
    echo	'showNextDia: function(){';
    echo	   'const animationDuration = this.mainImage.animationDuration;';
    echo		 'const animationInterval = this.mainImage.animationInterval;';
    echo		 'const animationCycleTime = animationInterval + animationDuration;';
    echo		 'let thisWonderfulImageGalleryObject = this;';
    echo		 'let imgUrls = this.imgUrls;';
    echo		 'let nextImageIndex = this.currentlyShownImageIndex + 1;';
    echo		 'if(nextImageIndex === imgUrls.length){';
    echo				'nextImageIndex = 0;';
    echo		'}';
    		    // set new index for the image to animate
    echo    'this.currentlyShownImageIndex = nextImageIndex;';
    		    // animate the image
    echo		'this.runSlide();';
    		    // animate the next dia later, after the specified interval (infinite loop)
    echo    'this.showNextDiaTimeout = setTimeout(function(){';
    echo        'thisWonderfulImageGalleryObject.showNextDia();';
    echo    '}, animationCycleTime);';
    echo  '},'; // end showNextDia
    // runslide()
    echo	'runSlide: function(){';
    echo	'let mask = `url(#wig_mask)`;';
    echo	'let slideIndex = this.currentlyShownImageIndex;';
    echo	'let imgUrls = this.imgUrls;';
    		  //remove .active-slide and set .active-slide on appropriate element
    echo	"document.querySelector('#wonderful-image-gallery-$this->index .slides>rect.active-slide').classList.remove('active-slide');";
    echo	"document.querySelector('#wonderful-image-gallery-$this->index .slide-nr-'+ slideIndex ).previousSibling.classList.add('active-slide');";
    		  //set slideDia to move if selection changes outside of the shown images
    echo	'if(slideIndex % 4 === 0){';
    			// set new value
    echo	   'this.diaPreview.hiddenLeftDias = slideIndex;';
    echo		 "document.querySelector('#wonderful-image-gallery-$this->index .slides').setAttribute('transform',".'`translate(${225-slideIndex*650}, 0)`'.");";
    			// hide the inappropriate next or previous button
    echo		  'this.hideInappropriateButtons();';
    echo  '}';
    		//createNewSlide image 'above' old image without clipping
    echo	"let node = document.createElementNS('http://www.w3.org/2000/svg','image');";
    echo	"node.setAttributeNS('http://www.w3.org/1999/xlink','href',imgUrls[slideIndex]);";
    echo	"node.setAttributeNS(null,'width','2550');";
    echo	"node.setAttributeNS(null,'height','1925');";
    echo	"node.setAttributeNS(null,'mask', mask);";
    echo	"node.setAttributeNS(null,'class','new-image');";
    echo	"let mainImages = this.mainImage.imageContainer;";
    echo	"mainImages.appendChild(node);";
    		  // add a rect so transitions look good between screen sizes
    echo	"node = document.createElementNS('http://www.w3.org/2000/svg','rect');";
    echo	"node.setAttributeNS(null,'width','2550');";
    echo	"node.setAttributeNS(null,'height','1925');";
    echo	"node.setAttributeNS(null,'mask', mask);";
    echo	"node.setAttributeNS(null,'id','new-rect$this->index');";
    echo	"mainImages.insertBefore(node, mainImages.lastElementChild);";
    		  //start animating
    echo	'this.mainImage.animation();';
    echo  '}'; // end runslide
    echo  '}'; // end ???
    echo "</script>";
  } // end $this->js()
  protected abstract function js_animateMask();
} // end Animation
// vanillaAnimation
class vanillaAnimation extends Animation{
  final protected function svg_mask(){
    echo    "<rect width='2550' height='100%' fill='white'></rect>";
  }
  final protected function js_animateMask(){
    echo  "function(){";
    echo    'let thisWonderfulImageGalleryObjectMainImage = this;';
    echo    'let duration = this.animationDuration;';
          	// end animation
    echo    'thisWonderfulImageGalleryObjectMainImage.endAnimation();';
    echo  '}'; // end $this->animation
  }
}
// circlesAnimation
class circlesAnimation extends Animation{
  final protected function svg_mask(){
    // echo '<rect width="0" height="1925" fill="black"></rect>';
    echo '<circle id="clip2_circle1" cx="1000" cy="1000" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle2" cx="500" cy="0" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle3" cx="2000" cy="500" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle4" cx="500" cy="1500" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle5" cx="500" cy="500" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle6" cx="1100" cy="50" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle7" cx="1300" cy="1650" r="0" fill="white"></circle>';
    echo '<circle id="clip2_circle8" cx="2200" cy="1650" r="0" fill="white"></circle>';
  }
  final protected function js_animateMask(){
    echo 'function(){';
    echo 	'let duration = this.animationDuration;';
    echo 	'let thisWonderfulImageGalleryObjectMainImage = this;';
    echo 	'const startTime = performance.now();';
    	    //get the element that will be animated
    echo 	"const circle1 = document.getElementById('clip2_circle1');";
    echo 	"const circle2 = document.getElementById('clip2_circle2');";
    echo	"const circle3 = document.getElementById('clip2_circle3');";
    echo	"const circle4 = document.getElementById('clip2_circle4');";
    echo	"const circle5 = document.getElementById('clip2_circle5');";
    echo 	"const circle6 = document.getElementById('clip2_circle6');";
    echo  "const circle7 = document.getElementById('clip2_circle7');";
    echo	"const circle8 = document.getElementById('clip2_circle8');";
          // return a value between start and end as l goes from 0 to 1
    echo  'function lerp(start, end, progress) {';
    echo    'return  (start + (end - start) *  progress);';
    echo  '}';
          // start animation sequence
    echo	'window.requestAnimationFrame(animateFrame);';
          // callback function for requestAnimationFrame
    echo	'function animateFrame(){';
    echo		'const timeSinceStart = (performance.now() - startTime);';
    		  // progress goes from 0 to 1 (1 means the animation is completed);
    echo 		'const progress = Math.min(timeSinceStart / duration, 1);';
    		  //update frame
    echo	  "circle1.setAttributeNS(null, 'r', lerp(0, 500, progress));";
    echo	  "circle2.setAttributeNS(null, 'r', lerp(0, 500, progress));";
    echo	  "circle3.setAttributeNS(null, 'r', lerp(0, 1000, progress));";
    echo	  "circle4.setAttributeNS(null, 'r', lerp(0, 750, progress));";
    echo	  "circle5.setAttributeNS(null, 'r', lerp(0, 700, progress));";
    echo	  "circle6.setAttributeNS(null, 'r', lerp(0, 150, progress));";
    echo	  "circle7.setAttributeNS(null, 'r', lerp(0, 650, progress));";
    echo	  "circle8.setAttributeNS(null, 'r', lerp(0, 500, progress));";
    echo	  'if(progress<1){';
    echo			 'window.requestAnimationFrame(animateFrame);';
    echo	  '}else{';
    			  //reset radius on clipping so we can apply it on the newest slide
    echo	  "circle1.setAttributeNS(null, 'r', '0');";
    echo	  "circle2.setAttributeNS(null, 'r', '0');";
    echo	  "circle3.setAttributeNS(null, 'r', '0');";
    echo	  "circle4.setAttributeNS(null, 'r', '0');";
    echo	  "circle5.setAttributeNS(null, 'r', '0');";
    echo	  "circle6.setAttributeNS(null, 'r', '0');";
    echo	  "circle7.setAttributeNS(null, 'r', '0');";
    echo	  "circle8.setAttributeNS(null, 'r', '0');";
    			// end animation
    echo	  'thisWonderfulImageGalleryObjectMainImage.endAnimation();';
    echo  '}'; // end else
    echo	'}';// end animateFrame()
    echo  '}'; // end anonamous function
  } // end js_animateMask
} // end circlesAnimation
// widthAnimation
class widthAnimation extends Animation{
  final protected function svg_mask(){
    echo '<rect id="clip1_rect" width="0" height="100%" fill="white"></rect>';
  }
  final protected function js_animateMask(){
    echo 'function(){';
      // return a value between start and end as l goes from 0 to 1
    echo  'function lerp(start, end, progress) {';
    echo    'return  (start + (end - start) *  progress);';
    echo  '}';
    echo	'let thisWonderfulImageGalleryObjectMainImage = this;';
    echo 	'let duration = this.animationDuration;';
    echo 	'const startTime = performance.now();';
    	    //get the element that will be animated
    echo	"const rect1 = document.getElementById('clip1_rect');";
    	    // start animation sequence
    echo	'window.requestAnimationFrame(animateFrame);';
    	    //animate a single frame
    echo	'function animateFrame(){';
    echo		  'const timeSinceStart = (performance.now() - startTime);';
    		  // progress goes from 0 to 1 (1 means the animation is completed);
    echo		  'const progress = Math.min(timeSinceStart / duration, 1);';
    		  //update frame
    echo	"rect1.setAttributeNS(null, 'width', lerp(0, 2550, progress));";
    echo	'if(progress<1){';
    echo			'window.requestAnimationFrame(animateFrame);';
    echo	'}else{';
    			//reset width on clipping so we can apply it on the newest slide
    echo	    "rect1.setAttributeNS(null, 'width', 0);";
    			// end animation
    echo			'thisWonderfulImageGalleryObjectMainImage.endAnimation();';
    echo   '}'; // end else
    echo  '}'; // end animateFrame()
    echo '}'; // end callback fucntion
  }
}

// test code
// $animation = new widthAnimation(
//   1001,
//   'black',
//   'blue',
//   'white',
//   'pink',
//   'black',
//   array('https://lorempixel.com/400/200/sports/1/', 'https://lorempixel.com/400/200/sports/2/', 'https://lorempixel.com/400/200/sports/3/', 'https://lorempixel.com/400/200/sports/4/', 'https://lorempixel.com/400/200/sports/5/'),
//   3000,
//   5000
// );
// $animation->create();
