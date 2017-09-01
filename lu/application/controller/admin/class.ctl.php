<?php

APP::load_file('admin/admin', 'ctl');
class class_ctl extends admin_ctl
{
	final public function get_list()
	{
		$type = get('type');
		$class = dr('admin/class.get_list', $type);
		$page = APP::adapter('pager', 'default');
		TPL::assign('class', $class);
		TPL::assign('page', $page);
		TPL::assign('type', $type);
		TPL::display('class');
	}

	final public function add()
	{
		TPL::display('class_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/class.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/class.get_list');
		}
	}

	final public function edit()
	{
		$c = dr('admin/class.get_one', (int) get('classid'));
		TPL::assign('c', $c);
		TPL::display('class_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/class.update_post', (int) post('classid'));
			$_SESSION['succ'] = true;
			redirect('admin/class.edit?classid=' . post('classid'));
		}
	}

	final public function del()
	{
		if (is_post()) {
			$classid = explode(',', post('classid'));

			foreach ($classid as $id) {
				$q = dr('admin/class.del', (int) $id);
			}
		}
	}
}

?>
