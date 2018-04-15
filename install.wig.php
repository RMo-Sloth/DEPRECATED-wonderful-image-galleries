<?php
// No direct access to this file
defined('_JEXEC') or die;

class mod_wigInstallerScript
{
    function install($parent) 
    {
		$wigFolder = JPATH_SITE."/images/image_galleries";
		JFolder::create($wigFolder);
		
		$exampleFolder = JPATH_SITE . "/images/image_galleries/example_data";
		
		JFolder::copy(JPATH_ROOT . '/modules/mod_wig/example_images/', $exampleFolder);
		JFactory::getApplication()->enqueueMessage('Thank you for using Wonderful Image Galleries</br> Please make sure you do <b>not</b> remove the map called "Wonderful Image Galleries" from the media manager.<br/><br/>');
    }

    function uninstall($parent) 
    {
        echo '<p>The "Wonderful Image Galleries"-module has been uninstalled</p>';
		JFactory::getApplication()->enqueueMessage('If you have any feedback for the module please provide it to: <a href="info@webimprovement.eu">info@evemails.com</a><br/><br/>');
		JError::raiseNotice( 100, 'We did <b>not</b> remove the map "Wonderful_Image_Galleries" from the media manager, since it may contain images you want to preserve. If you wish to remove these please do so manually.');
    }

    function update($parent) 
    {
    }

    function preflight($type, $parent) 
    {
        //echo '<p>Anything here happens before the installation/update/uninstallation of the module</p>';
    }

    function postflight($type, $parent) 
    {
        //echo '<p>Anything here happens after the installation/update/uninstallation of the module</p>';
    }
}
?>