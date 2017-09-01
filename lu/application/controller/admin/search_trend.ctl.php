<?php

APP::load_file('admin/admin', 'ctl');
class search_trend_ctl extends admin_ctl
{
	final public function get_list()
	{
		$page = APP::adapter('pager', 'default');
		$timerange = request('timerange');
		$get_timerange = $this->get_timerange();
		$search_sum = dr('admin/search_trend.get_sum_data');
		$search_sum = sort_array($search_sum, 'num', 'desc');
		$search = dr('admin/search_trend.get_data');
		$params = array('searchtype' => request('searchtype'), 'searchval' => request('searchval'), 'timerange' => $timerange);
		$page->params_url = $params;
		TPL::assign('get_timerange', $get_timerange);
		TPL::assign('search_sum', $search_sum);
		TPL::assign('search', $search);
		TPL::assign('page', $page);
		TPL::assign('timerange', $timerange);
		TPL::display('search_trend');
	}
}

?>
