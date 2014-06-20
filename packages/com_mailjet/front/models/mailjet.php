<?php
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

//require the mailjet API library

require_once (JPATH_COMPONENT . DS . 'mailjet-api.php');

class MailjetModelMailjet extends JModelLegacy {

    protected $api;
    protected $params;

    function __construct()
    {
        parent::__construct();
        $this->params = $this->getAsRecord();
        $this->api = new Mailjet($this->params['username'], $this->params['password']);
//        exit();
    }
    function store(){
        $email = filter_var($_POST['mailjet-email'], FILTER_SANITIZE_EMAIL);
        $list_id = filter_var($_POST['mailjet-list_id'], FILTER_SANITIZE_NUMBER_INT);
        if (empty($email)) $email = filter_var($_GET['mailjet-email'], FILTER_SANITIZE_EMAIL);
        if (empty($list_id)) $list_id = filter_var($_GET['mailjet-list_id'], FILTER_SANITIZE_NUMBER_INT);
        if ((empty($email)) || (empty($list_id))) return false;

        $params = array(
            'method' => 'POST',
	        'Action' => 'Add',
            'Addresses' => array($email),
            'ListID' => $list_id
        );
        $response = $this->api->manycontacts($params);
        if(isset($response->Data['0']->Errors->Items))
            return false;
        return true;
    }
//

    public function getAsRecord ()
    {
        //Below are some comments containing error messages to improve usability
        $credentials = sPrintF ('%s/components/%s/lib/db/data', JPATH_ADMINISTRATOR, 'com_mailjet');

        if(file_exists($credentials)){
            
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
