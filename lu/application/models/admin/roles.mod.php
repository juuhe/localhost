<?php

class roles_mod extends app_models
{
	public $default_from = 'roles';

	public function get_list()
	{
		$this->order_by('id');
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$data = array('name' => post('name'), 'action' => serialize(post('acl')));
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('id' => (int) $id);
		$data = array('name' => post('name'), 'action' => serialize(post('acl')));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}

	public function get_one($id)
	{
		$where = array('id' => $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
