<?php

APP::load_file('admin/admin', 'ctl');
class giftlog_ctl extends admin_ctl
{
	final public function get_list()
	{
		$status = request('status');
		$giftlog = dr('admin/giftlog.get_list');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('giftlog', $giftlog);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('giftlog');
	}

	final public function delivery()
	{
		if (is_post()) {
			$giftlogid = explode(',', post('id'));

			foreach ($giftlogid as $id) {
				$q = dr('admin/giftlog.delivery', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$giftlogid = explode(',', post('id'));

			foreach ($giftlogid as $id) {
				$q = dr('admin/giftlog.del', (int) $id);
			}
		}
	}
}

?>
