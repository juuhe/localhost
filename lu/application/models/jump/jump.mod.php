<?php

class jump_mod extends app_models
{
	public $default_from = '';

	public function get_ip($ip, $planid, $adsid = NULL, $uid = NULL)
	{
		$this->select('ip');
		$this->from('tempip');
		$where = array('ip' => $ip, 'planid' => (int) $planid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_plan_info($planid)
	{
		$this->from('plan');
		$this->where('planid', (int) $planid);
		$data = $this->find_one();
		return $data;
	}

	public function get_adsid_ad($adsid)
	{
		$this->select("a.adsid,a.url,a.status,a.adtplid,\r\n\t\t\t\tp.cookie,p.planid,p.deduction,p.plantype,\r\n\t\t\t\tp.price,p.mobile_price,p.priceadv,p.uid,p.expire,p.clearing,p.budget,p.gradeprice,p.siteprice,\r\n\t\t\t\tu.money As advmoney,u.uid AS advuid");
		$this->from('ads AS a');
		$this->from('plan AS p');
		$this->from('users AS u');
		$this->where('a.planid', ' p.planid', 'AND', false);
		$this->where('u.uid', ' p.uid', 'AND', false);
		$where = array('a.adsid' => (int) $adsid, 'a.status' => 3, 'p.status' => 1, 'u.status' => 2, 'u.money >' => 0.5);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_user($uid)
	{
		$this->from('users');
		$where = array('uid' => (int) $uid, 'status' => 2);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_site($siteid)
	{
		$this->select('grade');
		$this->from('site');
		$this->where('siteid', (int) $siteid);
		$data = $this->find_one();
		return $data;
	}

	public function get_pay_user($money_type, $username = NULL)
	{
		$this->select('uid,xmoney,' . $money_type);

		if ($username) {
			$this->where('username', $username);
		}

		$this->where($money_type . '+xmoney >=', $GLOBALS['C_ZYIIS']['min_clearing']);
		$where = array('type' => 1, 'status' => 2);
		$this->where($where);
		$this->from('users');
		$data = $this->get();
		return $data;
	}

	public function get_day()
	{
		$this->from('day');
		$data = $this->find_one();
		return $data;
	}

	public function update_day()
	{
		$this->set('day', DAYS);
		$this->update('day');
	}

	public function insert_day()
	{
		$this->set('day', DAYS);
		$this->insert('day');
	}

	public function get_day_ispay_user($uid, $money_type)
	{
		$this->select('uid');
		$where = array('uid' => (int) $uid, 'clearingtype' => $money_type, 'addtime' => DAYS);
		$this->where($where);
		$this->from('paylog');
		$data = $this->find_one();
		return $data;
	}

	public function get_plan_day_sumadvpay($day, $planid)
	{
		$this->select('sum(sumadvpay) AS sumadvpay');
		$this->from('stats');
		$where = array('day' => $day, 'planid' => (int) $planid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_zone_info($zoneid)
	{
		$this->from('zone');
		$where = array('zoneid' => (int) $zoneid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function insert_paylog($uid, $xmoney, $money, $tax, $charges, $pay, $money_type)
	{
		$data = array('uid' => $uid, 'xmoney' => $xmoney, 'money' => $money, 'tax' => $tax, 'charges' => $charges, 'pay' => $pay, 'addtime' => DAYS, 'clearingtype' => $money_type);
		$this->set($data);
		$this->insert('paylog');
	}

	public function update_plan_3to1()
	{
		$this->set('status', 1);
		$this->where('status', 3);
		$this->update('plan');
	}

	public function updata_plan_status($planid, $status)
	{
		$this->set('status', (int) $status);
		$this->where('planid', (int) $planid);
		$this->update('plan');
	}

	public function updata_user_money_null($uid, $money_type)
	{
		$this->set($money_type, 0);
		$this->set('xmoney', 0);
		$this->where('uid', (int) $uid);
		$this->update('users');
	}

	public function updata_hour($uid, $advuid, $hour = 0, $planid = 0, $plantype, $num = 1)
	{
		$data = array('advuid' => (int) $advuid, 'uid' => (int) $uid, 'hour' . $hour => (int) $num, 'planid' => (int) $planid, 'plantype' => $plantype, 'day' => DAYS);
		$updata = array('hour' . $hour => $num);
		$this->insert_update('log_hour', $data, $updata);
	}

	public function updata_click_num_stats($o, $u, $a)
	{
		$data = array('clicks' => 1, 'day' => DAYS, 'planid' => (int) $a['planid'], 'adsid' => (int) $a['adsid'], 'zoneid' => (int) $a['zoneid'], 'plantype' => $a['plantype'], 'siteid' => (int) $o['siteid'], 'uid' => (int) $u['uid'], 'adtplid' => (int) $a['adtplid'], 'zuid' => (int) $o['zuid']);
		$updata = array('clicks' => 1);
		$this->insert_update('stats', $data, $updata);
	}

	public function insert_tempip($o, $u, $a)
	{
		$data = array('ip' => $o['ip'], 'planid' => (int) $a['planid'], 'uid' => (int) $u['uid'], 'adsid' => (int) $a['adsid'], 'hour' => (int) date(G, TIMES));
		$this->set($data);
		$this->insert('tempip');
	}

	public function insert_temp_visitlog($o, $u, $a, $deduction, $referer_type = NULL, $referer_url = NULL, $browser = NULL, $os = NULL, $regionid = NULL, $cityid = NULL, $ispid = NULL)
	{
		$data = array('planid' => (int) $a['planid'], 'adsid' => (int) $a['adsid'], 'zoneid' => (int) $o['zoneid'], 'uid' => (int) $u['uid'], 'referer_type' => (int) $referer_type, 'referer_url' => $referer_url, 'referer_keyword' => $o['k'], 'siteid' => (int) $o['siteid'], 'site_page' => $o['u'], 'page_title' => urlencode($o['t']), 'browser_name' => $browser['name'], 'browser_version' => $browser['version'], 'browser_kernel' => $browser['kernel'], 'useragent' => str_replace(array('"', '\''), array('&quot;', '&#39;'), $_SERVER['HTTP_USER_AGENT']), 'os' => $os['name'], 'is_mobile' => $os['is_mobile'] ? $os['is_mobile'] : 'n', 'screen' => $o['res'], 'pdf' => (int) $o['p'], 'flash' => $o['f'], 'java' => (int) $o['j'], 'ip' => $o['ip'], 'cookie' => (int) $o['c'], 'browser_lang' => $o['l'], 'region_id' => (int) $regionid[0], 'city_id' => (int) $cityid[0], 'isp_id' => (int) $ispid[0], 'price' => $a['price'], 'priceadv' => $a['priceadv'], 'last_time' => DATETIMES, 'first_time' => $o['vtime'] ? $o['vtime'] : DATETIMES, 'deduction' => $deduction ? 'y' : 'n', 'visitnum' => (int) $_COOKIE['visitnum'], 'ch' => (int) $o['h'], 'xy' => $o['b'], 'xxyy' => $o['g']);
		$this->set($data);
		$this->insert('log_visit');
	}

	public function instert_search($uid, $keyword, $search_url, $search, $site_url = NULL)
	{
		$data = array('keyword' => $keyword, 'search_url' => $search_url, 'search' => $search, 'site_url' => $site_url, 'uid' => $uid, 'day' => DAYS);
		$this->set($data);
		$this->insert('log_search');
	}

	public function instert_os($uid, $os = NULL, $is_mobile = NULL, $num = 1)
	{
		$data = array('os' => $os, 'mobile' => $is_mobile, 'num' => (int) $num, 'uid' => (int) $uid, 'day' => DAYS);
		$updata = array('num' => (int) $num);
		$this->insert_update('log_os', $data, $updata);
	}

	public function instert_browser($uid, $browser = NULL, $version = NULL, $kernel, $num = 1)
	{
		$data = array('browser' => $browser, 'ver' => $version, 'kernel' => $kernel, 'num' => (int) $num, 'uid' => (int) $uid, 'day' => DAYS);
		$updata = array('num' => (int) $num);
		$this->insert_update('log_browser', $data, $updata);
	}

	public function instert_screen($uid, $screen = NULL, $num = 1)
	{
		$data = array('screen' => $screen, 'num' => (int) $num, 'uid' => (int) $uid, 'day' => DAYS);
		$updata = array('num' => (int) $num);
		$this->insert_update('log_screen', $data, $updata);
	}

	public function instert_city_isp($uid, $province = 0, $city = 0, $isp = 0, $num = 1)
	{
		$data = array('province' => (int) $province, 'city' => (int) $city, 'isp' => (int) $isp, 'num' => (int) $num, 'uid' => (int) $uid, 'day' => DAYS);
		$updata = array('num' => (int) $num);
		$this->insert_update('log_city_isp', $data, $updata);
	}

	public function truncate_ip()
	{
		$this->truncate('tempip');
		$this->truncate('tempcip');
	}

	public function repair_from()
	{
		$this->repair('tempip');
		$this->repair('tempcip');
	}

	public function get_tempc_ip($ip, $day, $zoneid, $type)
	{
		$this->select('ip');
		$this->from('tempcip');
		$where = array('ip' => $ip, 'day' => $day, 'zoneid' => $zoneid, 'type' => $type);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function insert_tempc_ip($day, $ip, $planid, $adsid, $zoneid, $type)
	{
		$data = array('ip' => $ip, 'planid' => (int) $planid, 'adsid' => (int) $adsid, 'zoneid' => (int) $zoneid, 'day' => $day, 'type' => (int) $type);
		$this->set($data);
		$this->insert('tempcip');
	}

	public function updata_2click_num_stats($day, $planid, $adsid, $uid, $zoneid, $siteid)
	{
		$data = array('do2click' => 1, 'uid' => (int) $uid, 'adsid' => (int) $adsid, 'zoneid' => (int) $zoneid, 'planid' => (int) $planid, 'siteid' => (int) $siteid, 'day' => $day);
		$updata = array('do2click' => 1);
		$this->insert_update('stats', $data, $updata);
	}

	public function updata_effect_num_stats($day, $planid, $adsid, $uid, $zoneid, $siteid)
	{
		$data = array('effectnum' => 1, 'uid' => (int) $uid, 'adsid' => (int) $adsid, 'zoneid' => (int) $zoneid, 'planid' => (int) $planid, 'siteid' => (int) $siteid, 'day' => $day);
		$updata = array('effectnum' => 1);
		$this->insert_update('stats', $data, $updata);
	}

	public function updata_effect($day, $ip, $planid, $adsid, $uid, $zoneid, $siteid, $type)
	{
		$g = $this->get_tempc_ip($ip, $day, $zoneid, $type);

		if (!$g) {
			$this->insert_tempc_ip($day, $ip, $planid, $adsid, $zoneid, $type);
			$this->updata_effect_num_stats($day, $planid, $adsid, $uid, $zoneid, $siteid);
		}
	}

	public function updata_2click($day, $ip, $planid, $adsid, $uid, $zoneid, $siteid, $type)
	{
		$g = $this->get_tempc_ip($ip, $day, $zoneid, $type);

		if (!$g) {
			$this->insert_tempc_ip($day, $ip, $planid, $adsid, $zoneid, $type);
			$this->updata_2click_num_stats($day, $planid, $adsid, $uid, $zoneid, $siteid);
		}
	}

	public function updata_click($day, $ip, $planid, $adsid, $uid, $zoneid, $siteid, $adtplid, $plantype, $type)
	{
		$g = $this->get_tempc_ip($ip, $day, $zoneid, $type);

		if (!$g) {
			$this->insert_tempc_ip($day, $ip, $planid, $adsid, $zoneid, $type);
			$o = array('siteid' => (int) $siteid);
			$a = array('planid' => (int) $planid, 'adsid' => (int) $adsid, 'zoneid' => (int) $zoneid, 'adtplid' => (int) $adtplid, 'plantype' => $plantype);
			$u = array('uid' => (int) $uid);
			$this->updata_click_num_stats($o, $u, $a);
		}
	}
}

?>
