<?php

class api_stats_mod extends app_models
{
	public $default_from = 'stats';

	public function update_stats($data)
	{
		$stats_data = array('num' => (int) $data['num'], 'views' => (int) $data['views'], 'deduction' => (int) $data['deduction'], 'day' => $data['day'] ? $data['day'] : DAYS, 'planid' => (int) $data['planid'], 'adsid' => (int) $data['adsid'], 'zoneid' => (int) $data['zoneid'], 'plantype' => $data['plantype'], 'siteid' => (int) $data['siteid'], 'uid' => (int) $data['uid'], 'adtplid' => (int) $data['adtplid'], 'zuid' => (int) $data['zuid'], 'advuid' => (int) $data['advuid'], 'sumpay' => floatval($data['sumpay']), 'sumprofit' => floatval($data['sumprofit']), 'sumadvpay' => floatval($data['sumadvpay']));
		$stats_updata = array('num' => (int) $data['num'], 'views' => (int) $data['views'], 'deduction' => (int) $data['deduction'], 'sumpay' => floatval($data['sumpay']), 'sumprofit' => floatval($data['sumprofit']), 'sumadvpay' => floatval($data['sumadvpay']));
		$this->insert_update('stats', $stats_data, $stats_updata);
	}

	public function get_day_data($data)
	{
		$where = array('uid' => (int) $data['uid'], 'planid' => (int) $data['planid'], 'zoneid' => (int) $data['zoneid'], 'adsid' => (int) $data['adsid'], 'day' => $data['day'] ? $data['day'] : DAYS);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
