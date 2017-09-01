<?php

APP::load_file('admin/admin', 'ctl');
class adstyle_ctl extends admin_ctl
{
	final public function get_list()
	{
		$adtplid = get('adtplid');
		$adstyle = dr('admin/adstyle.get_list', $adtplid);
		$adtpl = dr('admin/adtpl.get_list');
		TPL::assign('adstyle', $adstyle);
		TPL::assign('adtplid', $adtplid);
		TPL::assign('adtpl', $adtpl);
		TPL::display('adstyle');
	}

	final public function add()
	{
		$adtpl = dr('admin/adtpl.get_all');
		$specs = dr('admin/specs.get_all_ok');
		TPL::assign('adtpl', $adtpl);
		TPL::assign('specs', $specs);
		TPL::display('adstyle_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/adstyle.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/adstyle.get_list');
		}
	}

	final public function edit()
	{
		$a = dr('admin/adstyle.get_one', (int) get('styleid'));
		$adtpl = dr('admin/adtpl.get_all');
		$specs = dr('admin/specs.get_all_ok');
		$exp_specs = explode(',', $a['specs']);
		$exp_tplid = explode(',', $a['tplid']);
		TPL::assign('exp_specs', $exp_specs);
		TPL::assign('exp_tplid', $exp_tplid);
		TPL::assign('adtpl', $adtpl);
		TPL::assign('specs', $specs);
		TPL::assign('a', $a);
		TPL::display('adstyle_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/adstyle.update_post', (int) post('styleid'));
			$_SESSION['succ'] = true;
			redirect('admin/adstyle.edit?styleid=' . post('styleid'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$id = explode(',', post('styleid'));

			foreach ($id as $id) {
				$q = dr('admin/adstyle.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$id = explode(',', post('styleid'));

			foreach ($id as $id) {
				$q = dr('admin/adstyle.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post('styleid'));

			foreach ($id as $id) {
				$q = dr('admin/adstyle.del', (int) $id);
			}
		}
	}
}

?>
