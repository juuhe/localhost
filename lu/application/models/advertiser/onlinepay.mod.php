<?php

class onlinepay_mod extends app_models
{
	public $default_from = 'onlinepay';

	public function get_list()
	{
	}

	public function create_order($username, $imoney, $paytype, $orderid)
	{
		$data = array('username' => $username, 'imoney' => $imoney, 'paytype' => $paytype, 'orderid' => $orderid, 'adminuser' => $username, 'status' => 0, 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function get_orderid_one($id)
	{
		$where = array('orderid' => $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function update_order($orderid)
	{
		$o = $this->get_orderid_one($orderid);

		if ($o['status'] == 0) {
			$data = array('status' => 2);
			$this->where('orderid', $orderid);
			$this->set($data);
			$data = $this->update();
			$this->where('username', $o['username']);
			$this->set('money', 'money+' . $o['imoney'], false);
			$this->update('users');
		}
	}
}

?>
