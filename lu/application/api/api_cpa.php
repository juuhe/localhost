<?php

class api_cpa
{
	static public function create($data)
	{
		$u = dr('api/api_user.get_one', (int) $data['uid']);

		if (!$u) {
			exit('NOT UID	' . $data['uid']);
		}

		$p = dr('api/api_plan.get_one', $data['planid']);

		if (!$p) {
			exit('NOT PID	' . $data['planid']);
		}

		$g = dr('api/api_cpa_track.get_unique_id_planid_one', $data['unique_id'], $data['planid']);

		if ($g) {
			return NULL;
		}

		$c = dr('api/api_cpa_track.create', $data);
		if (($data['valid'] == 'true') && $c) {
			api_cpa::update_union_status($data['unique_id'], $data['planid']);
			api_cpa::update_satas_AND_update_user_money_data($data['unique_id'], $data['planid']);
		}

		return true;
	}

	static public function update($data)
	{
		if (!$data['uid'] || !$data['planid'] || !$data['unique_id'] || !$data['day']) {
			exit('NOT upucd');
		}

		dr('api/api_cpa_track.update_status', $data['unique_id'], $data['planid'], $data['cpastatus']);

		if ($data['valid'] == 'true') {
			$g = dr('api/api_cpa_track.get_unique_id_planid_one', $data['unique_id'], $data['planid']);

			if ($g['status'] == 0) {
				api_cpa::update_union_status($data['unique_id'], $data['planid']);
				api_cpa::update_satas_AND_update_user_money_data($data['unique_id'], $data['planid']);
			}
		}
	}

	static public function update_satas_AND_update_user_money_data($unique_id, $planid)
	{
		if (!$planid || !$unique_id) {
			exit('update_satas_AND_update_user_money_data NOT planid  unique_id');
		}

		api_cpa::update_satas($unique_id, $planid);
		api_cpa::update_user_money_data($unique_id, $planid);
	}

	static public function update_union_status($unique_id, $planid)
	{
		if (!$planid || !$unique_id) {
			exit('update_union_status NOT planid  unique_id');
		}

		dr('api/api_cpa_track.update_union_status', $unique_id, $planid);
	}

	static public function update_user_money_data($unique_id, $planid)
	{
		if (!$unique_id || !$planid) {
			exit('update_user_money_data NOT "unique_id,planid"');
		}

		$p = dr('api/api_plan.get_one', $planid);
		$g = dr('api/api_cpa_track.get_unique_id_planid_one', $unique_id, $planid);
		dr('api/api_user.update_money_type', $g['uid'], $g['price'], '+', $p['clearing']);
		dr('api/api_user.update_money_type', $p['uid'], $g['priceadv'], '-');
	}

	static public function update_satas($unique_id, $planid)
	{
		$p = dr('api/api_plan.get_one', $planid);
		$g = dr('api/api_cpa_track.get_unique_id_planid_one', $unique_id, $planid);
		$row_data = array('planid' => $planid, 'uid' => $g['uid'], 'advuid' => $p['uid'], 'adsid' => $g['adsid'], 'zoneid' => $g['zoneid'], 'siteid' => $g['siteid'], 'plantype' => $p['plantype'], 'day' => $g['day'] ? $g['day'] : DAYS, 'num' => $g['num'] ? $g['num'] : 1, 'sumpay' => $g['price'], 'sumadvpay' => $g['priceadv'], 'sumprofit' => $g['priceadv'] - $g['price']);
		dr('api/api_stats.update_stats', $row_data);
	}

	static public function get_price_priceadv($data)
	{
		if (!$data['num']) {
			$data['num'] = 1;
		}

		$p = dr('api/api_plan.get_one', $data['planid']);
		if (($p['gradeprice'] == 1) && $data['siteid']) {
			$sp = (array) unserialize($p['siteprice']);
			$site = dr('api/api_site.get_one', $data['siteid']);
			$grade = $site['grade'];
			$p['price'] = $sp[$grade];
		}

		$sumpay = $p['price'] * $data['num'];
		$sumadvpay = $p['priceadv'] * $data['num'];
		return array('price' => $sumpay, 'priceadv' => $sumadvpay);
	}
}


?>
