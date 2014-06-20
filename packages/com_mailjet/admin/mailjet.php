<?php

if (!function_exists('class_alias')) { // For php older then 5.3
  function class_alias($orig, $alias) {
    eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
  }
}

// No direct access to this file
error_reporting(E_ALL ^ E_STRICT);
defined('_JEXEC') or die('Restricted access');

if (!class_exists('JControllerLegacy')) {
  class_alias('JController','JControllerLegacy');
}

$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-logo {background-image: url('.sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'logo-48x48.png').');}');
$document->addStyleDeclaration('.icon-48-campaigns {background-image: url('.sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'campaigns-48x48.png').');}');
$document->addStyleDeclaration('.icon-48-stats {background-image: url('.sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'stats-48x48.png').');}');
$document->addStyleDeclaration('.icon-48-contacts {background-image: url('.sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'contacts-48x48.png').');}');


// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by HelloWorld
$controller = JControllerLegacy::getInstance('Mailjet');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
