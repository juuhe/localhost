<?php

class user_mod extends app_models
{
	public $default_from = 'users';

	public function get_one($uid)
	{
		$this->where('uid', (int) $uid);
		$data = $this->find_one();
		return $data;
	}

	public function get_username_one($username)
	{
		$this->where('username', $username);
		$data = $this->find_one();
		return $data;
	}

	public function post_register($register_status = 0, $money_type = false, $money = 0, $activateid = false, $serviceid = false)
	{
		$data = array('username' => post('username'), 'password' => md5(post('password') . 'zyiis'), 'contact' => post('contact'), 'mobile' => post('mobile'), 'qq' => post('qq'), 'email' => post('email'), 'accountname' => post('accountname'), 'bankname' => post('bankname'), 'bankaccount' => post('bankaccount'), 'bankbranch' => post('bankbranch'), 'recommend' => isset($_COOKIE['c_rid']) ? $_COOKIE['c_rid'] : '', 'serviceid' => (int) $serviceid, 'type' => post('type') == 2 ? 2 : 1, 'activateid' => $activateid, 'regtime' => DATETIMES, 'regip' => get_ip(), 'status' => (int) $register_status);
		if ($money_type && $money) {
			$this->set($money_type, $money_type . '+' . $money, false);
		}

		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_user_login($username)
	{
		$where = array('username' => $username);
		$data = array('loginip' => get_ip(), 'logintime' => DATETIMES);
		$this->where($where);
		$this->set('loginnum', 'loginnum+1', false);
		$this->set($data);
		$data = $this->update();
	}

	public function get_24_regtime($regip)
	{
		$where = array('regip' => $regip, 'HOUR( timediff( now( ) , regtime ) ) <' => 24);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_activateid_username($username, $activateid)
	{
		$where = array('username' => $username, 'activateid' => $activateid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function activate_users($username, $activateid)
	{
		$where = array('username' => $username, 'activateid' => $activateid, 'status' => 1);
		$data = array('status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function login_log($data)
	{
		$this->set($data);
		$this->insert('log_login');
		return true;
	}

	public function get_service_user()
	{
		$where = array('type' => 3, 'status' => 2);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_commercial_user()
	{
		$where = array('type' => 4, 'status' => 2);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_user_top_money($num = 1)
	{
		$this->limit($num);
		$this->select('*,(money+daymoney+weekmoney+monthmoney+xmoney) AS top_money');
		$where = array('type' => 1, 'status' => 2);
		$this->order_by('top_money');
		$this->where($where);
		$data = $this->get();
		return $data;
	}
}

?>
