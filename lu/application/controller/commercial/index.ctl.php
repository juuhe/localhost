<?php

APP::load_file('main/main', 'ctl');
class index_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_user_status0_list = dr('commercial/users.get_status0_list');
		$get_plan_status0_list = dr('commercial/plan.get_status0_list');
		$get_ads_status0_list = dr('commercial/ads.get_status0_list');
		TPL::assign('get_user_status0_list', $get_user_status0_list);
		TPL::assign('get_plan_status0_list', $get_plan_status0_list);
		TPL::assign('get_ads_status0_list', $get_ads_status0_list);
		TPL::assign('unionurl', $this->unionurl);
		TPL::display('index');
	}

	final public function user_unlock()
	{
		$q = dr('commercial/users.unlock', (int) get('uid'));
		$_SESSION['succ'] = true;
		redirect('commercial/index.get_list');
	}

	final public function user_lock()
	{
		$q = dr('commercial/users.lock', (int) get('uid'));
		redirect('commercial/index.get_list');
	}

	final public function plan_unlock()
	{
		$q = dr('commercial/plan.unlock', (int) get('planid'));
		$_SESSION['succ'] = true;
		redirect('commercial/index.get_list');
	}

	final public function plan_lock()
	{
		$q = dr('commercial/plan.lock', (int) get('planid'));
		redirect('commercial/index.get_list');
	}

	final public function ads_unlock()
	{
		$q = dr('commercial/ads.unlock', (int) get('adsid'));
		$_SESSION['succ'] = true;
		redirect('commercial/index.get_list');
	}

	final public function ads_lock()
	{
		$q = dr('commercial/ads.lock', (int) get('adsid'));
		redirect('commercial/index.get_list');
	}
}

?>
