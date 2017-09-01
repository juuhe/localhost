<?php

class cpa_report_mod extends app_models
{
	public $default_from = 'cpa_report';

	public function get_list($timerange = false, $status = false)
	{
		$this->from('plan AS plan');
		$this->from('cpa_report AS cpa_report');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('cpa_report.day >=' => date('YmdHis', $time_begin), 'cpa_report.day <=' => date('YmdHis', $time_end)));
		}

		if (is_numeric($status)) {
			$this->where('cpa_report.status', (int) $status);
		}

		$this->where('cpa_report.planid', ' plan.planid', 'AND', false);
		$this->where('plan.uid', $_SESSION['advertiser']['uid']);
		$this->order_by('cpa_report.id');
		$this->pager();
		$data = $this->get();
		return $data;
	}
}

?>
