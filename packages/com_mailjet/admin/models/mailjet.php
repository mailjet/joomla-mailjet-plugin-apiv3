<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

if (!function_exists('class_alias')) { // For php older then 5.3
  function class_alias($orig, $alias) {
    eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
  }
}

if (!class_exists('JModelLegacy')) {
  class_alias('JModel','JModelLegacy');
}

class MailjetModelMailjet extends JModelLegacy
{
    public function getAsPost ()
    {
        $post = JRequest::get ('post');

        $data ['enable'] = isSet ($post ['enable']);
        $data ['test'] = isSet ($post ['test']);
        $data ['test_address'] = $post ['test_address'];
        $data ['username'] = $post ['username'];
        $data ['password'] = $post ['password'];
        $data['api_token'] = serialize(false);

        return $data;
    }

    public function getAsRecord ()
    {
        $mailjetConfig = sPrintF ('%s/config.php', JPATH_COMPONENT);

        require_once ($mailjetConfig);

        $conf = new JMailjetConfig ();

        $host = $conf->host;

        $prev = new JConfig();
        $prev = JArrayHelper::fromObject($prev);

        $fields ['bak_mailer'] = $prev ['mailer'];
        $fields ['bak_smtpauth'] = $prev ['smtpauth'];
        $fields ['bak_smtpuser'] = $prev ['smtpuser'];
        $fields ['bak_smtppass'] = $prev ['smtppass'];
        $fields ['bak_smtphost'] = $prev ['smtphost'];
        $fields ['bak_smtpsecure'] = $prev ['smtpsecure'];
        $fields ['bak_smtpport'] = $prev ['smtpport'];

        $data ['enable'] = $conf->enable && 'smtp' == $prev ['mailer'] && $conf->host == $prev ['smtphost'];
        $data ['test'] = $conf->test;
        $data ['test_address'] = $conf->test_address;
        $data ['username'] = $conf->username;
        $data ['password'] = $conf->password;
        $data ['host'] = $host;
        if(isset($data['api_token']) && $data['api_token']) {
            $data['api_token'] = unserialize($conf->api_token);
        }

        return $data;
    }

    public function saveRecord($key, $value)
    {
        jimport ('joomla.filesystem.path');
        jimport ('joomla.filesystem.file');
        $mailjetConfig = sPrintF ('%s/components/%s/config.php', JPATH_ADMINISTRATOR, 'com_mailjet');
        require_once ($mailjetConfig);
        $config = new JRegistry ('config');
        $config->loadArray ($this->getAsRecord());
        $jversion = new JVersion();
        if (version_compare($jversion->getShortVersion(), '2.5.6', 'lt')) {
            $config->setValue($key, $value);
        } else {
            $config->set($key, $value);
        }
        $configString = $config->toString ('PHP', array ('class' => 'JMailjetConfig', 'closingtag' => false));

        if (! JFile::write ($mailjetConfig, $configString))
        {
            JError::raiseWarning (0, JText::_ ('Unable to write configuration file for Mailjet\'s settings.'));
        }

        $mailjetData = sPrintF ('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');
        $JSONString = '{"apiKey":"'.$post ['username'].'","apiSecret":"'.$post ['password'].'","token":null}';
        if (! JFile::write ($mailjetData, $JSONString))
        {
            JError::raiseWarning (0, JText::_ ('Unable to write data file for Mailjet\'s settings.'));
        }
    }
}
