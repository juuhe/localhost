<?php

class alexapr
{
	public $http;
	public $alexa_url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url=';
	public $google_pr_host = 'toolbarqueries.google.com';

	public function __construct()
	{
		$this->http = app::load_class('http/snoopy_http', 'adp');
	}

	public function get_alexa($url)
	{
		if (!$url) {
			exit('no url');
		}

		$this->http->fetch($this->alexa_url . $url);
		$html = $this->http->results;

		if (preg_match('/TEXT=\\"(\\d+)\\" SOURCE/', $html, $match)) {
			$alexa = (int) $match[1];
		}
		else {
			$alexa = 0;
		}

		return $alexa;
	}

	public function get_pr($q)
	{
		$ch = $this->checksum($this->makehash($q));
		$url = 'http://%s/tbr?client=navclient-auto&ch=%s&features=Rank&q=info:%s';
		$url = sprintf($url, $this->google_pr_host, $ch, $q);
		$this->http->fetch($url);
		$html = $this->http->results;

		if (0 < strlen($html)) {
			return substr($html, 9);
		}
		else {
			return 0;
		}
	}

	public function strtonum($str, $check, $magic)
	{
		$int32unit = 4294967296;
		$length = strlen($str);

		for ($i = 0; $i < $length; $i++) {
			$check *= $magic;

			if ($int32unit <= $check) {
				$check = $check - (($int32unit * (int) $check) / $int32unit);
				$check = ($check < -2147483648 ? $check + $int32unit : $check);
			}

			$check += ord($str[$i]);
		}

		return $check;
	}

	public function makehash($string)
	{
		$check1 = $this->strtonum($string, 5381, 33);
		$check2 = $this->strtonum($string, 0, 65599);
		$check1 >>= 2;
		$check1 = (($check1 >> 4) & 67108800) | ($check1 & 63);
		$check1 = (($check1 >> 4) & 4193280) | ($check1 & 1023);
		$check1 = (($check1 >> 4) & 245760) | ($check1 & 16383);
		$t1 = (((($check1 & 960) << 4) | ($check1 & 60)) << 2) | ($check2 & 3855);
		$t2 = (((($check1 & 4294950912) << 4) | ($check1 & 15360)) << 10) | ($check2 & 252641280);
		return $t1 | $t2;
	}

	public function checksum($hashnum)
	{
		$checkbyte = 0;
		$flag = 0;
		$hashstr = sprintf('%u', $hashnum);
		$length = strlen($hashstr);

		for ($i = $length - 1; 0 <= $i; $i--) {
			$re = $hashstr[$i];

			if (1 === ($flag % 2)) {
				$re += $re;
				$re = ((int) $re / 10) + ($re % 10);
			}

			$checkbyte += $re;
			$flag++;
		}

		$checkbyte %= 10;

		if (0 !== $checkbyte) {
			$checkbyte = 10 - $checkbyte;

			if (1 === ($flag % 2)) {
				if (1 === ($checkbyte % 2)) {
					$checkbyte += 9;
				}

				$checkbyte >>= 1;
			}
		}

		return '7' . $checkbyte . $hashstr;
	}
}


?>
