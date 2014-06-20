<?php
error_reporting(E_ALL & ~E_NOTICE);
// no direct access
defined( '_JEXEC' ) or die ( 'Restricted access' );

jimport('joomla.application.component.view');

if (!function_exists('class_alias')) { // For php older then 5.3
  function class_alias($orig, $alias) {
    eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
  }
}

if (!class_exists('JViewLegacy')) {
  class_alias('JView','JViewLegacy');
}

class MailjetViewMailjet extends JViewLegacy {

    function display($tpl = null) {
        global $result;
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        echo json_encode($result);
    }

}
