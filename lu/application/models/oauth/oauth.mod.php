<?php

class oauth_mod extends app_models
{
	public $default_from = 'oauth';

	public function get_one($type, $openid)
	{
		$this->select('uid');
		$where = array('type' => $type, 'openid' => $openid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function add_data($uid, $type, $openid)
	{
		$data = array('uid' => (int) $uid, 'type' => $type, 'openid' => $openid);
		$this->set($data);
		$this->insert();
		return true;
	}
}

?>
