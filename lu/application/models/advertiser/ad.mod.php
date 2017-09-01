<?php

class ad_mod extends app_models
{
	public $default_from = 'ads';

	public function get_list($planid = false, $adtplid = false)
	{
		$this->select('ads.*,plan.plantype,plan.planname,plan.status AS planstatus');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('plan.uid', $_SESSION['advertiser']['uid']);

		if ($planid) {
			$this->where('plan.planid', $planid);
		}

		if ($adtplid) {
			$this->where('ads.adtplid', $adtplid);
		}

		$this->order_by('ads.adsid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function create_post($imageurl)
	{
		$specs = explode('x', post('specs'));
		$data = array('width' => (int) $specs[0], 'height' => (int) $specs[1], 'imageurl' => $imageurl, 'alt' => post('alt'), 'url' => post('url'), 'adinfo' => post('adinfo'), 'status' => 0, 'planid' => post('planid'), 'files' => post('files'), 'uid' => $_SESSION['advertiser']['uid'], 'adtplid' => post('adtplid'), 'headline' => post('headline'), 'description' => post('description'), 'dispurl' => post('dispurl'), 'htmlcode' => post('htmlcode'), 'htmlfile' => post('htmlfile'), 'showtype' => post('showtype'), 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function edit_post($id, $imageurl = false)
	{
		$where = array('adsid' => (int) $id, 'uid' => $_SESSION['advertiser']['uid']);
		$data = array('alt' => post('alt'), 'url' => post('url'), 'adinfo' => post('adinfo'), 'files' => post('files'), 'headline' => post('headline'), 'description' => post('description'), 'dispurl' => post('dispurl'), 'htmlcode' => post('htmlcode'), 'htmlfile' => post('htmlfile'), 'showtype' => post('showtype'), 'htmlcontrol' => serialize(post('zd')));

		if ($imageurl) {
			$data['imageurl'] = $imageurl;
		}

		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_ad_one($id)
	{
		$where = array('adsid' => (int) $id, 'uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all()
	{
		$where = array('uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_ad_plan_one($id)
	{
		$this->select('plan.plantype,ads.*');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$where = array('ads.adsid' => (int) $id, 'ads.uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}
}

?>
