<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// no direct access
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')){
  define('DS',DIRECTORY_SEPARATOR);
}

// Require the base controller
require_once (JPATH_COMPONENT . DS . 'controller.php');

// Require specific controller if requested
if ($controller = JRequest::getVar('controller')) {
    require_once (JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php');
}

// Create the controller
$classname = 'MailjetController' . $controller;
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();
