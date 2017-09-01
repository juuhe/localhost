<?php

APP::load_file('admin/admin', 'ctl');
class ad_ctl extends admin_ctl
{
	public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$plantype = request('plantype');
		$status = request('status');
		$search = request('search');
		$adtplid = request('adtplid');
		$searchtype = request('searchtype');
		$ad = dr('admin/ad.get_list', $plantype, $status, $searchtype, $search, $adtplid);
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status, 'adtplid' => $adtplid);
		$page->params_url = $params;
		$get_adtype_all = $this->get_adtype_all();
		TPL::assign('page', $page);
		TPL::assign('ad', $ad);
		TPL::assign('plantype', $plantype);
		TPL::assign('status', $status);
		TPL::assign('search', $search);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('adtplid', $adtplid);
		TPL::assign('get_adtype_all', $get_adtype_all);
		TPL::display('ad');
	}

	final public function view()
	{
		$a = dr('admin/ad.get_one', (int) get('adsid'));
		TPL::assign('a', $a);
		TPL::display('view');
	}

	final public function add()
	{
		$plan = dr('admin/plan.get_all');
		TPL::assign('plan', $plan);
		TPL::display('ad_add');
	}

	public function add_post()
	{
		if (is_post()) {
			if (post('files') == 'up') {
				$imageurl = $this->_upload();
			}

			if (post('files') == 'url') {
				$imageurl = post('urlfile');
			}

			if (post('url') == '') {
				exit('URL NULL');
			}

			$q = dr('admin/ad.add_post', $imageurl);
			$_SESSION['succ'] = true;
			redirect('admin/ad.get_list');
		}
	}

	final public function edit()
	{
		$ads = dr('admin/ad.get_one', (int) get('adsid'));
		$plan = dr('admin/plan.get_one', $ads['planid']);
		$tpl = dr('admin/adtpl.get_one_adtpl_adtype', $ads['adtplid']);
		$htmlcontrol = unserialize($tpl['htmlcontrol']);
		$zd_htmlcontrol = unserialize($ads['htmlcontrol']);
		TPL::assign('plan', $plan);
		TPL::assign('tpl', $tpl);
		TPL::assign('htmlcontrol', $htmlcontrol);
		TPL::assign('zd_htmlcontrol', $zd_htmlcontrol);
		TPL::assign('ads', $ads);
		TPL::display('ad_add');
	}

	public function update_post()
	{
		if (is_post()) {
			if (post('files') == 'up') {
				$imageurl = $this->_upload();
			}

			if (post('files') == 'url') {
				$imageurl = post('urlfile');
			}

			if (post('url') == '') {
				exit('URL NULL');
			}

			$q = dr('admin/ad.update_post', (int) post('adsid'), $imageurl);
			$_SESSION['succ'] = true;
			redirect('admin/ad.edit?adsid=' . post('adsid'));
		}
	}

	final public function get_adtype()
	{
		if (is_post()) {
			$id = post('planid');
			$plan = dr('admin/plan.get_one', (int) $id);
			$sp = $this->_get_adtype($plan['plantype']);
			echo json_encode($sp);
		}
	}

	final public function get_adtype_all()
	{
		$sp = $this->_get_adtype();
		return $sp;
	}

	final public function _get_adtype($plantype = false)
	{
		$adtype = dr('admin/adtype.get_statstype', $plantype);
		$i = 1;

		foreach ((array) $adtype as $at) {
			$adtpl = dr('admin/adtpl.get_adtypeid_all', $at['id']);
			$at['sort'] = $at['sort'] + $i;
			$sp[$at['sort']]['name'] = $at['name'];
			$sp[$at['sort']]['id'] = $at['id'];
			$sp[$at['sort']]['tpl'] = array();

			foreach ((array) $adtpl as $al) {
				$sp[$at['sort']]['tpl'][$al['tplid']]['tplid'] = $al['tplid'];
				$sp[$at['sort']]['tpl'][$al['tplid']]['name'] = $al['tplname'];
			}

			$i++;
		}

		return $sp;
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

	final public function unlock()
	{
		if (is_post()) {
			$id = explode(',', post('adsid'));

			foreach ($id as $id) {
				$q = dr('admin/ad.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$id = explode(',', post('adsid'));

			foreach ($id as $id) {
				$q = dr('admin/ad.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post("adsid\r\n\t\t\t\t\t"));

			foreach ($id as $id) {
				$q = dr('admin/ad.del', (int) $id);
			}
		}
	}

	final public function view_ad()
	{
		$a = dr('affiliate/ad.get_ad_plan_one', (int) get('adsid'));

		if ($a['plantype'] != 'cpm') {
			$imgext = substr($a['imageurl'], -3);

			if ($imgext) {
				$parse_url = parse_url($a['imageurl']);

				if (!$parse_url['scheme']) {
					$a['imageurl'] = $GLOBALS['C_ZYIIS']['img_url'] . $a['imageurl'];
				}
			}

			if ($a['imageurl'] && (substr($a['imageurl'], -3) == 'swf')) {
				$html = '<embed src=' . $a['imageurl'] . ' quality=\'high\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\' type=\'application/x-shockwave-flash\'   wmode=transparent></embed>';
			}
			else {
				if ($a['imageurl'] && (substr($a['imageurl'], -4) == 'html')) {
					$html = '<iframe  width=' . $a['width'] . ' height=' . $a['height'] . ' frameborder=0 src="' . $a['imageurl'] . '" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no"></iframe>';
				}
				else {
					$html = '<img src=' . $a['imageurl'] . '  border=\'0\' >';
				}
			}

			echo $html;
		}
	}

	final public function implant_zone()
	{
		$ads = dr('admin/ad.get_one', (int) post('adsid'));
		dr('admin/zone.implant_zone', $ads);
	}

	final public function update_adname()
	{
		if (is_post()) {
			$adsid = post('adsid');
			$adname = post('adname ');
			dr('admin/ad.update_adname', $adsid, $adname);
			echo 'ok';
		}
	}

	final public function update_priority()
	{
		if (is_post()) {
			$adsid = post('adsid');
			$priority = post('priority ');
			dr('admin/ad.update_priority', $adsid, $priority);
			echo 'ok';
		}
	}

	final public function demo()
	{
		$ad = dr('admin/ad.get_one', (int) request('adsid'));
		echo api('ad.view', $ad['adsid'], $ad, false);
	}
}

?>
