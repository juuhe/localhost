<?php

class Http
{
	/**
     * Contains the target URL
     *
     * @var string
     */
	public $target;
	/**
     * Contains the target host
     *
     * @var string
     */
	public $host;
	/**
     * Contains the target port
     *
     * @var integer
     */
	public $port;
	/**
     * Contains the target path
     *
     * @var string
     */
	public $path;
	/**
     * Contains the target schema
     *
     * @var string
     */
	public $schema;
	/**
     * Contains the http method (GET or POST)
     *
     * @var string
     */
	public $method;
	/**
     * Contains the parameters for request
     *
     * @var array
     */
	public $params;
	/**
     * Contains the cookies for request
     *
     * @var array
     */
	public $cookies;
	/**
     * Contains the cookies retrieved from response
     *
     * @var array
     */
	public $_cookies;
	/**
     * Number of seconds to timeout
     *
     * @var integer
     */
	public $timeout;
	/**
     * Whether to use cURL or not
     *
     * @var boolean
     */
	public $useCurl;
	/**
     * Contains the referrer URL
     *
     * @var string
     */
	public $referrer;
	/**
     * Contains the User agent string
     *
     * @var string
     */
	public $userAgent;
	/**
     * Contains the cookie path (to be used with cURL)
     *
     * @var string
     */
	public $cookiePath;
	/**
     * Whether to use cookie at all
     *
     * @var boolean
     */
	public $useCookie;
	/**
     * Whether to store cookie for subsequent requests
     *
     * @var boolean
     */
	public $saveCookie;
	/**
     * Contains the Username (for authentication)
     *
     * @var string
     */
	public $username;
	/**
     * Contains the Password (for authentication)
     *
     * @var string
     */
	public $password;
	/**
     * Contains the fetched web source
     *
     * @var string
     */
	public $result;
	/**
     * Contains the last headers 
     *
     * @var string
     */
	public $headers;
	/**
     * Contains the last call's http status code
     *
     * @var string
     */
	public $status;
	/**
     * Whether to follow http redirect or not
     *
     * @var boolean
     */
	public $redirect;
	/**
     * The maximum number of redirect to follow
     *
     * @var integer
     */
	public $maxRedirect;
	/**
     * The current number of redirects
     *
     * @var integer
     */
	public $curRedirect;
	/**
     * Contains any error occurred
     *
     * @var string
     */
	public $error;
	/**
     * Store the next token
     *
     * @var string
     */
	public $nextToken;
	/**
     * Whether to keep debug messages
     *
     * @var boolean
     */
	public $debug;
	/**
     * Stores the debug messages
     *
     * @var array
     * @todo will keep debug messages
     */
	public $debugMsg;

	public function Http()
	{
		$this->clear();
	}

	public function initialize($config = array())
	{
		$this->clear();

		foreach ($config as $key => $val) {
			if (isset($this->$key)) {
				$method = 'set' . ucfirst(str_replace('_', '', $key));

				if (method_exists($this, $method)) {
					$this->$method($val);
				}
				else {
					$this->$key = $val;
				}
			}
		}
	}

	public function clear()
	{
		$this->host = '';
		$this->port = 0;
		$this->path = '';
		$this->target = '';
		$this->method = 'GET';
		$this->schema = 'http';
		$this->params = array();
		$this->headers = array();
		$this->cookies = array();
		$this->_cookies = array();
		$this->debug = false;
		$this->error = '';
		$this->status = 0;
		$this->timeout = '25';
		$this->useCurl = true;
		$this->referrer = '';
		$this->username = '';
		$this->password = '';
		$this->redirect = true;
		$this->nextToken = '';
		$this->useCookie = true;
		$this->saveCookie = true;
		$this->maxRedirect = 3;
		$this->cookiePath = 'cookie.txt';
		$this->userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.9';
	}

	public function setTarget($url)
	{
		if ($url) {
			$this->target = $url;
		}
	}

	public function setMethod($method)
	{
		if (($method == 'GET') || ($method == 'POST')) {
			$this->method = $method;
		}
	}

	public function setReferrer($referrer)
	{
		if ($referrer) {
			$this->referrer = $referrer;
		}
	}

	public function setUseragent($agent)
	{
		if ($agent) {
			$this->userAgent = $agent;
		}
	}

	public function setTimeout($seconds)
	{
		if (0 < $seconds) {
			$this->timeout = $seconds;
		}
	}

	public function setCookiepath($path)
	{
		if ($path) {
			$this->cookiePath = $path;
		}
	}

	public function setParams($dataArray)
	{
		if (is_array($dataArray)) {
			$this->params = array_merge($this->params, $dataArray);
		}
	}

	public function setAuth($username, $password)
	{
		if (!empty($username) && !empty($password)) {
			$this->username = $username;
			$this->password = $password;
		}
	}

	public function setMaxredirect($value)
	{
		if (!empty($value)) {
			$this->maxRedirect = $value;
		}
	}

	public function addParam($name, $value)
	{
		if (!empty($name) && !empty($value)) {
			$this->params[$name] = $value;
		}
	}

	public function addCookie($name, $value)
	{
		if (!empty($name) && !empty($value)) {
			$this->cookies[$name] = $value;
		}
	}

	public function useCurl($value = true)
	{
		if (is_bool($value)) {
			$this->useCurl = $value;
		}
	}

	public function useCookie($value = true)
	{
		if (is_bool($value)) {
			$this->useCookie = $value;
		}
	}

	public function saveCookie($value = true)
	{
		if (is_bool($value)) {
			$this->saveCookie = $value;
		}
	}

	public function followRedirects($value = true)
	{
		if (is_bool($value)) {
			$this->redirect = $value;
		}
	}

	public function getResult()
	{
		return $this->result;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getError()
	{
		return $this->error;
	}

	public function execute($target = '', $referrer = '', $method = '', $data = array())
	{
		$this->target = $target ? $target : $this->target;
		$this->method = $method ? $method : $this->method;
		$this->referrer = $referrer ? $referrer : $this->referrer;
		if (is_array($data) && (0 < count($data))) {
			$this->params = array_merge($this->params, $data);
		}

		if (is_array($this->params) && (0 < count($this->params))) {
			$tempString = array();

			foreach ($this->params as $key => $value) {
				if (0 < strlen(trim($value))) {
					$tempString[] = $key . '=' . urlencode($value);
				}
			}

			$queryString = join('&', $tempString);
		}

		$this->useCurl = $this->useCurl && in_array('curl', get_loaded_extensions());

		if ($this->method == 'GET') {
			if (isset($queryString)) {
				$this->target = $this->target . '?' . $queryString;
			}
		}

		$urlParsed = parse_url($this->target);

		if ($urlParsed['scheme'] == 'https') {
			$this->host = 'ssl://' . $urlParsed['host'];
			$this->port = $this->port != 0 ? $this->port : 443;
		}
		else {
			$this->host = $urlParsed['host'];
			$this->port = $this->port != 0 ? $this->port : 80;
		}

		$this->path = (isset($urlParsed['path']) ? $urlParsed['path'] : '/') . (isset($urlParsed['query']) ? '?' . $urlParsed['query'] : '');
		$this->schema = $urlParsed['scheme'];
		$this->_passCookies();
		if (is_array($this->cookies) && (0 < count($this->cookies))) {
			$tempString = array();

			foreach ($this->cookies as $key => $value) {
				if (0 < strlen(trim($value))) {
					$tempString[] = $key . '=' . urlencode($value);
				}
			}

			$cookieString = join('&', $tempString);
		}

		if ($this->useCurl) {
			$ch = curl_init();

			if ($this->method == 'GET') {
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				curl_setopt($ch, CURLOPT_POST, false);
			}
			else {
				if (isset($queryString)) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
				}

				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPGET, false);
			}

			if ($this->username && $this->password) {
				curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
			}

			if ($this->useCookie && isset($cookieString)) {
				curl_setopt($ch, CURLOPT_COOKIE, $cookieString);
			}

			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_NOBODY, false);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt($ch, CURLOPT_URL, $this->target);
			curl_setopt($ch, CURLOPT_REFERER, $this->referrer);
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->redirect);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $this->maxRedirect);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = curl_exec($ch);
			$contentArray = explode("\r\n\r\n", $content);
			$status = curl_getinfo($ch);
			$this->result = $contentArray[count($contentArray) - 1];
			$this->_parseHeaders($contentArray[count($contentArray) - 2]);
			$this->_setError(curl_error($ch));
			curl_close($ch);
		}
		else {
			$filePointer = fsockopen($this->host, $this->port, $errorNumber, $errorString, $this->timeout);

			if (!$filePointer) {
				$this->_setError('Failed opening http socket connection: ' . $errorString . ' (' . $errorNumber . ')');
				return false;
			}

			$requestHeader = $this->method . ' ' . $this->path . "  HTTP/1.1\r\n";
			$requestHeader .= 'Host: ' . $urlParsed['host'] . "\r\n";
			$requestHeader .= 'User-Agent: ' . $this->userAgent . "\r\n";
			$requestHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
			if ($this->useCookie && ($cookieString != '')) {
				$requestHeader .= 'Cookie: ' . $cookieString . "\r\n";
			}

			if ($this->method == 'POST') {
				$requestHeader .= 'Content-Length: ' . strlen($queryString) . "\r\n";
			}

			if ($this->referrer != '') {
				$requestHeader .= 'Referer: ' . $this->referrer . "\r\n";
			}

			if ($this->username && $this->password) {
				$requestHeader .= 'Authorization: Basic ' . base64_encode($this->username . ':' . $this->password) . "\r\n";
			}

			$requestHeader .= "Connection: close\r\n\r\n";

			if ($this->method == 'POST') {
				$requestHeader .= $queryString;
			}

			fwrite($filePointer, $requestHeader);
			$responseHeader = '';
			$responseContent = '';

			do {
				$responseHeader .= fread($filePointer, 1);
			} while (!preg_match('/\\r\\n\\r\\n$/', $responseHeader));

			$this->_parseHeaders($responseHeader);
			if (($this->status == '302') && ($this->redirect == true)) {
				if ($this->curRedirect < $this->maxRedirect) {
					$newUrlParsed = parse_url($this->headers['location']);

					if ($newUrlParsed['host']) {
						$newTarget = $this->headers['location'];
					}
					else {
						$newTarget = $this->schema . '://' . $this->host . '/' . $this->headers['location'];
					}

					$this->port = 0;
					$this->status = 0;
					$this->params = array();
					$this->method = 'GET';
					$this->referrer = $this->target;
					$this->curRedirect++;
					$this->result = $this->execute($newTarget);
				}
				else {
					$this->_setError('Too many redirects.');
					return false;
				}
			}
			else {
				if ($this->headers['transfer-encoding'] != 'chunked') {
					while (!feof($filePointer)) {
						$responseContent .= fgets($filePointer, 128);
					}
				}
				else {
					while ($chunkLength = hexdec(fgets($filePointer))) {
						$responseContentChunk = '';
						$readLength = 0;

						while ($readLength < $chunkLength) {
							$responseContentChunk .= fread($filePointer, $chunkLength - $readLength);
							$readLength = strlen($responseContentChunk);
						}

						$responseContent .= $responseContentChunk;
						fgets($filePointer);
					}
				}

				$this->result = chop($responseContent);
			}
		}

		return $this->result;
	}

	public function _parseHeaders($responseHeader)
	{
		$headers = explode("\r\n", $responseHeader);
		$this->_clearHeaders();

		if ($this->status == 0) {
			if (!preg_match('/HTTP\\/(\\d\\.\\d)\\s*(\\d+)\\s*(.*)/', $headers[0], $matches)) {
				$this->_setError('Unexpected HTTP response status');
				return false;
			}

			$this->status = $matches[2];
			array_shift($headers);
		}

		foreach ($headers as $header) {
			$headerName = strtolower($this->_tokenize($header, ':'));
			$headerValue = trim(chop($this->_tokenize("\r\n")));

			if (isset($this->headers[$headerName])) {
				if (gettype($this->headers[$headerName]) == 'string') {
					$this->headers[$headerName] = array($this->headers[$headerName]);
				}

				$this->headers[$headerName][] = $headerValue;
			}
			else {
				$this->headers[$headerName] = $headerValue;
			}
		}

		if ($this->saveCookie && isset($this->headers['set-cookie'])) {
			$this->_parseCookie();
		}
	}

	public function _clearHeaders()
	{
		$this->headers = array();
	}

	public function _parseCookie()
	{
		if (gettype($this->headers['set-cookie']) == 'array') {
			$cookieHeaders = $this->headers['set-cookie'];
		}
		else {
			$cookieHeaders = array($this->headers['set-cookie']);
		}

		for ($cookie = 0; $cookie < count($cookieHeaders); $cookie++) {
			$cookieName = trim($this->_tokenize($cookieHeaders[$cookie], '='));
			$cookieValue = $this->_tokenize(';');
			$urlParsed = parse_url($this->target);
			$domain = $urlParsed['host'];
			$secure = '0';
			$path = '/';
			$expires = '';

			while (($name = trim(urldecode($this->_tokenize('=')))) != '') {
				$value = urldecode($this->_tokenize(';'));

				switch ($name) {
				case 'path':
					$path = $value;
					break;

				case 'domain':
					$domain = $value;
					break;

				case 'secure':
					$secure = ($value != '' ? '1' : '0');
					break;
				}
			}

			$this->_setCookie($cookieName, $cookieValue, $expires, $path, $domain, $secure);
		}
	}

	public function _setCookie($name, $value, $expires = '', $path = '/', $domain = '', $secure = 0)
	{
		if (strlen($name) == 0) {
			return $this->_setError('No valid cookie name was specified.');
		}

		if ((strlen($path) == 0) || strcmp($path[0], '/')) {
			return $this->_setError($path . ' is not a valid path for setting cookie ' . $name . '.');
		}

		if (($domain == '') || !strpos($domain, '.', $domain[0] == '.' ? 1 : 0)) {
			return $this->_setError($domain . ' is not a valid domain for setting cookie ' . $name . '.');
		}

		$domain = strtolower($domain);

		if (!strcmp($domain[0], '.')) {
			$domain = substr($domain, 1);
		}

		$name = $this->_encodeCookie($name, true);
		$value = $this->_encodeCookie($value, false);
		$secure = intval($secure);
		$this->_cookies[] = array('name' => $name, 'value' => $value, 'domain' => $domain, 'path' => $path, 'expires' => $expires, 'secure' => $secure);
	}

	public function _encodeCookie($value, $name)
	{
		return $name ? str_replace('=', '%25', $value) : str_replace(';', '%3B', $value);
	}

	public function _passCookies()
	{
		if (is_array($this->_cookies) && (0 < count($this->_cookies))) {
			$urlParsed = parse_url($this->target);
			$tempCookies = array();

			foreach ($this->_cookies as $cookie) {
				if ($this->_domainMatch($urlParsed['host'], $cookie['domain']) && (0 === strpos($urlParsed['path'], $cookie['path'])) && (empty($cookie['secure']) || ($urlParsed['protocol'] == 'https'))) {
					$tempCookies[$cookie['name']][strlen($cookie['path'])] = $cookie['value'];
				}
			}

			foreach ($tempCookies as $name => $values) {
				krsort($values);

				foreach ($values as $value) {
					$this->addCookie($name, $value);
				}
			}
		}
	}

	public function _domainMatch($requestHost, $cookieDomain)
	{
		if ('.' != $cookieDomain[0]) {
			return $requestHost == $cookieDomain;
		}
		else if (substr_count($cookieDomain, '.') < 2) {
			return false;
		}
		else {
			return substr('.' . $requestHost, 0 - strlen($cookieDomain)) == $cookieDomain;
		}
	}

	public function _tokenize($string, $separator = '')
	{
		if (!strcmp($separator, '')) {
			$separator = $string;
			$string = $this->nextToken;
		}

		for ($character = 0; $character < strlen($separator); $character++) {
			if (gettype($position = strpos($string, $separator[$character])) == 'integer') {
				$found = (isset($found) ? min($found, $position) : $position);
			}
		}

		if (isset($found)) {
			$this->nextToken = substr($string, $found + 1);
			return substr($string, 0, $found);
		}
		else {
			$this->nextToken = '';
			return $string;
		}
	}

	public function _setError($error)
	{
		if ($error != '') {
			$this->error = $error;
			return $error;
		}
	}
}


?>
