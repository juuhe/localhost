<?php

class user_mod extends app_models
{
	public $default_from = 'users';

	public function get_list($type)
	{
		$search = request('search');
		$status = request('status');
		$groupid = request('groupid');

		if ($search) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'username':
				$this->like('username', $search);
				break;

			case 'uid':
				$where = array('uid' => (int) $search);
				$this->where($where);
				break;

			case 'qq':
				$this->like('qq', $search);
				break;

			case 'serviceid':
				$where = array('serviceid' => (int) $search);
				$this->where($where);
				break;

			case 'bankname':
				$this->like('bankname', $search);
				break;

			case 'recommend':
				$where = array('recommend' => (int) $search);
				$this->where($where);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('status' => (int) $status);
			$this->where($where);
		}

		if (is_numeric($groupid)) {
			$where = array('groupid' => (int) $groupid);
			$this->where($where);
		}

		$where = array('type' => (int) $type);

		if ($type == 1) {
			$this->select('*,(money+daymoney+weekmoney+monthmoney+xmoney) AS money');
		}

		$this->where($where)->order_by('uid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_one($uid)
	{
		$uid = (int) $uid;
		$where = array('uid' => $uid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_one_all($uid)
	{
		$uid = (int) $uid;
		$where = array('uid' => $uid);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_recommend_one($uid)
	{
		$this->select('recommend');
		$where = array('uid' => $uid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_one_username($username)
	{
		$where = array('username' => strtolower($username));
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all_type($type)
	{
		$where = array('type' => (int) $type);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_advertiser_ok()
	{
		$where = array('type' => 2, 'status' => 2, 'money >' => 1);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function unlock($uid)
	{
		$where = array('uid' => (int) $uid);
		$data = array('status' => 2, 'memo' => post('log_text'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($uid)
	{
		$where = array('uid' => (int) $uid);
		$data = array('status' => 4, 'memo' => post('log_text'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($uid)
	{
		$where = array('uid' => (int) $uid);
		$this->where($where);
		$data = $this->delete();
	}

	public function update_rating($uid, $rating)
	{
		$where = array('uid' => (int) $uid);
		$data = array('rating' => $rating);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_daymoney($uid, $money)
	{
		$where = array('uid' => (int) $uid);
		$data = array('daymoney' => $money);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_weekmoney($uid, $money)
	{
		$where = array('uid' => (int) $uid);
		$data = array('weekmoney' => $money);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_monthmoney($uid, $money)
	{
		$where = array('uid' => (int) $uid);
		$data = array('monthmoney' => $money);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_xmoney($uid, $money)
	{
		$where = array('uid' => (int) $uid);
		$data = array('xmoney' => $money);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_money($uid, $money)
	{
		$where = array('uid' => (int) $uid);
		$data = array('money' => $money);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_deduction($uid, $deduction)
	{
		$where = array('uid' => (int) $uid);
		$data = array('deduction' => serialize($deduction));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_group($uid, $groupid)
	{
		$where = array('uid' => (int) $uid);
		$data = array('groupid' => $groupid);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function add_post()
	{
		$username = strtolower(post('username'));
		$u = $this->get_one_username($username);

		if ($u == true) {
			return false;
		}

		$data = array('type' => (int) post('type'), 'username' => $username, 'password' => md5(post('password') . 'zyiis'), 'contact' => post('contact'), 'qq' => post('qq'), 'status' => 2);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('uid' => (int) $id);
		$data = array('deduction' => serialize(post('deduction')), 'usercommission' => post('usercommission'), 'commissiontime' => post('commissiontime'), 'memo' => post('memo'), 'email' => post('email'), 'contact' => post('contact'), 'qq' => post('qq'), 'mobile' => post('mobile'), 'idcard' => post('idcard'), 'bankname' => post('bankname'), 'bankbranch' => post('bankbranch'), 'accountname' => post('accountname'), 'bankaccount' => post('bankaccount'), 'recommend' => post('recommend'), 'insite' => post('insite'), 'pvstep' => post('pvstep'), 'recpm' => post('recpm'), 'recpmtime' => post('recpmtime'), 'groupid' => post('groupid'), 'serviceid' => post('serviceid'));

		if (post('pass')) {
			$data['password'] = md5(post('pass') . 'zyiis');
		}

		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_sum_recommend($uid)
	{
		$where = array('recommend' => (int) $uid);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_sum_service($uid)
	{
		$where = array('serviceid' => (int) $uid);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_service_user()
	{
		$where = array('type' => 3);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_commercial_user()
	{
		$where = array('type' => 4);
		$this->where($where);
		$data = $this->get();
		return $data;
	}
}

?>
