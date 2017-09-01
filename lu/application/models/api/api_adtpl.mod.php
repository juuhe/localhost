<?php

class api_adtpl_mod extends app_models
{
	public $default_from = 'adtpl';

	public function get_one($tplid)
	{
		$where = array('tplid' => (int) $tplid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
