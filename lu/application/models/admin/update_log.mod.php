<?php

class update_log_mod extends app_models
{
	public $default_from = 'update_log';

	public function add($adsid, $planid, $username, $old_data, $new_data, $diff)
	{
		$data = array('adsid' => $adsid, 'planid' => $planid, 'username' => $username, 'old_data' => serialize($old_data), 'new_data' => serialize($new_data), 'diff' => serialize($diff), 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function get_data($adsid = false, $planid = false, $num = false)
	{
		if ($adsid) {
			$this->where('adsid', $adsid);
		}

		if ($planid) {
			$this->where('adsid', $planid);
		}

		if ($num) {
			$this->limit((int) $num);
		}

		$this->order_by('id');
		$data = $this->get();
		return $data;
	}
}

?>
