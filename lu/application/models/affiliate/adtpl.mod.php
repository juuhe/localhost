<?php

class adtpl_mod extends app_models
{
	public $default_from = 'adtpl';

	public function get_one_adtpl_adtype($id)
	{
		$this->select('adtpl.tpltype,adtpl.tplid,adtpl.tplname,adtpl.customspecs,adtpl.customcolor,adtype.name,adtype.sort');
		$this->from('adtype AS adtype');
		$this->from('adtpl AS adtpl');
		$where = array('adtpl.tplid' => (int) $id);
		$this->where($where);
		$this->where('adtype.id', ' adtpl.adtypeid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}

	public function get_one($id)
	{
		$where = array('tplid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_adtpl_adstyle_specs($tplid)
	{
		$this->select('adstyle.specs');
		$this->from('adstyle AS adstyle');
		$this->from('adtpl AS adtpl');
		$where = array('adtpl.tplid' => (int) $tplid);
		$this->where('adstyle.tplid', ' adtpl.tplid', 'AND', false);
		$this->where($where);
		$data = $this->get();
		return $data;
	}
}

?>
