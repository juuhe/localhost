<?php

class adtype_mod extends app_models
{
	public $default_from = 'adtype';

	public function get_list()
	{
		$this->order_by('sort', 'ASC');
		$data = $this->get();
		return $data;
	}

	public function get_all()
	{
		return $this->get_list();
	}

	public function get_statstype($type = false)
	{
		if ($type) {
			$this->where('FIND_IN_SET(\'' . $type . '\',statstype)>0');
		}

		$this->where('status', 'y');
		$this->order_by('sort', 'ASC');
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$data = array('statstype' => implode(',', post('statstype')), 'name' => post('name'), 'promotiontype' => post('promotiontype'), 'description' => post('description'), 'sort' => post('sort'), 'time' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('id' => (int) $id);
		$data = array('statstype' => implode(',', post('statstype')), 'name' => post('name'), 'promotiontype' => post('promotiontype'), 'sort' => post('sort'), 'description' => post('description'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 'y');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 'n');
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
}

?>
