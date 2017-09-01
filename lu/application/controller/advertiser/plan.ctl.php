<?php

APP::load_file('main/main', 'ctl');
class plan_ctl extends main_ctl
{
	final public function get_list()
	{
		$plantype = get('plantype');
		$classid = get('classid');
		$page = APP::adapter('pager', 'default');
		$plantype_list = dr('advertiser/plan.get_list', $plantype, $classid, true);
		$plan_class = dr('main/class.get_all', 2);
		$all_plantype = dr('advertiser/plan.get_all_plantype');
		$params = array('plantype' => get('type'), 'classid' => get('classid'));
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('plantype', $plantype);
		TPL::assign('classid', $classid);
		TPL::assign('plan_class', $plan_class);
		TPL::assign('all_plantype', $all_plantype);
		TPL::assign('plantype_list', $plantype_list);
		TPL::display('plan_getlist');
	}

	final public function create()
	{
		$site_class = dr('main/class.get_all', 1);
		$plan_class = dr('main/class.get_all', 2);
		TPL::assign('site_class', $site_class);
		TPL::assign('plan_class', $plan_class);
		TPL::display('plan_create');
	}

	final public function create_post()
	{
		if (is_post()) {
			$logo_imageurl = $this->_upload('logo_imageurl', 'logo_imageurl');
			$q = dr('advertiser/plan.add_post', $logo_imageurl);
			redirect('advertiser/plan.get_list');
		}
	}

	final public function edit()
	{
		$site_class = dr('main/class.get_all', 1);
		$plan_class = dr('main/class.get_all', 2);
		$plan = dr('advertiser/plan.get_one', (int) get('planid'));
		$plan['checkplan'] = unserialize($plan['checkplan']);

		if (is_array($plan['checkplan'])) {
			$plan['checkplan'] == array();
		}

		TPL::assign('plan_class', $plan_class);
		TPL::assign('plan', $plan);
		TPL::assign('site_class', $site_class);
		TPL::display('plan_create');
	}

	final public function edit_post()
	{
		if (is_post()) {
			$id = (int) post('planid');
			$logo_imageurl = $this->_upload('logo_imageurl', 'logo_imageurl');
			$p = dr('advertiser/plan.get_one', $id);

			if ($p['status'] < 1) {
				$_SESSION['err'] = true;
			}
			else {
				$q = dr('advertiser/plan.update_post', $id, $logo_imageurl);
				$_SESSION['succ'] = true;
			}

			redirect('advertiser/plan.edit?planid=' . $id);
		}
	}
}

?>
