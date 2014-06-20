<?php
/**
 * @copyright    Copyright (C) 2012 Mailjet SAS. All rights reserved.
 * @license      MIT
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');
jimport('joomla.html.html');

$lang = JFactory::getLanguage();
$extension = 'mod_mailjet';
$base_dir = JPATH_SITE;
$language_tag =  $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

class JFormFieldMailjetcombo extends JFormFieldList
{

    protected $type = 'mailjetcombo';

    protected function getOptions()
    {
        $params = $this->getParams();
        jimport( 'joomla.client.http' );
        $options = new JRegistry;
        $http = JHttpFactory::getHttp($options,'curl');

        $headers = array("Authorization" => "Basic " . base64_encode($params['username'] . ":" . $params['password']));

        $res = $http->get('https://api.mailjet.com/v3/REST/contactslist?limit=0', $headers);

        $ret = array(JText::_("COM_MAILJET_NO_LISTS"));

        $result = json_decode($res->body);
        if(isset($result->Count) && $result->Count > 0) {
            $ret = array();
            foreach($result->Data as $list) {
                $ret[$list->ID] = sprintf('%s (%s subscribers)', $list->Name, $list->SubscriberCount);
            }
        }
        return $ret;
    }

    private function getParams ()
    {
        //Below are some comments containing error messages to improve usability
        $this->_dbData = sPrintF ('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');

        if(file_exists($this->_dbData)){
            
            $pre_data = null;
            $content = trim(file_get_contents($this->_dbData));
            
            if ($content) {
                $pre_data = json_decode($content);
            }

            /*if(!$pre_data->apiKey || !$pre_data->apiSecret){
                JError::raiseWarning( 100, JText::_("COM_MAILJET_INCOMPLETE_INFO") );
            }*/

            $data ['username'] = $pre_data->apiKey;
            $data ['password'] = $pre_data->apiSecret;

            return $data;
        }
        /*else{
            JError::raiseWarning( 100, JText::_("COM_MAILJET_NO_DATAFILE") );
        }*/
    }
}