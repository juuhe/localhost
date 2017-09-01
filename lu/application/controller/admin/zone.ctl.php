<?php

APP::load_file('admin/admin', 'ctl');
class zone_ctl extends admin_ctl
{
	final public function get_list()
	{
		$zone = dr('admin/zone.get_list');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$plantype = request('plantype');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'plantype' => $plantype);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('plantype', $plantype);
		TPL::assign('search', $search);
		TPL::assign('zone', $zone);
		TPL::display('zone');
	}

	final public function edit()
	{
		$zoneid = (int) get('zoneid');
		$z = dr('admin/zone.get_one', $zoneid);
		$url = api('user.set_login_seesion', $z['uid']);
		redirect('affiliate/zone.edit?zoneid=' . $z['zoneid']);
	}

	final public function del()
	{
		if (is_post()) {
			$zoneid = explode(',', post('zoneid'));

			foreach ($zoneid as $id) {
				$q = dr('admin/zone.del', (int) $id);
			}
		}
	}
}

?>
