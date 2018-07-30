# wonderful-image-galleries
A gallery module with animated transitions.

# examples and installation
If you want to see some examples of what this module does look at https://www.webimprovement.eu/wonderful-image-galleries. You can also download the latest released version of WIG (Wonderful Image Galleries) from there. You can also run the code from the tmpl/lib/animation.php file.
# project structure
<ul>
 <li><b>/example_images</b>: contains some example images</li>
 <li><b>/language</b>: contain the language files, defining translations</li>
 <li><b>/tmpl/lib/animation.php</b>: Contains the entire logic of the wonderful-image-gallery generation. Also contains all different animation types. New animation types are made by creating a new class that extends the Animation class. Only the svg mask and the JavaScript that animates this mask have to be added in the svg_mask() and the js_animateMask() methods of the newly created class.</li>
  <li><b>/install.wig.php</b>: Joomla! installation file</li>
  <li><b>/mod_wig.php</b>: forgot what this file was for. I'm sure it's somehow important in loading the module in Joomla! xD.</li>
  <li><b>/mod_wig.xml</b>: file that creates the module interface in Joomla! and provides general information abut the module.</li>
</ul>
