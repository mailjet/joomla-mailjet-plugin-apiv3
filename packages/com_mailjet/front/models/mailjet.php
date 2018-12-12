<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();

if (!defined('DS')) {
  define('DS',DIRECTORY_SEPARATOR);
}

jimport('joomla.application.component.model');

if (!function_exists('class_alias')) { // For php older then 5.3
  function class_alias($orig, $alias) {
    eval('abstract class ' . $alias . ' extends ' . $orig . ' {}');
  }
}

if (!class_exists('JModelLegacy')) {
  class_alias('JModel','JModelLegacy');
}

/* Require the Mailjet API library */
require_once (sPrintF ('%s/components/com_mailjet/lib/lib/mailjet-api-helper.php', JPATH_ADMINISTRATOR));
require_once(sPrintF('%s/components/com_mailjet/lib/lib/vendor/autoload.php', JPATH_ADMINISTRATOR));

class MailjetModelMailjet extends JModelLegacy {

    protected $mjClient;
    protected $params;

    function __construct()
    {
        parent::__construct();
        $this->params = $this->getAsRecord();
        $this->mjClient = Mailjet_Api_Helper::getMailjetClient($this->params['username'], $this->params['password']);
    }
	
    function store()
    {
    	// Get the data which we'll save
        $email = filter_var($_POST['mailjet-email'], FILTER_SANITIZE_EMAIL);
        $list_id = filter_var($_POST['mailjet-list_id'], FILTER_SANITIZE_NUMBER_INT);
        if (empty($email)) $email = filter_var($_GET['mailjet-email'], FILTER_SANITIZE_EMAIL);
        if (empty($list_id)) $list_id = filter_var($_GET['mailjet-list_id'], FILTER_SANITIZE_NUMBER_INT);
        if ((empty($email)) || (empty($list_id))) return false;
	
        $body = [
            'Action' => 'addforce',
            'Email' =>  $email
        ];

        $response = $this->mjClient->post(\Mailjet\Resources::$ContactslistManagecontact, ['id' => $list_id, 'body' => $body]);
        if ($response->success()) {
            return true;
        }
        return false;
    }

    public function getAsRecord()
    {
        //Below are some comments containing error messages to improve usability
        $credentials = sPrintF ('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');

        if(file_exists($credentials)) {
            $pre_data = null;
            $content = trim(file_get_contents($credentials));
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
