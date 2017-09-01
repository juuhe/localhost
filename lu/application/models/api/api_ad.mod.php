<?php

class api_ad_mod extends app_models
{
	public $default_from = 'ads';

	public function get_ad_plan_user_ok($plantype, $adtplid, $adnum = 1, $width = false, $height = false)
	{
		if (0 < $adnum) {
			$this->where(array('a.width' => $width, 'a.height' => $height));
		}

		$this->from('ads AS a');
		$this->from('plan AS p');
		$this->from('users AS u');
		$this->select("a.headline,a.description,a.dispurl,a.showtype,\r\n\t\t\t\ta.adsid,a.imageurl,a.alt,a.url,a.adtplid,a.priority,\r\n\t\t\t\ta.htmlcode,a.htmlcontrol,a.htmlfile,a.width,a.height,a.showtype");
		$this->select("p.checkplan,p.expire,p.planid,p.audit,\r\n\t\t\t\tp.clearing,p.plantype,p.uid,p.resuid,p.restrictions,p.sitelimit,p.limitsiteid");
		$this->where(array('p.plantype' => $plantype, 'p.status ' => 1, 'a.adtplid' => $adtplid, 'a.priority >' => 0, 'a.status ' => 3, 'u.type' => 2, 'u.status' => 2, 'u.money >' => 0.5));
		$this->where('(p.expire >', date('Y-m-d', TIMES));
		$this->where('p.expire =', '\'0000-00-00\')', 'OR', false);
		$this->where('a.planid', ' p.planid', 'AND', false);
		$this->where('u.uid', ' p.uid', 'AND', false);
		$data = $this->get();
		return $data;
	}

	public function get_one($adsid)
	{
		$where = array('adsid' => (int) $adsid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_ad_plan_one($adsid)
	{
		$this->select('plan.uid AS advuid,plan.planid,plan.plantype,plan.plantype,ads.*');
		$this->from('plan AS plan');
		$this->from('ads AS ads');
		$where = array('ads.adsid' => (int) $adsid);
		$this->where($where);
		$this->where('ads.planid', ' plan.planid', 'AND', false);
		$data = $this->find_one();
		return $data;
	}
}

?>
