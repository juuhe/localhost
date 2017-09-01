<?php

APP::load_file('main/main', 'ctl');
class apply_ctl extends main_ctl
{
	final public function get_list()
	{
		$status = request('status');
		$searchtype = request('searchtype');
		$search = request('search');
		$apply = dr('advertiser/apply.get_list', $status, $search, $searchtype);
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('apply', $apply);
		TPL::assign('page', $page);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::assign('status', $status);
		TPL::display('apply_getlist');
	}

	final public function unlock()
	{
		if (is_post()) {
			$applyid = explode(',', post('id'));

			foreach ($applyid as $id) {
				$g = dr('advertiser/apply.get_one', (int) $id);
				$p = dr('advertiser/plan.get_one', (int) $g['planid']);

				if (!$p) {
					continue;
				}

				dr('advertiser/apply.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$applyid = explode(',', post('id'));

			foreach ($applyid as $id) {
				$g = dr('advertiser/apply.get_one', (int) $id);
				$p = dr('advertiser/plan.get_one', (int) $g['planid']);

				if (!$p) {
					continue;
				}

				dr('advertiser/apply.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$applyid = explode(',', post('id'));

			foreach ($applyid as $id) {
				$g = dr('advertiser/apply.get_one', (int) $id);
				$p = dr('advertiser/plan.get_one', (int) $g['planid']);

				if (!$p) {
					continue;
				}
			}
		}
	}
}

?>
