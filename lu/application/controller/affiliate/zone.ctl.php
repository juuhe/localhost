<?php

APP::load_file('main/main', 'ctl');
class zone_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_size = request('get_size');
		$get_plantype = request('get_plantype');
		$get_tplid = request('get_tplid');
		$zoneid = request('zoneid');
		$plantype = dr('affiliate/plan.get_plantype_ok');
		$site_num = count(dr('affiliate/site.get_list_ok'));
		$zone = dr('affiliate/zone.get_list');
		$site = dr('affiliate/site.get_list');
		$zone_list = dr('affiliate/zone.get_list', $get_plantype, true, $get_size, $get_tplid, $zoneid);

		foreach ((array) $zone as $z) {
			$adsize[] = $z['width'] . 'x' . $z['height'];
			$zadtplid .= ',' . $z['adtplid'];
			$zplantype[] = $z['plantype'];
		}

		$page = APP::adapter('pager', 'default');
		$params = array('plantype' => get('type'), 'classid' => get('classid'), 'get_size' => get('get_size'), 'get_plantype' => get('get_plantype'), 'get_tplid' => get('get_tplid'));
		$page->params_url = $params;
		$adsize = array_unique((array) $adsize);
		$zplantype = array_unique((array) $zplantype);
		$zadtplid = explode(',', trim($zadtplid, ','));
		$zadtplid = array_unique((array) $zadtplid);
		TPL::assign('page', $page);
		TPL::assign('site_num', $site_num);
		TPL::assign('site', $site);
		TPL::assign('zone', $zone);
		TPL::assign('zone_list', $zone_list);
		TPL::assign('plantype', $plantype);
		TPL::assign('adsize', $adsize);
		TPL::assign('zadtplid', $zadtplid);
		TPL::assign('zplantype', $zplantype);
		TPL::assign('get_size', $get_size);
		TPL::assign('get_tplid', $get_tplid);
		TPL::assign('get_plantype', $get_plantype);
		TPL::assign('zoneid', $zoneid);
		TPL::display('zone_getlist');
	}
	
	
	final public function get_list_home()
	{
		$get_size = request('get_size');
		$get_plantype = request('get_plantype');
		$get_tplid = request('get_tplid');
		$zoneid = request('zoneid');
		$plantype = dr('affiliate/plan.get_plantype_ok');
		$site_num = count(dr('affiliate/site.get_list_ok'));
		$zone = dr('affiliate/zone.get_list');
		$site = dr('affiliate/site.get_list');
		$zone_list = dr('affiliate/zone.get_list', $get_plantype, true, $get_size, $get_tplid, $zoneid);

		foreach ((array) $zone as $z) {
			$adsize[] = $z['width'] . 'x' . $z['height'];
			$zadtplid .= ',' . $z['adtplid'];
			$zplantype[] = $z['plantype'];
		}

		$page = APP::adapter('pager', 'default');
		$params = array('plantype' => get('type'), 'classid' => get('classid'), 'get_size' => get('get_size'), 'get_plantype' => get('get_plantype'), 'get_tplid' => get('get_tplid'));
		$page->params_url = $params;
		$adsize = array_unique((array) $adsize);
		$zplantype = array_unique((array) $zplantype);
		$zadtplid = explode(',', trim($zadtplid, ','));
		$zadtplid = array_unique((array) $zadtplid);
		TPL::assign('page', $page);
		TPL::assign('site_num', $site_num);
		TPL::assign('site', $site);
		TPL::assign('zone', $zone);
		TPL::assign('zone_list', $zone_list);
		TPL::assign('plantype', $plantype);
		TPL::assign('adsize', $adsize);
		TPL::assign('zadtplid', $zadtplid);
		TPL::assign('zplantype', $zplantype);
		TPL::assign('get_size', $get_size);
		TPL::assign('get_tplid', $get_tplid);
		TPL::assign('get_plantype', $get_plantype);
		TPL::assign('zoneid', $zoneid);
		TPL::display('zone_getlist_home');
	}
	public function create($z = false)
	{
		$site_num = count(dr('affiliate/site.get_list_ok'));
		if (($GLOBALS['C_ZYIIS']['domain_limit'] == '1') && ($site_num < 1)) {
			redirect('affiliate/site.create');
		}

		$plantype = get('plantype');
		$adtplid = get('adtplid');
		$styleid = get('styleid');
		$adsid = get('adsid');
		$platform = get('platform');
		$specs = get('specs');

		if ($z) {
			$plantype = $z['plantype'];
			$adtplid = $z['adtplid'];
			$styleid = $z['adstyleid'];
			$specs = $z['width'] . 'x' . $z['height'];
		}

		if ($adsid) {
			$a = dr('affiliate/ad.get_ad_one', $adsid);
			$plan = dr('affiliate/plan.get_one', $a['planid']);
			$plantype = $plan['plantype'];
			$adtplid = $a['adtplid'];
			$styleid = $a['styleid'];
			$specs = $a['width'] . 'x' . $a['height'];
			$z['viewtype'] = 2;
			$z['plantype'] = $plantype;
			$z['viewadsid'] = $adsid;
		}

		$get_plantype_ok = dr('affiliate/ad.get_plantype_ok', $platform);
		$get_plantype_ok = $this->get_plantype_sort($get_plantype_ok);

		if (!$plantype) {
			$plantype = $get_plantype_ok[0]['plantype'];
		}

		$get_adtpl_ok = dr('affiliate/ad.get_adtpl_ok', $plantype, $platform);

		foreach ((array) $get_adtpl_ok as $a) {
			$get_tpl[$a['adtplid']] = dr('affiliate/adtpl.get_one_adtpl_adtype', $a['adtplid']);
		}

		$get_adtpl_ok = sort_array($get_tpl, 'sort');

		if (!$adtplid) {
			$adtplid = $get_adtpl_ok[0]['tplid'];
		}

		$adtpl = $get_tpl[$adtplid];

		if ($adtpl['customspecs'] == 1) {
			$get_specs = dr('affiliate/ad.get_adtplid_ads_specs', $adtplid, '', $plantype);

			foreach ($get_specs as $sp) {
				$adspecs[] = $sp['width'] . 'x' . $sp['height'];
			}

			if (!$specs) {
				$specs = $adspecs[0];
			}

			$sp = explode('x', $specs);
			$ads = dr('affiliate/ad.get_ads_ok', $plantype, $adtplid, $sp[0], $sp[1]);
		}
		else {
			$get_specs = dr('affiliate/adtpl.get_adtpl_adstyle_specs', $adtplid);

			foreach ((array) $get_specs as $sp) {
				$adspecs[] = $sp['specs'];
			}

			$adspecs = array_unique(explode(',', implode(',', (array) $adspecs)));

			if (!$specs) {
				$specs = $adspecs[0];
			}

			$ads = dr('affiliate/ad.get_ads_ok', $plantype, $adtplid);
		}

		$adstyle = dr('affiliate/adstyle.get_adstyle', $adtplid, $specs);
		@rsort($adspecs);

		if (!$styleid) {
			$styleid = $adstyle[0]['styleid'];
		}

		$adstyle_add_html = dr('affiliate/adstyle.get_adstyle_one', $styleid);
		$site = dr('affiliate/site.get_list_ok');
		TPL::assign('adstyle_add_html', $adstyle_add_html);
		TPL::assign('site', $site);
		TPL::assign('ads', $ads);
		TPL::assign('platform', $platform);
		TPL::assign('adsid', $adsid);
		TPL::assign('adtpl', $adtpl);
		TPL::assign('adstyle', $adstyle);
		TPL::assign('adspecs', $adspecs);
		TPL::assign('specs', $specs);
		TPL::assign('adtplid', $adtplid);
		TPL::assign('styleid', $styleid);
		TPL::assign('plantype', $plantype);
		TPL::assign('get_plantype_ok', $get_plantype_ok);
		TPL::assign('get_adtpl_ok', $get_adtpl_ok);
		TPL::assign('z', $z);
		TPL::display('zone_create');
	}

	final public function create_post($redirect = true)
	{
		if (is_post()) {
			if (post('zd_size_w') && post('zd_size_h')) {
				$_POST['specs'] = post('zd_size_w') . 'x' . post('zd_size_h');
			}

			$q = dr('affiliate/zone.create_post');

			if ($redirect) {
				redirect('affiliate/zone.get_list?zoneid=' . $q . '&code=yes&ztype=' . post('adtplid'));
			}

			return $q;
		}
	}

	final public function post_create()
	{
		$q = $this->create_post(false);
		redirect('affiliate/zone.get_code?zoneid=' . $q);
	}

	final public function get_code()
	{
		$z = dr('affiliate/zone.get_one', (int) get('zoneid'));
		$tpl = dr('affiliate/adtpl.get_one', $z['adtplid']);
		TPL::assign('z', $z);
		TPL::assign('tpl', $tpl);
		TPL::display('get_code');
	}

	final public function edit()
	{
		$z = dr('affiliate/zone.get_one', (int) get('zoneid'));
		$this->create($z);
	}

	final public function edit_post()
	{
		if (is_post()) {
			$q = dr('affiliate/zone.edit_post');
			$_SESSION['succ'] = true;
			redirect('affiliate/zone.get_list?type=' . post('plantype'));
		}
	}

	final public function del()
	{
		if (is_post()) {
			$q = dr('affiliate/zone.del', (int) post('zoneid'));
		}
	}
	final public function id()
	{
		if (is_post()) {
			$q = dr('affiliate/zone.id', (int)post('siteid'));
		}
	}
    
	final public function demo()
	{
		if (post('zd_size_w') && post('zd_size_h')) {
			$_POST['specs'] = post('zd_size_w') . 'x' . post('zd_size_h');
		}

		$wh = explode('x', post('specs'));
		require APP_PATH . '/ad/show.php';
		$_POST['width'] = (int) $wh[0];
		$_POST['height'] = (int) $wh[1];
		$z = array('zoneid' => 1, 'siteid' => post('siteid'), 'uid' => 1, 'plantype' => post('plantype'), 'width' => (int) $wh[0], 'height' => (int) $wh[1], 'adstyleid' => (int) post('styleid'), 'adtplid' => (int) post('adtplid'), 'viewtype' => (int) post('viewtype'), 'viewadsid' => @implode(',', post('viewadsid')), 'codestyle' => json_encode(post('color')), 'htmlcontrol' => serialize(post('a_h')));
		$c = json_decode($z['codestyle'], true);
		$c['width'] = $z['width'];
		$c['height'] = $z['height'];
		$c['zoneid'] = $z['zoneid'];
		$c['htmlcontrol'] = unserialize($z['htmlcontrol']);
		$c = json_encode($c);
		$v = cache::get_view_adstyle($z['adstyleid']);
		$ad = show::view_ad($z, $v['tpl'], false);
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo "\r\n\t\t\t\t\r\n <body>\r\n\t<div id='container' class='container'></div>\r\n\t \r\n</body>\r\n<script>\r\nvar pvid={pid:[],aid:[]};\r\nfunction pvstas(pvid){};\t\t\t\t\r\nvar ads = " . json_encode($ad) . ";\r\nvar config = " . $c . '; ';
		echo $v['style']['iframejs'] . $v['tpl']['iframejs'];
		echo '</script>';
	}
}

?>
