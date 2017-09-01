<?php

class ip_mod extends app_models
{
	public $default_from = 'log_visit';

	public function get_visit_all()
	{
		$planid = request('planid');
		$timerange = request('timerange');
		$searchval = request('searchval');
		$searchtype = request('searchtype');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('DATE_FORMAT(last_time,\'%Y%m%d\') >=' => date('Ymd', $time_begin), 'DATE_FORMAT(last_time,\'%Y%m%d\') <=' => date('Ymd', $time_end)));
		}

		if ($planid) {
			$this->where(array('planid' => (int) $planid));
		}

		if ($searchval) {
			$searchtype = preg_replace('/[^a-z]/i', '', $searchtype);
			$this->where(array($searchtype => $searchval));
		}

		$this->order_by('last_time');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function del($id)
	{
		$e = explode('_', $id);
		$last_time = $e[0];
		$planid = $e[1];
		$adsid = $e[2];
		$uid = $e[3];
		$ip = $e[4];
		$where = array('ip' => $ip, 'planid' => $planid, 'adsid' => $adsid, 'uid' => $uid, 'last_time' => $last_time);
		$this->where($where);
		$data = $this->delete();
	}

	public function truncate_data()
	{
		$this->truncate();
	}
}

?>
