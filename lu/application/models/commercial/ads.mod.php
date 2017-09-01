<?php

class ads_mod extends app_models
{
	public $default_from = 'ads';

	public function get_list()
	{
		$searchval = request('searchval');
		$sortingm = request('sortingm');
		$sortingtype = request('sortingtype');
		$status = request('status');

		if ($searchval) {
			$searchtype = request('searchtype');

			switch ($searchtype) {
			case 'adsid':
				$this->like('ads.adsid', $searchval);
				break;

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
			$where = array('ads.status' => (int) $status);
			$this->where($where);
		}

		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'users.type' => 2);
		$this->where($where);
		$this->select('ads.*,users.username,plan.plantype,plan.planname');
		$this->order_by('plan.planid');
		$this->from('ads AS ads');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$this->where($where);
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_one($adsid)
	{
		$where = array('adsid' => (int) $adsid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_ad_plan_one($adsid)
	{
		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'ads.adsid' => (int) $adsid);
		$this->where($where);
		$this->select('ads.*,users.username,plan.plantype,plan.planname');
		$this->order_by('plan.planid');
		$this->from('ads AS ads');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_status0_num()
	{
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->from('ads AS ads');
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'ads.status' => 0);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status0_list()
	{
		$where = array('users.serviceid' => (int) $_SESSION['commercial']['uid'], 'users.type' => 2, 'ads.status' => 0);
		$this->where($where);
		$this->select('ads.*,users.username,plan.plantype,plan.planname');
		$this->order_by('plan.planid');
		$this->from('ads AS ads');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('plan.uid', ' users.uid', 'AND', false);
		$this->where($where);
		$this->limit(50);
		$data = $this->get();
		return $data;
	}

	public function unlock($adsid)
	{
		$this->where('ads.uid', ' users.uid', 'AND', false);
		$where = array('ads.adsid' => (int) $adsid, 'users.serviceid' => (int) $_SESSION['commercial']['uid']);
		$data = array('ads.status' => 3);
		$this->where($where);
		$this->set($data);
		$data = $this->update(array('ads AS ads', 'users AS users'));
	}

	public function lock($adsid)
	{
		$this->where('ads.uid', ' users.uid', 'AND', false);
		$where = array('ads.adsid' => (int) $adsid, 'users.serviceid' => (int) $_SESSION['commercial']['uid']);
		$data = array('ads.status' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update(array('ads AS ads', 'users AS users'));
	}
}

?>
