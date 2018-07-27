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

  public function __construct( $id, $imagesBackgroundColor, $backgroundColor, $buttonBackgroundColor, $buttonColor, $borderColor ){
    $this->index = $id;
    $this->imagesBackgroundColor = $imagesBackgroundColor;
    $this->backgroundColor = $backgroundColor;
    $this->buttonColor = $buttonColor;
    $this->buttonBackgroundColor = $buttonBackgroundColor;
    $this->borderColor = $borderColor;
  }
  public function css(){
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
  public function svg(){
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


    echo  "<mask id='displayed_mask'>";
    echo    "<rect width='2550' height='100%' fill='white'></rect>";
    echo  '</mask>';
        // TODO: this should go to the top level selecting classes for now we just use vanilla
    		// 	$animation = explode(", ", $params->get('animation-type-list', 'vanilla'))[0];
    		// 	switch($animation){
    		// 		case 'animateWidth':
    		// 			require JModuleHelper::getLayoutPath('mod_wig', 'svg/animations/animate-width');
    		// 			break;
    		// 		case 'animationCircles':
    		// 			require JModuleHelper::getLayoutPath('mod_wig', 'svg/animations/animate-circles');
    		// 			break;
    		// 		case 'vanilla':
    		// 			break;
    		// 		default: ;
    		// 	}
    	echo '</defs>';

    	// slider background
    	echo "<rect width='3000' height='2500' fill='$this->backgroundColor'></rect>";

      // main slide
    	// container for images (will be filled using js)
    	echo "<g class='main-images' transform='translate(225,50)' ></g>";

    	// navigation left and right
    	echo "<g transform='translate(0,1975)'>";
    	echo "<g class='slides' transform='translate(225, 0)'>";
    			// SLIDES WILL BE ADDED BY JS
          // TODO: refactor js used here to php?
    	echo '</g>';

    	// left button
    	echo "<g class='leftNav'>";
    	echo   "<rect id='more-pictures-left$this->index' x='0' y='0' width='200' height='500'  fill='$this->backgroundColor'></rect>";
    	echo   "<path d='m140,100 l-70,150 70,150' stroke-width='15' stroke='$this->buttonBackgroundColor' stroke-linecap='round' fill='none' pointer-events='none' />";
    	echo '</g>';

    	// right button
    	echo "<g class='rightNav'>";
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
  protected abstract function get_js();
}

class testAnimation extends Animation{
  protected function get_svg(){}
  protected function get_js(){}
}
//
// $animation = new testAnimation(
//   1001,
//   'red',
//   'blue',
//   'white',
//   'pink',
//   'black'
// );
// $animation->css();
// $animation->svg();
