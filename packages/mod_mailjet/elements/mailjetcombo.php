<?php

require_once(sPrintF('%s/components/com_mailjet/lib/lib/mailjet-api-helper.php', JPATH_ADMINISTRATOR));
require_once(sPrintF('%s/components/com_mailjet/lib/lib/vendor/autoload.php', JPATH_ADMINISTRATOR));

/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access to this file

// Check to ensure this file is within the rest of the framework

defined('JPATH_BASE') or die();

jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');
jimport('joomla.html.html');


$lang = \Joomla\CMS\Factory::getApplication()->getLanguage();
$extension = 'mod_mailjet';
$base_dir = JPATH_SITE;
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

class JFormFieldMailjetcombo extends JFormFieldList
{
    protected function getOptions()
    {
        // Get the parameters
        $params = $this->getParams();

        // The new approach of making Mailjet API calls
        $mjClient = Mailjet_Api_Helper::getMailjetClient($params['username'], $params['password']);
        $filters = ['Limit' => '50'];
        $response = $mjClient->get(\Mailjet\Resources::$Contactslist, ['filters' => $filters]);
        if (!$response->success()) {
            return array(\Joomla\CMS\Language\Text::_("COM_MAILJET_NO_LISTS"));
        }

        $ret = [];
        $conactsLists = $response->getData();
        foreach ($conactsLists as $list) {
            if ($list['IsDeleted'] == false) {
                $ret[$list['ID']] = sprintf('%s (%s subscribers)', $list['Name'], $list['SubscriberCount']);
            }
        }

        return $ret;
    }


    /**
     * @return array|void
     * @since 4.0
     */
    private function getParams()
    {
        //Below are some comments containing error messages to improve usability
        $this->_dbData = sPrintF('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');

        if (file_exists($this->_dbData)) {
            $pre_data = null;
            $content = trim(file_get_contents($this->_dbData));

            if ($content) {
                $pre_data = json_decode($content);
            }

            $data ['username'] = $pre_data->apiKey;
            $data ['password'] = $pre_data->apiSecret;

            return $data;
        }
    }
}
