<?php 

/**
 * 
 *	You can use whatever data base or store method you want.
 *	The idea of this class is public methods always to return the correct answer.
 *	Don't forget that it is possible to be registered, but the generated token to be invalid if you change the IP
 *	of your site. That is why in the DB you must save both isRegistered and token.
 */
class Auth
{

	/**
	 * 
	 * @var String
	 */
	private $_dbData;

	/**
	 * 
	 * @var String
	 */
	private $_apiKey;
	
	/**
	 * 
	 * @var String
	 */
	private $_apiSecret;
	
	/**
	 * 
	 * @var String
	 */
	private $_token;
	
	/**
	 * 
	 */
	public function __construct()
	{
		$this->_initData();
	}
	
	/**
	 * 
	 * @return Auth
	 */
	protected function _initData()
	{
		$this->_dbData = realpath(__DIR__.'/../db/data');
		touch($this->_dbData);
		
		$data = null;
		$content = trim(file_get_contents($this->_dbData));
		
		if ($content) {
			$data = json_decode($content);
		}

		if (!$data) {
			$this->saveData();
		}
		
		if (isset($data->apiKey) && $data->apiKey) {
			$this->setApiKey($data->apiKey);
		}

		if (isset($data->apiSecret) && $data->apiSecret) {
			$this->setApiSecret($data->apiSecret);
		}

		if (isset($data->token) && $data->token) {
			$this->setToken($data->token);
		}
		
		
		return $this;
	}

	/**
	 * 
	 * @return Auth
	 */
	public function saveData()
	{
		$data = array(
			'apiKey'	=> $this->getApiKey(),
			'apiSecret'	=> $this->getApiSecret(),
			'token'		=> $this->getToken(),
		);
			
		file_put_contents($this->_dbData, json_encode($data));

		$mailjetConfig = realpath(__DIR__.'/../../config.php');
        $dataConf = '<?php
class JMailjetConfig {
	public $bak_mailer = "smtp";
	public $bak_smtpauth = "1";
	public $bak_smtpuser = "your API key";
	public $bak_smtppass = "your API secret";
	public $bak_smtphost = "in-v3.mailjet.com";
	public $bak_smtpsecure = "tls";
	public $bak_smtpport = "587";
	public $enable = "";
	public $test = "";
	public $test_address = "test@emailaddress";
	public $username = "'.(($this->getApiKey())?$this->getApiKey():"your API key").'";
	public $password = "'.(($this->getApiSecret())?$this->getApiSecret():"your API secret").'";
	public $host = "in-v3.mailjet.com";
	public $secure = "tls";
	public $port = "587";
}';
        file_put_contents($mailjetConfig, $dataConf);
		
		return $this;
	}
	
	/**
	 * 
	 * @return Auth
	 */
	public function deleteData()
	{
		$content = trim(file_get_contents($this->_dbData));
		$data = array(
			'apiKey'	=> null,
			'apiSecret'	=> null,
			'token'		=> null
		);

		file_put_contents($this->_dbData, json_encode($data));

		$this->_apiKey = null;
		$this->_apiSecret = null;
		$this->_token = null;
		
		return $this;
	}
	
	/**
	 * 
	 * @param String $apiKey
	 * @return Auth
	 */
	public function setApiKey($apiKey)
	{
		$this->_apiKey = $apiKey;
		return $this;
	}
	
	/**
	 * 
	 * @return String
	 */
	public function getApiKey()
	{
		return $this->_apiKey;
	}
	
	/**
	 * 
	 * @param String $apiSecret
	 * @return Auth
	 */
	public function setApiSecret($apiSecret)
	{
		$this->_apiSecret = $apiSecret;
		return $this;
	}
	
	/**
	 * 
	 * @return String
	 */
	public function getApiSecret()
	{
		return $this->_apiSecret;
	}
	
	/**
	 * 
	 * @param String $token
	 * @return Auth
	 */
	public function setToken($token)
	{
		$this->_token = $token;
		return $this;
	}
	
	/**
	 * 
	 * @return String
	 */
	public function getToken()
	{
		if (!$this->_token && $this->getApiKey() && $this->getApiSecret()) {
			$this->generateToken();
		}
		return $this->_token;
	}

	/**
	 * 
	 * @return boolean
	 */
	public function haveToken()
	{
		return (boolean) $this->getToken();
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function canGenerateToken()
	{
		return $this->getApiKey() && $this->getApiSecret();
	}

	/**
	 * 
	 * @return boolean
	 */
	public function generateToken()
	{
		
		if (!$this->canGenerateToken()) {
			return false;
		}
		
		$mj = new MailjetApi($this->getApiKey(), $this->getApiSecret());
		
		$params = array(
			'AllowedAccess' => 'campaigns,contacts,stats,pricing,account,reports',
			'method'	 	=> 'POST',
			'APIKeyALT' 	=> $this->getApiKey(),
			'TokenType'		=> 'iframe'
		);
		

		$response = $mj->apitoken($params);
		$this->_log($mj);
		if ($mj->_response_code < 400 && $mj->_response->Count > 0) {
			$token = $mj->_response->Data[0]->Token;
			$this->setToken($token);
			$this->saveData();
		}
		
		return true;
		
	}

	public function __destruct()
	{
		$this->saveData();
	}
	
	private function _log($response)
	{
		$log = realpath(__DIR__.'/../tmp/log');
		
		touch($log);
		
		$delimiter = str_repeat('=', 100);
		
		$content = file_get_contents($log);
		
		$prepend = '';
		
		$prepend .= $delimiter;
		$prepend .= PHP_EOL;
		$prepend .= date('Y-m-d H:i:s');
		$prepend .= PHP_EOL;
		$prepend .= 'RESPONSE';
		$prepend .= PHP_EOL;
		$prepend .= print_r($response, true);
		$prepend .= PHP_EOL;

		
		$content = $prepend.$content;
		file_put_contents($log, $content);
	}
	
}

?>