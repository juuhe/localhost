<?php

class UserAgent
{
	static public $browsers = array(
		'Chrome'    => array('chrome'),
		'Firefox'   => array('Firefox'),
		'QQbrowser' => array('qqbrowser'),
		'Sogou'     => array('se '),
		'Liebao'    => array('liebao'),
		'Maxthon'   => array('maxthon'),
		'Theworld'  => array('theworld'),
		'Opera'     => array('opera'),
		'UC'        => array('uc'),
		'IE'        => array('msie'),
		'Safari'    => array('safari', 'webkit')
		);
	static public $mobile = array(
		'ios'            => array('Iphone', 'Ipad', 'Ipod'),
		'windows mobile' => array('Windows Mobile'),
		'windows phone'  => array('Windows Mhone'),
		'windows ce'     => array('Windows CE'),
		'android'        => array('Android'),
		'blackberry'     => array('BlackBerry', 'BB10'),
		'maemo'          => array('Maemo')
		);
	static protected $pc = array(
		'win8.1'  => array('Windows NT 6.3', 'Windows 8.1', 'CYGWIN_NT-6.3'),
		'win8'    => array('Windows NT 6.2', 'Windows 8', 'CYGWIN_NT-6.2'),
		'win7'    => array('Windows NT 6.1', 'Windows 7', 'CYGWIN_NT-6.1'),
		'winxp'   => array('Windows NT 5.1', 'Windows XP', 'CYGWIN_NT-5.1'),
		'win2003' => array('Windows NT 5.2', 'Windows Server 2003', 'CYGWIN_NT-5.2'),
		'win2000' => array('Windows NT 5.0', 'Windows 2000', 'CYGWIN_NT-5.0'),
		'vista'   => array('Windows NT 6.0', 'Windows Vista', 'CYGWIN_NT-6.0'),
		'mac'     => array('Darwin', 'Macintosh', 'Power Macintosh', 'Mac_PowerPC', 'Mac PPC', 'Mac PowerPC', 'PPC', 'Mac OS'),
		'linux'   => array('Linux'),
		'freebsd' => array('FreeBSD'),
		'ubuntu'  => array('Ubuntu')
		);

	static protected function urldecodeUserAgent($userAgent)
	{
		return urldecode($userAgent);
	}

	static public function getBrowser($userAgent)
	{
		$userAgent = self::urldecodeUserAgent($userAgent);
		$kernel_pattern = '/(Trident|Presto|AppleWebKit|Gecko)/i';
		preg_match_all($kernel_pattern, $userAgent, $kernel);
		$version_pattern = '/(?:rv|me|ra|ie)[\\/: ]([\\d.]+)/i';
		preg_match_all($version_pattern, $userAgent, $version);
		$info = array('name' => '', 'version' => $version[1][0], 'kernel' => $kernel[1][0]);
		$browser = self::$browsers;

		foreach ($browser as $key => $b) {
			foreach ($b as $bn) {
				if (stristr($userAgent, $bn)) {
					$info['name'] = $key == 'IE' ? $key . $info['version'] : $key;
					return $info;
				}
			}
		}
	}

	static public function getOs($userAgent)
	{
		$userAgent = self::urldecodeUserAgent($userAgent);
		$info = array('name' => '', 'is_mobile' => '');
		$mobile = self::$mobile;

		foreach ($mobile as $key => $m) {
			foreach ($m as $mn) {
				if (stristr($userAgent, $mn)) {
					$info['name'] = $key;
					$info['is_mobile'] = 'y';
					return $info;
				}
			}
		}

		$pc = self::$pc;

		foreach ($pc as $key => $p) {
			foreach ($p as $pn) {
				if (stristr($userAgent, $pn)) {
					$info['name'] = $key;
					$info['is_mobile'] = 'n';
					return $info;
				}
			}
		}
	}
}


?>
