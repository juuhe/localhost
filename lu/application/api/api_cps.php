<?php

class api_cps
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

		dr('api/api_order.create', $data);
		return true;
	}

	static public function update($ordersn, $planid, $orderstatus = false, $valid = false)
	{
		if (!$planid || !$ordersn) {
			exit('update NOT planid ordersn');
		}

		if ($ordersn) {
			dr('api/api_order.get_ordersn_planid_one', $ordersn, $planid);
		}

		api_cps::update_status($ordersn, $planid, $orderstatus);

		if ($valid == 'true') {
			$o = dr('api/api_order.get_ordersn_planid_one', $ordersn, $planid);

			if ($o['status'] == 0) {
				api_cps::update_union_status($ordersn, $planid);
				api_cps::update_satas_AND_update_user_money_data($ordersn, $planid);
			}
		}
	}

	static public function update_satas_AND_update_user_money_data($ordersn, $planid)
	{
		if (!$planid || !$ordersn) {
			exit('update_satas_AND_update_user_money_data NOT planid ordersn');
		}

		api_cps::update_satas($ordersn, $planid);
		api_cps::update_user_money_data($ordersn, $planid);
	}

	static public function update_union_status($ordersn, $planid)
	{
		if (!$planid || !$ordersn) {
			exit('update_union_status NOT planid ordersn');
		}

		dr('api/api_order.update_union_status', $ordersn, $planid);
	}

	static public function update_status($ordersn, $planid, $orderstatus)
	{
		if (!$planid || !$ordersn) {
			exit('update_status NOT planid ordersn');
		}

		dr('api/api_order.update_order_status', $ordersn, $planid, $orderstatus);
	}

	static public function update_user_money_data($ordersn, $planid)
	{
		if (!$ordersn || !$planid) {
			exit('update_user_money_data NOT "$ordersn,$planid"');
		}

		$p = dr('api/api_plan.get_one', $planid);
		$o = dr('api/api_order.get_ordersn_planid_one', $ordersn, $planid);
		dr('api/api_user.update_money_type', $o['uid'], $o['payamountaff'], '+', $p['clearing']);
		dr('api/api_user.update_money_type', $p['uid'], $o['payamountadv'], '-');
	}

	static public function update_satas($ordersn, $planid)
	{
		$p = dr('api/api_plan.get_one', $planid);
		$o = dr('api/api_order.get_ordersn_planid_one', $ordersn, $planid);
		$row_data = array('planid' => $o['planid'], 'uid' => $o['uid'], 'advuid' => $p['uid'], 'adsid' => $o['adsid'], 'zoneid' => $o['zoneid'], 'siteid' => $o['siteid'], 'plantype' => $p['plantype'], 'day' => $o['day'] ? $o['day'] : DAYS, 'num' => 1, 'sumpay' => $o['payamountaff'], 'sumadvpay' => $o['payamountadv'], 'sumprofit' => $o['payamountadv'] - $o['payamountaff']);
		dr('api/api_stats.update_stats', $row_data);
	}

	static public function get_proportion_money($data)
	{
		$p = dr('api/api_plan.get_one', $data['planid']);

		if ($p['gradeprice'] == 0) {
			$proportion_aff = abs($p['price']);
			$proportion_adv = abs($p['priceadv']);
			$aff_proportion_money = $data['orderamount'] * ($proportion_aff / 100);
			$adv_proportion_money = $data['orderamount'] * ($proportion_adv / 100);
		}

		if ($p['gradeprice'] == 1) {
			if (!$data['goodsmark']) {
				exit('Not goodsmark');
			}

			$cp = (array) unserialize($p['classprice']);
			$egm = explode('|', $data['goodsmark']);
			$egs = explode('|', $data['goodsprice']);
			$orderamount = array_sum($egs);
			if (($egm[0] <= 0) || ($egs[0] <= 0)) {
				exit('goodsmark OR goodsprice Not Num');
			}

			if (count($egm) != count($egs)) {
				exit('goodsmark OR goodsprice Not equal');
			}

			$i = 0;

			foreach ($egm as $markid) {
				$k = array_keys($cp['classprice_mark'], $markid);

				if (!$k) {
					$proportion_aff[$i] = 0;
					$proportion_adv[$i] = 0;
				}
				else {
					$proportion_aff[$i] = $cp['classprice_aff'][$k[0]];
					$proportion_adv[$i] = $cp['classprice_adv'][$k[0]];
				}

				$aff_proportion_money[$i] = $egs[$i] * ($proportion_aff[$i] / 100);
				$adv_proportion_money[$i] = $egs[$i] * ($proportion_adv[$i] / 100);
				$i++;
			}
		}

		if ($p['gradeprice'] == 2) {
			if (!$data['customproportion']) {
				exit('Not customproportion');
			}

			$proportion_aff = $data['customaff'];
			$proportion_adv = $data['customadv'];
			$aff_proportion_money = $data['orderamount'] * ($proportion_aff / 100);
			$adv_proportion_money = $data['orderamount'] * ($proportion_adv / 100);
		}

		$data = array('proportion_aff' => $proportion_aff, 'proportion_adv' => $proportion_adv, 'aff_proportion_money' => $aff_proportion_money, 'adv_proportion_money' => $adv_proportion_money, 'orderamount' => $orderamount);
		return $data;
	}
}


?>
