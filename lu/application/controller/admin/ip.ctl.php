<?php

APP::load_file('admin/admin', 'ctl');
class ip_ctl extends admin_ctl
{
	final public function get_list()
	{
		$get_timerange = $this->get_timerange();
		$page = APP::adapter('pager', 'default');
		$plan = dr('admin/plan.get_all');
		$ip = dr('admin/ip.get_visit_all');
		$searchval = request('searchval');
		$searchtype = request('searchtype');
		$timerange = request('timerange');
		$planid = request('planid');
		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'timerange' => $timerange, 'planid' => $planid);
		$page->params_url = $params;
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('page', $page);
		TPL::assign('plan', $plan);
		TPL::assign('ip', $ip);
		TPL::assign('searchval', $searchval);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('timerange', $timerange);
		TPL::assign('planid', $planid);
		TPL::display('ip');
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post('del_id'));

			foreach ($id as $id) {
				$q = dr('admin/ip.del', $id);
			}
		}
	}

	final public function truncate()
	{
		dr('admin/ip.truncate_data');
	}
}

?>
