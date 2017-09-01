<?php

class giftlog_mod extends app_models
{
	public $default_from = 'giftlog';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'username':
				$this->like('username', $search);
				break;

			case 'contact':
				$this->like('contact', $search);
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

	public function get_one($id)
	{
		$where = array('id' => $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function delivery($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 'y');
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
