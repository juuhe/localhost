<?php

class syslog_mod extends app_models
{
	public $default_from = 'syslog';

	public function get_list()
	{
		$search = request('search');
		$type = request('type');

		if ($search) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'username':
				$this->like('username', $search);
				break;

			case 'ip':
				$this->like('ip', $search);
				break;
			}
		}

		if ($type) {
			$where = array('action' => $type);
			$this->where($where);
		}

		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$p = $_POST;

		if ($p['password']) {
			$p['password'] = '***';
		}

		$data = array('username' => $_SESSION['admin']['username'], 'controller' => RUN_CONTROLLER_CLASS, 'action' => RUN_ACTION, 'content' => var_export($p, true), 'ip' => get_ip(), 'time' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}
}

?>
