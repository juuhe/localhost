<?php

class api_user_mod extends app_models
{
	public $default_from = 'users';

	public function get_one($uid)
	{
		$where = array('uid' => (int) $uid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_one_username($username)
	{
		$where = array('username' => $username);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function update_money_type($uid, $money, $type = '+', $moneytype = NULL)
	{
		$moneytype = $moneytype . 'money';
		$this->set($moneytype, $moneytype . $type . $money, false);
		$this->where('uid', (int) $uid);
		$data = $this->update();
	}

	public function update_password($new_password, $uid)
	{
		$where = array('uid' => (int) $uid);
		$data = array('password' => md5($new_password . 'zyiis'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
		return true;
	}
}

?>
