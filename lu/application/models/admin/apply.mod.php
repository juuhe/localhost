<?php

class apply_mod extends app_models
{
	public $default_from = 'apply';

	public function get_list($status = false, $search = false, $searchtype = false)
	{
		$this->select('apply.*,users.username,plan.planname');
		$this->from('apply AS apply');
		$this->from('users AS users');
		$this->from('plan AS plan');
		$this->where('users.uid', ' apply.uid', 'AND', false);
		$this->where('apply.planid', ' plan.planid', 'AND', false);

		if ($search) {
			switch ($searchtype) {
			case 'planid':
				$this->where('plan.planid', (int) $search);
				break;

			case 'uid':
				$this->where('users.uid', (int) $search);
				break;

			case 'username':
				$this->where('users.username', $search);
				break;
			}
		}

		if (is_numeric($status)) {
			if ($status == 0) {
				$this->where('apply.status', 0);
				$this->where('apply.status', 5, 'OR', false);
			}
			else {
				$where = array('apply.status' => (int) $status);
				$this->where($where);
			}
		}

		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 2, 'denyinfo' => post('denyinfo'), 'approvaluser' => $_SESSION['admin_username'], 'approvaltime' => DATETIMES);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 1, 'approvaluser' => $_SESSION['admin_username'], 'approvaltime' => DATETIMES, 'denyinfo' => post('denyinfo'));
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
