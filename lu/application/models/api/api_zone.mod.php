<?php

class api_zone_mod extends app_models
{
	public $default_from = 'zone';

	public function get_one_zone_user($zoneid)
	{
		$this->from('zone AS z');
		$this->from('users AS u');
		$this->select('z.*,u.insite,u.pvstep');
		$this->select('u.status AS userstatus');
		$this->where('z.zoneid', (int) $zoneid);
		$this->where('z.uid', ' u.uid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}
}

?>
