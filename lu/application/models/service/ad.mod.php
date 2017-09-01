<?php

class ad_mod extends app_models
{
	public $default_from = 'ads';

	public function get_one($id)
	{
		$where = array('adsid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
