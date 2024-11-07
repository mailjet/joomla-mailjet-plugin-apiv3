<?php

/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access

use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

if (!class_exists('JModelLegacy')) {
    class_alias('JModel', 'JModelLegacy');
}

class MailjetModelMailjet extends JModelLegacy
{
    /**
     * @return array
     * @throws Exception
     * @since 4.0
     */
    public function getAsPost()
    {
        $jInput = Factory::getApplication()->input;
        $post = $jInput->post->getArray();

        $data ['enable'] = isset($post['enable']) && $post['enable'] === 'on';
        $data ['test'] = isset($post['test']);
        $data ['test_address'] = $post ['test_address'];
        $data ['username'] = $post ['username'];
        $data ['password'] = $post ['password'];
        $data ['api_token'] = serialize(false);

        return $data;
    }


    /**
     * @return array
     * @since 4.0
     */
    public function getAsRecord(): array
    {
        $mailjetConfig = sPrintF('%s/config.php', JPATH_COMPONENT);

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

        $data['enable'] = $conf->enable && 'smtp' == $prev ['mailer'] && $conf->host == $prev ['smtphost'];
        $data['test'] = $conf->test;
        $data['test_address'] = $conf->test_address;
        $data['username'] = $conf->username;
        $data['password'] = $conf->password;
        $data['host'] = $host;

        if (isset($data['api_token']) && $data['api_token']) {
            $data['api_token'] = unserialize($conf->api_token);
        }

        return $data;
    }


    /**
     * @param $key
     * @param $value
     *
     * @return void
     * @throws Exception
     * @since 4.0
     */
    public function saveRecord($key, $value)
    {
        jimport('joomla.filesystem.path');
        jimport('joomla.filesystem.file');

        $mailjetConfig = sPrintF('%s/components/%s/config.php', JPATH_ADMINISTRATOR, 'com_mailjet');

        require_once($mailjetConfig);

        $config = new JRegistry('config');
        $config->loadArray($this->getAsRecord());

        $jversion = new Version();

        if (version_compare($jversion->getShortVersion(), '2.5.6', 'lt')) {
            $config->setValue($key, $value);
        } else {
            $config->set($key, $value);
        }

        $configString = $config->toString('PHP', ['class' => 'JMailjetConfig', 'closingtag' => false]);

        if (!\Joomla\Filesystem\File::write($mailjetConfig, $configString)) {
            Factory::getApplication()->enqueueMessage(
                \Joomla\CMS\Language\Text::_('Unable to write configuration file for Mailjet\'s settings.'),
                \Joomla\CMS\Application\CMSApplication::MSG_WARNING
            );
        }

        $mailjetData = sPrintF('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');

        $JSONString = '{"apiKey":"' . $post['username'] . '","apiSecret":"' . $post['password'] . '","token":null}';

        if (!\Joomla\Filesystem\File::write($mailjetData, $JSONString)) {
            Factory::getApplication()->enqueueMessage(
                \Joomla\CMS\Language\Text::_('Unable to write data file for Mailjet\'s settings.'),
                \Joomla\CMS\Application\CMSApplication::MSG_WARNING
            );
        }
    }
}

