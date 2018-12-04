<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once realpath(__DIR__.'/lib/Auth.php');
require_once realpath(__DIR__.'/lib/vendor/autoload.php');


$auth = new Auth();

$log = realpath(__DIR__.'/tmp/log');

touch($log);

$delimiter = str_repeat('=', 100);

$content = file_get_contents($log);

$prepend = '';

$prepend .= $delimiter;
$prepend .= PHP_EOL;
$prepend .= date('Y-m-d H:i:s');
$prepend .= PHP_EOL;
$prepend .= 'POST';
$prepend .= PHP_EOL;
$prepend .= print_r($_POST, true);
$prepend .= PHP_EOL;
$prepend .= 'GET';
$prepend .= PHP_EOL;
$prepend .= print_r($_GET, true);

if (isset($_POST['data'])) {
	$data = (object) $_POST['data'];
} else if (isset($_POST['mailjet'])) {
	$mailjet = json_decode($_POST['mailjet']);
	$data = $mailjet->data;
}

if (isset($data->next_step_url) && $data->next_step_url) {
//
//	//we have api key and secret but no token so generate it
//	if (
//		isset($data->apikey)
//		&& $data->apikey
//		&& isset($data->secretkey)
//		&& $data->secretkey
//		&& !$auth->haveToken()
//	) {
//		$auth->setApiKey($data->apikey);
//		$auth->setApiSecret($data->secretkey);
//		$auth->generateToken();
//		$auth->saveData();
//	}

	$response = array(
		"code"				=> 1,
		"continue"			=> true,
		"continue_address"	=> $data->next_step_url,
	);
	
} else {

	$response = array(		
		"code"		=> 0,		
		"continue"	=> false,		
		"exit_url"	=> 'http://prestashop.com/exit.php',
	);
	
}

$json = json_encode($response);

$prepend .= PHP_EOL;
$prepend .= 'RESPONSE';
$prepend .= PHP_EOL;
$prepend .= $json;
$prepend .= PHP_EOL;
$prepend .= $delimiter;
$prepend .= PHP_EOL;

$content = $prepend.$content;
file_put_contents($log, $content);

echo $json;