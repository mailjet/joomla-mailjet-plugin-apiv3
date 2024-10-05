<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if (!function_exists('class_alias')) { // For php older then 5.3
    function class_alias($orig, $alias)
    {
        eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
    }
}

error_reporting(E_ALL ^ E_STRICT);

if (!class_exists('JControllerLegacy')) {
    class_alias('JController', 'JControllerLegacy');
}

$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-logo {background-image: url(' . sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'logo-48x48.png') . ');}');
$document->addStyleDeclaration('.icon-48-campaigns {background-image: url(' . sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'campaigns-48x48.png') . ');}');
$document->addStyleDeclaration('.icon-48-stats {background-image: url(' . sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'stats-48x48.png') . ');}');
$document->addStyleDeclaration('.icon-48-contacts {background-image: url(' . sprintf('%s/components/%s/images/%s', '../administrator', 'com_mailjet', 'contacts-48x48.png') . ');}');


// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by HelloWorld
$controller = JControllerLegacy::getInstance('Mailjet');

// Perform the Request task
$input = \Joomla\CMS\Factory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
