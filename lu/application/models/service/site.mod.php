<?php

class site_mod extends app_models
{
	public $default_from = 'site';

	public function get_list()
	{
		$searchval = request('searchval');
		$sortingm = request('sortingm');
		$sortingtype = request('sortingtype');
		$status = request('status');

		if ($searchval) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'sitename':
				$this->like('site.sitename', $searchval);
				break;

			case 'siteid':
				$this->like('site.siteid', $searchval);
				break;

			case 'username':
				$this->like('users.username', $searchval);
				break;

			case 'uid':
				$where = array('site.uid' => (int) $searchval);
				$this->where($where);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('site.status' => (int) $status);
			$this->where($where);
		}

		$where = array('users.serviceid' => (int) $_SESSION['service']['uid'], 'users.type' => 1);
		$this->where($where);
		if ($sortingtype && $sortingm) {
			$this->order_by($sortingtype, $sortingm);
		}
		else {
			$this->order_by('site.siteid');
		}

		$this->select('site.*,users.username');
		$this->from('site AS site');
		$this->from('users AS users');
		$this->where('site.uid', ' users.uid', 'AND', false);
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_status0_num()
	{
		$this->from('site AS site');
		$this->from('users AS users');
		$this->where('site.uid', ' users.uid', 'AND', false);
		$where = array('users.serviceid' => (int) $_SESSION['service']['uid'], 'site.status' => 0);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status0_list()
	{
		$this->from('site AS site');
		$this->from('users AS users');
		$this->where('site.uid', ' users.uid', 'AND', false);
		$where = array('users.serviceid' => (int) $_SESSION['service']['uid'], 'site.status' => 0);
		$this->limit(50);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function unlock($siteid)
	{
		$this->where('site.uid', ' users.uid', 'AND', false);
		$where = array('site.siteid' => (int) $siteid, 'users.serviceid' => (int) $_SESSION['service']['uid']);
		$data = array('site.status' => 3);
		$this->where($where);
		$this->set($data);
		$data = $this->update(array('site AS site', 'users AS users'));
	}

	public function lock($siteid)
	{
		$this->where('site.uid', ' users.uid', 'AND', false);
		$where = array('site.siteid' => (int) $siteid, 'users.serviceid' => (int) $_SESSION['service']['uid']);
		$data = array('site.status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update(array('site AS site', 'users AS users'));
	}
}

?>
