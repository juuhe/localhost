<?php

class zone_mod extends app_models
{
	public $default_from = 'zone';

	public function get_list()
	{
		$search = request('search');
		$sitetype = request('sitetype');
		$plantype = request('plantype');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'zoneid':
				$this->where('zoneid', $search);
				break;

			case 'siteid':
				$this->where('siteid', (int) $search);
				break;

			case 'uid':
				$this->where('uid', (int) $search);
				break;
			}
		}

		if ($plantype) {
			$this->where('plantype', $plantype);
		}

		$this->order_by('zoneid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function get_one($zoneid)
	{
		$where = array('zoneid' => (int) $zoneid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function del($zoneid)
	{
		$where = array('zoneid' => (int) $zoneid);
		$this->where($where);
		$data = $this->delete();
	}

	public function implant_zone($ads)
	{
		$where = array('adtplid' => $ads['adtplid'], 'width' => $ads['width'], 'height' => $ads['height']);
		$this->where($where);
		$this->where('FIND_IN_SET(\'' . $ads['adsid'] . '\' ,viewadsid)=0');
		$this->set('viewadsid', 'concat(`viewadsid`,",' . $ads['adsid'] . '")', false);
		$data = $this->update();
	}

	public function get_sum_recommend($uid)
	{
		$where = array('uid' => (int) $uid);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}
}

?>
