<?php

class plan_mod extends app_models
{
	public $default_from = 'plan';

	public function get_list($plantype = false, $classid = false, $page = true)
	{
		if ($plantype) {
			$this->where('plantype', $plantype);
		}

		if ($classid) {
			$this->where('classid', $classid);
		}

		$where = array('uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$this->order_by('planid');

		if ($page) {
			$this->pager();
		}
		else {
			$this->ar_limit = array();
		}

		$data = $this->get();
		return $data;
	}

	public function get_all_plantype()
	{
		$this->select('plantype');
		$where = array('uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$this->group_by('plantype');
		$data = $this->get();
		return $data;
	}

	public function get_all()
	{
		$where = array('uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$data = $this->get();
		return $data;
	}

	public function get_one($id)
	{
		$where = array('planid' => (int) $id, 'uid' => $_SESSION['advertiser']['uid']);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function add_post($logo_imageurl = false)
	{
		$classprice = array('classprice_mark' => post('classprice_mark'), 'classprice_mark_info' => post('classprice_mark_info'), 'classprice_aff' => '', 'classprice_adv' => post('classprice_adv'), 'classprice_memo' => post('classprice_memo'));
		$data = array('uid' => $_SESSION['advertiser']['uid'], 'planname' => post('planname'), 'priceadv' => floatval(post('priceadv')), 'gradeprice' => (int) post('gradeprice'), 'classprice' => serialize($classprice), 'budget' => floatval(post('budget')), 'expire' => post('expire') != '0000-00-00' ? post('expire_year') . '-' . post('expire_month') . '-' . post('expire_day') : post('expire'), 'checkplan' => serialize(post('acl')), 'plantype' => post('plantype'), 'datatime' => post('datatime'), 'pkey' => post('pkey'), 'cookie' => post('cookie'), 'linkon' => post('linkon'), 'linkurl' => post('linkurl'), 'clearing' => post('clearing'), 'planinfo' => post('planinfo'), 'audit' => post('audit'), 'priceinfo' => post('priceinfo'), 'classid' => post('classid'), 'size' => post('size'), 'status' => 0, 'addtime' => DATETIMES);

		if ($logo_imageurl) {
			$data['logo'] = $logo_imageurl;
		}

		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id, $logo_imageurl = false)
	{
		$where = array('planid' => (int) $id, 'uid' => $_SESSION['advertiser']['uid']);
		$classprice = array('classprice_mark' => post('classprice_mark'), 'classprice_mark_info' => post('classprice_mark_info'), 'classprice_aff' => post('classprice_aff'), 'classprice_adv' => '', 'classprice_memo' => post('classprice_memo'));
		$data = array('planname' => post('planname'), 'priceadv' => floatval(post('priceadv')), 'gradeprice' => (int) post('gradeprice'), 'classprice' => serialize($classprice), 'budget' => floatval(post('budget')), 'expire' => post('expire') != '0000-00-00' ? post('expire_year') . '-' . post('expire_month') . '-' . post('expire_day') : post('expire'), 'checkplan' => serialize(post('acl')), 'datatime' => post('datatime'), 'pkey' => post('pkey'), 'cookie' => post('cookie'), 'linkon' => post('linkon'), 'linkurl' => post('linkurl'), 'clearing' => post('clearing'), 'planinfo' => post('planinfo'), 'audit' => post('audit'), 'in_site' => (int) post('in_site'), 'priceinfo' => post('priceinfo'), 'classid' => post('classid'), 'size' => post('size'), 'status' => 6);

		if ($logo_imageurl) {
			$data['logo'] = $logo_imageurl;
		}

		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}
}

?>
