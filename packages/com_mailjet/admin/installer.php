<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_mailjetInstallerScript
{
    function install ($parent)
    {
        //$parent->getParent()->setRedirectURL('index.php?option=com_mjcustomsmtp');
    }

    function uninstall ($parent)
    {
        echo JText::_ ('Check your mail configuration to restore it.');
    }

    function update ($parent)
    {
    }

    function preflight ($type, $parent)
    {
    }

    function postflight ($type, $parent)
    {
    }
}