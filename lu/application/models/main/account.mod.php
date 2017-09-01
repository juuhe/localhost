<?php

class account_mod extends app_models
{
	public $default_from = 'users';

	public function get_one($uid)
	{
		$where = array('uid' => (int) $uid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function edit_post($uid)
	{
		$where = array('uid' => (int) $uid);
		$data = array('mobile' => post('mobile'), 'qq' => post('qq'), 'email' => post('email'), 'tel' => post('tel'), 'idcard' => post('idcard'), 'contact' => post('contact'), 'bankname' => post('bankname'), 'bankbranch' => post('bankbranch'), 'bankaccount' => post('bankaccount'),'accountname' => post('accountname'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function edit_password($new_password, $uid)
	{
		$where = array('uid' => (int) $uid);
		$data = array('password' => md5($new_password . 'zyiis'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
		return true;
	}

	public function check_old_password($old_password, $uid)
	{
		$password = $this->get_password($uid);
		$old_password = md5($old_password . 'zyiis');

		if ($password == $old_password) {
			return true;
		}

		return false;
	}

	public function get_password($uid)
	{
		$where = array('uid' => (int) $uid);
		$this->select('password');
		$this->where($where);
		$data = $this->find_one();
		return $data['password'];
	}

	public function check_login_password($uid, $olb_password)
	{
		$u = $this->get_one($uid);
		if (!$u || !$olb_password) {
			return false;
		}

		if ($u['password'] != $olb_password) {
			return false;
		}

		return true;
	}

	public function get_sum_recommend($uid)
	{
		$where = array('recommend' => (int) $uid);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_service_qq($type, $serviceid = false)
	{
		$this->select('qq');

		if ($serviceid) {
			$this->where('uid', $serviceid);
			$data = $this->find_one();
		}
		else {
			if ($type == 1) {
				$this->where('type', 3);
			}

			if ($type == 2) {
				$this->where('type', 4);
			}

			$data = $this->get();
			$data = $data[array_rand((array) $data, 1)];
		}

		return $data['qq'];
	}
	public function get_serviceid_qq($serviceid)   //搜索客服QQ
	{

		   $this->select('serviceid');
			$this->where('uid', $serviceid);
			$data = $this->find_one();
		$uid=$data['serviceid'];  //客服ID
		//return $uid;
		$this->select('qq');
		$this->where('uid', $uid);
		$data = $this->find_one();
		return $data['qq'];
	}

	public function update_integral($username, $type = '-', $integral)
	{
		$where = array('username' => $username);
		$this->set('integral', 'integral' . $type . $integral, false);
		$this->update('users');
	}
}

?>
