<?php

class api_cpa_track_mod extends app_models
{
	public $default_from = 'cpa_report';

	public function create($data)
	{
		$g = api('cpa.get_price_priceadv', $data);
		$row_data = array('unique_id' => $data['unique_id'], 'uid' => (int) $data['uid'], 'info' => $data['info'], 'cpastatus' => $data['cpastatus'], 'price' => $g['price'], 'priceadv' => $g['priceadv'], 'planid' => (int) $data['planid'], 'adsid' => (int) $data['adsid'], 'zoneid' => (int) $data['zoneid'], 'siteid' => (int) $data['siteid'], 'status' => $data['valid'] == 'true' ? 1 : 0, 'addtime' => DATETIMES, 'day' => $data['day'] ? $data['day'] : DAYS);
		$this->set($row_data);
		$this->insert();
		return true;
	}

	public function update_status($unique_id, $planid, $cpastatus, $info = false)
	{
		$where = array('unique_id' => $unique_id, 'planid' => (int) $planid);
		$data = array('cpastatus ' => $cpastatus, 'info' => $info);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_union_status($unique_id, $planid)
	{
		$where = array('unique_id' => $unique_id, 'planid' => $planid, 'status' => 0);
		$data = array('status ' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_unique_id_planid_one($unique_id, $planid)
	{
		$where = array('unique_id' => $unique_id, 'planid' => $planid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
