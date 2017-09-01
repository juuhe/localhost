<?php

APP::load_file('admin/admin', 'ctl');
class administrator_ctl extends admin_ctl
{
	final public function get_list()
	{
		$administrator = dr('admin/administrator.get_list');
		$roles = dr('admin/roles.get_list');
		$page = APP::adapter('pager', 'default');
		TPL::assign('administrator', $administrator);
		TPL::assign('rol', $roles);
		TPL::assign('page', $page);
		TPL::display('administrator');
	}

	final public function add_post()
	{
		if (is_post()) {
			$u = dr('admin/administrator.get_username_one', post('username'));

			if ($u) {
				exit('RE username');
			}

			$q = dr('admin/administrator.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/administrator.get_list');
		}
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/administrator.update_post', (int) post('id'));
			$_SESSION['succ'] = true;
			redirect('admin/administrator.get_list');
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$adminid = explode(',', post('id'));

			foreach ($adminid as $id) {
				$q = dr('admin/administrator.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$adminid = explode(',', post('id'));

			foreach ($adminid as $id) {
				$q = dr('admin/administrator.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$adminid = explode(',', post('id'));

			foreach ($adminid as $id) {
				$q = dr('admin/administrator.del', (int) $id);
			}

			echo $q;
		}
	}
}

?>
