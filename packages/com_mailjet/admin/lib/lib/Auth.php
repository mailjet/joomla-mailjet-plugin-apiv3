<?php

defined('_JEXEC') or die('Restricted access');

/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
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
	 * @var String
	 */
	private $_apiUrl;

	/**
	 *
	 * @var String
	 */
	private $_apiVersion;

    /**
     *
     * @var Boolean
     */
    private $_enable = false;

    /**
     *
     * @var String
     */
    private $_testAddress = 'test@emailaddress';

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

		if (isset($data->apiUrl) && $data->apiUrl) {
			$this->setApiUrl($data->apiUrl);
		}
		
		if (isset($data->apiVersion) && $data->apiVersion) {
			$this->setApiVersion($data->apiVersion);
		}

        if(!empty($data->enable)) {
            $this->setEnable($data->enable);
        }

        if(!empty($data->test_address)) {
            $this->setTestAddress($data->test_address);
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
			'apiKey'		=> $this->getApiKey(),
			'apiSecret'		=> $this->getApiSecret(),
			'apiUrl'		=> $this->getApiUrl(),
			'apiVersion'	=> $this->getApiVersion(),
            'enable'        => $this->getEnable(),
            'test_address'  => $this->getTestAddress()
		);

		file_put_contents($this->_dbData, json_encode($data));

		$mailjetConfig = realpath(__DIR__.'/../../config.php');
        $dataConf = '<?php
class JMailjetConfig {
	public $bak_mailer = "smtp";
	public $bak_smtpauth = "1";
	public $bak_smtpuser = "your API key";
	public $bak_smtppass = "your API secret";
	public $bak_smtphost = "'.(($this->getApiUrl())?$this->getApiUrl():"in-v3.mailjet.com").'";
	public $bak_smtpsecure = "tls";
	public $bak_smtpport = "587";
	public $test = "";
	public $test_address = "'.$this->getTestAddress().'";
	public $enable = "'.$this->getEnable().'";
	public $username = "'.(($this->getApiKey())?$this->getApiKey():"your API key").'";
	public $password = "'.(($this->getApiSecret())?$this->getApiSecret():"your API secret").'";
	public $host = "'.(($this->getApiUrl())?$this->getApiUrl():"in-v3.mailjet.com").'";
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
			'apiKey'		=> null,
			'apiSecret'		=> null,
			'token'			=> null,
			'apiUrl'		=> null,
			'apiVersion'	=> null,
            'enable'        => null,
            'test_address'  => null,
		);

		file_put_contents($this->_dbData, json_encode($data));

		$this->_apiKey = null;
		$this->_apiSecret = null;
		$this->_apiUrl = null;
		$this->_apiVersion = null;
        $this->_enable = null;
        $this->_testAddress = null;

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
     * @return Boolean
     */
    public function getEnable()
    {
        return $this->_enable;
    }

    /**
     *
     * @param Boolean
     * @return Boolean
     */
    public function setEnable($val)
    {
        return $this->_enable = $val;
    }

    /**
     *
     * @return String
     */
    public function getTestAddress()
    {
        return $this->_testAddress;
    }

    /**
     *
     * @param String
     * @return String
     */
    public function setTestAddress($val)
    {
        return $this->_testAddress = $val;
    }

	/**
	 *
	 * @param String $apiUrl
	 * @return Auth
	 */
	public function setApiUrl($apiUrl)
	{
		$this->_apiUrl = $apiUrl;
		return $this;
	}

	/**
	 *
	 * @return String
	 */
	public function getApiUrl()
	{
		return $this->_apiUrl;
	}

	/**
	 *
	 * @param String $apiVersion
	 * @return Auth
	 */
	public function setApiVersion($apiVersion)
	{
		$this->_apiVersion = $apiVersion;
		return $this;
	}

	/**
	 *
	 * @return String
	 */
	public function getApiVersion()
	{
		return $this->_apiVersion;
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