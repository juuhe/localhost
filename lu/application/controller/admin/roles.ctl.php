<?php

APP::load_file('admin/admin', 'ctl');
class roles_ctl extends admin_ctl
{
	final public function get_list()
	{
		$roles = dr('admin/roles.get_list');
		TPL::assign('rol', $roles);
		TPL::display('roles');
	}

	final public function add()
	{
		$file = APP_PATH . '/config/resource.php';
		include_once $file;
		$ctl = $resource;
		TPL::assign('ac', $ac);
		TPL::assign('ctl', $ctl);
		TPL::display('roles_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/roles.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/roles.get_list');
		}
	}

	final public function edit()
	{
		$r = dr('admin/roles.get_one', (int) get('id'));
		$file = APP_PATH . '/config/resource.php';
		include_once $file;
		$ctl = $resource;
		$acl = unserialize($r['action']);
		TPL::assign('acl', $acl);
		TPL::assign('ac', $ac);
		TPL::assign('ctl', $ctl);
		TPL::assign('r', $r);
		TPL::display('roles_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/roles.update_post', (int) post('id'));
			$_SESSION['succ'] = true;
			redirect('admin/roles.get_list');
		}
	}

	final public function del()
	{
		if (is_post()) {
			$rolesid = explode(',', post('id'));

			foreach ($rolesid as $id) {
				if ($id <= 3) {
					continue;
				}

				$q = dr('admin/roles.del', (int) $id);
			}
		}
	}
}

?>
