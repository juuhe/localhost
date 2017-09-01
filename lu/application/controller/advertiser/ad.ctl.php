<?php

APP::load_file('main/main', 'ctl');
class ad_ctl extends main_ctl
{
	final public function get_list()
	{
		$planid = (int) get('planid');
		$adtplid = (int) get('adtplid');
		$page = APP::adapter('pager', 'default');
		$plan_all = dr('advertiser/plan.get_list', '', false);
		$ads = dr('advertiser/ad.get_list', $planid, $adtplid);
		$params = array('type' => get('type'), 'classid' => get('classid'));
		$page->params_url = $params;
		TPL::assign('plan_all', $plan_all);
		TPL::assign('page', $page);
		TPL::assign('ads', $ads);
		TPL::assign('planid', $planid);
		TPL::assign('adtplid', $adtplid);
		TPL::display('ad_getlist');
	}

	final public function create()
	{
		$plan_all = dr('advertiser/plan.get_list', '', false);
		TPL::assign('plan_all', $plan_all);
		TPL::display('ad_create');
	}

	final public function create_post()
	{
		if (is_post()) {
			if (post('files') == 'up') {
				$imageurl = $this->_upload();
			}

			if (post('files') == 'url') {
				$imageurl = post('urlfile');
			}

			$q = dr('advertiser/ad.create_post', $imageurl);
			$_SESSION['succ'] = true;
			redirect('advertiser/ad.get_list');
		}
	}

	final public function edit()
	{
		$plan_all = dr('advertiser/plan.get_list', '', false);
		$ads = dr('advertiser/ad.get_ad_one', (int) get('adsid'));
		$tpl = dr('admin/adtpl.get_one_adtpl_adtype', $ads['adtplid']);
		$htmlcontrol = unserialize($tpl['htmlcontrol']);
		$zd_htmlcontrol = unserialize($ads['htmlcontrol']);
		TPL::assign('plan_all', $plan_all);
		TPL::assign('ads', $ads);
		TPL::assign('htmlcontrol', $htmlcontrol);
		TPL::assign('zd_htmlcontrol', $zd_htmlcontrol);
		TPL::assign('tpl', $tpl);
		TPL::display('ad_create');
	}

	final public function edit_post()
	{
		if (is_post()) {
			if (post('files') == 'up') {
				$imageurl = $this->_upload();
			}

			if (post('files') == 'url') {
				$imageurl = post('urlfile');
			}

			$q = dr('advertiser/ad.edit_post', (int) post('adsid'), $imageurl);
			$_SESSION['succ'] = true;
			redirect('advertiser/ad.edit?adsid=' . post('adsid'));
		}
	}

	final public function get_adtype()
	{
		if (is_post()) {
			$id = post('planid');
			$plan = dr('advertiser/plan.get_one', (int) $id);
			$adtype = dr('admin/adtype.get_statstype', $plan['plantype']);

			foreach ((array) $adtype as $at) {
				$adtpl = dr('admin/adtpl.get_adtypeid_all', $at['id']);
				$sp[$at['sort']]['name'] = $at['name'];
				$sp[$at['sort']]['id'] = $at['id'];
				$sp[$at['sort']]['tpl'] = array();

				foreach ((array) $adtpl as $al) {
					$sp[$at['sort']]['tpl'][$al['tplid']]['tplid'] = $al['tplid'];
					$sp[$at['sort']]['tpl'][$al['tplid']]['name'] = $al['tplname'];
				}
			}

			echo json_encode($sp);
		}
	}

	final public function get_adtpl()
	{
		if (is_post()) {
			$tplid = post('tplid');
			$specs = dr('admin/adstyle.get_tplid_specs_all', $tplid);

			foreach ((array) $specs as $s1) {
				$spe[] = $s1['specs'];
			}

			$specs = @array_unique(explode(',', trim(implode(',', $spe), ',')));
			$i = 0;

			foreach ((array) $specs as $s) {
				$sg = dr('admin/adstyle.get_sytlename', $tplid, $s);
				$sn[$i]['specs'] = $s;
				$sn[$i]['stylename'] = $sg['stylename'];
				$i++;
			}

			$adtpl = dr('admin/adtpl.get_one', (int) $tplid);
			$sp['tplid'] = $adtpl['tplid'];
			$sp['name'] = $adtpl['tplname'];
			$sp['htmlcontrol'] = unserialize($adtpl['htmlcontrol']);
			$sp['customspecs'] = $adtpl['customspecs'];
			$sp['specs'] = $sn;
			echo json_encode($sp);
		}
	}
}

?>
