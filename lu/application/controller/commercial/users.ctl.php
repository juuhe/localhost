<?php

APP::load_file('main/main', 'ctl');
class users_ctl extends main_ctl
{
	final public function get_list()
	{
		$status = get('status');
		$searchval = request('searchval');
		$sortingm = request('sortingm');
		$searchtype = request('searchtype');
		$sortingtype = request('sortingtype');
		$get_user_list = dr('commercial/users.get_list');
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'status' => $status, 'sortingm' => $sortingm, 'sortingtype' => $sortingtype);
		$page->params_url = $params;
		TPL::assign('status', $status);
		TPL::assign('searchval', $searchval);
		TPL::assign('sortingm', $sortingm);
		TPL::assign('sortingtype', $sortingtype);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('get_user_list', $get_user_list);
		TPL::assign('page', $page);
		TPL::display('user');
	}

	final public function unlock()
	{
		$q = dr('commercial/users.unlock', (int) get('uid'));
		$_SESSION['succ'] = true;
		redirect('commercial/users.get_list');
	}

	final public function lock()
	{
		$q = dr('commercial/users.lock', (int) get('uid'));
		redirect('commercial/users.get_list');
	}

	final public function glogin()
	{
		$uid = (int) get('uid');
		$url = api('user.set_login_seesion', $uid);
		redirect($url);
	}
}

?>
