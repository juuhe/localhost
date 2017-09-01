<?php

APP::load_file('main/main', 'ctl');
class paylog_ctl extends main_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$get_timerange = $this->get_timerange();
		$paylog = dr('affiliate/paylog.get_list');
		$paylog_sunpay = dr('affiliate/paylog.get_sunpay');
		$clearing=dr('affiliate/report.get_clearing');   //获取计划的付款周期（日 月 周）
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('page', $page);
		TPL::assign('paylog_sunpay', $paylog_sunpay);
		TPL::assign('paylog', $paylog);
		TPL::assign('clearing', $clearing);
		TPL::display('paylog_getlist');
	}
}

?>
