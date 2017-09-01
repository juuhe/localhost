<?php

class api_order_mod extends app_models
{
	public $default_from = 'order';

	public function create($data)
	{
		$gpm = api('cps.get_proportion_money', $data);
		$data = array('ordersn' => $data['ordersn'], 'orderstatus' => $data['orderstatus'], 'orderamount' => $gpm['orderamount'] ? $gpm['orderamount'] : $data['orderamount'], 'goodsname' => $data['goodsname'], 'goodsprice' => $data['goodsprice'], 'goodsclassid' => $data['goodsclassid'], 'goodsmark' => $data['goodsmark'], 'proportionadv' => is_array($gpm['proportion_adv']) ? implode('|', $gpm['proportion_adv']) : $gpm['proportion_adv'], 'proportionaff' => is_array($gpm['proportion_aff']) ? implode('|', $gpm['proportion_aff']) : $gpm['proportion_adv'], 'payamountaff' => is_array($gpm['aff_proportion_money']) ? array_sum($gpm['aff_proportion_money']) : $gpm['aff_proportion_money'], 'payamountadv' => is_array($gpm['adv_proportion_money']) ? array_sum($gpm['adv_proportion_money']) : $gpm['adv_proportion_money'], 'planid' => (int) $data['planid'], 'uid' => (int) $data['uid'], 'zoneid' => (int) $data['zoneid'], 'adsid' => (int) $data['adsid'], 'siteid' => (int) $data['siteid'], 'day' => DAYS, 'status' => 0, 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
	}

	public function get_ordersn_one($ordersn)
	{
		$this->where('ordersn', $ordersn);
		$data = $this->find_one();
		return $data;
	}

	public function get_ordersn_planid_one($ordersn, $planid)
	{
		$this->where('ordersn', $ordersn);
		$this->where('planid', $planid);
		$data = $this->find_one();
		return $data;
	}

	public function update_order_status($ordersn, $planid, $order_status)
	{
		$where = array('ordersn' => $ordersn, 'planid' => $planid);
		$data = array('orderstatus ' => $order_status);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_union_status($ordersn, $planid)
	{
		$where = array('ordersn' => $ordersn, 'planid' => $planid, 'status' => 0);
		$data = array('status ' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}
}

?>
