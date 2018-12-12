<?phprequire_once(sPrintF('%s/components/com_mailjet/lib/lib/mailjet-api-helper.php', JPATH_ADMINISTRATOR));require_once(sPrintF('%s/components/com_mailjet/lib/lib/vendor/autoload.php', JPATH_ADMINISTRATOR));/** * @author Mailjet SAS * * @copyright  Copyright (C) 2014 Mailjet SAS. * @license    GNU General Public License version 2 or later; see LICENSE */// No direct access to this file// Check to ensure this file is within the rest of the frameworkdefined('JPATH_BASE') or die();jimport('joomla.form.formfield');JFormHelper::loadFieldClass('list');jimport('joomla.html.html');$lang = JFactory::getLanguage();$extension = 'mod_mailjet';$base_dir = JPATH_SITE;$language_tag = $lang->getTag();$reload = true;$lang->load($extension, $base_dir, $language_tag, $reload);class JFormFieldMailjetcombo extends JFormFieldList{    protected $type = 'mailjetcombo';    protected function getOptions()    {        // Get the parameters        $params = $this->getParams();        // The new approach of making Mailjet API calls        $mjClient = Mailjet_Api_Helper::getMailjetClient($params['username'], $params['password']);        $filters = ['Limit' => '50'];        $response = $mjClient->get(\Mailjet\Resources::$Contactslist, ['filters' => $filters]);        /*          Read the response        */        if ($response->success()) {           // var_dump($response->getData());        } else {            // Set the array for the view            $ret = array(JText::_("COM_MAILJET_NO_LISTS"));            return $ret;//            var_dump($response->getStatus());        }        $ret = array();        $conactsLists = $response->getData();        foreach ($conactsLists as $list) {            if ($list['IsDeleted'] == false) {                $ret[$list['ID']] = sprintf('%s (%s subscribers)', $list['Name'], $list['SubscriberCount']);            }        }        return $ret;    }    private function getParams()    {        //Below are some comments containing error messages to improve usability        $this->_dbData = sPrintF('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');        if (file_exists($this->_dbData)) {            $pre_data = null;            $content = trim(file_get_contents($this->_dbData));            if ($content) {                $pre_data = json_decode($content);            }            /* if(!$pre_data->apiKey || !$pre_data->apiSecret) {                JError::raiseWarning(100, JText::_("COM_MAILJET_INCOMPLETE_INFO"));            } */            $data ['username'] = $pre_data->apiKey;            $data ['password'] = $pre_data->apiSecret;            return $data;        }        /* else {            JError::raiseWarning(100, JText::_("COM_MAILJET_NO_DATAFILE"));        } */    }}