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
    // make sure to output using echo, because templating creates linebreaks, which cause issues with js's perviousSibling selector
    echo "<svg id='wonderful-image-gallery-$this->index' class='wonderful-image-gallery' viewBox='0 0 3000 2500' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'> ";
    echo '<defs>';
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
  final protected function js(){ ?>
    <script>

    window.addEventListener('load', function(){
      wonderfulImageGallery<?php echo $this->index; ?>.init()
    }, false);

    let wonderfulImageGallery<?php echo $this->index; ?> = {
      // setup wonderfulImageGallery object
      wonderfulImageGalleryElement: document.getElementById('wonderful-image-gallery-<?php echo $this->index; ?>'),
      activeDia: 0,
      isCurrentlyAnimating: false,
      imgUrls: <?php echo json_encode($this->imageUrls); ?>,
      currentlyShownImageIndex: 0,
      mainImage: {
        imageContainer: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .main-images'),
        animationDuration: <?php echo $this->animationDuration; ?>,
        animationInterval: <?php echo $this->animationInterval ?>,
        animation: <?php $this->js_animateMask(); ?>,
        //remove old image if one exists
        endAnimation: function(){
          if( document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .old-image') !== null ){
            document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .old-image').remove();
          }
          //remove rect
          document.getElementById('new-rect<?php echo $this->index; ?>').remove();
          //make new image the old image
          document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .new-image').setAttribute('class', 'old-image');
          document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .old-image').setAttribute('mask','url(#displayed_mask)');
        } // end endAnimation
      }, // end mainImage
      diaPreview:{
        imagePreviewContainer: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .slides'),
        imageBackgrounds: document.querySelectorAll('#wonderful-image-gallery-<?php echo $this->index; ?> .slides>rect'),
        hiddenLeftDias: 0,
        previousPageButton: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .leftNav'),
        nextPageButton: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .rightNav')
      }, // end diaPreview
      playAndPauseButton:{
        pauseAndPlayButton: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .play-button'),
        playIcon: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .play-button-play-mode'),
        pauseIcon: document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .play-button-pause-mode')
      }, // end playAndPauseButton
      init: function(){
        let thisWonderfulImageGalleryObject = this;
        // add eventlisteners pause and play button
		    this.playAndPauseButton.pauseAndPlayButton.addEventListener('click', function(){
		        if(thisWonderfulImageGalleryObject.isCurrentlyAnimating ){
		            thisWonderfulImageGalleryObject.pauseSlideShow();
			      }else{
		            thisWonderfulImageGalleryObject.runSlideShow();
		        }
		    }); // end click this.playAndPauseButton.pauseAndPlayButton
        // add eventlisteners next four dias button
        this.diaPreview.nextPageButton.addEventListener('click', function(){
            thisWonderfulImageGalleryObject.showNextFourDiaPreviews();
        }); // end click this.diaPreview.nextPageButton
        // add eventlisteners previous four dias button
		      this.diaPreview.previousPageButton.addEventListener('click', function(){
		          thisWonderfulImageGalleryObject.showPreviousFourDiaPreviews();
		      }); // end click this.diaPreview.previousPageButton
          // add eventlisteners main image
          this.mainImage.imageContainer.addEventListener('click', function(){
            if(thisWonderfulImageGalleryObject.isCurrentlyAnimating ){
              thisWonderfulImageGalleryObject.pauseSlideShow();
            }else{
              thisWonderfulImageGalleryObject.runSlideShow();
            }
          }); // end click this.mainImage.imageContainer
          // add eventlisteners to all preview images
          this.diaPreview.imageBackgrounds.forEach(function(slide){
            slide.addEventListener('click', function(){
              thisWonderfulImageGalleryObject.currentlyShownImageIndex = parseInt(this.getAttribute('data-dia-nr'));
              thisWonderfulImageGalleryObject.pauseSlideShow();
              thisWonderfulImageGalleryObject.runSlide();
            }); // end click single item in this.diaPreview.imageBackgrounds
          }); // end forEach this.diaPreview.imageBackgrounds
        }, // end init()
        // hideInappropriateButtons()
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
        }, // end hideInappropriateButtons
        // showNextFourDiaPreviews()
        showNextFourDiaPreviews: function(){
          let newHiddenLeftDias = this.diaPreview.hiddenLeftDias + 4;
          let slideContainer = this.diaPreview.imagePreviewContainer;
          slideContainer.setAttribute("transform", `translate(${225-newHiddenLeftDias*650}, 0)`);
    		  // set the new value
          this.diaPreview.hiddenLeftDias = newHiddenLeftDias;
    		  // hide inappropriate buttons
          this.hideInappropriateButtons();
        }, // end showNextFourDiaPreviews
    // showPreviousFourDiaPreviews()
        showPreviousFourDiaPreviews: function(){
            let newHiddenLeftDias = this.diaPreview.hiddenLeftDias - 4;
            let slideContainer = this.diaPreview.imagePreviewContainer;
            slideContainer.setAttribute("transform", `translate(${225-newHiddenLeftDias*650}, 0)`);
    		    // set the new value
    		    this.diaPreview.hiddenLeftDias = newHiddenLeftDias;
    		    // hide inappropriate buttons
            this.hideInappropriateButtons();
          }, // end showPreviousFourDiaPreviews
          // runSlideShow()
          runSlideShow: function(){
            let imgUrls = this.imgUrls;
            let playIcon = this.playAndPauseButton.playIcon;
            let pauseIcon = this.playAndPauseButton.pauseIcon;
    		    //indicate that the slideshow is currently running
            this.isCurrentlyAnimating = true;
    		    //change button to "paused-icon"
            playIcon.style.display = "none";
            pauseIcon.style.display = "inline";
    		    // immidiately animate the next dia
            this.showNextDia();
          }, // end runSlideShow
          // pauseSlideShow()
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
          }, // end pauseSlideShow
          // showNextDiaTimeout contains a placeholder for the timeout queing animations
          showNextDiaTimeout: null,
          // showNextDia()
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
          }, // end showNextDia
    // runslide()
          runSlide: function(){
            let mask = `url(#wig_mask)`;
            let slideIndex = this.currentlyShownImageIndex;
            let imgUrls = this.imgUrls;
    		    //remove .active-slide and set .active-slide on appropriate element
            document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .slides>rect.active-slide').classList.remove('active-slide');
            document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .slide-nr-'+ slideIndex ).previousSibling.classList.add('active-slide');
    		    //set slideDia to move if selection changes outside of the shown images
            if(slideIndex % 4 === 0){
    			       // set new value
                 this.diaPreview.hiddenLeftDias = slideIndex;
                 document.querySelector('#wonderful-image-gallery-<?php echo $this->index; ?> .slides').setAttribute('transform',`translate(${225-slideIndex*650}, 0)`);
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
            node.setAttributeNS(null,'id','new-rect<?php echo $this->index; ?>');
            mainImages.insertBefore(node, mainImages.lastElementChild);
    		    // start animating
            this.mainImage.animation();
          } // end runslide
        } // end ???
    </script>
    <?php
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
    echo 'function lerp(start, end, progress) {';
    echo    'return  (start + (end - start) *  progress);';
    echo  '}';
    echo  'let thisWonderfulImageGalleryObjectMainImage = this;';
    echo  'let duration = this.animationDuration;';
    echo  'const startTime = performance.now();';
    	    //get the element that will be animated
    echo  "const rect1 = document.getElementById('clip1_rect');";
    	    // start animation sequence
    echo  'window.requestAnimationFrame(animateFrame);';
    	    //animate a single frame
    echo  'function animateFrame(){';
    echo    'const timeSinceStart = (performance.now() - startTime);';
    	       // progress goes from 0 to 1 (1 means the animation is completed);
    echo     'const progress = Math.min(timeSinceStart / duration, 1);';
    		      //update frame
    echo     "rect1.setAttributeNS(null, 'width', lerp(0, 2550, progress));";
    echo   'if(progress<1){';
    echo      'window.requestAnimationFrame(animateFrame);';
    echo     '}else{';
    		    //reset width on clipping so we can apply it on the newest slide
    echo    "rect1.setAttributeNS(null, 'width', 0);";
    			  // end animation
    echo    'thisWonderfulImageGalleryObjectMainImage.endAnimation();';
    echo    '}'; // end else
    echo  '}'; // end animateFrame()
    echo '}'; // end callback fucntion
  }
}

// test code
$animation = new widthAnimation(
  1001,
  'black',
  'blue',
  'white',
  'pink',
  'black',
  array('https://lorempixel.com/400/200/sports/1/', 'https://lorempixel.com/400/200/sports/2/', 'https://lorempixel.com/400/200/sports/3/', 'https://lorempixel.com/400/200/sports/4/', 'https://lorempixel.com/400/200/sports/5/'),
  3000,
  5000
);
$animation->create();
