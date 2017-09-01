<?php

class group_mod extends app_models
{
	public $default_from = 'group';

	public function get_list()
	{
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$data = array('groupname' => post('groupname'));
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($groupid)
	{
		$where = array('groupid' => (int) $groupid);
		$data = array('groupname' => post('groupname'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($groupid)
	{
		$where = array('groupid' => $groupid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all()
	{
		$data = $this->get();
		return $data;
	}

	public function del($groupid)
	{
		$where = array('groupid' => (int) $groupid);
		$this->where($where);
		$data = $this->delete();
	}

	public function get_sum_groupid($groupid)
	{
		$where = array('groupid' => (int) $groupid);
		$this->where($where);
		$data = $this->find_count('users');
		$this->ar_where = array();
		return $data;
	}
}

?>
