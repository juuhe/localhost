<?php

class site_mod extends app_models
{
	public $default_from = 'site';

	public function get_one($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_list_ok($uid)
	{
		$this->where(array('status' => 3, 'uid' => (int) $uid));
		$data = $this->get();
		return $data;
	}
}

?>
