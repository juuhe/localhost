<?php

class loginlog_mod extends app_models
{
	public $default_from = 'log_login';

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

			case 'ip':
				$this->like('ip', $search);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('status' => (int) $status);
			$this->where($where);
		}

		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function del($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}

	public function create_login_log($username, $ip, $time, $status)
	{
		$data = array('username' => $username, 'ip' => $ip, 'type' => 0, 'time' => $time, 'status' => $status);
		$this->set($data);
		$this->insert();
	}
}

?>
