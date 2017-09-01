<?php

class ad_mod extends app_models
{
	public $default_from = 'ads';

	public function get_plantype_ok($promotiontype = false)
	{
		$this->select('plan.plantype');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->from('users AS users');
		$this->from('adtype AS adtype');
		$this->from('adtpl AS adtpl');
		$this->where('users.status', '2');
		$this->where('(ads.status= \'3\' OR ads.status= \'2\')');
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('users.uid', ' plan.uid', 'AND', false);
		$this->where('adtpl.adtypeid', ' adtype.id', 'AND', false);
		$this->where('ads.adtplid', ' adtpl.tplid', 'AND', false);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');
		$this->group_by('plan.plantype');
		$data = $this->get();
		return $data;
	}

	public function get_adtpl_ok($plantype = false, $promotiontype = false)
	{
		$this->select('ads.adtplid');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->from('users AS users');
		$this->from('adtype AS adtype');
		$this->from('adtpl AS adtpl');
		$this->where('users.status', '2');
		$this->where('(ads.status= \'3\' OR ads.status= \'2\')');
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('users.uid', ' plan.uid', 'AND', false);
		$this->where('adtpl.adtypeid', ' adtype.id', 'AND', false);
		$this->where('ads.adtplid', ' adtpl.tplid', 'AND', false);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');
		$this->group_by('ads.adtplid');

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		$data = $this->get();
		return $data;
	}

	public function get_ads_ok($plantype = false, $adtplid = false, $width = false, $height = false)
	{
		$this->select('ads.*,plan.plantype,plan.audit,plan.planname');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->from('users AS users');

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		if ($adtplid) {
			$this->where('ads.adtplid', $adtplid);
		}

		if ($width) {
			$this->where('ads.width', $width);
		}

		if ($height) {
			$this->where('ads.height', $height);
		}

		$asql = '  AND ((p.restrictions=3 && FIND_IN_SET(' . $_SESSION['uid'] . ",p.resuid)=0) OR (p.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['uid'] . ',p.resuid)>0) OR p.restrictions=1)';
		$this->where('users.status', '2');
		$this->where('(ads.status= \'3\' OR ads.status= \'2\')');
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('users.uid', ' plan.uid', 'AND', false);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');
		$data = $this->get();
		return $data;
	}

	public function get_adtplid_ads($adtplid, $width = false, $height = false, $planid = false, $plantype = false)
	{
		$adtplid = preg_replace('/-\\d/', '', $adtplid);
		$adtplid = explode(',', trim($adtplid, ','));
		$this->select('ads.imageurl,ads.width,ads.height,ads.headline,ads.url,ads.adsid,ads.adtplid,ads.headline');
		$this->select('plan.plantype,plan.planname,plan.gradeprice,plan.price,plan.planid,plan.audit');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->from('users AS users');
		$this->where('users.status', '2');
		$this->where('(ads.status= \'3\' OR ads.status= \'2\')');
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('users.uid', ' plan.uid', 'AND', false);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');
		if ($width && $height) {
			$this->where('ads.width', (int) $width);
			$this->where('ads.height', (int) $height);
		}

		if ($planid) {
			$this->where('plan.planid', (int) $planid);
		}

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		$this->where_in('ads.adtplid', $adtplid);
		$data = $this->get();
		return $data;
	}

	public function get_width_height_adtplid($width, $height, $adtplid)
	{
		$this->select('ads.*,plan.plantype,plan.audit,plan.planname');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('ads.width', (int) $width);
		$this->where('ads.height', (int) $height);
		$this->where('ads.adtplid', (int) $adtplid);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');
		$this->group_by('plan.planid');
		$data = $this->get();
		return $data;
	}

	public function get_planid_adtplid($planid)
	{
		$this->select('ads.adtplid,count(*) AS num');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('plan.planid', (int) $planid);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');
		$this->group_by('ads.adtplid');
		$data = $this->get();
		return $data;
	}

	public function get_planid_adnum($planid)
	{
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('plan.planid', (int) $planid);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_statstype($type)
	{
		if (!$type) {
			return NULL;
		}

		$this->from('adtpl');
		$this->select('id,name');
		$this->where('FIND_IN_SET(\'' . $type . '\',statstype)>0');
		$this->order_by('id', 'ASC');
		$data = $this->get();
		return $data;
	}

	public function get_adtplid_ads_specs($adtplid, $planid = false, $plantype = false)
	{
		$this->select('ads.width,ads.height');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$this->from('users AS users');
		$this->where('users.status', '2');
		$this->where('(ads.status= \'3\' OR ads.status= \'2\')');
		$this->where('(plan.status= \'3\' OR plan.status= \'1\')');
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$this->where('users.uid', ' plan.uid', 'AND', false);
		$this->where('ads.adtplid', (int) $adtplid);
		$this->where('  ((plan.restrictions=3 && FIND_IN_SET(' . $_SESSION['affiliate']['uid'] . ",plan.resuid)=0) OR (plan.restrictions=2 &&\r\n\t\t FIND_IN_SET(" . $_SESSION['affiliate']['uid'] . ',plan.resuid)>0) OR plan.restrictions=1)');

		if ($planid) {
			$this->where('plan.planid', (int) $planid);
		}

		if ($plantype) {
			$this->where('plan.plantype', $plantype);
		}

		$this->group_by('ads.width,ads.height');
		$data = $this->get();
		return $data;
	}

	public function get_ad_one($id)
	{
		$where = array('adsid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_ad_plan_one($id)
	{
		$this->select('plan.plantype,ads.*');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$where = array('adsid' => (int) $id);
		$this->where($where);
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}
}

?>
