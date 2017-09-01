<?php

class search_trend_mod extends app_models
{
	public $default_from = 'log_search';

	public function get_data()
	{
		$timerange = request('timerange');
		$uid = request('uid');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($uid) {
			$this->where(array('uid' => (int) $uid));
		}

		$this->select('count(*) AS num,search,site_url,keyword,search_url,day');
		$this->group_by('search,site_url');
		$this->order_by('day');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_sum_data()
	{
		$timerange = request('timerange');
		$uid = request('uid');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($uid) {
			$this->where(array('uid' => (int) $uid));
		}

		$this->select('count(*) AS num,search');
		$this->group_by('search');
		$data = $this->get();
		return $data;
	}
}

?>
