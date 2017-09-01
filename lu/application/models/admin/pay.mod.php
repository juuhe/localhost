<?php

class pay_mod extends app_models
{
	public $default_from = 'paylog';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');
		$bank = request('bank');

		if ($search) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'username':
				$this->like('users.username', $search);
				break;

			case 'uid':
				$where = array('users.uid' => (int) $search);
				$this->where($where);
				break;

			case 'bankaccount':
				$where = array('pay.bankaccount' => $search);
				$this->where($where);
				break;

			case 'bankname':
				$where = array('pay.bankname' => $search);
				$this->where($where);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('pay.status' => (int) $status);
			$this->where($where);
		}

		if ($bank) {
			$where = array('users.bankname' => $bank);
			$this->where($where);
		}

		$this->select('pay.*,users.username,users.uid,users.bankbranch,users.bankname,users.bankaccount,users.accountname');
		$this->from('paylog AS pay');
		$this->join('users AS users', 'users.uid = pay.uid ', 'left');

		if ($status == 1) {
			$this->order_by('pay.paytime');
		}
		else {
			$this->order_by('pay.status,users.bankname,pay.id');
		}

		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_do_excel_bankname($day = false, $status = false)
	{
		if (is_numeric($status)) {
			$where = array('pay.status' => (int) $status);
			$this->where($where);
		}

		if ($day) {
			$where = array('pay.addtime' => $day);
			$this->where($where);
		}

		$this->select('users.bankname');
		$this->from('paylog AS pay');
		$this->join('users AS users', 'users.uid = pay.uid ', 'left');
		$this->group_by('users.bankname');
		$data = $this->get();
		return $data;
	}

	public function get_do_excel_bankname_data($bankname, $day = false, $status = false)
	{
		if (is_numeric($status)) {
			$where = array('pay.status' => (int) $status);
			$this->where($where);
		}

		if ($day) {
			$where = array('pay.addtime' => $day);
			$this->where($where);
		}

		$this->where('users.bankname', $bankname);
		$this->select("users.username,users.uid,users.bankbranch,\r\n\t\t\t\tusers.bankname,users.bankaccount,users.accountname,\r\n\t\t\t\tpay.pay,pay.addtime,pay.status");
		$this->from('paylog AS pay');
		$this->join('users AS users', 'users.uid = pay.uid ', 'left');
		$data = $this->get();
		return $data;
	}

	public function post_payment($id)
	{
		$where = array('id' => $id, 'status' => 0);
		$this->where($where);
		$get_pay = $this->find_one();

		if ($get_pay) {
			$data = array('status' => 1, 'clearingadmin' => $_SESSION['admin']['username'], 'paytime' => DATETIMES);
			$this->where('id', (int) $id);
			$this->set($data);
			$data = $this->update();
			$recommendTc = $GLOBALS['C_ZYIIS']['recommend_tc'] / 100;
			$recommendTc = (0 < $get_pay['pay'] ? round($get_pay['pay'] * $recommendTc, 3) : 0);
			$u = dr('admin/user.get_recommend_one', $get_pay['uid']);

			if ($u['recommend']) {
				$this->where('uid', $u['recommend']);
				$this->set('xmoney', 'xmoney+' . $recommendTc, false);
				$data = $this->update('users');
			}

			if ($GLOBALS['C_ZYIIS']['integral_status'] == '1') {
				$pv_day = dr('admin/stats.get_time_day_uid_pv', $get_pay['uid'], $get_pay['addtime']);
				$integralnum = ($get_pay['pay'] * $GLOBALS['C_ZYIIS']['integral_topay']) + ($pv_day * $GLOBALS['C_ZYIIS']['integral_day']);
				$this->where('uid', $get_pay['uid']);
				$this->set('integral', 'integral+' . $integralnum, false);
				$data = $this->update('users');
			}
		}

		return $get_pay;
	}

	public function get_payid_username($id)
	{
		$this->where('p.id', $id);
		$this->select('u.username,u.email');
		$this->from('paylog AS p');
		$this->from('users AS u');
		$this->where('p.uid', ' u.uid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}

	public function get_sum_status0()
	{
		$this->select('sum(pay) AS pay');
		$this->where('status', 0);
		$data = $this->find_one();
		return $data;
	}

	public function get_pay_date($pager = false)
	{
		$this->select('addtime');
		$this->group_by('addtime');
		$this->order_by('addtime');

		if ($pager) {
			$this->pager();
		}

		$data = $this->get();
		return $data;
	}

	public function get_date_bankname_sumpay($date)
	{
		$this->select('sum(p.pay) AS pay ,u.bankname as bankname ');
		$this->from('paylog AS p');
		$this->join('users AS u', 'u.uid = p.uid ', 'left');
		$this->where('p.addtime', $date);
		$this->group_by('u.bankname');
		$data = $this->get();
		return $data;
	}

	public function get_bankname_sumpay()
	{
		$this->select('sum(p.pay) AS pay ,u.bankname as bankname ');
		$this->from('paylog AS p');
		$this->join('users AS u', 'u.uid = p.uid ', 'left');
		$this->where('p.status', 0);
		$this->group_by('u.bankname');
		$data = $this->get();
		return $data;
	}

	public function get_sum_uid($uid)
	{
		$this->select('sum(pay) AS pay');
		$this->where('uid', (int) $uid);
		$this->where('status', 1);
		$data = $this->find_one();
		return $data['pay'];
	}

	public function del($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
