<?php

class orders_mod extends app_models
{
	public $default_from = 'order';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'orderid':
				$this->where('o.ordersn', $search);
				break;

			case 'username':
				$this->where('u.username', $search);
				break;

			case 'uid':
				$this->where('o.uid', (int) $search);
				break;

			case 'planid':
				$this->where('p.planid', (int) $search);
				break;

			case 'planname':
				$this->where('p.planname', $search);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('o.status' => (int) $status);
			$this->where($where);
		}

		$this->select('o.*,u.username,u.uid,p.planname');
		$this->from('order AS o');
		$this->from('users AS u');
		$this->from('plan AS p');
		$this->where('o.uid', ' u.uid', 'AND', false);
		$this->where('o.planid', ' p.planid', 'AND', false);
		$this->order_by('o.orderid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_orderid_one($orderid)
	{
		$this->where('orderid', $orderid);
		$data = $this->find_one();
		return $data;
	}

	public function get_ordersn_one($ordersn)
	{
		$this->where('ordersn', $ordersn);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($orderid)
	{
		$where = array('orderid' => (int) $orderid);
		$data = array('status' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
		return $this->affected_rows();
	}

	public function lock($orderid)
	{
		$where = array('orderid' => (int) $orderid);
		$data = array('status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($orderid)
	{
		$where = array('orderid' => (int) $orderid);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
