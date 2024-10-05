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

if (!class_exists('JViewLegacy'))
{
    class_alias('JView', 'JViewLegacy');
}

class MailjetViewMailjet extends JViewLegacy
{
    /**
     * @param   null  $tpl
     *
     * @return void
     * @throws Exception
     * @since 4.0
     */
    public function display($tpl = null): void
    {
        JToolBarHelper::title(\Joomla\CMS\Language\Text::_('COM_MAILJET_MAILJET_SETTINGS'), 'logo.png');
        JToolBarHelper::save('save');

        $model = $this->getModel('mailjet');

        if (count(\Joomla\CMS\Factory::getApplication()->input->post)) {
            $params = $model->getAsPost();
        } else {
            $params = $model->getAsRecord();
        }
        $this->params = $params;
        \Joomla\CMS\Factory::getApplication()->getDocument()->addStyleSheet(\Joomla\CMS\Uri\Uri::root() . "components/com_mailjet/styles.css");
        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }
}
