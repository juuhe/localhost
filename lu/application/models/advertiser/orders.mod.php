<?php

class orders_mod extends app_models
{
	public $default_from = 'order';

	public function get_list($timerange = false, $status = false)
	{
		$this->from('plan AS p');
		$this->from('order AS o');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('o.day >=' => date('YmdHis', $time_begin), 'o.day <=' => date('YmdHis', $time_end)));
		}

		if (is_numeric($status)) {
			$this->where('o.status', (int) $status);
		}

		$this->where('o.planid', ' p.planid', 'AND', false);
		$this->where('p.uid', $_SESSION['advertiser']['uid']);
		$this->order_by('o.orderid');
		$this->pager();
		$data = $this->get();
		return $data;
	}
}

?>
