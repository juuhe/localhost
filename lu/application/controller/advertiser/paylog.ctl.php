<?php

APP::load_file('main/main', 'ctl');
class paylog_ctl extends main_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$paylog = dr('advertiser/paylog.get_list');
		TPL::assign('page', $page);
		TPL::assign('paylog', $paylog);
		TPL::display('paylog_getlist');
	}
}

?>
