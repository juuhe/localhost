<?php

class api_plan_mod extends app_models
{
	public $default_from = 'plan';
	public $planinfo;

	public function get_one($planid)
	{
		if ($this->planinfo) {
			return $this->planinfo;
		}

		$where = array('planid' => (int) $planid);
		$this->where($where);
		$this->planinfo = $data = $this->find_one();
		return $data;
	}
}

?>
