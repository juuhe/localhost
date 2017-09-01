<?php

class plan_mod extends app_models
{
	public $default_from = 'plan';

	public function get_plantype_ok($plantype = false, $classid = false)
	{
		$this->select('plan.*');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('users.status', '2');
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('users.uid', ' plan.uid', 'AND', false);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		if ($classid) {
			$this->where('plan.classid', (int) $classid);
		}

		$this->group_by('plan.plantype');
		$data = $this->get();
		return $data;
	}

	public function get_ok_plan_no_page($plantype = false, $classid = false)
	{
		return $this->get_plantype_ok_list($plantype, $classid, false);
	}

	public function get_plantype_ok_list($plantype = false, $classid = false, $page = true)
	{
		$this->select('plan.*');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('users.status', '2');
		$this->where("((plan.restrictions=3\r\n\t\t\t\t        && FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0)\r\n\t\t\t\t        OR (plan.restrictions=2 &&\r\n\t \t\t\t\t\tFIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ",plan.resuid)>0)\r\n\t\t\t\t        OR plan.restrictions=1)");
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('users.uid', ' plan.uid', 'AND', false);

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		if ($classid) {
			$this->where('plan.classid', (int) $classid);
		}

		$this->group_by('plan.planid');
		$this->order_by('plan.top Desc,plan.planid');

		if ($page) {
			$this->pager();
		}

		$data = $this->get();
		return $data;
	}

	public function get_plantype_ok_all($plantype = false, $classid = false)
	{
		$this->select('plan.*');
		$this->from('plan AS plan');
		$this->from('users AS users');
		$this->where('users.status', '2');
		$this->where("((plan.restrictions=3\r\n\t\t\t\t        && FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0)\r\n\t\t\t\t        OR (plan.restrictions=2 &&\r\n\t \t\t\t\t\tFIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ",plan.resuid)>0)\r\n\t\t\t\t        OR plan.restrictions=1)");
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('users.uid', ' plan.uid', 'AND', false);

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		if ($classid) {
			$this->where('plan.classid', (int) $classid);
		}

		$this->group_by('plan.planid');
		$this->order_by('plan.planid');
		$data = $this->get();
		return $data;
	}

	public function get_zlink_on($planid = false)
	{
		$this->select('linkurl,planid,planname');
		$this->where('linkon', 'y');
		$data = $this->get();
		return $data;
	}

	public function get_one($id)
	{
		$where = array('planid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
	public function get_clearing($id)
	{
		$where = array('planid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
