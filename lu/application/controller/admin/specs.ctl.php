<?php

APP::load_file('admin/admin', 'ctl');
class specs_ctl extends admin_ctl
{
	final public function get_list()
	{
		$specs = dr('admin/specs.get_list');
		TPL::assign('specs', $specs);
		TPL::display('specs');
	}

	final public function add()
	{
		TPL::display('specs_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$g = dr('admin/specs.get_width_height', post('width'), post('height'));

			if ($g) {
				exit('Repeat the width and height');
			}

			$q = dr('admin/specs.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/specs.get_list');
		}
	}

	final public function edit()
	{
		$s = dr('admin/specs.get_one', (int) get('id'));
		TPL::assign('s', $s);
		TPL::display('specs_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/specs.update_post', (int) post('id'));
			$_SESSION['succ'] = true;
			redirect('admin/specs.edit?id=' . post('id'));
		}
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/specs.del', (int) $id);
			}
		}
	}
}

?>
