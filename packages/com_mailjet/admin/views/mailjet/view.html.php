<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

if (!function_exists('class_alias')) { // For php older then 5.3
  function class_alias($orig, $alias) {
    eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
  }
}

if (!class_exists('JViewLegacy')) {
  class_alias('JView','JViewLegacy');
}

/**
 * HelloWorlds View
 */
class MailjetViewMailjet extends JViewLegacy
{
    /**
     * HelloWorlds view display method
     * @return void
     */
    function display($tpl = null)
    {
        JToolBarHelper::title (JText::_( 'COM_MAILJET_MAILJET_SETTINGS' ), 'logo.png' );
        JToolBarHelper::save('save');

        $model = $this->getModel('mailjet');

        if (count (JRequest::get ('post')))
        {
            $params = $model->getAsPost ();
        }
        else
        {
            $params = $model->getAsRecord ();
        }
        $this->assignRef ('params', $params);
        parent::display ($tpl);
    }
}
