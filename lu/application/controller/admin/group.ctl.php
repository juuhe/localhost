<?php

APP::load_file('admin/admin', 'ctl');
class group_ctl extends admin_ctl
{
	final public function get_list()
	{
		$group = dr('admin/group.get_list');
		$page = APP::adapter('pager', 'default');
		TPL::assign('group', $group);
		TPL::assign('page', $page);
		TPL::display('group');
	}

	final public function add()
	{
		TPL::display('group_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/group.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/group.get_list');
		}
	}

	final public function edit()
	{
		$g = dr('admin/group.get_one', (int) get('groupid'));
		TPL::assign('g', $g);
		TPL::display('group_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/group.update_post', (int) post('groupid'));
			$_SESSION['succ'] = true;
			redirect('admin/group.edit?groupid=' . post('groupid'));
		}
	}

	final public function del()
	{
		if (is_post()) {
			$groupid = explode(',', post('groupid'));

			foreach ($groupid as $id) {
				$q = dr('admin/group.del', (int) $id);
			}
		}
	}
}

?>
