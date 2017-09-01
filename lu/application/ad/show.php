<?php

class show
{
	static public $zlink;
	static public $siteid;

	static public function os()
	{
		$os = UserAgent::getOs($_SERVER['HTTP_USER_AGENT']);
		return $os;
	}

	static public function view_ad($z, $v, $no_demo = true)
	{
		if (!$z['zoneid']) {
			exit('zoneid error');
		}

		$get_ad = api('adcache.get_ad', $z, $v);
		$ad = $get_ad[0][$z['adtplid']];

		if ($no_demo) {
			$ad = show::filter_ad($z, $ad);
			if (!$ad && ($z['viewtype'] == 2)) {
				$z['viewtype'] = 1;
				$ad = show::filter_ad($z, $ad);
			}

			$ad = $ad['ads'];
		}

		if ($v['adnum'] < 1) {
			$adnum = count($ad);
		}
		else {
			$adnum = $v['adnum'];
		}

		$ad = show::ad_sort($ad, $adnum, $z, $v);
		return $ad;
	}

	static public function ad_sort(&$data, $adnum = 1, $z, $v)
	{
		if (!$data) {
			return NULL;
		}

		for ($i = 0; $i < $adnum; $i++) {
			$totalWeight = 0;

			foreach ((array) $data as $k => $d) {
				$totalWeight += $d['priority'];
				$data[$k]['new_priority'] = $totalWeight;
			}

			$rand_num = mt_rand(1, $totalWeight);
			$pos = show::get_pos($data, 0, count($data), $rand_num);
			array_splice($data, $pos[1], 1);
			unset($pos[0]['checkplan']);
			unset($pos[0]['new_priority']);
			unset($pos[0]['resuid']);
			$pos[0]['url'] = show::jump_url($z, $pos[0], $v);
			$ad[$i]['headline'] = $pos[0]['headline'];
			$ad[$i]['description'] = $pos[0]['description'];
			$ad[$i]['dispurl'] = $pos[0]['dispurl'];
			$ad[$i]['imageurl'] = $pos[0]['imageurl'];
			$ad[$i]['alt'] = $pos[0]['alt'];
			$ad[$i]['url'] = $pos[0]['url'];
			$ad[$i]['adsid'] = $pos[0]['adsid'];
			$ad[$i]['planid'] = $pos[0]['planid'];
			$ad[$i]['htmlcontrol'] = unserialize($pos[0]['htmlcontrol']);

			if ($z['plantype'] == 'cpv') {
				$ad[$i]['c2_url'] = show::get_c2_url($z, $pos[0]);
			}
		}

		return $ad;
	}

	static public function get_c2_url($z, $a)
	{
		return $GLOBALS['C_ZYIIS']['jump_url'] . WEB_URL . 'effect.php?type=ecv&planid=' . $a['planid'] . '&adsid=' . $a['adsid'] . '&zoneid=' . $z['zoneid'] . '&uid=' . $z['uid'] . '&adtplid=' . $a['adtplid'] . '&plantype=' . $a['plantype'];
	}

	static public function get_pos(&$arr, $begin, $end, $rand_num)
	{
		if ($end <= $begin) {
			return array($arr[$begin], $begin);
		}

		$mid = (int) ($begin + $end) / 2;

		if ($rand_num <= $arr[$mid]['new_priority']) {
			return show::get_pos($arr, $begin, $mid, $rand_num);
		}
		else {
			return show::get_pos($arr, $mid + 1, $end, $rand_num);
		}
	}

	static public function filter_ad($z, $a)
	{
		if (!$a || !$z) {
			return NULL;
		}

		function get_siteid_sitetype($z)
		{
			if ($_GET['l']) {
				$url = base64_decode($_GET['l']);
			}
			else {
				$url = $_SERVER['HTTP_REFERER'];
				$urlhost = parse_url($url);
				$url = $urlhost['host'];
			}

			$ghost = '*.' . deny::getdomain($url);

			foreach ((array) $z['site'] as $k => $v) {
				$site = explode(',', trim($v['siteurl'], ','));
				if (in_array($url, $site) || in_array($ghost, $site)) {
					return array('siteid' => $v['siteid'], 'sitetype' => $v['sitetype']);
				}
			}
		}
		function siteclass($c)
		{
			$sitetype = $GLOBALS['sitetype'];
			$z = $GLOBALS['zone'];

			if ($c['comparison'] == '==') {
				return !in_array($sitetype, $c['data']);
			}
			else {
				return in_array($sitetype, $c['data']);
			}

			return false;
		}
		function mobile($c)
		{
			$z = $GLOBALS['zone'];
			$os = show::os();

			if ($os['is_mobile'] == 'n') {
				$os['name'] = 'pc';
			}

			if (!in_array($os['name'], (array) $c['data'])) {
				return true;
			}

			return false;
		}
		function week($c)
		{
			$z = $GLOBALS['zone'];
			$w = date('w', TIMES);

			if (!in_array($w, $c['data'])) {
				return true;
			}

			$time = date('G', TIMES);

			if (!in_array($time, $c[$w]['data'])) {
				return true;
			}

			return false;
		}
		function city($c)
		{
			$z = $GLOBALS['zone'];
			$city = $_COOKIE['city'];

			if (!$city) {
				require_once LIB_PATH . '/class/region.cls.php';
				$region = new region();
				$g = $region->query(show::get_ip());
				$region->close();
				$e = explode('/', $g[0]);
				$region = $e[0];
				$city = $e[1];
				setcookie('city', $city, time() + 864000);
			}

			if ($c['comparison'] == '==') {
				return !in_array($city, $c['data']);
			}
			else {
				return in_array($city, $c['data']);
			}

			return false;
		}
		$s = get_siteid_sitetype($z);
		$sitetype = $s['sitetype'];
		show::$siteid = $s['siteid'];
		$GLOBALS['zone'] = $z;
		$GLOBALS['sitetype'] = $sitetype;

		foreach ($a as $id => $rows) {
			$zysuccess = true;
			$ckp = unserialize($rows['checkplan']);

			foreach ((array) $ckp as $ck => $cp) {
				if ($cp['isacl'] == 'acls') {
					if (function_exists($ck)) {
						if ($ck($cp)) {
							$zysuccess = false;
						}
					}
				}
			}

			if (($zysuccess == true) && ('1' < $rows['restrictions'])) {
				if ($rows['restrictions'] == '2') {
					$resuid = explode(',', $rows['resuid']);

					if (!in_array($z['uid'], $resuid)) {
						$zysuccess = false;
					}
				}

				if ($rows['restrictions'] == '3') {
					$resuid = explode(',', $rows['resuid']);

					if (in_array($z['uid'], $resuid)) {
						$zysuccess = false;
					}
				}
			}

			if (($zysuccess == true) && ('1' < $rows['sitelimit'])) {
				$siteid = $s['siteid'];

				if ($rows['sitelimit'] == '2') {
					$resuid = explode(',', $rows['limitsiteid']);

					if (!in_array($siteid, $resuid)) {
						$zysuccess = false;
					}
				}

				if ($rows['sitelimit'] == '3') {
					$resuid = explode(',', $rows['limitsiteid']);

					if (in_array($siteid, $resuid)) {
						$zysuccess = false;
					}
				}
			}

			if (($zysuccess == true) && ($z['viewtype'] == '2')) {
				$viewadsid = explode(',', $z['viewadsid']);

				if (!in_array($rows['adsid'], $viewadsid)) {
					$zysuccess = false;
				}
			}

			if ($rows['expire'] != '0000-00-00') {
				$daydate = date('Y-m-d', TIMES);
				$expire = $rows['expire'];

				if ($expire < $daydate) {
					$zysuccess = false;
				}
			}

			if (($zysuccess == true) && ($rows['audit'] == 'y')) {
				if (!in_array($rows['planid'], (array) $z['applyplanid'])) {
					$zysuccess = false;
				}
			}

			if ($zysuccess == false) {
				unset($a[$id]);
			}
			else {
				$prioritysum += $rows['priority'];
				$a_id[] = $rows['adsid'];
				$a_pri[$rows['adsid']] = $rows['priority'];
			}
		}

		return array('ads' => array_values($a), 'prioritysum' => $prioritysum, 'a_id' => $a_id, 'a_pri' => $a_pri);
	}

	static public function jump_url($z, $ad, $v)
	{
		if (show::$zlink) {
			return NULL;
		}

		$str_p = '&zoneid=' . $z['zoneid'] . '&siteid=' . show::$siteid . '&uid=' . $z['uid'] . '&adsid=' . $ad['adsid'] . '&planid=' . $ad['planid'] . '&plantype=' . $ad['plantype'] . '&url=' . urlencode($ad['url']);
		$str = $str_p . '&vtime=' . DATETIMES . '&ip=' . show::get_ip();
		$str = base64_encode($str);
		$hx = md5($str . $GLOBALS['C_ZYIIS']['url_key']);
		$au = $str . ';' . $hx . ';';
		$p = $_GET['p'];

		if ($p) {
			$aurl = '&p=' . $p;
		}

		$au = $GLOBALS['C_ZYIIS']['jump_url'] . WEB_URL . 'c.php?s=' . $au . $aurl;
		return $au;
	}

	static public function get_ip()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	static public function fl_aes($z)
	{
		$aesinfo = $GLOBALS['C_ZYIIS']['js_url'] . '|' . $z['uid'] . '|';
		return APP::load_class('aes')->encode($aesinfo, 'i1d2g3r64"2Ck:7!');
	}
}

class cache
{
	static public function get_zoneinfo($zoneid)
	{
		return api('adcache.get_zoneinfo', $zoneid);
	}

	static public function get_view_tpl($adtplid)
	{
		return api('adcache.get_view_tpl', $adtplid);
	}

	static public function get_view_adstyle($styleid)
	{
		return api('adcache.get_view_adstyle', $styleid);
	}
}

class deny
{
	static public function user_not_status($z)
	{
		if ($z['userstatus'] != 2) {
			exit('document.write(\'' . $GLOBALS['C_ZYIIS']['show_text_nouserstatus'] . '\');})();');
		}
	}

	static public function domian($z, $url = false)
	{
		if (($z['insite'] == '2') || (($z['insite'] == '1') && ($GLOBALS['C_ZYIIS']['domain_limit'] == '1'))) {
			if (!$url) {
				$url = $_SERVER['HTTP_REFERER'];
			}

			$urlhost = parse_url($url);
			$urlhost = $urlhost['host'];

			foreach ((array) $z['site'] as $k => $v) {
				$siteurl .= ',' . $v['siteurl'];
			}

			$site = explode(',', trim($siteurl, ','));
			$isn = false;

			if (!in_array($urlhost, $site)) {
				$isn = true;
				$ghost = '*.' . deny::getdomain($urlhost);
			}

			if ($isn && in_array($ghost, $site)) {
				$isn = false;
			}

			if ($isn) {
				exit('document.write(\'' . $GLOBALS['C_ZYIIS']['show_text_domain_limit'] . '\');})();');
			}
		}
	}

	static public function getdomain($url)
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
}

APP::adapter('cache', 'file', false);
require_once LIB_PATH . '/class/useragent.cls.php';

?>
