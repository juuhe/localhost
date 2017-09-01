<?php

class cpa_report_mod extends app_models
{
	public $default_from = 'cpa_report';

	public function get_list($timerange = false, $status = false)
	{
		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if (is_numeric($status)) {
			$this->where('status', (int) $status);
		}

		$this->where('uid', $_SESSION['affiliate']['uid']);
		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}
}

?>
