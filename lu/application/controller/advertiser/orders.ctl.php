<?php

APP::load_file('main/main', 'ctl');
class orders_ctl extends main_ctl
{
	final public function get_list()
	{
		$get_timerange = $this->get_timerange();
		$timerange = get('timerange');
		$status = get('status');
		$orders = dr('advertiser/orders.get_list', $timerange, $status);
		$params = array('status' => $status, 'timerange' => $timerange);
		$page = APP::adapter('pager', 'default');
		$page->params_url = $params;
		TPL::assign('page', $page);
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('timerange', $timerange);
		TPL::assign('orders', $orders);
		TPL::display('orders_getlist');
	}
}

?>
