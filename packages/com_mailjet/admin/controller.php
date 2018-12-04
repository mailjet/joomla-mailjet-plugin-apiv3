<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
ini_set('display_errors', 0);

// import Joomla controller library
jimport('joomla.application.component.controller');

if (!function_exists('class_alias')) { // For php older then 5.3
    function class_alias($orig, $alias)
    {
        eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
    }
}

if (!class_exists('JControllerLegacy')) {
    class_alias('JController', 'JControllerLegacy');
}

/**
 * General Controller of HelloWorld component
 */
class MailjetController extends JControllerLegacy
{
    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/mailjet.php';

        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'mailjet'));

        $view = $this->input->get('view', 'messages');
        $layout = $this->input->get('layout', 'default');
        $id = $this->input->getInt('id');

        MailjetHelper::addSubmenu($this->input->get('view', 'mailjet'));
        // call parent behavior
        parent::display($cachable);
    }


    function save()
    {
        JRequest::checkToken() or jexit('Invalid Token');

        $error = FALSE;
        $mailjetConfig = sPrintF('%s/components/%s/config.php', JPATH_ADMINISTRATOR, 'com_mailjet');
        $fileConfig = JPATH_ROOT . '/configuration.php';

        require_once($mailjetConfig);

        $conf = new JMailjetConfig();

        $host = $conf->host;

        $prev = new JConfig();
        $prev = JArrayHelper::fromObject($prev);

        $fields['bak_mailer'] = $prev['mailer'];
        $fields['bak_smtpauth'] = $prev['smtpauth'];
        $fields['bak_smtpuser'] = $prev['smtpuser'];
        $fields['bak_smtppass'] = $prev['smtppass'];
        $fields['bak_smtphost'] = $prev['smtphost'];
        $fields['bak_smtpsecure'] = $prev['smtpsecure'];
        $fields['bak_smtpport'] = $prev['smtpport'];

        $data = JRequest::get('post');

        $fields['enable'] = isSet ($data['enable']);
        $fields['test'] = isSet ($data['test']);
        $fields['test_address'] = $data['test_address'];
        $fields['username'] = $data['username'];
        $fields['password'] = $data['password'];
        $fields['host'] = $host;

        $configs = array(array('ssl://', 465),
            array('tls://', 587),
            array('', 587),
            array('', 588),
            array('tls://', 25),
            array('', 25),
            array('', 80)
        );

        $connected = FALSE;

        for ($i = 0; $i < count($configs); ++$i) {
            $soc = @fSockOpen($configs[$i][0] . $host, $configs[$i][1], $errno, $errstr, 5);
            if ($soc) {
                fClose($soc);
                $connected = TRUE;
                break;
            }
        }

        if ($connected) {
            if ('ssl://' == $configs [$i] [0]) {
                $fields ['secure'] = 'ssl';
            } elseif ('tls://' == $configs [$i] [0]) {
                $fields ['secure'] = 'tls';
            } else {
                $fields ['secure'] = 'none';
            }

            $fields ['port'] = $configs [$i] [1];
        } else {
            JError::raiseWarning(0, sPrintF(JText::_('COM_MAILJET_CONTACT_SUPPORT_ERROR'), $errno, $errstr));
        }

        jimport('joomla.mail.helper');

        if ($fields ['test'] && (empty ($fields ['test_address']) || !JMailHelper::isEmailAddress($fields ['test_address']))) {
            JError::raiseWarning(0, JText::_('COM_MAILJET_RECIPIENT_INVALID', $fields ['test_address']));
            $error = TRUE;
        }

        if (empty ($fields ['username']) || empty ($fields ['password'])) {
            JError::raiseWarning(0, JText::_('COM_MAILJET_SETTINGS_MANDATORY'));
            $error = TRUE;
        }

        if (!$error) {
            jimport('joomla.filesystem.path');
            jimport('joomla.filesystem.file');

            $config = new JRegistry ('config');
            $config->loadArray($fields);

            $configString = $config->toString('PHP', array('class' => 'JMailjetConfig', 'closingtag' => false));

            if (!JFile::write($mailjetConfig, $configString)) {
                JError::raiseWarning(0, JText::_('COM_MAILJET_CONFIG_FILE_UNWRITABLE'));
            }

            $mailjetData = sPrintF('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');
            $JSONString = json_encode(array(
                'apiKey' => $fields['username'],
                'apiSecret' => $fields['password'],
                'test_address' => $fields['test_address'],
                'enable' => $fields['enable'],
            ));

            if (!JFile::write($mailjetData, $JSONString)) {
                JError::raiseWarning(0, JText::_('Unable to write data file for Mailjet\'s settings.'));
            }

            if ($fields ['enable']) {
                $prev['mailer'] = 'smtp';
                $prev['smtpauth'] = '1';
                $prev['smtpuser'] = $fields['username'];
                $prev['smtppass'] = $fields['password'];
                $prev['smtphost'] = $fields['host'];
                $prev['smtpsecure'] = $fields['secure'];
                $prev['smtpport'] = $fields['port'];
            } else {
                $prev['mailer'] = $fields['bak_mailer'];
                $prev['smtpauth'] = $fields['bak_smtpauth'];
                $prev['smtpuser'] = $fields['bak_smtpuser'];
                $prev['smtppass'] = $fields['bak_smtppass'];
                $prev['smtphost'] = $fields['bak_smtphost'];
                $prev['smtpsecure'] = $fields['bak_smtpsecure'];
                $prev['smtpport'] = $fields['bak_smtpport'];
            }

            $config = new JRegistry ('config');
            $config->loadArray($prev);

            if (!JPath::setPermissions($fileConfig, '0644')) {
                JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_CONFIG_ERROR_CONFIGURATION_PHP_NOTWRITABLE'));
            }

            $configString = $config->toString('PHP', array('class' => 'JConfig', 'closingtag' => false));
            if (!JFile::write($fileConfig, $configString)) {
                JError::raiseWarning(0, JText::_('COM_CONFIG_ERROR_WRITE_FAILED'));
            }

            if (!JPath::setPermissions($fileConfig, '0444')) {
                JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_CONFIG_ERROR_CONFIGURATION_PHP_NOTUNWRITABLE'));
            }

            // The new approach of making Mailjet API calls
            require_once(dirname(__FILE__) . '/lib/lib/mailjet-api-helper.php');
            $mjClient = Mailjet_Api_Helper::getMailjetClient($fields['username'], $fields['password']);
            if (!$mjClient) {
                JError::raiseWarning(0, JText::_('COM_MAILJET_API_KEY_ERROR'));
            }
            if ($fields['test']) {
                $jversion = new JVersion();
                if (version_compare($jversion->getShortVersion(), '2.5.6', 'lt')) {
                    if (JUtility::sendMail($prev['mailfrom'], $prev['fromname'], $fields['test_address'],
                            JText::_('Your test mail from Mailjet and Joomla'),
                            JText::_('COM_MAILJET_CONFIG_OK')) !== TRUE
                    ) {
                        JError::raiseNotice(500, JText:: _('COM_MAILJET_TEST_EMAIL_NOT_SENT'));
                    }
                } else {
                    $mail = JMail::getInstance();
                    $mail->useSMTP(true, $prev['smtphost'], $prev['smtpuser'], $prev['smtppass'], 'tls', 587);
                    if ($mail->sendMail($prev['mailfrom'], $prev['fromname'], $fields['test_address'],
                            JText::_('Your test mail from Mailjet and Joomla'),
                            JText::_('COM_MAILJET_CONFIG_OK')) !== TRUE
                    ) {
                        JError::raiseNotice(500, JText:: _('COM_MAILJET_TEST_EMAIL_NOT_SENT'));
                    }
                }
            }
            JFactory::getApplication()->enqueueMessage(JText:: _('COM_MAILJET_SETTINGS_SAVED'));
        }
        $this->display();
    }

}
