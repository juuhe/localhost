<?php

class client_trend_mod extends app_models
{
	public $default_from = '';

	public function get_os_data()
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

		$this->from('log_os');
		$this->select('sum(num) AS num,os,mobile,day');
		$this->group_by('day,os');
		$this->order_by('day');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_pc_mob_sum_data($is_mob = false)
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

		$this->from('log_os');
		$this->select('sum(num) AS num,mobile,os');

		if ($is_mob) {
			$group_by = 'mobile';
		}
		else {
			$group_by = 'os';
		}

		$this->group_by($group_by);
		$data = $this->get();
		return $data;
	}

	public function get_browser_kernel_sum_data($is_kernel = false)
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

		$this->from('log_browser');
		$this->select('sum(num) AS num,browser,kernel ');

		if ($is_kernel) {
			$group_by = 'kernel';
		}
		else {
			$group_by = 'browser';
		}

		$this->group_by($group_by);
		$data = $this->get();
		return $data;
	}

	public function get_browser_data()
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

		$this->from('log_browser');
		$this->select('sum(num) AS num,day,browser,kernel,ver');
		$this->group_by('day,browser');
		$this->order_by('day');
		$this->pager();
		$this->order_by('num');
		$data = $this->get();
		return $data;
	}

	public function get_screen_sum_data()
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

		$this->from('log_screen');
		$this->select('sum(num) AS num,screen ');
		$this->group_by('screen');
		$this->limit(10);
		$data = $this->get();
		return $data;
	}

	public function get_screen_data()
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

		$this->from('log_screen');
		$this->select('sum(num) AS num,day,screen');
		$this->group_by('day,screen');
		$this->order_by('day');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_province_map_sum_data()
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

		$this->from('log_city_isp');
		$this->select('sum(num) AS num,province ');
		$this->group_by('province');
		$data = $this->get();
		return $data;
	}

	public function get_province_data()
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

		$this->from('log_city_isp');
		$this->select('sum(num) AS num,day,province,group_concat( distinct city) AS city');
		$this->group_by('day,province');
		$this->order_by('day');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_province_city_num_data($timerange, $city)
	{
		if (!$timerange) {
			return NULL;
		}

		$timerange = strtotime($timerange);
		$this->where(array('day' => date('YmdHis', $timerange), 'city' => (int) $city));
		$this->from('log_city_isp');
		$this->select('sum(num) AS num, city');
		$this->group_by('day');
		$data = $this->get();
		return $data;
	}

	public function get_isp_sum_data()
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

		$this->from('log_city_isp');
		$this->select('sum(num) AS num,isp');
		$this->group_by('isp');
		$this->limit(10);
		$data = $this->get();
		return $data;
	}

	public function get_isp_data()
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

		$this->from('log_city_isp');
		$this->select('sum(num) AS num,day,isp');
		$this->group_by('day,isp');
		$this->order_by('day');
		$this->pager();
		$data = $this->get();
		return $data;
	}
}

?>
