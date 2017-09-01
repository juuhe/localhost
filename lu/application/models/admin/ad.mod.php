<?php

class ad_mod extends app_models
{
	public $default_from = 'ads';

	public function get_list($plantype = false, $status = false, $searchtype = false, $search = false, $adtplid = false)
	{
		if ($search && $searchtype) {
			switch ($searchtype) {
			case 'adsid':
				$this->like('ads.adsid', $search);
				break;

			case 'planid':
				$this->where('ads.planid', $search);
				break;

			case 'uid':
				$this->where('ads.uid', $search);
				break;

			case 'url':
				$this->where('ads.url', $search);
				break;
			}
		}

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		if (is_numeric($status)) {
			if ($status == 6) {
				$this->where('plan.status !=', 1);
			}
			else if ($status == 3) {
				$this->where('plan.status', 1);
				$this->where('ads.status', 3);
			}
			else {
				$this->where('ads.status', (int) $status);
			}
		}

		if (is_numeric($adtplid) && (0 < $adtplid)) {
			$this->where('ads.adtplid', (int) $adtplid);
		}

		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->order_by('ads.adsid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post($imageurl)
	{
		$plan = dr('admin/plan.get_one', (int) post('planid'));
		$specs = explode('x', post('specs'));
		$data = array('width' => (int) $specs[0], 'height' => (int) $specs[1], 'specsid' => (int) $specs[2], 'imageurl' => $imageurl, 'adname' => post('adname'), 'alt' => post('alt'), 'url' => post('url'), 'adinfo' => post('adinfo'), 'status' => 3, 'planid' => post('planid'), 'files' => post('files'), 'uid' => $plan['uid'], 'adtplid' => post('adtplid'), 'headline' => post('headline'), 'description' => post('description'), 'dispurl' => post('dispurl'), 'htmlcode' => post('htmlcode'), 'htmlfile' => post('htmlfile'), 'showtype' => post('showtype'), 'htmlcontrol' => serialize(post('zd')), 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id, $imageurl = false)
	{
		$where = array('adsid' => (int) $id);
		$data = array('adname' => post('adname'), 'alt' => post('alt'), 'url' => post('url'), 'adinfo' => post('adinfo'), 'files' => post('files'), 'headline' => post('headline'), 'description' => post('description'), 'dispurl' => post('dispurl'), 'htmlcode' => post('htmlcode'), 'htmlfile' => post('htmlfile'), 'showtype' => post('showtype'), 'priority' => post('priority'), 'htmlcontrol' => serialize(post('zd')));

		if ($imageurl) {
			$data['imageurl'] = $imageurl;
		}

		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('adsid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_num($uid)
	{
		$where = array('uid' => (int) $uid);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status01_num()
	{
		$this->where('status', 0);
		$this->where('status', 2, 'OR');
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('adsid' => (int) $id);
		$data = array('status' => 3);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('adsid' => (int) $id);
		$data = array('status' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$where = array('adsid' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}

	public function del_planid($planid)
	{
		$where = array('planid' => (int) $planid);
		$this->where($where);
		$data = $this->delete();
	}

	public function update_adname($adsid, $adname)
	{
		$where = array('adsid' => (int) $adsid);
		$data = array('adname' => $adname);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_priority($adsid, $priority)
	{
		$where = array('adsid' => (int) $adsid);
		$data = array('priority' => $priority);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}
}

?>
