<?php

APP::load_file('admin/admin', 'ctl');
class onlinepay_ctl extends admin_ctl
{
	final public function get_list()
	{
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$paytype = request('paytype');
		$touser = request('touser');

		if ($touser) {
			$u = dr('admin/user.get_one', (int) $touser);
		}

		$onlinepay = dr('admin/onlinepay.get_list', $status, $paytype);
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status, 'paytype' => $paytype);
		$page->params_url = $params;
		TPL::assign('onlinepay', $onlinepay);
		TPL::assign('page', $page);
		TPL::assign('paytype', $paytype);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::assign('touser', $u['username']);
		TPL::display('onlinepay');
	}

	final public function post_add_pay()
	{
		$q = dr('admin/onlinepay.post_add_pay');
		$_SESSION['succ'] = true;
		redirect('admin/onlinepay.get_list');
	}
}

?>
