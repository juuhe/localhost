<?php

class class_mod extends app_models
{
	public $default_from = 'class';

	public function get_list($type = false)
	{
		if ($type) {
			$this->where('type', (int) $type);
		}

		$this->order_by('classid');
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$data = array('classname' => post('classname'), 'type' => (int) post('type'));
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($classid)
	{
		$where = array('classid' => (int) $classid);
		$data = array('classname' => post('classname'), 'type' => (int) post('type'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($classid)
	{
		$where = array('classid' => $classid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all($type = false)
	{
		if ($type) {
			$this->where('type', (int) $type);
		}

		$this->order_by('classid');
		$data = $this->get();
		return $data;
	}

	public function del($classid)
	{
		$where = array('classid' => (int) $classid);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
