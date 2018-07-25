<?php
// No direct access
defined('_JEXEC') or die;

abstract class Animation{
  protected $index;
  protected $imagesBackgroundColor;
  protected $backgroundColor;

  public function __construct( $id, $imagesBackgroundColor, $backgroundColor ){
    $this->index = $id;
    $this->imagesBackgroundColor = $imagesBackgroundColor;
    $this->backgroundColor = $backgroundColor;
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
    echo "	stroke: $this->backgroundColor";
    echo "}";

    echo "#wonderful-image-gallery-$this->index .slides>image{";
    echo '  pointer-events: none;';
    echo '}';

    echo "#wonderful-image-gallery-$this->index .leftNav{";
    echo '  display: none;';
    echo '}';

    echo "</style>";
  }
  protected abstract function get_svg();
  protected abstract function get_js();
}

class testAnimation extends Animation{
  protected function get_svg(){}
  protected function get_js(){}
}
