<?php

APP::load_file('main/main', 'ctl');
class index_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_user_status0_list = dr('service/users.get_status0_list');
		$get_site_status0_list = dr('service/site.get_status0_list');
		TPL::assign('get_user_status0_list', $get_user_status0_list);
		TPL::assign('get_site_status0_list', $get_site_status0_list);
		TPL::assign('unionurl', $this->unionurl);
		TPL::display('index');
	}

	final public function user_unlock()
	{
		$q = dr('service/users.unlock', (int) get('uid'));
		$_SESSION['succ'] = true;
		redirect('service/index.get_list');
	}

	final public function user_lock()
	{
		$q = dr('service/users.lock', (int) get('uid'));
		redirect('service/index.get_list');
	}

	final public function site_unlock()
	{
		$q = dr('service/site.unlock', (int) get('siteid'));
		$_SESSION['succ'] = true;
		redirect('service/index.get_list');
	}

	final public function site_lock()
	{
		$q = dr('service/site.lock', (int) get('siteid'));
		redirect('service/index.get_list');
	}
}

?>
