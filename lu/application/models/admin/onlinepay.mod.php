<?php

class onlinepay_mod extends app_models
{
	public $default_from = 'onlinepay';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');
		$paytype = request('paytype');

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

			case 'orderid':
				$where = array('pay.orderid' => $search);
				$this->where($where);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('pay.status' => (int) $status);
			$this->where($where);
		}

		if ($paytype) {
			$where = array('pay.paytype' => $paytype);
			$this->where($where);
		}

		$this->select('pay.*,users.username,users.uid');
		$this->from('onlinepay AS pay');
		$this->from('users AS users');
		$this->where('pay.username', ' users.username', 'AND', false);
		$this->order_by('pay.onlineid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_pay($username, $status, $imoney, $payinfo, $adminuser)
	{
		$data = array('username' => $username, 'status' => $status, 'imoney' => $imoney, 'payinfo' => $payinfo, 'paytype' => 'sd', 'adminuser' => $adminuser, 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert('onlinepay');
	}

	public function post_add_pay()
	{
		$u = dr('admin/user.get_one_username', post('username'));

		if ($u['type'] == '1') {
			$moneytype = post('clearing') . 'money';
		}
		else {
			$moneytype = 'money';
		}

		$data = array('username' => post('username'), 'status' => post('status'), 'imoney' => post('imoney'), 'payinfo' => post('payinfo'), 'orderid' => post('clearing'), 'paytype' => 'sd', 'adminuser' => $_SESSION['admin']['username'], 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert('onlinepay');

		if (post('status') == '3') {
			$this->where('username', post('username'));

			if (post('clearing') == 'integral') {
				$this->set('integral', 'integral+' . post('imoney'), false);
			}
			else {
				$this->set($moneytype, $moneytype . '+' . post('imoney'), false);
			}

			$this->update('users');
		}

		if (post('status') == '4') {
			if (($u['integral'] < post('imoney')) && ($moneytype == 'integral')) {
				return false;
			}

			$this->where('username', post('username'));

			if (post('clearing') == 'integral') {
				$this->set('integral', 'integral-' . post('imoney'), false);
			}
			else {
				$this->set($moneytype, $moneytype . '-' . post('imoney'), false);
			}

			$this->update('users');
		}
	}
}

?>
