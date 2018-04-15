# wonderful-image-galleries
A gallery module with animated transitions.

# examples and installation
This code is intended for use in Joomla! If you want to see some examples look at https://www.webimprovement.eu/wonderful-image-galleries. You can also download the currently released version of WIG (Wonderful Image Galleries) from there.
# project structure
<ul>
 <li><b>/example_images</b>: contains some example images</li>
 <li><b>/language</b>: contain the language files, defining translations</li>
 <li><b>/tmpl</b>: contain the module specific files</li>
 <ul>   
  <li><b>/css</b>: contains a php file that creates the css to assign user defined colors to the svg. (does nothing besides assigning colors!!!)</li>
  <li><b>/js</b>: contains javascript files</li>
   <ul>
     <li><b>/animations</b>: contains a function with the animation logic of a certain animations using javascript's	window.requestAnimationFrame().</li>
     <li><b>/js.php</b>: contains the logic for the general functionality and implements the relevant animation from the animations folder</li>
   </ul>
  <li><b>/svg</b>: contains files that create the svg</li>
   <ul>
    <li><b>/animations</b>: contains the mask used for a specific animation</li>
    <li><b>/svg.php</b>: creates the svg it also includes a file with a mask from the animations folder</li>
   </ul>
 </ul>
  <li><b>/install.wig.php</b>: Joomla! installation file</li>
  <li><b>/mod_wig.php</b>: forgot what this file was for. I'm sure it's somehow important xD.</li>
  <li><b>/mod_wig.xml</b>: file that creates the module interface in Joomla! and provides general information abut the module.</li>
</ul>
