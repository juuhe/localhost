<?php

APP::load_file('main/main', 'ctl');
class report_ctl extends main_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$get_timerange = $this->get_timerange();
		$stats_type = explode(',', $GLOBALS['C_ZYIIS']['stats_type']);
		$type = request('type');
		$id = request('id');

		if (!$type) {
			$type = 'all';
		}

		$timerange = request('timerange');

		if ($type == 'plan') {
			$plan = dr('advertiser/plan.get_all');
			$to_id_type = 'planid';
			$to_name = 'planname';
		}

		if ($type == 'ads') {
			$plan = dr('advertiser/ad.get_all');
			$to_id_type = 'adsid';
			$to_name = 'adsid';
		}

		$report = dr('advertiser/report.get_list', $timerange, $type, $to_id_type, $id);
		$get_sum_data = dr('advertiser/report.get_list', $timerange, $type, $to_id_type, $id, false, true);
		$get_trend_data = dr('advertiser/report.get_list', $timerange, $type, $to_id_type, $id, false);

		foreach ((array) $get_trend_data as $d) {
			$report_day[] = '\'' . $d['day'] . '\'';

			if ($type == 'all') {
				$d['num'] = array_sum(explode(',', $d['num']));
				$d['views'] = array_sum(explode(',', $d['views']));
			}

			$trend_num_data[] = $d['num'];
			$trend_view_data[] = $d['views'];
		}

		$xAxis = implode(',', (array) $report_day);
		$trend_num_data = implode(',', (array) $trend_num_data);
		$trend_view_data = implode(',', (array) $trend_view_data);
		$params = array('type' => $type, 'timerange' => $timerange, 'id' => $id);
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('get_sum_data', $get_sum_data);
		TPL::assign('type', $type);
		TPL::assign('view_type_data', $plan);
		TPL::assign('to_id_type', $to_id_type);
		TPL::assign('to_name', $to_name);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('id', $id);
		TPL::assign('timerange', $timerange);
		TPL::assign('xAxis', $xAxis);
		TPL::assign('trend_num_data', $trend_num_data ? $trend_num_data : 0);
		TPL::assign('trend_view_data', $trend_view_data ? $trend_view_data : 0);
		TPL::assign('report', $report);
		TPL::assign('stats_type', $stats_type);
		TPL::display('report_getlist');
	}

	public function highcharts()
	{
		$v = get('v');
		setcookie('highcharts', $v, TIMES + (86400 * 30), '/');
	}

	final public function set_defaul_report()
	{
		$type = get('type');
		setcookie('defaul_report', $type, TIMES + (86400 * 300), '/');
	}
}

?>
