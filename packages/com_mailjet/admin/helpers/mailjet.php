<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_mailjet
 * @since       1.6
 */
class MailjetHelper
{
    /**
     * Configure the Linkbar.
     *
     * @param   string    The name of the active view.
     *
     * @return  void
     * @since   1.6
     */
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            \Joomla\CMS\Language\Text::_('COM_MAILJET_SETTINGS'),
            'index.php?option=com_mailjet&view=mailjet',
            $vName == 'mailjet'
        );

        JHtmlSidebar::addEntry(
            \Joomla\CMS\Language\Text::_('COM_MAILJET_CONTACTS'),
            'index.php?option=com_mailjet&view=contacts',
            $vName == 'contacts'
        );

        JHtmlSidebar::addEntry(
            \Joomla\CMS\Language\Text::_('COM_MAILJET_CAMPAIGNS'),
            'index.php?option=com_mailjet&view=campaigns',
            $vName == 'campaigns'
        );

        JHtmlSidebar::addEntry(
            \Joomla\CMS\Language\Text::_('COM_MAILJET_STATS'),
            'index.php?option=com_mailjet&view=statistics',
            $vName == 'statistics'
        );
    }
}
