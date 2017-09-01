<?php

APP::load_file('admin/admin', 'ctl');
class plan_ctl extends admin_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$plantype = request('plantype');
		$status = request('status');
		$search = request('search');
		$searchtype = request('searchtype');
		$get_timerange = $this->get_timerange();
		$plan = dr('admin/plan.get_list', $plantype, $status, $searchtype, $search);
		$new_num = dr('admin/plan.get_new_num');
		$edit_num = dr('admin/plan.get_edit_num');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status, '$plantype' => plantype);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('plan', $plan);
		TPL::assign('plantype', $plantype);
		TPL::assign('status', $status);
		TPL::assign('search', $search);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('new_num', $new_num);
		TPL::assign('edit_num', $edit_num);
		TPL::assign('get_timerange', $get_timerange);
		TPL::display('plan');
	}

	final public function add()
	{
		$adv = dr('admin/user.get_advertiser_ok');
		$site_class = dr('admin/class.get_all', 1);
		$plan_class = dr('admin/class.get_all', 2);
		TPL::assign('adv', $adv);
		TPL::assign('site_class', $site_class);
		TPL::assign('plan_class', $plan_class);
		TPL::display('plan_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$logo_imageurl = $this->_upload('logo_imageurl', 'logo_imageurl');
			$q = dr('admin/plan.add_post', $logo_imageurl);
			$_SESSION['succ'] = true;
			redirect('admin/plan.get_list');
		}
	}

	final public function edit()
	{
		$plan_class = dr('admin/class.get_all', 2);
		$site_class = dr('admin/class.get_all', 1);
		$plan = dr('admin/plan.get_one', (int) get('planid'));
		$adv = dr('admin/user.get_one_all', $plan['uid']);
		$plan['checkplan'] = unserialize($plan['checkplan']);

		if (is_array($plan['checkplan'])) {
			$plan['checkplan'] == array();
		}

		TPL::assign('plan_class', $plan_class);
		TPL::assign('plan', $plan);
		TPL::assign('adv', $adv);
		TPL::assign('site_class', $site_class);
		TPL::display('plan_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$id = (int) post('planid');
			$logo_imageurl = $this->_upload('logo_imageurl', 'logo_imageurl');
			$p = dr('admin/plan.get_one', $id);

			if ($p['status'] == 0) {
				$status = 1;
			}

			$q = dr('admin/plan.update_post', $id, $logo_imageurl, $status);
			$_SESSION['succ'] = true;

			if ($status) {
				redirect('admin/plan.get_list');
			}

			redirect('admin/plan.edit?planid=' . $id);
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$id = explode(',', post('planid'));

			foreach ($id as $id) {
				$q = dr('admin/plan.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$id = explode(',', post('planid'));

			foreach ($id as $id) {
				$q = dr('admin/plan.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$id = explode(',', post("planid\r\n\t\t\t\t\t"));

			foreach ($id as $id) {
				$q = dr('admin/plan.del', (int) $id);
				dr('admin/ad.del_planid', (int) $id);
			}
		}
	}

	final public function update_price()
	{
		$planid = post('planid');
		$type = get('type');
		if (!$planid || !$type) {
			exit('pt null');
		}

		if ($type == 'aff') {
			$price = post('price');
			$plan = dr('admin/plan.get_one', (int) $planid);

			if ($plan['priceadv'] < $price) {
				exit('price > priceadv');
			}

			dr('admin/plan.update_price', $planid, $price);
		}
		else {
			$price = post('priceadv');
			dr('admin/plan.update_priceadv', $planid, $price);
		}

		echo 'ok';
	}

	final public function update_budget()
	{
		$planid = post('planid');
		$budget = post('budget');
		if (!$planid || !$budget) {
			exit('pb null');
		}

		dr('admin/plan.update_budget', $planid, $budget);
		echo 'ok';
	}

	final public function update_clearing()
	{
		$planid = post('planid');
		$clearing = post('clearing');
		if (!$planid || !$clearing) {
			exit('pc null');
		}

		dr('admin/plan.update_clearing', $planid, $clearing);
		echo 'ok';
	}

	final public function update_deduction()
	{
		$planid = post('planid');
		$deduction = post('deduction');
		if (!$planid || !$deduction) {
			exit('pd null');
		}

		dr('admin/plan.update_deduction', $planid, $deduction);
		echo 'ok';
	}

	final public function get_7day_trend()
	{
		$day_hour = array();
		$day_sum_stats = dr('admin/stats.get_data', 'day,plantype', 'day', false);
		$plan = dr('admin/plan.get_one', (int) request('planid'));

		foreach ((array) $day_sum_stats as $d) {
			$day_hour[] = $d['day'];
			$day_views[] = $d['views'];
			$day_num[] = $d['num'];
		}

		if (!$day_hour) {
			$day_hour[0] = 0;
		}

		if (!$day_num) {
			$day_num[0] = 0;
		}

		if (!$day_views) {
			$day_views[0] = 0;
		}

		if (in_array($plan['plantype'], array('cpc', 'cpm', 'cpv'))) {
			$atype = 'Ip';
		}

		if (in_array($plan['plantype'], array('cpa'))) {
			$atype = 'Nun';
		}

		if (in_array($plan['plantype'], array('cps'))) {
			$atype = 'Order';
		}

		$series_deta[] = array('name' => $atype, 'data' => $day_num);
		$series_deta[] = array('name' => 'Pv', 'data' => $day_views);
		$a['xAxis'] = $day_hour;
		$a['series'] = $series_deta;
		echo json_encode($a, JSON_NUMERIC_CHECK);
	}
}

?>
