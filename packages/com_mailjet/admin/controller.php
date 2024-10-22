<?php
/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access to this file
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die('Restricted access');
ini_set('display_errors', 0);

// import Joomla controller library
jimport('joomla.application.component.controller');

if (!class_exists('JControllerLegacy')) {
    class_alias('JController', 'JControllerLegacy');
}

class MailjetController extends JControllerLegacy
{
    /**
     * @param bool $cachable
     * @param bool $urlparams
     *
     * @return void
     * @throws Exception
     * @since 4.0
     */
    public function display($cachable = false, $urlparams = false): void
    {
        require_once JPATH_COMPONENT . '/helpers/mailjet.php';
        $jInput = Factory::getApplication()->input;
        $jInput->set('view', $jInput->getCmd('view', 'mailjet'));

        MailjetHelper::addSubmenu($this->input->get('view', 'mailjet'));
        parent::display($cachable);
    }


    /**
     * @throws Exception
     * @since 4.0
     */
    public function save(): void
    {
        Session::checkToken() or jexit('Invalid Token');

        $error = false;
        $mailjetConfig = sPrintF('%s/components/%s/config.php', JPATH_ADMINISTRATOR, 'com_mailjet');
        $fileConfig = JPATH_ROOT . '/configuration.php';

        require_once($mailjetConfig);

        $conf = new JMailjetConfig();
        $host = $conf->host;
        $prev = new JConfig();
        $prev = ArrayHelper::fromObject($prev);

        $fields['bak_mailer'] = $prev['mailer'];
        $fields['bak_smtpauth'] = $prev['smtpauth'];
        $fields['bak_smtpuser'] = $prev['smtpuser'];
        $fields['bak_smtppass'] = $prev['smtppass'];
        $fields['bak_smtphost'] = $prev['smtphost'];
        $fields['bak_smtpsecure'] = $prev['smtpsecure'];
        $fields['bak_smtpport'] = $prev['smtpport'];

        $data = Factory::getApplication()->input->post->getArray();

        $fields['enable'] = isset($data['enable']) && $data['enable'] === 'on';
        $fields['test'] = isset($data['test']);
        $fields['test_address'] = $data['test_address'];
        $fields['username'] = $data['username'];
        $fields['password'] = $data['password'];
        $fields['host'] = $host;

        $configs = [['ssl://', 465],
            ['tls://', 587],
            ['', 587],
            ['', 588],
            ['tls://', 25],
            ['', 25],
            ['', 80]
        ];

        $connected = false;

        for ($i = 0; $i < count($configs); ++$i) {
            $soc = @fSockOpen($configs[$i][0] . $host, $configs[$i][1], $errno, $errstr, 5);
            if ($soc) {
                fClose($soc);
                $connected = true;
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
            Factory::getApplication()->enqueueMessage(
                json_encode([
                    'message' => \Joomla\CMS\Language\Text::_('COM_MAILJET_CONTACT_SUPPORT_ERROR'),
                    'error' => $errno,
                    'str' => $errstr
                ]),
                \Joomla\CMS\Application\CMSApplication::MSG_WARNING
            );
        }

        jimport('joomla.mail.helper');

        if ($fields ['test'] && (empty ($fields ['test_address']) || !JMailHelper::isEmailAddress($fields ['test_address']))) {
            Factory::getApplication()->enqueueMessage(
                json_encode([
                    'message' => \Joomla\CMS\Language\Text::_('COM_MAILJET_RECIPIENT_INVALID'),
                    'error' => $fields['test_address'],
                ]),
                \Joomla\CMS\Application\CMSApplication::MSG_WARNING
            );
            $error = true;
        }

        if (empty($fields ['username']) || empty($fields ['password'])) {
            Factory::getApplication()->enqueueMessage(
                \Joomla\CMS\Language\Text::_('COM_MAILJET_SETTINGS_MANDATORY'),
                \Joomla\CMS\Application\CMSApplication::MSG_WARNING
            );
            $error = true;
        }

        if (!$error) {
            jimport('joomla.filesystem.path');
            jimport('joomla.filesystem.file');

            $config = new \Joomla\Registry\Registry('config');
            $config->loadArray($fields);

            $configString = $config->toString('PHP', array('class' => 'JMailjetConfig', 'closingtag' => false));

            if (!\Joomla\Filesystem\File::write($mailjetConfig, $configString)) {
                Factory::getApplication()->enqueueMessage(
                    \Joomla\CMS\Language\Text::_('COM_MAILJET_CONFIG_FILE_UNWRITABLE'),
                    \Joomla\CMS\Application\CMSApplication::MSG_WARNING
                );
            }

            $mailjetData = sPrintF('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');
            $JSONString = json_encode(array(
                'apiKey' => $fields['username'],
                'apiSecret' => $fields['password'],
                'test_address' => $fields['test_address'],
                'enable' => $fields['enable'],
            ));

            if (!\Joomla\Filesystem\File::write($mailjetData, $JSONString)) {
                Factory::getApplication()->enqueueMessage(
                    \Joomla\CMS\Language\Text::_('Unable to write data file for Mailjet\'s settings.'),
                    \Joomla\CMS\Application\CMSApplication::MSG_WARNING
                );
            }

            if ($fields['enable']) {
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

            $config = new \Joomla\Registry\Registry('config');
            $config->loadArray($prev);

            if (!\Joomla\Filesystem\Path::setPermissions($fileConfig, '0644')) {
                Factory::getApplication()->enqueueMessage(
                    \Joomla\CMS\Language\Text::_('COM_CONFIG_ERROR_CONFIGURATION_PHP_NOTWRITABLE'),
                    \Joomla\CMS\Application\CMSApplication::MSG_NOTICE
                );
            }

            $configString = $config->toString('PHP', ['class' => 'JConfig', 'closingtag' => false]);
            if (!\Joomla\Filesystem\File::write($fileConfig, $configString)) {
                Factory::getApplication()->enqueueMessage(
                    \Joomla\CMS\Language\Text::_('COM_CONFIG_ERROR_WRITE_FAILED'),
                    \Joomla\CMS\Application\CMSApplication::MSG_WARNING
                );
            }

            if (!\Joomla\Filesystem\Path::setPermissions($fileConfig, '0444')) {
                Factory::getApplication()->enqueueMessage(
                    \Joomla\CMS\Language\Text::_('COM_CONFIG_ERROR_CONFIGURATION_PHP_NOTUNWRITABLE'),
                    \Joomla\CMS\Application\CMSApplication::MSG_NOTICE
                );
            }

            // The new approach of making Mailjet API calls
            require_once(dirname(__FILE__) . '/lib/lib/mailjet-api-helper.php');
            $mjClient = Mailjet_Api_Helper::getMailjetClient($fields['username'], $fields['password']);
            if (!$mjClient) {
                Factory::getApplication()->enqueueMessage(
                    \Joomla\CMS\Language\Text::_('COM_MAILJET_API_KEY_ERROR'),
                    \Joomla\CMS\Application\CMSApplication::MSG_WARNING
                );
            }

            if ($fields['test']) {
                $jversion = new \Joomla\CMS\Version();
                if (version_compare($jversion->getShortVersion(), '2.5.6', 'lt')) {
                    if (JUtility::sendMail($prev['mailfrom'], $prev['fromname'], $fields['test_address'],
                            \Joomla\CMS\Language\Text::_('Your test mail from Mailjet and Joomla'),
                            \Joomla\CMS\Language\Text::_('COM_MAILJET_CONFIG_OK')) !== true
                    ) {
                        Factory::getApplication()->enqueueMessage(
                            \Joomla\CMS\Language\Text::_('COM_MAILJET_TEST_EMAIL_NOT_SENT'),
                            \Joomla\CMS\Application\CMSApplication::MSG_NOTICE
                        );
                    }
                } else {
                    $mail = Factory::getContainer()->get(\Joomla\CMS\Mail\MailerFactoryInterface::class)->createMailer();
                    $mail->useSMTP(true, $prev['smtphost'], $prev['smtpuser'], $prev['smtppass'], 'tls', 587);
                    if ($mail->sendMail($prev['mailfrom'], $prev['fromname'], $fields['test_address'],
                            \Joomla\CMS\Language\Text::_('Your test mail from Mailjet and Joomla'),
                            \Joomla\CMS\Language\Text::_('COM_MAILJET_CONFIG_OK')) !== true
                    ) {
                        Factory::getApplication()->enqueueMessage(
                            \Joomla\CMS\Language\Text::_('COM_MAILJET_TEST_EMAIL_NOT_SENT'),
                            \Joomla\CMS\Application\CMSApplication::MSG_NOTICE
                        );
                    }
                }
            }
            Factory::getApplication()->enqueueMessage(\Joomla\CMS\Language\Text:: _('COM_MAILJET_SETTINGS_SAVED'));
        }
        $this->display();
    }
}
