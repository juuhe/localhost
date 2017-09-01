<?php

class import_mod extends app_models
{
	public $default_from = 'import';

	public function get_list()
	{
		$search = request('search');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'username':
				$this->where('u.username', $search);
				break;

			case 'uid':
				$this->where('i.uid', (int) $search);
				break;

			case 'planid':
				$this->where('p.planid', (int) $search);
				break;

			case 'planname':
				$this->where('p.planname', $search);
				break;
			}
		}

		$this->select('i.*,u.username,u.uid,p.planname,p.plantype');
		$this->from('import AS i');
		$this->from('users AS u');
		$this->from('plan AS p');
		$this->where('i.uid', ' u.uid', 'AND', false);
		$this->where('i.planid', ' p.planid', 'AND', false);
		$this->order_by('i.importid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post($planid, $data)
	{
		$plan = dr('admin/plan.get_one', $planid);

		foreach ((array) $data as $key => $d) {
			$ex = explode('|', $d);
			$date = trim($ex[0]);
			$uid = (int) $ex[1];

			if ($plan['plantype'] == 'cps') {
				$ordersn = $ex[2];
				$orders_prices = $ex[3];
				$commission_aff = (int) $ex[4];
				$commission_adv = (int) $ex[5];
			}
			else {
				$num_aff = (int) $ex[2];
				$num_adv = (int) $ex[3];
				$siteid = (int) $ex[4];
			}

			$data_array = array('day' => $date, 'planid' => $planid, 'uid' => $uid, 'siteid' => $siteid, 'cid' => '', 'num_aff' => $num_aff ? $num_aff : 1, 'num_adv' => $num_adv ? $num_adv : 1, 'ordersn' => $ordersn, 'orders_prices' => $orders_prices, 'commission_aff' => $commission_aff, 'commission_adv' => $commission_adv, 'like' => '', 'ckorders' => false, 'data' => $d);
			$this->import_data($data_array);
		}
	}

	public function import_data($data)
	{
		$plan = dr('admin/plan.get_one', $data['planid']);

		if ($data['ckorders']) {
			$order = $this->get_one_ordersn($data['ordersn']);

			if ($order) {
				return false;
			}
		}
		else {
			if ($plan['plantype'] == 'cps') {
				$commission_aff = ($data['commission_aff'] ? $data['commission_aff'] : $plan['price']);
				$commission_adv = ($data['commission_adv'] ? $data['commission_adv'] : $plan['priceadv']);
				$aff_money = ($data['orders_prices'] * $commission_aff) / 100;
				$adv_money = ($data['orders_prices'] * $commission_adv) / 100;
			}
			else {
				if ($plan['gradeprice'] == 1) {
					$siteid = (int) $data['siteid'];
					$sp = (array) unserialize($plan['siteprice']);
					$site = dr('jump/jump.get_site', $siteid);
					$grade = (int) $site['grade'];
					$plan['price'] = $sp[$grade];
				}

				$aff_money = $plan['price'] * $data['num_aff'];
				$adv_money = $plan['priceadv'] * $data['num_adv'];
			}

			$sumpay = $aff_money;
			$sumadvpay = $adv_money;
			$sumprofit = $adv_money - $aff_money;
			$row_data = array('uid' => $data['uid'], 'planid' => $data['planid'], 'numaff' => $data['num_aff'], 'numadv' => $data['num_adv'], 'orders' => $data['ordersn'], 'ordersprices' => floatval($data['orders_prices']), 'userproportion' => floatval($commission_aff), 'advproportion' => floatval($commission_adv), 'userprice' => $aff_money, 'advprice' => $adv_money, 'sumpay' => $sumpay, 'sumadvpay' => $sumadvpay, 'sumprofit' => $sumprofit, 'addtime' => DATETIMES, 'data' => $data['data'], 'day' => $data['day'] ? $data['day'] : DAYS);
			$this->set($row_data);
			$this->insert();
		}

		$moneytype = $plan['clearing'] . 'money';
		$this->where('uid', $data['uid']);
		$this->set($moneytype, $moneytype . '+' . $sumpay, false);
		$this->update('users');
		$this->where('uid', $plan['uid']);
		$this->set('money', 'money-' . $sumadvpay, false);
		$this->update('users');
		$stats_data = array('num' => $data['num_aff'], 'deduction' => $data['num_adv'] - $data['num_aff'], 'day' => $data['day'] ? $data['day'] : DAYS, 'planid' => $data['planid'], 'adsid' => 0, 'zoneid' => 0, 'plantype' => $plan['plantype'], 'advuid' => $plan['uid'], 'siteid' => $data['siteid'], 'uid' => $data['uid'], 'adtplid' => 0, 'zuid' => 0, 'sumpay' => $sumpay, 'sumprofit' => $sumprofit, 'sumadvpay' => $sumadvpay);
		$stats_updata = array('num' => $data['num_aff'], 'deduction' => $data['num_adv'] - $data['num_aff'], 'sumpay' => $sumpay, 'sumprofit' => $sumprofit, 'sumadvpay' => $sumadvpay);
		$this->insert_update('stats', $stats_data, $stats_updata);
	}

	public function get_one_ordersn($ordersn)
	{
		$where = array('ordersn' => $ordersn);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_one_importid($importid)
	{
		$where = array('importid' => (int) $importid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function del($importid)
	{
		$where = array('importid' => (int) $importid);
		$this->where($where);
		$data = $this->delete();
	}

	public function revocation($importid)
	{
		$data = $this->get_one_importid($importid);

		if ($data['status'] == 1) {
			return NULL;
		}

		$this->where('importid', $data['importid']);
		$this->set('status', 1);
		$this->update();
		$plan = dr('admin/plan.get_one', $data['planid']);
		$num = $data['numaff'];
		echo $num;
		$sumpay = $data['sumpay'];
		$sumadvpay = $data['sumadvpay'];
		$sumprofit = $data['sumprofit'];
		$moneytype = $plan['clearing'] . 'money';
		$this->where('uid', $data['uid']);
		$this->set($moneytype, $moneytype . '-' . $sumpay, false);
		$this->update('users');
		$this->where('uid', $plan['uid']);
		$this->set('money', 'money+' . $sumadvpay, false);
		$this->update('users');
		$sats_where = array('day' => $data['day'], 'planid' => $data['planid'], 'uid' => $data['uid'], 'adsid' => 0, 'zoneid' => 0);
		$this->where($sats_where);
		$this->set('num', 'num-' . $num, false);
		$this->set('sumpay', 'sumpay-' . $sumpay, false);
		$this->set('sumadvpay', 'sumadvpay-' . $sumadvpay, false);
		$this->set('sumprofit', 'sumprofit-' . $sumprofit, false);
		$this->set('deduction', 'deduction-' . ($data['num_adv'] - $data['num_aff']), false);
		$this->update('stats');
	}
}

?>
