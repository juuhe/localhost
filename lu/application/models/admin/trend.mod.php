<?php

class trend_mod extends app_models
{
	public $default_from = 'log_hour';

	public function get_data($fields = NULL, $group_by)
	{
		$timerange = request('timerange');
		$searchval = request('searchval');
		$searchtype = request('searchtype');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($searchval) {
			$searchtype = preg_replace('/[^a-z]/i', '', $searchtype);
			$this->where(array($searchtype => $searchval));
		}

		$this->select("sum(hour0) AS hour0,sum(hour1) AS hour1,sum(hour2) AS hour2,sum(hour3) AS hour3,sum(hour4) AS hour4,sum(hour5) AS hour5,sum(hour6) AS hour6,sum(hour7) AS hour7,sum(hour8) AS hour8,sum(hour9) AS hour9,sum(hour10) AS hour10,sum(hour11) AS hour11,sum(hour12) AS hour12,sum(hour13) AS hour13,sum(hour14) AS hour14,sum(hour15) AS hour15,sum(hour16) AS hour16,sum(hour17) AS hour17,sum(hour18) AS hour18,sum(hour19) AS hour19,sum(hour20) AS hour20,sum(hour21) AS hour21,sum(hour22) AS hour22,sum(hour23) AS hour23,day,plantype\r\n\t\t\t\t");
		$this->group_by($group_by);
		$data = $this->get();
		return $data;
	}

	public function get_compare_sum_data($compare_1, $compare_2, $plantype = false)
	{
	}

	public function get_compare_data($compare_1, $compare_2, $plantype = false)
	{
		$this->select('sum(hour0) AS hour0,sum(hour1) AS hour1,sum(hour2) AS hour2,sum(hour3) AS hour3,sum(hour4) AS hour4,sum(hour5) AS hour5,sum(hour6) AS hour6,sum(hour7) AS hour7,sum(hour8) AS hour8,sum(hour9) AS hour9,sum(hour10) AS hour10,sum(hour11) AS hour11,sum(hour12) AS hour12,sum(hour13) AS hour13,sum(hour14) AS hour14,sum(hour15) AS hour15,sum(hour16) AS hour16,sum(hour17) AS hour17,sum(hour18) AS hour18,sum(hour19) AS hour19,sum(hour20) AS hour20,sum(hour21) AS hour21,sum(hour22) AS hour22,sum(hour23) AS hour23,day AS plantype');
		$group_by = 'day';

		if ($plantype) {
			$this->where('plantype', $plantype);
			$group_by = 'day,plantype';
		}

		$this->where('(day', $compare_1);
		$this->where('day', '\'' . $this->db->escape($compare_2) . '\')', 'OR', false);
		$this->group_by($group_by);
		$data = $this->get();
		return $data;
	}
}

?>
