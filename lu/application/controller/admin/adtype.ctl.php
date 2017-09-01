<?php

APP::load_file('admin/admin', 'ctl');
class adtype_ctl extends admin_ctl
{
	final public function get_list()
	{
		$statstype = request('statstype');
		$adtpl = dr('admin/adtype.get_list');
		TPL::assign('adtpl', $adtpl);
		TPL::assign('statstype', $statstype);
		TPL::display('adtype');
	}

	final public function add()
	{
		TPL::display('adtype_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/adtype.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/adtype.get_list');
		}
	}

	final public function edit()
	{
		$a = dr('admin/adtype.get_one', (int) get('id'));
		TPL::assign('a', $a);
		TPL::display('adtype_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/adtype.update_post', (int) post('id'));
			$_SESSION['succ'] = true;
			redirect('admin/adtype.edit?id=' . post('id'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/adtype.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/adtype.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/adtype.del', (int) $id);
			}
		}
	}
}

?>
