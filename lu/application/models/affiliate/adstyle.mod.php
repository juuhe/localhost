<?php

class adstyle_mod extends app_models
{
	public $default_from = 'adstyle';

	public function get_adstyle($tplid, $specs = false)
	{
		$this->select('styleid,stylename,htmlcontrol');
		$this->where('tplid', $tplid);

		if ($specs != '0x0') {
			$this->where('FIND_IN_SET(\'' . $specs . '\',specs)>0');
		}

		$data = $this->get();
		return $data;
	}

	public function get_adstyle_one($styleid)
	{
		$this->where('styleid', (int) $styleid);
		$data = $this->find_one();
		return $data;
	}
}

?>
