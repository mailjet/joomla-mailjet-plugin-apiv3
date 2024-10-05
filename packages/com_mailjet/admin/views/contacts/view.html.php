<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
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

if (!class_exists('JModelLegacy')) {
  class_alias('JModel','JModelLegacy');
}

$jversion = new JVersion;
$jshort = $jversion->getShortVersion();

$lang = \Joomla\CMS\Factory::getApplication()->getLanguage();
$extension = 'com_mailjet';
$base_dir = JPATH_SITE;
$language_tag =  $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

class MailjetViewContacts extends JViewLegacy
{
    /**
     * HelloWorlds view display method
     * @return void
     */
    function display($tpl = null)
    {
        JToolBarHelper::title (JText::_("COM_MAILJET_CONTACTS"), 'logo.png' );
        //JToolBarHelper::save ();
        
        $this->sidebar = JHtmlSidebar::render();

        parent::display ($tpl);
    }
}