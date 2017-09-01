<?php

class gift_mod extends app_models
{
	public $default_from = 'gift';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'name':
				$this->like('name', $search);
				break;
			}
		}

		if ($status) {
			$where = array('status' => $status);
			$this->where($where);
		}

		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post($imageurl)
	{
		$data = array('type' => (int) post('type'), 'integral' => post('integral'), 'content' => post('content'), 'name' => post('name'), 'status' => 1, 'top' => (int) post('top'), 'imageurl' => $imageurl, 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id, $imageurl = false)
	{
		$where = array('id' => (int) $id);
		$data = array('type' => (int) post('type'), 'integral' => post('integral'), 'content' => post('content'), 'name' => post('name'), 'top' => (int) post('top'));

		if ($imageurl) {
			$data['imageurl'] = $imageurl;
		}

		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('id' => $id);
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
