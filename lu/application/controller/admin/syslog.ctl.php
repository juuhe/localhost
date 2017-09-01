<?php

APP::load_file('admin/admin', 'ctl');
class syslog_ctl extends admin_ctl
{
	final public function get_list()
	{
		$file = APP_PATH . '/config/resource.php';
		include_once $file;
		$syslog = dr('admin/syslog.get_list');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$type = request('type');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'type' => $type);
		$page->params_url = $params;
		TPL::assign('ac', $ac);
		TPL::assign('ctl', $resource);
		TPL::assign('syslog', $syslog);
		TPL::assign('page', $page);
		TPL::assign('type', $type);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('syslog');
	}
}

?>
