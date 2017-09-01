<?php

APP::load_file('admin/admin', 'ctl');
class site_ctl extends admin_ctl
{
	final public function get_list()
	{
		$status = request('status');
		$sitetype = request('sitetype');
		$site = dr('admin/site.get_list');
		$searchtype = request('searchtype');
		$grade = request('grade');
		$search = request('search');
		$status = request('status');
		$alexapr = request('alexapr');
		$alexaprval = request('alexaprval');
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'sitetype' => $sitetype, 'status' => $status, 'alexapr' => $alexapr, 'alexaprval' => $alexaprval, 'grade' => $grade);
		$page->params_url = $params;
		$sitetype_data = dr('admin/class.get_all', 1);
		TPL::assign('sitetype_data', $sitetype_data);
		TPL::assign('site', $site);
		TPL::assign('page', $page);
		TPL::assign('grade', $grade);
		TPL::assign('status', $status);
		TPL::assign('alexapr', $alexapr);
		TPL::assign('alexaprval', $alexaprval);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('site');
	}

	final public function add()
	{
		$sitetype = dr('admin/class.get_all', 1);
		TPL::assign('sitetype', $sitetype);
		TPL::display('site_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/site.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/site.get_list');
		}
	}

	final public function edit()
	{
		$sitetype = dr('admin/class.get_all', 1);
		TPL::assign('sitetype', $sitetype);
		$site = dr('admin/site.get_one', (int) get('siteid'));
		TPL::assign('site', $site);
		TPL::display('site_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/site.update_post', (int) post('siteid'));
			$_SESSION['succ'] = true;
			redirect('admin/site.edit?siteid=' . post('siteid'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$siteid = explode(',', post('siteid'));

			foreach ($siteid as $id) {
				$site = dr('admin/site.get_one', (int) $id);
				$u = dr('admin/user.get_one', (int) $site['uid']);
				$q = dr('admin/site.unlock', (int) $id);
				if (($site['status'] == 0) && in_array('siteactivate', explode(',', $GLOBALS['C_ZYIIS']['tomail']))) {
					$body = @file_get_contents(TPL_DIR . '/email/siteactivate.html');
					$body = str_replace('{username}', $u['username'], $body);
					$body = str_replace('{usersiteurl}', $site['siteurl'], $body);
					Sendmail($u['email'], '', $body);
				}
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$siteid = explode(',', post('siteid'));

			foreach ($siteid as $id) {
				$q = dr('admin/site.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$siteid = explode(',', post('siteid'));

			foreach ($siteid as $id) {
				$q = dr('admin/site.del', (int) $id);
			}
		}
	}

	final public function get_alexapr()
	{
		$data = get('data');
		$e = explode(',', $data);
		$siteid = $e[1];
		$url = $e[0];
		$g = app::load_class('alexapr', 'cls');
		$pr = $g->get_pr($url);
		$alexa = $g->get_alexa($url);
		$q = dr('admin/site.updata_alexa_pr', $siteid, $alexa, $pr);
		echo $alexa . '/' . (int) $pr;
	}
}

?>
