<?php

APP::load_file('admin/admin', 'ctl');
class msg_ctl extends admin_ctl
{
	final public function get_list()
	{
		$type = request('type');
		$status = request('status');
		$msg = dr('admin/msg.get_list');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'type ' => $type, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('msg', $msg);
		TPL::assign('type', $type);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('msg');
	}

	final public function add()
	{
		TPL::display('msg_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/msg.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/msg.get_list');
		}
	}

	final public function edit()
	{
		$m = dr('admin/msg.get_one', (int) get('messageid'));
		TPL::assign('m', $m);
		TPL::display('msg_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/msg.update_post', (int) post('messageid'));
			$_SESSION['succ'] = true;
			redirect('admin/msg.edit?messageid=' . post('messageid'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$messageid = explode(',', post('messageid'));

			foreach ($messageid as $id) {
				$q = dr('admin/msg.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$messageid = explode(',', post('messageid'));

			foreach ($messageid as $id) {
				$q = dr('admin/msg.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$messageid = explode(',', post('messageid'));

			foreach ($messageid as $id) {
				$q = dr('admin/msg.del', (int) $id);
			}
		}
	}
}

?>
