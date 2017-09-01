<?php

APP::load_file('main/main', 'ctl');
class index_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_timerange = $this->get_timerange();
		$get_day_sunpay = dr('affiliate/report.get_day_sunpay');
		$get_day_stats = dr('affiliate/report.get_index_stats');
		$get_yesterday_sunpay = dr('affiliate/report.get_yesterday_sunpay');
		$get_week_sunpay = dr('affiliate/report.get_week_sunpay');
		$get_last_month_sunpay = dr('affiliate/report.get_last_month_sunpay');
		$stats_type = explode(',', $GLOBALS['C_ZYIIS']['stats_type']);
		$type='all';
		$clearing=dr('affiliate/report.get_clearing');   //获取计划的付款周期（日 月 周）
		$weekmoney=$GLOBALS['C_ZYIIS']['clearing_weekdata'];
		$monthmoney=$GLOBALS['C_ZYIIS']['clearing_monthdata'];
		$timerange= $get_timerange['7day'];
		$get_week_stats = dr('affiliate/report.get_list', $timerange, $type);
		$timerange=$get_timerange['thismonth'];
		$get_month_stats = dr('affiliate/report.get_list', $timerange, $type);
		$timerange=$get_timerange['lastmonth'];
		$get_unpaid_sunpay=dr('affiliate/report.nopaid_sunpay');
		$get_nopaid_stats=dr('affiliate/report.nopaid_stats');
		$get_last_month_stats = dr('affiliate/report.get_list', $timerange, $type);
		$get_month_sunpay = dr('affiliate/report.get_month_sunpay');
		$stats = dr('affiliate/report.get_index_stats');
		$qq = dr('main/account.get_serviceid_qq',  $GLOBALS['userinfo']['uid']);  //获取客服的qq
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('stats', $stats);
		TPL::assign('get_day_stats', $get_day_stats);
		TPL::assign('get_day_sunpay', $get_day_sunpay);
		TPL::assign('get_yesterday_sunpay', $get_yesterday_sunpay);
		TPL::assign('get_week_sunpay', $get_week_sunpay);
		TPL::assign('get_week_stats', $get_week_stats);
		TPL::assign('get_month_sunpay', $get_month_sunpay);
		TPL::assign('qq', $qq);
		TPL::assign('get_month_stats', $get_month_stats);
		TPL::assign('clearing', $clearing);
		TPL::assign('get_last_month_stats', $get_last_month_stats);
		TPL::assign('get_last_month_sunpay', $get_last_month_sunpay);
		TPL::assign('get_unpaid_sunpay', $get_unpaid_sunpay);
		TPL::assign('get_nopaid_stats', $get_nopaid_stats);
		TPL::assign('stats_type', $stats_type);
		TPL::display('index');
	}
	final public function home()
	{
		$get_timerange = $this->get_timerange();
		$get_day_sunpay = dr('affiliate/report.get_day_sunpay');
		$get_day_stats = dr('affiliate/report.get_index_stats');
		$get_yesterday_sunpay = dr('affiliate/report.get_yesterday_sunpay');
		$get_week_sunpay = dr('affiliate/report.get_week_sunpay');
		$get_last_month_sunpay = dr('affiliate/report.get_last_month_sunpay');
		$stats_type = explode(',', $GLOBALS['C_ZYIIS']['stats_type']);
		$type='all';
		$clearing=dr('affiliate/report.get_clearing');   //获取计划的付款周期（日 月 周）
		$weekmoney=$GLOBALS['C_ZYIIS']['clearing_weekdata'];
		$monthmoney=$GLOBALS['C_ZYIIS']['clearing_monthdata'];
		$timerange=$get_timerange['7day'];
		$get_week_stats = dr('affiliate/report.get_list', $timerange, $type);
		$time_month_begin = strtotime(date('Y-n-1', TIMES));
		$time_month_end = strtotime(DAYS);
		$timerange=$get_timerange['thismonth'];
		$get_month_stats = dr('affiliate/report.get_list', $timerange, $type);
		$timerange=$get_timerange['lastmonth'];
		$get_unpaid_sunpay=dr('affiliate/report.nopaid_sunpay');
		$get_nopaid_stats=dr('affiliate/report.nopaid_stats');
		$get_last_month_stats = dr('affiliate/report.get_list', $timerange, $type);
		$get_month_sunpay = dr('affiliate/report.get_month_sunpay');
		$stats = dr('affiliate/report.get_index_stats');
		$qq = dr('main/account.get_serviceid_qq',  $GLOBALS['userinfo']['uid']);  //获取客服的qq
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('stats', $stats);
		TPL::assign('get_day_stats', $get_day_stats);
		TPL::assign('get_day_sunpay', $get_day_sunpay);
		TPL::assign('get_yesterday_sunpay', $get_yesterday_sunpay);
		TPL::assign('get_week_sunpay', $get_week_sunpay);
		TPL::assign('get_week_stats', $get_week_stats);
		TPL::assign('get_month_sunpay', $get_month_sunpay);
		TPL::assign('qq', $qq);
		TPL::assign('get_month_stats', $get_month_stats);
		TPL::assign('clearing', $clearing);
		TPL::assign('get_last_month_stats', $get_last_month_stats);
		TPL::assign('get_last_month_sunpay', $get_last_month_sunpay);
		TPL::assign('get_unpaid_sunpay', $get_unpaid_sunpay);
		TPL::assign('get_nopaid_stats', $get_nopaid_stats);
		TPL::assign('stats_type', $stats_type);
		TPL::display('home');
	}
}

?>
