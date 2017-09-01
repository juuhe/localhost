<?php

class zone_mod extends app_models
{
	public $default_from = 'zone';

	public function get_one($zoneid)
	{
		$this->select('z.adtplid,z.zoneid,z.adstyleid,z.plantype,z.width,z.height,z.viewtype,z.viewadsid,z.uid,z.codestyle,u.status AS userstatus');
		$this->from('zone AS z');
		$this->from('users AS u');
		$this->where('u.uid', ' z.uid', 'AND', false);
		$this->where('z.zoneid', $zoneid);
		$data = $this->find_one();
		return $data;
	}
}

?>
