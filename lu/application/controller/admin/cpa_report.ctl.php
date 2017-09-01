<?php

APP::load_file('admin/admin', 'ctl');
class cpa_report_ctl extends admin_ctl
{
	final public function get_list()
	{
		$searchtype = request('searchtype');
		$search = request('search');
		$timerange = request('timerange');
		$status = request('status');
		$get_timerange = $this->get_timerange();
		$report = dr('admin/cpa_report.get_list', $status);
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('report', $report);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::assign('timerange', $timerange);
		TPL::assign('get_timerange', $get_timerange);
		TPL::display('cpa_report');
	}

	final public function unlock()
	{
		if (is_post()) {
			$reportid = explode(',', post('id'));

			foreach ($reportid as $id) {
				$q = dr('admin/cpa_report.get_id_one', (int) $id);

				if ($q['status'] == 0) {
					dr('admin/cpa_report.unlock', (int) $id);
					api('cpa.update_satas_AND_update_user_money_data', $q['unique_id'], $q['planid']);
				}
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$reportid = explode(',', post('id'));

			foreach ($reportid as $id) {
				$g = dr('admin/cpa_report.get_id_one', (int) $id);

				if ($g['status'] != '0') {
					continue;
				}

				$q = dr('admin/cpa_report.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$reportid = explode(',', post('id'));

			foreach ($reportid as $id) {
				$q = dr('admin/cpa_report.del', (int) $id);
			}
		}
	}
}

?>
