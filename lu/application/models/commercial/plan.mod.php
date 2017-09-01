<?php

class plan_mod extends app_models
{
	public $default_from = 'plan';

	public function get_list()
	{
		$searchval = request('searchval');
		$sortingm = request('sortingm');
		$sortingtype = request('sortingtype');
		$status = request('status');

		if ($searchval) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'planname':
				$this->like('plan.planname', $searchval);
				break;

			case 'planid':
				$where = array('plan.planid' => (int) $searchval);
				$this->where($where);
				break;

			case 'username':
				$this->like('users.username', $searchval);
				break;

			case 'uid':
				$where = array('users.uid' => (int) $searchval);
				$this->where($where);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('plan.status' => (int) $status);
			$this->where($where);
		}

		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'users.type' => 2);
		$this->where($where);
		$this->select('plan.*,users.username,users.status AS userstatus');
		$this->order_by('plan.planid');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$this->where($where);
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_status0_num()
	{
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'plan.status' => 0);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status0_list()
	{
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'plan.status' => 0);
		$this->limit(50);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function unlock($planid)
	{
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$where = array('plan.planid' => (int) $planid, 'users.serviceid' => (int) $_SESSION['commercial']['uid']);
		$data = array('plan.status' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update(array('plan AS plan', 'users AS users'));
	}

	public function get_one($planid)
	{
		$where = array('planid' => (int) $planid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function lock($planid)
	{
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$where = array('plan.planid' => (int) $planid, 'users.serviceid' => (int) $_SESSION['commercial']['uid']);
		$data = array('plan.status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update(array('plan AS plan', 'users AS users'));
	}
}

?>
