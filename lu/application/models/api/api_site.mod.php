<?php

class api_site_mod extends app_models
{
	public $default_from = 'site';

	public function get_one($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_uid_siteok($uid)
	{
		$where = array('uid' => (int) $uid, 'status' => 3);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_site_apply_ok($uid)
	{
		$this->select('planid');
		$where = array('uid' => (int) $uid, 'status' => 2);
		$this->where($where);
		$data = $this->get('apply');
		return $data;
	}
}

?>
