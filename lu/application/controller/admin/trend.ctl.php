<?php

APP::load_file('admin/admin', 'ctl');
class trend_ctl extends admin_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$compare = request('compare');
		$get_timerange = $this->get_timerange();

		if ($compare) {
			$compare_1 = request('compare_1');
			$compare_2 = request('compare_2');
			$plantype = request('plantype');
			$st = array($compare_1, $compare_2);
			$st_c = count($st);
			$group = 'hour';
			$day_hour = range(0, 23);
			$day_sum_stats = dr('admin/trend.get_compare_data', $compare_1, $compare_2, $plantype);
		}
		else {
			$st = explode(',', $GLOBALS['C_ZYIIS']['stats_type']);
			$st_c = count($st);
			$searchval = request('searchval');
			$searchtype = request('searchtype');
			$group = (request('group') ? request('group') : 'day');
			$timerange = request('timerange');
			$sum_stats = dr('admin/stats.get_data', 'plantype', 'plantype', false);
			$d = @explode('/', $timerange);
			if ((trim($d[0]) == trim($d[1])) && $timerange) {
				$group = 'hour';
			}

			if ($group == 'day') {
				$day_hour = array();
				$day_sum_stats = dr('admin/stats.get_data', 'day,plantype', 'day', false);

				foreach ((array) $day_sum_stats as $d) {
					$day_hour[] = '\'' . $d['day'] . '\'';
					$day_views[] = '' . $d['views'] . '';
					$day_num[] = '' . $d['num'] . '';
				}

				$series_deta['pv'] = implode(',', (array) $day_views);
				$series_deta['ip'] = implode(',', (array) $day_num);

				foreach ($series_deta as $key => $val) {
					$series[] = '{name: \'' . $key . '\',data: [' . $val . ']}';
				}

				$xAxis = implode(',', (array) $day_hour);
				$series = implode(',', (array) $series);
				arsort($day_hour);
				$day_sum_stats_page = dr('admin/stats.get_data', 'day,plantype', 'day', true);
			}

			if ($group == 'hour') {
				$day_sum_stats = dr('admin/trend.get_data', 'plantype', 'plantype');
				$day_hour = range(0, 23);

				foreach ((array) $day_sum_stats as $d) {
					$ptype = $d['plantype'];
					unset($d['day']);
					unset($d['plantype']);
					$series_deta[$ptype] = implode(',', (array) $d);
				}

				foreach ($series_deta as $key => $val) {
					$series[] = '{name: \'' . $key . '\',data: [' . $val . ']}';
				}

				$series = implode(',', (array) $series);
			}
		}

		$params = array('searchtype' => $searchtype, 'searchval' => $searchval, 'timerange' => $timerange);
		$page->params_url = $params;
		TPL::assign('series', $series);
		TPL::assign('st', $st);
		TPL::assign('st_c', $st_c);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('sum_stats', $sum_stats);
		TPL::assign('day_sum_stats', $day_sum_stats);
		TPL::assign('xAxis', $xAxis);
		TPL::assign('day_hour', $day_hour);
		TPL::assign('group', $group);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('searchval', $searchval);
		TPL::assign('timerange', $timerange);
		TPL::assign('page', $page);
		TPL::assign('day_sum_stats_page', $day_sum_stats_page);
		TPL::display('trend');
	}
}

?>
