<?php

APP::load_file('admin/admin', 'ctl');
class user_ctl extends admin_ctl
{
	final public function advertiser_list()
	{
		$this->_get_list(2);
	}

	final public function affiliate_list()
	{
		$this->_get_list(1);
	}

	final public function service_list()
	{
		$this->_get_list(3);
	}

	final public function commercial_list()
	{
		$this->_get_list(4);
	}

	final public function _get_list()
	{
		$arg = func_get_args();
		$get_timerange = $this->get_timerange();
		$user = dr('admin/user.get_list', $arg[0]);
		$group = dr('admin/group.get_all');
		$groupid = request('groupid');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('user', $user);
		TPL::assign('group', $group);
		TPL::assign('groupid', $groupid);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('users');
	}

	final public function k_performance()
	{
		$uid = request('uid');
		$timerange = request('timerange');
		$this->performance($uid, $timerange, 'service');
	}

	final public function s_performance()
	{
		$uid = request('uid');
		$timerange = request('timerange');
		$this->performance($uid, $timerange, 'commercial');
	}

	final public function performance($uid, $timerange, $type)
	{
		if ($type == 'service') {
			$user = dr('admin/user.get_service_user');
		}
		else {
			$user = dr('admin/user.get_commercial_user');
		}

		if (!$uid) {
			$uid = $user[0]['uid'];
		}

		if (!$uid) {
			exit('Uid can\'t be empty');
		}

		$page = APP::adapter('pager', 'default');
		$get_timerange = $this->get_timerange();

		if ($timerange == '') {
			$timerange = $get_timerange['7day'];
		}

		$performance = dr('admin/stats.get_day_performance', $uid, $timerange, $type);

		foreach ((array) $performance as $key => $d) {
			if ($d['day'] == '') {
				unset($performance[$key]);
				continue;
			}

			$day[] = '\'' . $d['day'] . '\'';
			$data[] = abs($d['pay']);
		}

		$day = @array_unique($day);
		@arsort($day);
		$xAxis = @implode(',', $day);
		$series_data = @implode(',', $data);
		TPL::assign('uid', $uid);
		TPL::assign('user', $user);
		TPL::assign('type', $type);
		TPL::assign('timerange', $timerange);
		TPL::assign('xAxis', $xAxis);
		TPL::assign('series_data', $series_data);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('performance', $performance);
		TPL::display('performance');
	}

	final public function edit()
	{
		$serviceuser = dr('admin/user.get_service_user');
		$commercialuser = dr('admin/user.get_commercial_user');
		$group = dr('admin/group.get_all');
		$uid = (int) get('uid');
		$user = dr('admin/user.get_one', $uid);
		$deduction = unserialize($user['deduction']);
		TPL::assign('user', $user);
		TPL::assign('deduction', $deduction);
		TPL::assign('serviceuser', $serviceuser);
		TPL::assign('commercialuser', $commercialuser);
		TPL::assign('group', $group);
		TPL::display('usersedit');
	}

	final public function unlock()
	{
		if (is_post()) {
			$uid = explode(',', post('uid'));

			foreach ($uid as $id) {
				$u = dr('admin/user.get_one', (int) $id);
				$q = dr('admin/user.unlock', (int) $id);
				if (($u['status'] == 0) && in_array('useractivate', explode(',', $GLOBALS['C_ZYIIS']['tomail']))) {
					$body = @file_get_contents(TPL_DIR . '/email/useractivate.html');
					$body = str_replace('{username}', $u['username'], $body);
					Sendmail($u['email'], '', $body);
				}
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$uid = explode(',', post('uid'));

			foreach ($uid as $u) {
				$q = dr('admin/user.lock', (int) $u);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$uid = explode(',', post('uid'));

			foreach ($uid as $u) {
				$q = dr('admin/user.del', (int) $u);
			}
		}
	}

	final public function update_rating()
	{
		if (is_post()) {
			$uid = (int) post('uid');
			$rating = post('rating');
			$q = dr('admin/user.update_rating', $uid, $rating);
		}
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/user.add_post');

			switch (post('type')) {
			case '1':
				$type_text = 'affiliate_list';
				break;

			case '2':
				$type_text = 'advertiser_list';
				break;

			case '3':
				$type_text = 'service_list';
				break;

			case '4':
				$type_text = 'advertiser_list';
				break;
			}

			if ($q == false) {
				$_SESSION['err'] = true;
			}
			else {
				$_SESSION['succ'] = true;
			}

			redirect('admin/user.' . $type_text);
		}
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/user.update_post', (int) post('uid'));
			$_SESSION['succ'] = true;
			redirect('admin/user.edit?uid=' . post('uid'));
		}
	}

	final public function remote_user()
	{
		if (is_post()) {
			$q = dr('admin/user.get_one_username', post('username'));
			$repeat = request('repeat');

			if ($repeat === 'false') {
				if ($q) {
					echo 'true';
				}
				else {
					echo 'false';
				}
			}
			else if ($q) {
				echo 'false';
			}
			else {
				echo 'true';
			}
		}
	}

	final public function glogin()
	{
		$uid = (int) get('uid');
		$url = api('user.set_login_seesion', $uid);
		redirect($url);
	}

	final public function update_group()
	{
		if (is_post()) {
			$uid = post('uid');
			$groupid = post('groupid ');
			dr('admin/user.update_group', $uid, $groupid);
			echo 'ok';
		}
	}

	final public function update_deduction()
	{
		if (is_post()) {
			$uid = post('uid');

			foreach (explode(',', $GLOBALS['C_ZYIIS']['stats_type']) as $t) {
				$deduction[strtoupper($t)] = (int) post($t);
			}

			dr('admin/user.update_deduction', $uid, $deduction);
			echo 'ok';
		}
	}

	final public function update_money()
	{
		if (is_post()) {
			$uid = post('uid');
			$monetype = post('moneytype');
			$money = post('money');
			$olb_money = post('olb_money');
			$payinfo = post('payinfo');
			$user = dr('admin/user.get_one', $uid);
			dr('admin/user.update_' . $monetype, $uid, $money);

			if ($olb_money < $money) {
				$status = 3;
			}
			else {
				$status = 4;
			}

			$imoney = $money - $olb_money;
			$adminuser = $_SESSION['admin']['username'];
			dr('admin/onlinepay.add_pay', $user['username'], $status, $imoney, $payinfo, $adminuser);
			echo 'ok';
		}
	}
}

?>
