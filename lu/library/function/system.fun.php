<?php

function strip_slashes_recursive($variable)
{
	$variable = str_replace('zyads', ' ', $variable);

	if (is_string($variable)) {
		return stripslashes($variable);
	}

	if (is_array($variable)) {
		foreach ($variable as $i => $value) {
			$i = strip_slashes_recursive($i);
			$variable[$i] = strip_slashes_recursive($value);
		}
	}

	return $variable;
}

function trans($str)
{
	$str = str_replace(array('&lt;', '&gt;', '&quot;', '&#39;'), array('<', '>', '"', '\''), $str);
	return $str;
}

function ck_input(&$value)
{
	if (is_array($value)) {
		foreach ($value as $key => $val) {
			ck_input($value[$key]);
		}
	}
	else if (!is_numeric($value)) {
		$value = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/', '', $value);
		$value = str_replace(array('%3C', '<'), '&lt;', $value);
		$value = str_replace(array('%3E', '>'), '&gt;', $value);
		$value = str_replace(array('"', '\'', '	'), array('&quot;', '&#39;', '    '), $value);
		$value = str_ireplace(array('char(', 'zyads', '%00', '\\0', '\\r', '\\x1a', '/*'), '', $value);
	}

	return $value;
}

function _gprscf($mod, $key = NULL, $default_val = NULL, $is_ck = true)
{
	if (!$mod) {
		return NULL;
	}

	$modes = array('C' => $_COOKIE, 'G' => $_GET, 'P' => $_POST, 'R' => $_REQUEST, 'F' => $_FILES, 'S' => $_SERVER);

	if (empty($modes['R'])) {
		$modes['R'] = array_merge($_GET, $_POST);
	}

	if (!is_null($key)) {
		$key = preg_replace('/[^a-z0-9_]+/i', '', $key);
		$v = $modes[$mod][$key];
	}
	else {
		$v = $modes[$mod];
	}

	if ($v === '') {
		$v = $default_val;
	}

	$v = strip_slashes_recursive($v);

	if ($is_ck) {
		return ck_input($v);
	}

	return $v;
}

function get($key = NULL, $default_val = NULL, $is_ck = true)
{
	return _gprscf('G', $key, $default_val, $is_ck);
}

function post($key = NULL, $default_val = NULL, $is_ck = true)
{
	return _gprscf('P', $key, $default_val, $is_ck);
}

function request($key = NULL, $default_val = NULL, $is_ck = true)
{
	return _gprscf('R', $key, $default_val, $is_ck);
}

function server($key = NULL, $default_val = NULL, $is_ck = true)
{
	return _gprscf('S', $key, $default_val, $is_ck);
}

function files($key = NULL, $default_val = NULL, $is_ck = true)
{
	return _gprscf('F', $key, $default_val, $is_ck);
}

function redirect($route, $time = 0)
{
	if (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) {
		$route = str_replace('http:', 'https:', $route);
	}

	if (!headers_sent()) {
		if (0 === $time) {
			header('Location: ' . url($route));
		}
		else {
			header('refresh:' . $time . ';url=' . url($route) . '');
		}
	}
	else {
		echo '<meta http-equiv=\'Refresh\' content=\'' . $time . ';URL=' . url($route) . '\'>';
	}

	exit();
}

function is_post()
{
	return strtolower(server('REQUEST_METHOD')) == 'post';
}

function is_get()
{
	return strtolower(server('REQUEST_METHOD')) == 'get';
}

function url($route, $vars = NULL)
{
	$url = APP::format_url($route, $vars);
	$url = str_replace(array('=advertiser/', '/advertiser/'), array('=adv/', '/adv/'), $url);
	$url = str_replace(array('=affiliate/', '/affiliate/'), array('=aff/', '/aff/'), $url);
	return $url;
}

function url_f($str)
{
	if (APP_REWRITE) {
		$str = ($str ? preg_replace('#(?:^|&)([a-z0-9_]+)=#sim', "/\x01-", $str) : '/');
	}

	return $str;
}

function is_robot()
{
	$robots = array('robot', 'spider', 'slurp');

	foreach ($robots as $robot) {
		if (strpos(server('HTTP_USER_AGENT'), $robot) !== false) {
			return true;
		}
	}

	return false;
}

function convert_ip($ip)
{
	if (!preg_match('/^\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}$/', $ip)) {
		return NULL;
	}

	$city = APP::load_class('region');
	$c = $city->query($ip);
	$city->close();
	return $c[0];
}

function Search($keyWord, $stack)
{
	foreach ((array) $stack as $key => $val) {
		if (in_array($keyWord, $val)) {
			return $key;
		}
	}

	return false;
}

function zarray_sum($array, $key)
{
	$sum = 0;

	foreach ((array) $array as $k => $v) {
		$sum += $array[$k][$key];
	}

	return $sum;
}

function zplantype_sum($array, $plantype, $key)
{
	$sum = 0;

	foreach ((array) $array as $k => $v) {
		if ($array[$k]['plantype'] == $plantype) {
			$sum += $array[$k][$key];
		}
	}

	return $sum;
}

function check_utf8($str)
{
	$len = strlen($str);

	for ($i = 0; $i < $len; $i++) {
		$c = ord($str[$i]);

		if (128 < $c) {
			if (247 < $c) {
				return false;
			}
			else if (239 < $c) {
				$bytes = 4;
			}
			else if (223 < $c) {
				$bytes = 3;
			}
			else if (191 < $c) {
				$bytes = 2;
			}
			else {
				return false;
			}

			if ($len < ($i + $bytes)) {
				return false;
			}

			while (1 < $bytes) {
				$i++;
				$b = ord($str[$i]);
				if (($b < 128) || (191 < $b)) {
					return false;
				}

				$bytes--;
			}
		}
	}

	return true;
}

function get_root_domain($url)
{
	$d2 = 'ac,ah,biz,bj,cc,com,cq,edu,fj,gd,gov,gs,gx,gz,ha,hb,he,hi,hk,hl,hn,info,io,jl,js,jx,ln,mo,mobi,net,nm,nx,org,qh,sc,sd,sh,sn,sx,tj,tm,travel,tv,tw,ws,xj,xz,yn,zj';
	$dex1 = explode('.', $url);
	$dex2 = explode(',', $d2);
	$ed = end($dex1);
	$ped = prev($dex1);

	if (in_array($ped, $dex2)) {
		if (count($dex1) == 2) {
			$aw = 'www';
		}

		$pped = prev($dex1) . $aw . '.';
	}

	return $pped . $ped . '.' . $ed;
}

function up_num($s)
{
	return array_sum(unpack('C*', $s));
}

function sort_array($array, $keyid, $order = 'asc', $type = 'number')
{
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			$order_arr[$key] = $value[$keyid];
		}

		$order = ($order == 'asc' ? SORT_ASC : SORT_DESC);
		$type = ($type == 'number' ? SORT_NUMERIC : SORT_STRING);
		array_multisort($order_arr, $order, $type, $array);
		return $array;
	}
}

function Ctr($sum, $num)
{
	if (0 < $sum) {
		$ctr = number_format(($num * 100) / $sum, 2);
	}
	else {
		$ctr = 0;
	}

	return $ctr;
}

function get_ip()
{
	return $_SERVER['REMOTE_ADDR'];
}

function zstrlen($str, $strlen = 10)
{
	$j = 0;

	for ($i = 0; $i < $strlen; $i++) {
		if (160 < ord(substr($str, $i, 1))) {
			$j++;
		}
	}

	if (($j % 2) != 0) {
		$strlen++;
	}

	$tmp_str = substr($str, 0, $strlen);

	if ($strlen < strlen($str)) {
		$tmp_str .= '...';
	}

	return $tmp_str;
}

function Sendmail($email, $subject = false, $body)
{
	set_time_limit(0);
	static $mail;

	if (!$subject) {
		preg_match_all('/(.*)\\{subject\\}(.*)\\{\\/subject\\}(.*)/i', $body, $s);
		$subject = $s[2][0];
	}

	$r = array($GLOBALS['C_ZYIIS']['sitename'], 'http://' . $GLOBALS['C_ZYIIS']['authorized_url'] . WEB_URL);
	$s = array('{sitename}', '{siteurl}');
	$body = str_replace($s, $r, $body);

	if (is_null($mail)) {
		include_once LIB_PATH . '/smtp/class.phpmailer.php';
		$mail = new PHPMailer();
	}

	if ($GLOBALS['C_ZYIIS']['mail_send'] == '1') {
		$mail->IsSendmail();
		$mail->Host = $GLOBALS['C_ZYIIS']['mail_server'];
		$mail->Port = $GLOBALS['C_ZYIIS']['mail_port'];
	}

	if ($GLOBALS['C_ZYIIS']['mail_send'] == '2') {
		$mail->IsSMTP();
		$mail->Host = $GLOBALS['C_ZYIIS']['mail_server'];
		$mail->Port = $GLOBALS['C_ZYIIS']['mail_port'];
		$mail->SMTPAuth = $GLOBALS['C_ZYIIS']['mail_auth'];
		$mail->Username = $GLOBALS['C_ZYIIS']['mail_username'];
		$mail->Password = $GLOBALS['C_ZYIIS']['mail_password'];
	}

	if ($GLOBALS['C_ZYIIS']['mail_send'] == '3') {
		$mail->IsMail();
	}

	$mail->From = $GLOBALS['C_ZYIIS']['mail_from'];
	$mail->FromName = $GLOBALS['C_ZYIIS']['sitename'];
	$mail->CharSet = 'utf-8';
	$mail->MsgHTML($body);
	$mail->Subject = $subject;

	if (!is_array($email)) {
		$mail->AddAddress($email);
	}
	else {
		foreach ($email as $e) {
			$mail->AddBcc($e['email']);
		}
	}

	if (!$mail->Send()) {
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	}

	return true;
}

function is_date($date)
{
	if (preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $date)) {
		$ex = explode('-', $date);
		$year = $ex[0];
		$month = $ex[1];
		$day = $ex[2];

		if (checkdate($month, $day, $year)) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}

function Random($length)
{
	mt_srand((double) microtime() * 100000000);
	$hash = rand(10000000, 90000000);
	return $hash;
}

function rand_str($length, $numeric = 0)
{
	mt_srand((double) microtime() * 1000000);

	if ($numeric) {
		$hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
	}
	else {
		$hash = '';
		$chars = 'Aa1Bb2Cc3Dd4Ee5Ff6Gg7Hh8Ii9JjKkLlMmNnOPpQqRrSsTtUuVvWwXxYyZz';
		$max = strlen($chars) - 1;

		for ($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}

	return $hash;
}

function get_url($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function http_send($host, $head, $port = 80, $timeout = 10, $block = true)
{
	if (!$port) {
		$port = 80;
	}

	$fp = @fsockopen($host, $port, $errno, $errstr, $timeout);

	if (!$fp) {
		return '';
	}
	else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $head);
		$status = stream_get_meta_data($fp);

		if (!$status['timed_out']) {
			while (!feof($fp)) {
				if (($header = @fgets($fp)) && (($header == "\r\n") || ($header == "\n"))) {
					break;
				}
			}

			$stop = false;

			while (!$stop) {
				$data = fread($fp, ($limit == 0) || (8192 < $limit) ? 8192 : $limit);
				$return .= $data;

				if ($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}

		@fclose($fp);
		return $return;
	}
}

function zarray_diff($array_1, $array_2)
{
	foreach ($array_1 as $key => $val) {
		if ($array_2[$key] == $val) {
			unset($array_1[$key]);
		}
	}

	return $array_1;
}

function getdomain($url)
{
	$dom = array('ac', 'ah', 'biz', 'bj', 'cc', 'com', 'cq', 'edu', 'fj', 'gd', 'gov', 'gs', 'gx', 'gz', 'ha', 'hb', 'he', 'hi', 'hk', 'hl', 'hn', 'info', 'io', 'jl', 'js', 'jx', 'ln', 'mo', 'mobi', 'net', 'nm', 'nx', 'org', 'qh', 'sc', 'sd', 'sh', 'sn', 'sx', 'tj', 'tm', 'travel', 'tv', 'tw', 'ws', 'xj', 'xz', 'yn', 'zj');
	$exp = explode('.', $url);
	$cexp = count($exp);
	$dom2 = $exp[$cexp - 2];

	if (in_array($dom2, $dom)) {
		if ($cexp == 2) {
			$aw = 'www.';
		}
		else {
			$aw = '.';
		}

		return $exp[$cexp - 3] . $aw . $dom2 . '.' . $exp[$cexp - 1];
	}
	else {
		return $dom2 . '.' . $exp[$cexp - 1];
	}
}

function fieldFormat(&$value)
{
	$value = '\'' . $value . '\'';
	return $value;
}


?>
