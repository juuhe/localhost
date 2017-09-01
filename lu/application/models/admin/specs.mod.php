<?php

class specs_mod extends app_models
{
	public $default_from = 'specs';

	public function get_list()
	{
		$this->order_by('width');
		$data = $this->get();
		return $data;
	}

	public function get_all_ok()
	{
		$where = array('status' => 'y');
		$this->order_by('sort,width');
		$data = $this->get();
		return $data;
	}

	public function get_width_height($width, $height)
	{
		$where = array('width' => (int) $width, 'height' => (int) $height);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function add_post()
	{
		$data = array('width' => (int) post('width'), 'height' => (int) post('height'), 'sort' => (int) post('sort'));
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('id' => (int) $id);
		$data = array('width' => (int) post('width'), 'height' => (int) post('height'), 'sort' => (int) post('sort'));
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

	public function del($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
