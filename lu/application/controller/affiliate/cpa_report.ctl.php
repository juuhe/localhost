<?php

class cpa_report_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_timerange = $this->get_timerange();
		$timerange = get('timerange');
		$status = get('status');
		$cpa_report = dr('affiliate/cpa_report.get_list', $timerange, $status);
		$params = array('status' => $status, 'timerange' => $timerange);
		$page = APP::adapter('pager', 'default');
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('page', $page);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::assign('cpa_report', $cpa_report);
		TPL::display('cpa_report');
	}
}
APP::load_file('main/main', 'ctl');

?>
