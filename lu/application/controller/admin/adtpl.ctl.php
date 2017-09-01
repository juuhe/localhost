<?php

APP::load_file('admin/admin', 'ctl');
class adtpl_ctl extends admin_ctl
{
	final public function get_list()
	{
		$id = request('id');
		$adtpl = dr('admin/adtpl.get_list', $id);
		$adtype = dr('admin/adtype.get_all');
		TPL::assign('id', $id);
		TPL::assign('adtpl', $adtpl);
		TPL::assign('adtype', $adtype);
		TPL::display('adtpl');
	}

	final public function add()
	{
		$adtype = dr('admin/adtype.get_all');
		$specs = dr('admin/specs.get_all_ok');
		TPL::assign('adtype', $adtype);
		TPL::assign('specs', $specs);
		TPL::display('adtpl_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/adtpl.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/adtpl.get_list');
		}
	}

	final public function edit()
	{
		$adtype = dr('admin/adtype.get_all');
		$a = dr('admin/adtpl.get_one', (int) get('tplid'));
		$specs = dr('admin/specs.get_all_ok');
		$exp_specs = explode(',', $a['specs']);
		$tplid = get('tplid');
		TPL::assign('exp_specs', $exp_specs);
		TPL::assign('specs', $specs);
		TPL::assign('a', $a);
		TPL::assign('tplid', $tplid);
		TPL::assign('adtype', $adtype);
		TPL::display('adtpl_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/adtpl.update_post', (int) post('tplid'));
			$_SESSION['succ'] = true;
			redirect('admin/adtpl.edit?tplid=' . post('tplid'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/adtpl.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/adtpl.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post('id'));

			foreach ($id as $id) {
				$q = dr('admin/adtpl.del', (int) $id);
			}
		}
	}
}

?>
