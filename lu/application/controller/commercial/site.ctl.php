<?php

APP::load_file('main/main', 'ctl');
class site_ctl extends main_ctl
{
	final public function get_list()
	{
		$status = get('status');
		$searchval = request('searchval');
		$searchtype = request('searchtype');
		$get_site_list = dr('service/site.get_list');
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchval', $searchval);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('get_site_list', $get_site_list);
		TPL::display('site');
	}

	final public function unlock()
	{
		$q = dr('service/site.unlock', (int) get('siteid'));
		$_SESSION['succ'] = true;
		redirect('service/site.get_list');
	}

	final public function lock()
	{
		$q = dr('service/site.lock', (int) get('siteid'));
		redirect('service/site.get_list');
	}
}

?>
