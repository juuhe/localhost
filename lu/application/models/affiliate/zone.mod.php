<?php

class zone_mod extends app_models
{
	public $default_from = 'zone';

	public function get_list($plantype)
	{
		if ($plantype) {
			$this->where('plantype', $plantype);
		}

		$this->where('uid', $_SESSION['affiliate']['uid']);
		$this->order_by('zoneid');
		$data = $this->get();
		return $data;
	}

	public function get_all()
	{
		$this->where('uid', $_SESSION['affiliate']['uid']);
		$this->order_by('zoneid');
		$data = $this->get();
		return $data;
	}

	public function create_post()
	{
		$specs = post('specs');
		$wh = explode('x', $specs);
		$data = array('uid' => (int) $_SESSION['affiliate']['uid'], 'zonename' => post('zonename'), 'plantype' => post('plantype'), 'width' => (int) $wh[0], 'height' => (int) $wh[1], 'adstyleid' => (int) post('styleid'), 'adtplid' => (int) post('adtplid'), 'viewtype' => (int) post('viewtype'), 'viewadsid' => '' . @implode(',', post('viewadsid')) . '', 'codestyle' => json_encode(post('color')), 'htmlcontrol' => serialize(post('a_h')), 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return $this->get_insert_id();
	}
    public function get_site(){
		$where = array('uid' => $_SESSION['affiliate']['uid']);
		

	}
	public function edit_post()
	{
		$where = array('zoneid' => (int) post('zoneid'), 'uid' => $_SESSION['affiliate']['uid']);
		$data = array('zonename' => post('zonename'), 'adstyleid' => (int) post('styleid'), 'viewtype' => (int) post('viewtype'), 'viewadsid' => @implode(',', post('viewadsid')), 'codestyle' => json_encode(post('color')), 'htmlcontrol' => serialize(post('a_h')), 'uptime' => DATETIMES);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($zoneid)
	{
		$where = array('zoneid' => (int) $zoneid, 'uid' => $_SESSION['affiliate']['uid']);
		$this->where($where);
		$data = $this->delete();
	}
	public function id($siteid)
	{
		$where = array('uid' => $_SESSION['affiliate']['uid']);
		$sid=dr('affiliate/site.get_sord_id', $siteid);   //获取网站的排序id
		$this->where($where);
		$this->order_by('zoneid', asc);
		$data = $this->get();
		echo $data[$sid]['zoneid'];
	}

	public function get_one($id)
	{
		$where = array('zoneid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
