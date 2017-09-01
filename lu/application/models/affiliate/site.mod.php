<?php

class site_mod extends app_models
{

	/**
	* get_sord_id   站长获取网站添加顺序的id

   **/
	public $default_from = 'site';

	public function get_list()
	{
		$this->where(array('uid' => $_SESSION['affiliate']['uid']));
		$this->order_by('siteid');
		$data = $this->get();
		return $data;
	}

	public function get_list_ok()
	{
		$this->where(array('status' => 3, 'uid' => $_SESSION['affiliate']['uid']));
		$data = $this->get();
		return $data;
	}
	public function get_sord_id($id)
	{
		$this->where(array('status' => 3, 'uid' => $_SESSION['affiliate']['uid']));
		$this->order_by('siteid', asc);
		$data = $this->get();
		for($i=0; $i<count($data); $i++){
             if($data[$i]['siteid']==$id){
				 $sid =$i;
			 }
		}
		return $sid;
	}

	public function create_post($uid, $status)
	{
		$root_domain = get_root_domain(post('siteurl'));
		$data = array('uid' => (int) $uid, 'sitename' => post('sitename'), 'siteurl' => post('siteurl'), 'pertainurl' => $root_domain, 'siteinfo' => post('siteinfo'), 'sitetype' => post('sitetype'), 'beian' => post('beian'), 'dayip' => post('dayip'), 'addtime' => DATETIMES, 'status' => (int) $status);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function edit_post()
	{
		$where = array('siteid' => (int) post('siteid'), 'uid' => $_SESSION['affiliate']['uid']);
		$data = array('sitename' => post('sitename'), 'siteinfo' => post('siteinfo'), 'sitetype' => post('sitetype'), 'beian' => post('beian'), 'dayip' => post('dayip'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($siteid)
	{
		$where = array('siteid' => (int) $siteid, 'uid' => $_SESSION['affiliate']['uid']);
		$this->where($where);
		$data = $this->delete();
	}

	public function get_one($id)
	{
		$where = array('siteid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_url_one($url)
	{
		$where = array('siteurl' => $url);
		$this->where($where);
		$this->or_like('pertainurl', $url);
		$data = $this->find_one();
		return $data;
	}

	public function get_id_one($id)
	{
		$where = array('siteid' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_site_type()
	{
		$this->from('class');
		$this->where('type', 1);
		$this->order_by('classid');
		$data = $this->get();
		return $data;
	}
}

?>
