# DEPRECATED: wonderful-image-galleries
A gallery module with animated transitions.

# installation
This code is intended for use in Joomla! To see the current version on Github just download the master branch and install the module using the Joomla installer.
# project structure
<ul>
 <li><b>/example_images</b>: contains some example images</li>
 <li><b>/language</b>: contain the language files, defining translations</li>
 <li><b>/tmpl</b>: contain the module specific files</li>
 <ul>   
  <li><b>/css</b>: contains a php file that creates the css to assign user defined colors to the svg. (does nothing besides assigning colors!!!)</li>
  <li><b>/js</b>: contains javascript files</li>
   <ul>
     <li><b>/animations</b>: contains a function with the animation logic of a certain animation using javascript's	window.requestAnimationFrame().</li>
     <li><b>/js.php</b>: contains the logic for the general functionality and implements the relevant animation from the animations folder</li>
   </ul>
  <li><b>/svg</b>: contains files that create the svg</li>
   <ul>
    <li><b>/animations</b>: contains the mask used for a specific animation</li>
    <li><b>/svg.php</b>: creates the svg it also includes a file with a mask from the animations folder</li>
   </ul>
 </ul>
  <li><b>/install.wig.php</b>: Joomla! installation file</li>
  <li><b>/mod_wig.php</b>: forgot what this file was for. I'm sure it's somehow important in loading the module in Joomla! xD.</li>
  <li><b>/mod_wig.xml</b>: file that creates the module interface in Joomla! and provides general information abut the module.</li>
</ul>
