<?php

class users_mod extends app_models
{
	public $default_from = 'users';

	public function get_list()
	{
		$searchval = request('searchval');
		$sortingm = request('sortingm');
		$sortingtype = request('sortingtype');
		$status = request('status');

		if ($searchval) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'username':
				$this->like('username', $searchval);
				break;

			case 'uid':
				$where = array('uid' => (int) $searchval);
				$this->where($where);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('status' => (int) $status);
			$this->where($where);
		}

		$where = array('serviceid' => (int) $_SESSION['commercial']['uid'], 'type' => 2);
		if ($sortingtype && $sortingm) {
			$this->order_by($sortingtype, $sortingm);
		}
		else {
			$this->order_by('uid');
		}

		$this->where($where);
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_day_register_num()
	{
		$where = array('serviceid' => (int) $_SESSION['commercial']['uid'], 'date_format(regtime,\'%Y-%m-%d\')' => DAYS, 'type' => 2);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status0_num()
	{
		$where = array('serviceid' => (int) $_SESSION['commercial']['uid'], 'status' => 0, 'type' => 2);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status0_list()
	{
		$where = array('serviceid' => (int) $_SESSION['commercial']['uid'], 'status' => 0, 'type' => 2);
		$this->limit(50);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function unlock($uid)
	{
		$where = array('uid' => (int) $uid, 'serviceid' => (int) $_SESSION['commercial']['uid']);
		$data = array('status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($uid)
	{
		$where = array('uid' => (int) $uid, 'serviceid' => (int) $_SESSION['commercial']['uid']);
		$data = array('status' => 4);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}
}

?>
