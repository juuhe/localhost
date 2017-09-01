<?php

class api_adstyle_mod extends app_models
{
	public $default_from = 'adstyle';

	public function get_one($id)
	{
		$where = array('styleid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_tplid_specs($id)
	{
		$this->where('tplid', $id);
		$data = $this->find_one();
		return $data;
	}

	public function get_tplid_specs_all($id)
	{
		$this->where('tplid', $id);
		$data = $this->get();
		return $data;
	}
}

?>
