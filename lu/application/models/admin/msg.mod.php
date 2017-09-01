<?php

class msg_mod extends app_models
{
	public $default_from = 'msg';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');
		$type = request('type');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'title':
				$this->like('title', $search);
				break;
			}
		}

		if (is_numeric($type)) {
			$where = array('type' => (int) $type);
			$this->where($where);
		}

		if ($status) {
			$where = array('status' => $status);
			$this->where($where);
		}

		$this->order_by('messageid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$data = array('type' => (int) post('type'), 'color' => post('color'), 'content' => post('content', '', false), 'title' => post('title'), 'status' => '', 'rid1' => '', 'rid2' => '', 'rid3' => '', 'rid4' => '', 'rid5' => '', 'rid6' => '', 'rid7' => '', 'rid8' => '', 'rid9' => '', 'rid0' => '', 'sendusername' => $_SESSION['admin']['username'], 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($messageid)
	{
		$where = array('messageid' => (int) $messageid);
		$data = array('type' => (int) post('type'), 'color' => post('color'), 'content' => post('content', '', false), 'title' => post('title'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($messageid)
	{
		$where = array('messageid' => (int) $messageid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($messageid)
	{
		$where = array('messageid' => (int) $messageid);
		$data = array('status' => 'y');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($messageid)
	{
		$where = array('messageid' => (int) $messageid);
		$data = array('status' => 'n');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($messageid)
	{
		$where = array('messageid' => (int) $messageid);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
