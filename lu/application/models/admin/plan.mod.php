<?php

class plan_mod extends app_models
{
	public $default_from = 'plan';

	public function get_list($plantype = false, $status = false, $searchtype = false, $search = false)
	{
		if ($search && $searchtype) {
			switch ($searchtype) {
			case 'planname':
				$this->like('planname', $search);
				break;

			case 'planid':
				$this->where('planid', $search);
				break;

			case 'uid':
				$this->where('uid', $search);
				break;
			}
		}

		if ($plantype) {
			$this->where('plantype', $plantype);
		}

		if (is_numeric($status)) {
			if ($status == 3) {
				$this->where('status', (int) $status);
				$this->where('status', 4, 'or');
			}
			else {
				$this->where('status', (int) $status);
			}
		}

		$this->order_by('planid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post($logo_imageurl = false)
	{
		$classprice = array('classprice_mark' => post('classprice_mark'), 'classprice_mark_info' => post('classprice_mark_info'), 'classprice_aff' => post('classprice_aff'), 'classprice_adv' => post('classprice_adv'), 'classprice_memo' => post('classprice_memo'));
		$data = array('uid' => post('uid'), 'planname' => post('planname'), 'priceadv' => floatval(post('priceadv')), 'price' => 0 < post('price') ? floatval(post('price')) : 0, 'mobile_price' => 0 < post('mobile_price') ? floatval(post('mobile_price')) : 1, 'gradeprice' => (int) post('gradeprice'), 'classprice' => serialize($classprice), 'siteprice' => serialize(post('siteprice')), 'budget' => floatval(post('budget')), 'expire' => post('expire') != '0000-00-00' ? post('expire_year') . '-' . post('expire_month') . '-' . post('expire_day') : post('expire'), 'checkplan' => serialize(post('acl')), 'plantype' => post('plantype'), 'datatime' => post('datatime'), 'pkey' => post('pkey'), 'cookie' => post('cookie'), 'linkon' => post('linkon'), 'linkurl' => post('linkurl'), 'clearing' => post('clearing'), 'planinfo' => post('planinfo'), 'audit' => post('audit'), 'restrictions' => post('restrictions') ? (int) post('restrictions') : 1, 'resuid' => preg_replace('/\\s+/', '', post('resuid')), 'sitelimit' => post('sitelimit') ? (int) post('sitelimit') : 1, 'limitsiteid' => preg_replace('/\\s+/', '', post('limitsiteid')), 'deduction' => 0 < post('deduction') ? post('deduction') : 0, 'resuid' => post('resuid'), 'top' => 0 < post('top') ? (int) post('top') : 0, 'in_site' => (int) post('in_site'), 'priceinfo' => post('priceinfo'), 'classid' => post('classid'), 'size' => post('size'), 'status' => 1, 'addtime' => DATETIMES);

		if ($logo_imageurl) {
			$data['logo'] = $logo_imageurl;
		}

		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id, $logo_imageurl = false, $status = false)
	{
		$where = array('planid' => (int) $id);
		$classprice = array('classprice_mark' => post('classprice_mark'), 'classprice_mark_info' => post('classprice_mark_info'), 'classprice_aff' => post('classprice_aff'), 'classprice_adv' => post('classprice_adv'), 'classprice_memo' => post('classprice_memo'));
		$data = array('planname' => post('planname'), 'priceadv' => floatval(post('priceadv')), 'price' => 0 < post('price') ? floatval(post('price')) : 0, 'mobile_price' => 0 < post('mobile_price') ? floatval(post('mobile_price')) : 1, 'gradeprice' => (int) post('gradeprice'), 'classprice' => serialize($classprice), 'siteprice' => serialize(post('siteprice')), 'budget' => floatval(post('budget')), 'expire' => post('expire') != '0000-00-00' ? post('expire_year') . '-' . post('expire_month') . '-' . post('expire_day') : post('expire'), 'checkplan' => serialize(post('acl')), 'datatime' => post('datatime'), 'pkey' => post('pkey'), 'cookie' => post('cookie'), 'linkon' => post('linkon'), 'linkurl' => post('linkurl'), 'clearing' => post('clearing'), 'planinfo' => post('planinfo'), 'audit' => post('audit'), 'restrictions' => post('restrictions') ? (int) post('restrictions') : 1, 'resuid' => preg_replace('/\\s+/', '', post('resuid')), 'sitelimit' => post('sitelimit') ? (int) post('sitelimit') : 1, 'limitsiteid' => preg_replace('/\\s+/', '', post('limitsiteid')), 'deduction' => 0 < post('deduction') ? post('deduction') : 0, 'resuid' => post('resuid'), 'top' => 0 < post('top') ? (int) post('top') : 0, 'in_site' => (int) post('in_site'), 'priceinfo' => post('priceinfo'), 'classid' => post('classid'), 'size' => post('size'));

		if ($status) {
			$data['status'] = 1;
		}

		if ($logo_imageurl) {
			$data['logo'] = $logo_imageurl;
		}

		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_price($planid, $price)
	{
		$where = array('planid' => (int) $planid);
		$data = array('price' => $price);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_budget($planid, $budget)
	{
		$where = array('planid' => (int) $planid);
		$data = array('budget' => $budget);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	final public function update_clearing($planid, $clearing)
	{
		$where = array('planid' => (int) $planid);
		$data = array('clearing' => $clearing);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	final public function update_deduction($planid, $deduction)
	{
		$where = array('planid' => (int) $planid);
		$data = array('deduction' => $deduction);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function update_priceadv($planid, $priceadv)
	{
		$where = array('planid' => (int) $planid);
		$data = array('priceadv' => $priceadv);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('planid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all()
	{
		$this->order_by('planid');
		$data = $this->get();
		return $data;
	}

	public function get_new_num()
	{
		$where = array('status' => 0);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_status01_num()
	{
		$this->where('status', 0);
		$this->where('status', 6, 'OR');
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function get_edit_num()
	{
		$where = array('status' => 6);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
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

	public function unlock($id)
	{
		$where = array('planid' => (int) $id);
		$data = array('status' => 1);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('planid' => (int) $id);
		$data = array('status' => 2);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$where = array('planid' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
