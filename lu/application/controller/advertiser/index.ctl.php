<?php

APP::load_file('main/main', 'ctl');
class index_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_day_sunpay = dr('advertiser/report.get_day_sunpay');
		$get_yesterday_sunpay = dr('advertiser/report.get_yesterday_sunpay');
		$get_month_sunpay = dr('advertiser/report.get_month_sunpay');
		$stats = dr('advertiser/report.get_index_stats');
		$get_timerange = $this->get_timerange();
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('stats', $stats);
		TPL::assign('get_day_sunpay', $get_day_sunpay);
		TPL::assign('get_yesterday_sunpay', $get_yesterday_sunpay);
		TPL::assign('get_month_sunpay', $get_month_sunpay);
		TPL::display('index');
	}
}

?>
