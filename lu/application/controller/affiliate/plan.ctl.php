<?php

APP::load_file('main/main', 'ctl');
class plan_ctl extends main_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$plantype = dr('affiliate/plan.get_plantype_ok');
		$plantype_list = dr('affiliate/plan.get_plantype_ok_list', get('type'), get('classid'));
		$plantype = $this->get_plantype_sort($plantype);
		$site = dr('affiliate/site.get_list_ok');
		$plan_class = dr('main/class.get_all', 2);
		$site_num = count(dr('affiliate/site.get_list_ok'));
		$params = array('type' => get('type'), 'classid' => get('classid'));
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('site_num', $site_num);
		TPL::assign('plantype', $plantype);
		TPL::assign('site', $site);
		TPL::assign('plan_class', $plan_class);
		TPL::assign('plantype_list', $plantype_list);
		TPL::display('plan_getlist');
	}

	final public function info()
	{
		$adtpl = dr('affiliate/ad.get_planid_adtplid', (int) get('planid'));
		$p = dr('affiliate/plan.get_one', (int) get('planid'));
		$checkplan = false;
		$ucp = (array) unserialize($p['checkplan']);

		foreach ($ucp as $k => $v) {
			if ($v['isacl'] != 'all') {
				$checkplan = true;
			}
		}

		$pc_mob = array();

		if ($ucp['mobile']['isacl'] != 'all') {
			$md = $ucp['mobile']['data'];

			if (in_array('pc', $md)) {
				$pc_mob[] = 'pc';
			}

			if (in_array('pc', $md) && (1 < count($md))) {
				$pc_mob[] = 'pc';
				$pc_mob[] = 'mob';
			}

			if (!in_array('pc', $md) && $md) {
				$pc_mob[] = 'mob';
			}
		}

		if ($p['audit'] == 'y') {
			$ap = dr('affiliate/apply.get_apply_status', (int) $_SESSION['affiliate']['uid'], $p['planid']);
		}

		TPL::assign('pc_mob', $pc_mob);
		TPL::assign('adtpl', $adtpl);
		TPL::assign('checkplan', $checkplan);
		TPL::assign('p', $p);
		TPL::assign('ap', $ap);
		TPL::display('plan_info');
	}

	final public function get_ad()
	{
		$type = (int) get('type');
		$width = (int) get('width');
		$height = (int) get('height');
		$adtpl = dr('affiliate/ad.get_planid_adtplid', (int) get('planid'));

		if (!$type) {
			$type = $adtpl[0]['adtplid'];
		}

		$specs = dr('affiliate/ad.get_adtplid_ads_specs', $type, (int) get('planid'));
		$desc_func = create_function('$a,$b', "\r\n\t\t\t\t\t\t\t\t\t\t\$k = \"0\";\r\n\t\t\t\t\t\t\t\t\t\tif(\$a[\$k] == \$b[\$k]) return 0;\r\n\t\t\t\t\t\t\t\t\t\treturn \$a[\$k]>\$b[\$k]?-1:1;\r\n\t\t\t\t\t\t\t\t\t\t");
		@usort($specs, $desc_func);
		$p = dr('affiliate/plan.get_one', (int) get('planid'));
		$ad = dr('affiliate/ad.get_adtplid_ads', (int) $type, $width, $height, (int) get('planid'));
		TPL::assign('type', $type);
		TPL::assign('specs', $specs);
		TPL::assign('adtpl', $adtpl);
		TPL::assign('width', $width);
		TPL::assign('height', $height);
		TPL::assign('p', $p);
		TPL::assign('ad', $ad);
		TPL::display('plan_get_ad');
	}
}

?>
