<?php

class POP3
{
	/**
   * Default POP3 port
   * @var int
   */
	public $POP3_PORT = 110;
	/**
   * Default Timeout
   * @var int
   */
	public $POP3_TIMEOUT = 30;
	/**
   * POP3 Carriage Return + Line Feed
   * @var string
   */
	public $CRLF = "\r\n";
	/**
   * Displaying Debug warnings? (0 = now, 1+ = yes)
   * @var int
   */
	public $do_debug = 2;
	/**
   * POP3 Mail Server
   * @var string
   */
	public $host;
	/**
   * POP3 Port
   * @var int
   */
	public $port;
	/**
   * POP3 Timeout Value
   * @var int
   */
	public $tval;
	/**
   * POP3 Username
   * @var string
   */
	public $username;
	/**
   * POP3 Password
   * @var string
   */
	public $password;
	/**
   * Sets the POP3 PHPMailer Version number
   * @var string
   */
	public $Version = '5.2.4';
	/**
   * @var resource Resource handle for the POP connection socket
   */
	private $pop_conn;
	/**
   * @var boolean Are we connected?
   */
	private $connected;
	/**
   * @var array Error container
   */
	private $error;

	public function __construct()
	{
		$this->pop_conn = 0;
		$this->connected = false;
		$this->error = NULL;
	}

	public function Authorise($host, $port = false, $tval = false, $username, $password, $debug_level = 0)
	{
		$this->host = $host;

		if ($port == false) {
			$this->port = $this->POP3_PORT;
		}
		else {
			$this->port = $port;
		}

		if ($tval == false) {
			$this->tval = $this->POP3_TIMEOUT;
		}
		else {
			$this->tval = $tval;
		}

		$this->do_debug = $debug_level;
		$this->username = $username;
		$this->password = $password;
		$this->error = NULL;
		$result = $this->Connect($this->host, $this->port, $this->tval);

		if ($result) {
			$login_result = $this->Login($this->username, $this->password);

			if ($login_result) {
				$this->Disconnect();
				return true;
			}
		}

		$this->Disconnect();
		return false;
	}

	public function Connect($host, $port = false, $tval = 30)
	{
		if ($this->connected) {
			return true;
		}

		set_error_handler(array(&$this, 'catchWarning'));
		$this->pop_conn = fsockopen($host, $port, $errno, $errstr, $tval);
		restore_error_handler();
		if ($this->error && (1 <= $this->do_debug)) {
			$this->displayErrors();
		}

		if ($this->pop_conn == false) {
			$this->error = array('error' => 'Failed to connect to server ' . $host . ' on port ' . $port, 'errno' => $errno, 'errstr' => $errstr);

			if (1 <= $this->do_debug) {
				$this->displayErrors();
			}

			return false;
		}

		if (version_compare(phpversion(), '5.0.0', 'ge')) {
			stream_set_timeout($this->pop_conn, $tval, 0);
		}
		else if (substr(PHP_OS, 0, 3) !== 'WIN') {
			socket_set_timeout($this->pop_conn, $tval, 0);
		}

		$pop3_response = $this->getResponse();

		if ($this->checkResponse($pop3_response)) {
			$this->connected = true;
			return true;
		}

		return false;
	}

	public function Login($username = '', $password = '')
	{
		if ($this->connected == false) {
			$this->error = 'Not connected to POP3 server';

			if (1 <= $this->do_debug) {
				$this->displayErrors();
			}
		}

		if (empty($username)) {
			$username = $this->username;
		}

		if (empty($password)) {
			$password = $this->password;
		}

		$pop_username = 'USER ' . $username . $this->CRLF;
		$pop_password = 'PASS ' . $password . $this->CRLF;
		$this->sendString($pop_username);
		$pop3_response = $this->getResponse();

		if ($this->checkResponse($pop3_response)) {
			$this->sendString($pop_password);
			$pop3_response = $this->getResponse();

			if ($this->checkResponse($pop3_response)) {
				return true;
			}
		}

		return false;
	}

	public function Disconnect()
	{
		$this->sendString('QUIT');
		fclose($this->pop_conn);
	}

	private function getResponse($size = 128)
	{
		$pop3_response = fgets($this->pop_conn, $size);
		return $pop3_response;
	}

	private function sendString($string)
	{
		$bytes_sent = fwrite($this->pop_conn, $string, strlen($string));
		return $bytes_sent;
	}

	private function checkResponse($string)
	{
		if (substr($string, 0, 3) !== '+OK') {
			$this->error = array('error' => 'Server reported an error: ' . $string, 'errno' => 0, 'errstr' => '');

			if (1 <= $this->do_debug) {
				$this->displayErrors();
			}

			return false;
		}
		else {
			return true;
		}
	}

	private function displayErrors()
	{
		echo '<pre>';

		foreach ($this->error as $single_error) {
			print_r($single_error);
		}

		echo '</pre>';
	}

	private function catchWarning($errno, $errstr, $errfile, $errline)
	{
		$this->error[] = array('error' => 'Connecting to the POP3 server raised a PHP warning: ', 'errno' => $errno, 'errstr' => $errstr);
	}
}


?>
