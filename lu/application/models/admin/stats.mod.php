<?php

class stats_mod extends app_models
{
	public $default_from = 'stats';

	public function get_data($fields = false, $group_by = true, $page = true)
	{
		$planid = request('planid');
		$timerange = request('timerange');
		$searchval = request('searchval');
		$searchtype = request('searchtype');
		$sortingtype = request('sortingtype');
		$plantype = request('plantype');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($planid) {
			$this->where(array('planid' => (int) $planid));
		}

		if ($plantype) {
			$this->where(array('plantype' => $plantype));
		}

		if ($searchval && $searchtype) {
			$searchtype = preg_replace('/[^a-z]/i', '', $searchtype);
			$this->where(array($searchtype => $searchval));
		}

		$this->select("sum(views) As views,\r\n\t\t\t\tsum(clicks) As clicks,\r\n\t\t\t\tsum(num) As num,\r\n\t\t\t\tsum(deduction) As deduction,\r\n\t\t\t\tsum(do2click) As do2click ,\r\n\t\t\t\tsum(effectnum) As effectnum ,\r\n\t\t\t\tsum(sumadvpay) As sumadvpay  ,\r\n\t\t\t\tsum(sumpay) As sumpay  ,\r\n\t\t\t\tsum(sumprofit) As sumprofit  ,\r\n\t\t\t\t" . $fields . "\r\n\t\t\t\t");

		if ($group_by) {
			if (!$sortingtype) {
				$sortingtype = 'day';
			}

			$this->order_by($sortingtype);
			$this->group_by($group_by);

			if ($page) {
				$this->pager();
			}
			else {
				$this->ar_limit = array();
			}
		}

		$data = $this->get();
		return $data;
	}

	public function del($day, $field, $val)
	{
		$this->where($field, $val);
		$this->where('day', $day);
		$data = $this->delete();
	}

	public function get_time_day_uid_pv($uid, $time)
	{
		if (!$uid || !$time) {
			return false;
		}

		$this->where(array('uid' => (int) $uid, 'day >=' => date('YmdHis', strtotime($time)), 'day <=' => date('YmdHis', strtotime(DAYS)), 'views >' => $GLOBALS['C_ZYIIS']['integral_daypv']));
		$this->group_by('day');
		$data = $this->find_count();
		return $data;
	}

	public function get_performance($serveic_uid, $timerange, $type)
	{
		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$a_where = 's.day >=' . date('YmdHis', $time_begin) . '  AND s.day <=' . date('YmdHis', $time_end) . ' AND ';
		}

		$this->from('users AS u ');

		if ($type == 'service') {
			$this->select(' sum( s.sumpay ) AS pay');
			$this->join('stats AS s', $a_where . '  u.uid = s.uid ', 'left');
		}
		else {
			$this->select(' sum( s.sumadvpay ) AS pay');
			$this->join('stats AS s', $a_where . '  u.uid = s.advuid ', 'left');
		}

		$this->where('u.serviceid', $serveic_uid);
		$data = $this->find_one();
		return $data;
	}

	public function get_day_performance($uid, $timerange, $type)
	{
		if (!$uid) {
			return NULL;
		}

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$a_where = 's.day >=' . date('YmdHis', $time_begin) . '  AND s.day <=' . date('YmdHis', $time_end) . ' AND ';
		}

		$this->from('users AS u ');

		if ($type == 'service') {
			$this->select(' sum( s.sumpay ) AS pay,s.day,count(distinct s.uid) AS u_num');
			$this->join('stats AS s', $a_where . '  u.uid = s.uid ', 'left');
		}
		else {
			$this->select(' sum( s.sumadvpay ) AS pay,s.day,count(distinct s.advuid) AS u_num');
			$this->join('stats AS s', $a_where . '  u.uid = s.advuid ', 'left');
		}

		$this->where('u.serviceid', $uid);
		$this->group_by('s.day');
		$this->order_by('s.day');
		$this->limit(90);
		$data = $this->get();
		return $data;
	}
}

?>
