<?php
/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')) {
    class_alias('JView', 'JViewLegacy');
}

if(!class_exists('JModelLegacy')) {
    class_alias('JModel', 'JModelLegacy');
}

$jversion = new JVersion;
$jshort   = $jversion->getShortVersion();
$lang         = \Joomla\CMS\Factory::getApplication()->getLanguage();
$extension    = 'com_mailjet';
$base_dir     = JPATH_SITE;
$language_tag = $lang->getTag();
$reload       = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

class MailjetViewContacts extends JViewLegacy
{
    /**
     * @since 4.0
     * @return void
     * @throws Exception
     */
    public function display($tpl = null)
    {
        \Joomla\CMS\Toolbar\ToolbarHelper::title(\Joomla\CMS\Language\Text::_("COM_MAILJET_CONTACTS"), 'logo.png' );

        $this->sidebar = \Joomla\CMS\HTML\Helpers\Sidebar::render();

        parent::display($tpl);
    }
}