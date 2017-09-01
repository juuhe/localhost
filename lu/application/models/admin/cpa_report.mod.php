<?php

class cpa_report_mod extends app_models
{
	public $default_from = 'cpa_report';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');
		$timerange = request('timerange');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'username':
				$this->where('u.username', $search);
				break;

			case 'uid':
				$this->where('c.uid', (int) $search);
				break;

			case 'planid':
				$this->where('p.planid', (int) $search);
				break;

			case 'planname':
				$this->where('p.planname', $search);
				break;
			}
		}

		if (is_numeric($status)) {
			$where = array('c.status' => (int) $status);
			$this->where($where);
		}

		$this->select('c.*,u.username,u.uid,p.planname');
		$this->from('cpa_report AS c');
		$this->from('users AS u');
		$this->from('plan AS p');
		$this->where('c.uid', ' u.uid', 'AND', false);
		$this->where('c.planid', ' p.planid', 'AND', false);
		$this->order_by('c.id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_id_one($id)
	{
		$this->where('id', $id);
		$data = $this->find_one();
		return $data;
	}

	public function get_ordersn_one($ordersn)
	{
		$this->where('ordersn', $ordersn);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
		return $this->affected_rows();
	}

	public function lock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
