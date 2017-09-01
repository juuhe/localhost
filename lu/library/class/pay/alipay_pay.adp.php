<?php

class alipay_pay
{
	public $alipay_config;
	public $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

	public function config()
	{
		$alipay_config['partner'] = $GLOBALS['C_ZYIIS']['alipay_id'];
		$alipay_config['key'] = $GLOBALS['C_ZYIIS']['alipay_key'];
		$alipay_config['seller_email'] = $GLOBALS['C_ZYIIS']['alipay_email'];
		$alipay_config['sign_type'] = strtoupper('MD5');
		$alipay_config['input_charset'] = strtolower('utf-8');
		$alipay_config['cacert'] = getcwd() . '\\cacert.pem';
		$alipay_config['transport'] = 'http';
		return $alipay_config;
	}

	public function start($data)
	{
		$this->alipay_config = $this->config();
		$union_url = 'http://' . $GLOBALS['C_ZYIIS']['authorized_url'] . WEB_URL;
		$payment_type = '1';
		$notify_url = $union_url . 'pay/notify/alipay.php';
		$return_url = $union_url . 'pay/receive/alipay.php';
		$out_trade_no = $data['order'];
		$subject = '联盟在线充值';
		$total_fee = $data['money'];
		$body = '';
		$show_url = '';
		$anti_phishing_key = '';
		$exter_invoke_ip = '';
		$parameter = array('service' => 'create_direct_pay_by_user', 'partner' => trim($this->alipay_config['partner']), 'payment_type' => $payment_type, 'notify_url' => $notify_url, 'return_url' => $return_url, 'seller_email' => $this->alipay_config['seller_email'], 'out_trade_no' => $out_trade_no, 'subject' => $subject, 'total_fee' => $total_fee, 'body' => $body, 'show_url' => $show_url, 'anti_phishing_key' => $anti_phishing_key, 'exter_invoke_ip' => $exter_invoke_ip, '_input_charset' => trim(strtolower($this->alipay_config['input_charset'])));
		return $this->buildRequestForm($parameter, 'get', '确认');
	}

	public function receive()
	{
		$alipay_config = alipay_pay::config();
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();

		if ($verify_result) {
			if (($_REQUEST['trade_status'] == 'TRADE_FINISHED') || ($_REQUEST['trade_status'] == 'TRADE_SUCCESS')) {
				return 'success';
			}
			else {
				return 'success';
			}
		}
		else {
			return 'fail';
		}
	}

	public function buildRequestMysign($para_sort)
	{
		$prestr = createLinkstring($para_sort);
		$mysign = '';

		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
		case 'MD5':
			$mysign = md5Sign($prestr, $this->alipay_config['key']);
			break;

		default:
			$mysign = '';
		}

		return $mysign;
	}

	public function buildRequestPara($para_temp)
	{
		$para_filter = paraFilter($para_temp);
		$para_sort = argSort($para_filter);
		$mysign = $this->buildRequestMysign($para_sort);
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));
		return $para_sort;
	}

	public function buildRequestParaToString($para_temp)
	{
		$para = $this->buildRequestPara($para_temp);
		$request_data = createLinkstringUrlencode($para);
		return $request_data;
	}

	public function buildRequestForm($para_temp, $method, $button_name)
	{
		$para = $this->buildRequestPara($para_temp);
		$sHtml = '<form id=\'sendpay\' name=\'sendpay\' action=\'' . $this->alipay_gateway_new . '_input_charset=' . trim(strtolower($this->alipay_config['input_charset'])) . '\' method=\'' . $method . '\'>';

		while (list($key, $val) = each($para)) {
			$sHtml .= '<input type=\'hidden\' name=\'' . $key . '\' value=\'' . $val . '\'/>';
		}

		return $sHtml;
	}

	public function buildRequestHttp($para_temp)
	{
		$sResult = '';
		$request_data = $this->buildRequestPara($para_temp);
		$sResult = getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'], $request_data, trim(strtolower($this->alipay_config['input_charset'])));
		return $sResult;
	}

	public function buildRequestHttpInFile($para_temp, $file_para_name, $file_name)
	{
		$para = $this->buildRequestPara($para_temp);
		$para[$file_para_name] = '@' . $file_name;
		$sResult = getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'], $para, trim(strtolower($this->alipay_config['input_charset'])));
		return $sResult;
	}

	public function query_timestamp()
	{
		$url = $this->alipay_gateway_new . 'service=query_timestamp&partner=' . trim(strtolower($this->alipay_config['partner'])) . '&_input_charset=' . trim(strtolower($this->alipay_config['input_charset']));
		$encrypt_key = '';
		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName('encrypt_key');
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
		return $encrypt_key;
	}
}

class AlipayNotify
{
	/**
	 * HTTPS形式消息验证地址
	 */
	public $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * HTTP形式消息验证地址
	 */
	public $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	public $alipay_config;

	public function __construct($alipay_config)
	{
		$this->alipay_config = $alipay_config;
	}

	public function AlipayNotify($alipay_config)
	{
		$this->__construct($alipay_config);
	}

	public function verifyNotify()
	{
		if (empty($_POST)) {
			return false;
		}
		else {
			$isSign = $this->getSignVeryfy($_POST, $_POST['sign']);
			$responseTxt = 'true';

			if (!empty($_POST['notify_id'])) {
				$responseTxt = $this->getResponse($_POST['notify_id']);
			}

			if (preg_match('/true$/i', $responseTxt) && $isSign) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	public function verifyReturn()
	{
		if (empty($_GET)) {
			return false;
		}
		else {
			$isSign = $this->getSignVeryfy($_GET, $_GET['sign']);
			return $isSign;
			$responseTxt = 'true';

			if (!empty($_GET['notify_id'])) {
				$responseTxt = $this->getResponse($_GET['notify_id']);
			}

			if (preg_match('/true$/i', $responseTxt) && $isSign) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	public function getSignVeryfy($para_temp, $sign)
	{
		$para_filter = paraFilter($para_temp);
		$para_sort = argSort($para_filter);
		$prestr = createLinkstring($para_sort);
		$isSgin = false;

		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
		case 'MD5':
			$isSgin = md5Verify($prestr, $sign, $this->alipay_config['key']);
			break;

		default:
			$isSgin = false;
		}

		return $isSgin;
	}

	public function getResponse($notify_id)
	{
		$transport = strtolower(trim($this->alipay_config['transport']));
		$partner = trim($this->alipay_config['partner']);
		$veryfy_url = '';

		if ($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		}
		else {
			$veryfy_url = $this->http_verify_url;
		}

		$veryfy_url = $veryfy_url . 'partner=' . $partner . '&notify_id=' . $notify_id;
		$responseTxt = getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);
		return $responseTxt;
	}
}

function createLinkstring($para)
{
	$arg = '';

	while (list($key, $val) = each($para)) {
		$arg .= $key . '=' . $val . '&';
	}

	$arg = substr($arg, 0, count($arg) - 2);

	if (get_magic_quotes_gpc()) {
		$arg = stripslashes($arg);
	}

	return $arg;
}

function createLinkstringUrlencode($para)
{
	$arg = '';

	while (list($key, $val) = each($para)) {
		$arg .= $key . '=' . urlencode($val) . '&';
	}

	$arg = substr($arg, 0, count($arg) - 2);

	if (get_magic_quotes_gpc()) {
		$arg = stripslashes($arg);
	}

	return $arg;
}

function paraFilter($para)
{
	$para_filter = array();

	while (list($key, $val) = each($para)) {
		if (($key == 'sign') || ($key == 'sign_type') || ($val == '')) {
			continue;
		}
		else {
			$para_filter[$key] = $para[$key];
		}
	}

	return $para_filter;
}

function argSort($para)
{
	ksort($para);
	reset($para);
	return $para;
}

function logResult($word = '')
{
	$fp = fopen('log.txt', 'a');
	flock($fp, LOCK_EX);
	fwrite($fp, '执行日期：' . strftime('%Y%m%d%H%M%S', time()) . "\n" . $word . "\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '')
{
	if (trim($input_charset) != '') {
		$url = $url . '_input_charset=' . $input_charset;
	}

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_CAINFO, $cacert_url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $para);
	$responseText = curl_exec($curl);
	curl_close($curl);
	return $responseText;
}

function getHttpResponseGET($url, $cacert_url)
{
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_CAINFO, $cacert_url);
	$responseText = curl_exec($curl);
	curl_close($curl);
	return $responseText;
}

function charsetEncode($input, $_output_charset, $_input_charset)
{
	$output = '';

	if (!isset($_output_charset)) {
		$_output_charset = $_input_charset;
	}

	if (($_input_charset == $_output_charset) || ($input == NULL)) {
		$output = $input;
	}
	else if (function_exists('mb_convert_encoding')) {
		$output = mb_convert_encoding($input, $_output_charset, $_input_charset);
	}
	else if (function_exists('iconv')) {
		$output = iconv($_input_charset, $_output_charset, $input);
	}
	else {
		exit('sorry, you have no libs support for charset change.');
	}

	return $output;
}

function charsetDecode($input, $_input_charset, $_output_charset)
{
	$output = '';

	if (!isset($_input_charset)) {
		$_input_charset = $_input_charset;
	}

	if (($_input_charset == $_output_charset) || ($input == NULL)) {
		$output = $input;
	}
	else if (function_exists('mb_convert_encoding')) {
		$output = mb_convert_encoding($input, $_output_charset, $_input_charset);
	}
	else if (function_exists('iconv')) {
		$output = iconv($_input_charset, $_output_charset, $input);
	}
	else {
		exit('sorry, you have no libs support for charset changes.');
	}

	return $output;
}

function md5Sign($prestr, $key)
{
	$prestr = $prestr . $key;
	return md5($prestr);
}

function md5Verify($prestr, $sign, $key)
{
	$prestr = $prestr . $key;
	$mysgin = md5($prestr);

	if ($mysgin == $sign) {
		return true;
	}
	else {
		return false;
	}
}


?>
