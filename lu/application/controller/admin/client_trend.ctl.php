<?php

APP::load_file('admin/admin', 'ctl');
class client_trend_ctl extends admin_ctl
{
	final public function get_os()
	{
		$timerange = request('timerange');
		$get_timerange = $this->get_timerange();
		$page = APP::adapter('pager', 'default');
		$os_mob_sum = dr('admin/client_trend.get_pc_mob_sum_data', true);
		$os_mob_sum = sort_array($os_mob_sum, 'num', 'desc');
		$os_sum = dr('admin/client_trend.get_pc_mob_sum_data');
		$os_sum = sort_array($os_sum, 'num', 'desc');
		$data = dr('admin/client_trend.get_os_data');
		$params = array('searchtype' => request('searchtype'), 'search' => request('search'), 'timerange' => request('timerange'));
		$page->params_url = $params;
		TPL::assign('os_sum', $os_sum);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::assign('os_mob_sum', $os_mob_sum);
		TPL::assign('data', $data);
		TPL::assign('page', $page);
		TPL::display('client_trend_os');
	}

	final public function get_browser()
	{
		$timerange = request('timerange');
		$get_timerange = $this->get_timerange();
		$page = APP::adapter('pager', 'default');
		$browser_kernel_sum = dr('admin/client_trend.get_browser_kernel_sum_data', true);
		$browser_kernel_sum = sort_array($browser_kernel_sum, 'num', 'desc');
		$browser_sum = dr('admin/client_trend.get_browser_kernel_sum_data');
		$browser_sum = sort_array($browser_sum, 'num', 'desc');
		$data = dr('admin/client_trend.get_browser_data');
		$params = array('searchtype' => request('searchtype'), 'search' => request('search'), 'timerange' => request('timerange'));
		$page->params_url = $params;
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::assign('browser_sum', $browser_sum);
		TPL::assign('browser_kernel_sum', $browser_kernel_sum);
		TPL::assign('data', $data);
		TPL::assign('page', $page);
		TPL::display('client_trend_browser');
	}

	final public function get_screen()
	{
		$timerange = request('timerange');
		$get_timerange = $this->get_timerange();
		$page = APP::adapter('pager', 'default');
		$screen_sum = dr('admin/client_trend.get_screen_sum_data');
		$screen_sum = sort_array($screen_sum, 'num', 'desc');
		$data = dr('admin/client_trend.get_screen_data');
		$params = array('searchtype' => request('searchtype'), 'search' => request('search'), 'timerange' => request('timerange'));
		$page->params_url = $params;
		TPL::assign('screen_sum', $screen_sum);
		TPL::assign('data', $data);
		TPL::assign('page', $page);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::display('client_trend_screen');
	}

	final public function get_city()
	{
		$timerange = request('timerange');
		$get_timerange = $this->get_timerange();
		$page = APP::adapter('pager', 'default');
		$province_sum = dr('admin/client_trend.get_province_map_sum_data');
		$province_sum = sort_array($province_sum, 'num', 'desc');
		$sum = 0;

		foreach ((array) $province_sum as $p) {
			$sum += $p['num'];
		}

		$C_p = $GLOBALS['C_province'];

		foreach ((array) $province_sum as $p) {
			$ctr = Ctr($sum, $p['num']);
			if ((1 <= $ctr) && ($ctr < 3)) {
				$col = '#ffe167';
			}

			if ((3 <= $ctr) && ($ctr < 5)) {
				$col = '#9fccff';
			}

			if ((5 <= $ctr) && ($ctr < 7)) {
				$col = '#6c98d5';
			}

			if ((7 <= $ctr) && ($ctr < 9)) {
				$col = '#136bd4';
			}

			if ((9 <= $ctr) && ($ctr < 15)) {
				$col = '#1556a1';
			}

			if (15 < $ctr) {
				$col = '#005C86';
			}

			$data_status[] = '{ cha: \'' . $p['province'] . '\', name: \'' . $C_p[$p['province']] . '<BR>' . $p['num'] . '\', des: \'' . $col . '\' }';
		}

		$data = dr('admin/client_trend.get_province_data');
		$params = array('searchtype' => request('searchtype'), 'search' => request('search'), 'timerange' => request('timerange'));
		$page->params_url = $params;
		TPL::assign('province_sum', $province_sum);
		TPL::assign('data', $data);
		TPL::assign('page', $page);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::assign('data_status', implode(',', (array) $data_status));
		TPL::display('client_trend_city');
	}

	final public function get_isp()
	{
		$timerange = request('timerange');
		$get_timerange = $this->get_timerange();
		$page = APP::adapter('pager', 'default');
		$isp_sum = dr('admin/client_trend.get_isp_sum_data');
		$isp_sum = sort_array($isp_sum, 'num', 'desc');
		$data = dr('admin/client_trend.get_isp_data');
		$params = array('searchtype' => request('searchtype'), 'search' => request('search'), 'timerange' => request('timerange'));
		$page->params_url = $params;
		TPL::assign('isp_sum', $isp_sum);
		TPL::assign('data', $data);
		TPL::assign('page', $page);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::display('client_trend_isp');
	}
}

?>
