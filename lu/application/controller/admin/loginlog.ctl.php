<?php

APP::load_file('admin/admin', 'ctl');
class loginlog_ctl extends admin_ctl
{
	final public function get_list()
	{
		$loginlog = dr('admin/loginlog.get_list');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('loginlog', $loginlog);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('loginlog');
	}

	final public function del()
	{
		if (is_post()) {
			$logid = explode(',', post('id'));

			foreach ($logid as $id) {
				$q = dr('admin/loginlog.del', (int) $id);
			}
		}
	}
}

?>
