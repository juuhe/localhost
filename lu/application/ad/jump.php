<?php

class jump
{
	public $o;
	public $a;
	public $z;
	public $u;
	public $os;
	private $browser;
	private $regionid;
	private $cityid;
	private $ispid;
	private $deduction = false;

	public function Start()
	{
		$s = addslashes($_GET['s']);

		if ($s) {
			$s_explode = explode(';', $s);
			$decode_string = addslashes(base64_decode($s_explode[0]));
			$hashValue = $s_explode[1];
			$this->hashValue = $hashValue;
			$newHashValue = md5($s_explode[0] . $GLOBALS['C_ZYIIS']['url_key']);

			if ($newHashValue != $hashValue) {
				exit('Illicit Referer hx');
			}

			$decode_string = $decode_string . '&' . base64_decode($_GET['p']) . '&b=' . $_GET['b'] . '&g=' . $_GET['g'];
			parse_str($decode_string, $o);
			$this->o = $o;
		}
		else {
			$this->zlink = true;
			$this->o['zoneid'] = (int) $_GET['id'];
			$this->run_zlink();
		}
	}

	public function run_zlink()
	{
		require APP_PATH . '/ad/show.php';
		$zoneid = $this->o['zoneid'];
		show::$zlink = true;
		$z = cache::get_zoneinfo($zoneid);
		$v = cache::get_view_adstyle($z['adstyleid']);
		$ad = show::view_ad($z, $v);
		$this->o['adsid'] = $ad[0]['adsid'];
		$this->o['uid'] = $z['uid'];
		$this->o['ip'] = $this->get_ip();
	}

	public function run_tourl_zlink($uid, $planid, $url)
	{
		$this->a = dr('jump/jump.get_plan_info', $planid);
		$this->a['adsid'] = 0;
		$this->o['uid'] = (int) $uid;
		$this->u = $this->get_uid_user_info();
		$this->o['ip'] = $this->get_ip();

		if ($this->ck_ip()) {
			require_once LIB_PATH . '/class/useragent.cls.php';
			$this->os = UserAgent::getOs($_SERVER['HTTP_USER_AGENT']);
			$this->insert_data();
			$this->updata_click_num_stats();
			$this->set_cps_cpa_cookie();
			$this->day_time();
		}

		$this->to_ulr($url);
	}

	public function run()
	{
		$cookie_vid = $this->o['uid'] . '_' . $this->o['planid'];
		if (in_array($this->o['plantype'], array('cpa', 'cps', 'cpas')) && $_COOKIE[$cookie_vid]) {
			$this->u['uid'] = $this->o['uid'];
			$this->a['adsid'] = $this->o['adsid'];
			$this->to_ulr($this->o['url']);
			exit();
		}

		require_once LIB_PATH . '/class/useragent.cls.php';
		$this->os = UserAgent::getOs($_SERVER['HTTP_USER_AGENT']);
		$this->a = $this->get_adsid_ad();
		$this->u = $this->get_uid_user_info();
		$plantype = $this->a['plantype'];

		if ($this->ck_ip()) {
			$this->insert_data();

			if (in_array($plantype, array('cpa', 'cps', 'cpas'))) {
				$this->updata_click_num_stats();
				$this->set_cps_cpa_cookie();
			}
			else {
				$this->updata_data();
			}

			$this->ck_budget_expire();
			$this->set_cookie('visitnum', $_COOKIE['visitnum'] + 1, TIMES + 604800);
			$this->set_cookie($cookie_vid, 're', TIMES + 18000);
			$va = $this->a['adsid'] . '|' . $this->o['planid'] . '|' . $this->o['uid'] . '|' . $this->o['zoneid'] . '|' . $this->o['siteid'];
			$this->set_cookie('do2click_' . $this->o['planid'], $va, TIMES + 10800);
			$this->set_cookie('doEffect_' . $this->o['planid'], $va, TIMES + 604800);
			$this->day_time();
		}
		else if (in_array($plantype, array('cpa', 'cps', 'cpas'))) {
			$this->set_cps_cpa_cookie();
		}

		$this->to_ulr();
	}

	public function to_ulr($url = '')
	{
		if ($_GET['srccpv']) {
			exit();
		}

		if (!$url) {
			$url = $this->a['url'];
		}

		if (!$url) {
			exit('NOT url');
		}

		$urlhost = parse_url($url);

		if (!$urlhost['scheme']) {
			$url = 'http://' . $this->a['url'];
		}

		$url = str_replace(array('{uid}', '{adsid}', '{siteid}', '{zoneid}'), array($this->u['uid'], $this->a['adsid'], $this->o['siteid'], $this->o['zoneid']), $url);
		Header('Location: ' . $url);
	}

	public function day_time()
	{
		$gtime = date('G', TIMES);
		$minutes = (int) date('i', TIMES);
		$g = dr('jump/jump.get_day');
		$day = $g['day'];
		if (($gtime == 1) && (1 < $minutes) && ($minutes < 30)) {
			$this->update_plan_3to1();
		}

		if (($day < DAYS) && (0 <= $gtime) && (1 < $minutes)) {
			$this->truncate_ip();
			$this->update_plan_3to1();

			if ($GLOBALS['C_ZYIIS']['clearing_atuo'] == '0') {
				$s = pay::run();
			}
			else {
				$s = 'succeed';
			}

			if ($s == 'succeed') {
				if ($day) {
					dr('jump/jump.update_day');
				}
				else {
					dr('jump/jump.insert_day');
				}
			}
		}
	}

	public function truncate_ip()
	{
		dr('jump/jump.truncate_ip');
		dr('jump/jump.repair_from');
		dr('jump/jump.insert_tempip', $this->o, $this->u, $this->a);
	}

	public function update_plan_3to1()
	{
		dr('jump/jump.update_plan_3to1');
	}

	public function ck_budget_expire()
	{
		$rand = rand(1, 3);

		if (1 == $rand) {
			$this->ck_plan_budget();
			$this->ck_plan_expire();
		}
	}

	public function ck_plan_budget()
	{
		$b = dr('jump/jump.get_plan_day_sumadvpay', DAYS, $this->a['planid']);
		$sumadvpay = $b['sumadvpay'];

		if ($this->a['budget'] <= $sumadvpay) {
			dr('jump/jump.updata_plan_status', $this->a['planid'], 3);
		}
	}

	public function ck_plan_expire()
	{
		if ((($this->a['expire'] != '0000-00-00') && ($this->a['expire'] <= DAYS)) || ($this->a['advmoney'] < 1)) {
			dr('jump/jump.updata_plan_status', $this->a['planid'], 4);
		}
	}

	public function set_cps_cpa_cookie()
	{
		require LIB_PATH . '/class/aes.cls.php';
		$ip = ($this->o['ip'] ? $this->o['ip'] : $this->get_ip());
		$key = $ip . '|' . $this->u['uid'] . '|' . $this->a['adsid'] . '|' . $this->o['zoneid'] . '|' . DAYS . '|' . $this->o['siteid'];
		$key = base64_encode(AES::encode($key));

		if ($this->a['cookie']) {
			$cookietime = $this->a['cookie'];
		}
		else if ($this->u['commissiontime']) {
			$cookietime = $this->u['commissiontime'];
		}
		else {
			$cookietime = 30;
		}

		if ($this->a['plantype'] == 'cpa') {
			$cookie_name = 'ca_key';
		}
		else {
			$cookie_name = 'cs_key';
		}

		$this->set_cookie($cookie_name, $key, TIMES + ($cookietime * 86400));
	}

	public function get_zone_info()
	{
		$z = dr('jump/jump.get_zone_info', $this->o['zoneid']);

		if (!$z) {
			exit('N[z]');
		}

		return $z;
	}

	public function get_uid_user_info()
	{
		$u = dr('jump/jump.get_user', $this->o['uid']);

		if (!$u) {
			exit('N[u]');
		}

		return $u;
	}

	public function get_adsid_ad()
	{
		$a = dr('jump/jump.get_adsid_ad', $this->o['adsid']);

		if (!$a) {
			if ($this->o['url']) {
				$this->to_ulr($this->o['url']);
			}
			else {
				exit('N[a]');
			}
		}

		if ($a['gradeprice'] == 1) {
			$sp = (array) unserialize($a['siteprice']);
			$site = dr('jump/jump.get_site', (int) $this->o['siteid']);
			$grade = (int) $site['grade'];
			$a['price'] = $sp[$grade];
		}

		if ($this->os['is_mobile'] == 'y') {
			$a['price'] = $a['price'] * $a['mobile_price'];
		}

		return $a;
	}

	public function insert_temp_visitlog()
	{
		$deduction = $this->get_deduction();
		$referer_url = $this->o['r'];

		if ($referer_url) {
			if (preg_match('/(google.|baidu.|yodao.|soso.|so.|sogou.|bing.)/i', $referer_url)) {
				$referer_type = 1;
			}
			else {
				$referer_type = 0;
			}
		}

		$os = $this->os;
		$this->browser = $browser = UserAgent::getBrowser($_SERVER['HTTP_USER_AGENT']);
		$region = $this->get_region();
		$r = explode('/', $region);
		$this->regionid = $regionid = array_keys($GLOBALS['C_province'], $r[0]);
		$this->cityid = $cityid = array_keys($GLOBALS['C_city'], $r[1]);
		$this->ispid = $ispid = array_keys($GLOBALS['C_isp'], $r[2]);
		$this->o['xy'] = $_GET['b'];
		$this->o['xxyy'] = $_GET['g'];
		dr('jump/jump.insert_temp_visitlog', $this->o, $this->u, $this->a, $deduction, $referer_type, $referer_url, $browser, $os, $regionid, $cityid, $ispid);
	}

	public function insert_tempip()
	{
		dr('jump/jump.insert_tempip', $this->o, $this->u, $this->a);
	}

	public function instert_search()
	{
		$keyword = $this->o['k'];

		if (!$keyword) {
			return NULL;
		}

		preg_match_all('/(google.|baidu.|yodao.|soso.|so.|sogou.|bing.)/i', $this->o['r'], $search);
		dr('jump/jump.instert_search', $this->u['uid'], $keyword, $this->o['r'], trim($search[0][0], '.'), $this->o['s']);
	}

	public function instert_os()
	{
		dr('jump/jump.instert_os', $this->u['uid'], $this->os['name'], $this->os['is_mobile'], 1);
	}

	public function instert_browser()
	{
		$a = array('sogou', 'liebao');
		if (!in_array($this->browser['name'], $a) && (1 < $_GET['se'])) {
			$this->browser['name'] = '360se';
		}

		dr('jump/jump.instert_browser', $this->u['uid'], $this->browser['name'], $this->browser['version'], $this->browser['kernel'], 1);
	}

	public function instert_screen()
	{
		dr('jump/jump.instert_screen', $this->u['uid'], $this->o['res'], 1);
	}

	public function instert_city_isp()
	{
		dr('jump/jump.instert_city_isp', $this->u['uid'], $this->regionid[0], $this->cityid[0], $this->ispid[0], 1);
	}

	public function insert_data()
	{
		$this->insert_tempip();
		$this->insert_temp_visitlog();
		$this->instert_search();
		$this->instert_os();
		$this->instert_browser();
		$this->instert_screen();
		$this->instert_city_isp();
	}

	public function updata_data()
	{
		$this->updata_stats();
		$this->updata_user();
		$this->updata_user_adv();
		$this->updata_hour();
	}

	public function updata_hour()
	{
		$hours = (int) date(G, TIMES);
		dr('jump/jump.updata_hour', $this->u['uid'], $this->a['advuid'], $hours, $this->a['planid'], $this->a['plantype'], 1);
	}

	public function updata_click_num_stats()
	{
		dr('jump/jump.updata_click_num_stats', $this->o, $this->u, $this->a);
	}

	public function updata_stats()
	{
		$deduction = $this->get_deduction();

		if ($deduction == 0) {
			$deduction = 0;
			$day_num = 1;
			$sumpay = $this->a['price'];
		}
		else {
			$deduction = 1;
			$day_num = 0;
			$sumpay = 0;
		}

		$sumadvpay = $this->a['priceadv'];
		$sumprofit = $this->a['priceadv'] - $sumpay;
		$data = array('num' => $day_num, 'deduction' => $deduction, 'day' => DAYS, 'planid' => (int) $this->a['planid'], 'adsid' => (int) $this->a['adsid'], 'zoneid' => (int) $this->o['zoneid'], 'plantype' => $this->a['plantype'], 'siteid' => (int) $this->o['siteid'], 'uid' => (int) $this->u['uid'], 'advuid' => (int) $this->a['advuid'], 'adtplid' => (int) $this->a['adtplid'], 'zuid' => (int) $this->o['zuid'], 'sumpay' => $sumpay, 'sumprofit' => $sumprofit, 'sumadvpay' => $sumadvpay);
		dr('api/api_stats.update_stats', $data);
	}

	public function updata_user()
	{
		$deduction = $this->get_deduction();

		if ($deduction == 0) {
			dr('api/api_user.update_money_type', (int) $this->u['uid'], $this->a['price'], '+', $this->a['clearing']);
		}
	}

	public function updata_user_adv()
	{
		dr('api/api_user.update_money_type', (int) $this->a['advuid'], $this->a['priceadv'], '-');
	}

	public function get_deduction()
	{
		if ($this->deduction === false) {
			$ded = unserialize($this->u['deduction']);
			$plantype = strtoupper($this->a['plantype']);

			if (0 < $ded[$plantype]) {
				$deduction = $ded[$plantype];
			}
			else if (0 < $this->a['deduction']) {
				$deduction = $this->a['deduction'];
			}
			else {
				$aDeduction = $this->a['plantype'] . '_deduction';
				$deduction = $GLOBALS['C_ZYIIS'][$aDeduction];
			}

			$rannum = rand(1, 100);

			if ($rannum <= $deduction) {
				$deduction = 1;
			}
			else {
				$deduction = 0;
			}

			$this->deduction = $deduction;
		}

		return $this->deduction;
	}

	public function ck_ip()
	{
		$row = dr('jump/jump.get_ip', $this->o['ip'], $this->a['planid'], $this->a['adsid'], $this->u['uid']);

		if (!$row) {
			return true;
		}

		return false;
	}

	public function get_region()
	{
		$region = $_COOKIE['region'];

		if (!$region) {
			require LIB_PATH . '/class/region.cls.php';
			$r = new region();
			$region = $r->query($this->get_ip());
			$r->close();
			$region = $region[0];
			$this->set_cookie('region', $region, TIMES + 15552000);
		}

		return $region;
	}

	public function set_cookie($name, $value, $expire = 0)
	{
		$p3pCompactPolicy = 'Powered by Www.Zyiis.Com 2005-2016';
		$p3pHeader .= 'CP="' . $p3pCompactPolicy . '"';
		header('P3P: ' . $p3pHeader);
		setcookie($name, $value, $expire, '/');
	}

	public function addslashes(&$value)
	{
		return str_replace(array('"', '\''), array('&quot;', '&#39;'), addslashes($value));
	}

	public function get_ip()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
}

class pay
{
	static public $type;
	static public $db;
	static public $tousers;

	static public function run()
	{
		$weekPayData = date('w', TIMES);
		$monthPayData = date('j', TIMES);
		$cycle = explode(',', $GLOBALS['C_ZYIIS']['clearing_cycle']);

		if (in_array('day', $cycle)) {
			pay::$type = 'day';
			pay::start();
		}

		if (($weekPayData == $GLOBALS['C_ZYIIS']['clearing_weekdata']) && in_array('week', $cycle)) {
			pay::$type = 'week';
			pay::start();
		}

		if (($monthPayData == $GLOBALS['C_ZYIIS']['clearing_monthdata']) && in_array('month', $cycle)) {
			pay::$type = 'month';
			pay::start();
		}

		return 'succeed';
	}

	static public function start()
	{
		$moneyType = pay::$type . 'money';
		$user = dr('jump/jump.get_pay_user', $moneyType, pay::$tousers);

		foreach ((array) $user as $u) {
			$money = $u[$moneyType];
			$tax = 0;

			if ($GLOBALS['C_ZYIIS']['tax_status']) {
				if ($money <= $GLOBALS['C_ZYIIS']['tax_1']) {
					$tax = 0;
				}
				else {
					if ((800 < $money) && ($money < 4000)) {
						$tax = ($money - 800) * (20 / 100);
					}
					else {
						if ((4000 < $money) && ($money < 20000)) {
							$tax = ($money - ($money * (20 / 100))) * (20 / 100);
						}
						else {
							if ((20000 < $money) && ($money < 50000)) {
								$tax = (($money - ($money * (20 / 100))) * (30 / 100)) - 2000;
							}
							else if (50000 < $money) {
								$tax = (($money - ($money * (20 / 100))) * (40 / 100)) - 7000;
							}
							else {
								$tax = 0;
							}
						}
					}
				}

				$tax = (0 < $tax ? round($tax, 3) : 0);
			}

			$charges = $GLOBALS['C_ZYIIS']['clearing_charges'] / 100;
			$charges = (0 < $money ? round($money * $charges, 3) : 0);
			$pay = round((abs($money) - $tax - $charges) + abs($u['xmoney']), 2);
			$ispay = dr('jump/jump.get_day_ispay_user', $u['uid'], pay::$type);

			if (!$ispay['uid']) {
				dr('jump/jump.insert_paylog', $u['uid'], $u['xmoney'], $money, $tax, $charges, $pay, $moneyType);
				dr('jump/jump.updata_user_money_null', $u['uid'], $moneyType);
			}
		}

		return true;
	}
}


?>
