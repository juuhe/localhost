<?php

class report_mod extends app_models
{
	public $default_from = 'stats';

	public function get_list($timerange, $type = false, $to_id_type = false, $id = false, $pager = true, $sumall = false)
	{
		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($type != 'all') {
			if ($id) {
				$this->where($to_id_type, $id);
			}

			if ($pager) {
				$this->group_by('day,' . $to_id_type);
			}
			else {
				$this->group_by('day');
			}

			$this->select("sum(views) As views,\r\n\t\t\t\tsum(num+deduction) As num,\r\n\t\t\t\tsum(sumadvpay) As sumadvpay  ,\r\n\t\t\t\tplantype,day," . $to_id_type . ", \r\n\t\t\t\t");
			$this->order_by('day');
		}
		else {
			if (!$sumall) {
				$this->group_by('day');
			}
			else {
				$this->group_by('plantype');
			}

			$this->select("sum(views) As views,\r\n\t\t\t\tsum(num+deduction) As num,\r\n\t\t\t\tsum(sumadvpay) As sumadvpay ,plantype,day\r\n\t\t\t\t");
			$this->order_by('day');
		}

		$this->where('advuid', (int) $_SESSION['advertiser']['uid']);
		$this->where('(num>0 OR views>0)');

		if ($pager) {
			$this->pager();
		}
		else {
			$this->ar_limit = array();
		}

		$data = $this->get();
		return $data;
	}

	public function get_index_stats()
	{
		$this->where('day', DAYS);
		$this->where('advuid', (int) $_SESSION['advertiser']['uid']);
		$this->select('sum(views) AS views,sum(num+deduction) AS num,sum(sumadvpay) AS sumadvpay,plantype');
		$this->group_by('plantype');
		$data = $this->get();
		return $data;
	}

	public function get_24hour()
	{
		$timerange = request('timerange');
		$this->from('log_hour');
		$this->select('sum(hour0) AS hour0,sum(hour1) AS hour1,sum(hour2) AS hour2,sum(hour3) AS hour3,sum(hour4) AS hour4,sum(hour5) AS hour5,sum(hour6) AS hour6,sum(hour7) AS hour7,sum(hour8) AS hour8,sum(hour9) AS hour9,sum(hour10) AS hour10,sum(hour11) AS hour11,sum(hour12) AS hour12,sum(hour13) AS hour13,sum(hour14) AS hour14,sum(hour15) AS hour15,sum(hour16) AS hour16,sum(hour17) AS hour17,sum(hour18) AS hour18,sum(hour19) AS hour19,sum(hour20) AS hour20,sum(hour21) AS hour21,sum(hour22) AS hour22,sum(hour23) AS hour23');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		$this->where('advuid', (int) $_SESSION['advertiser']['uid']);
		$this->group_by('day,advuid');
		$data = $this->get();
		return $data;
	}

	public function get_day_sunpay()
	{
		$this->select('sum(sumadvpay) As sumadvpay');
		$this->where('day', DAYS);
		$this->where('advuid', (int) $_SESSION['advertiser']['uid']);
		$data = $this->find_one();
		return $data;
	}

	public function get_yesterday_sunpay()
	{
		$this->select('sum(sumadvpay) As sumadvpay');
		$date = date('Y-n-d', TIMES - 86400);
		$this->where('day', $date);
		$this->where('advuid', (int) $_SESSION['advertiser']['uid']);
		$data = $this->find_one();
		return $data;
	}

	public function get_month_sunpay()
	{
		$this->select('sum(sumadvpay) As sumadvpay');
		$time_begin = strtotime(date('Y-n-1', TIMES));
		$time_end = strtotime(DAYS);
		$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		$this->where('advuid', (int) $_SESSION['advertiser']['uid']);
		$data = $this->find_one();
		return $data;
	}
}

?>
