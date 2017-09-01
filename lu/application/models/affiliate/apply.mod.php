<?php

class apply_mod extends app_models
{
	public $default_from = 'apply';

	public function post_apply()
	{
		$get = $this->get_apply_status($_SESSION['affiliate']['uid'], (int) post('planid'));

		if ($get) {
			return NULL;
		}

		$data = array('uid' => $_SESSION['affiliate']['uid'], 'siteid' => post('siteid'), 'planid' => (int) post('planid'), 'addtime' => DATETIMES, 'applysiteidtype' => (int) post('applysiteidtype'), 'status' => 0);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function get_apply_status($uid, $planid)
	{
		$where = array('uid' => (int) $uid, 'planid' => (int) $planid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
