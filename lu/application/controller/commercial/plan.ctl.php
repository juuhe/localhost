<?php

APP::load_file('main/main', 'ctl');
class plan_ctl extends main_ctl
{
	final public function get_list()
	{
		$status = get('status');
		$searchval = request('searchval');
		$searchtype = request('searchtype');
		$get_plna_list = dr('commercial/plan.get_list');
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchval', $searchval);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('get_plna_list', $get_plna_list);
		TPL::display('plna');
	}

	final public function unlock()
	{
		$q = dr('commercial/plan.unlock', (int) get('planid'));
		$_SESSION['succ'] = true;
		redirect('commercial/plan.get_list');
	}

	final public function lock()
	{
		$q = dr('commercial/plan.lock', (int) get('planid'));
		redirect('commercial/plan.get_list');
	}

	final public function edit()
	{
		$p = dr('commercial/plan.get_one', (int) get('planid'));
		$u = dr('main/account.get_one', $p['uid']);
		$url = api('user.set_login_seesion', $u['uid']);
		redirect('advertiser/plan.edit?planid=' . $p['planid']);
	}
}

?>
