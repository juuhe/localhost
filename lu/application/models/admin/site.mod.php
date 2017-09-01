<?php

class site_mod extends app_models
{
	public $default_from = 'site';

	public function get_list()
	{
		$search = request('search');
		$status = request('status');
		$sitetype = request('sitetype');
		$grade = request('grade');
		$alexapr = request('alexapr');
		$alexaprval = request('alexaprval');

		if ($search) {
			$type = request('searchtype');

			switch ($type) {
			case 'siteurl':
				$this->like('siteurl', $search);
				break;

			case 'sitename':
				$this->like('sitename', $search);
				break;

			case 'siteid':
				$this->where('siteid', (int) $search);
				break;

			case 'uid':
				$this->where('uid', (int) $search);
				break;

			case 'username':
				$u = dr('admin/user.get_one_username', $search);
				$this->where('uid', $u['uid']);
				break;
			}
		}

		if ($alexaprval) {
			$alexapr = request('alexapr');

			switch ($alexapr) {
			case 'alexa':
				$this->where('alexa >', $alexaprval);
				break;

			case 'pr':
				$this->where('pr >', $alexaprval);
				break;
			}
		}

		if (is_numeric($sitetype)) {
			$where = array('sitetype' => (int) $sitetype);
			$this->where($where);
		}

		if (is_numeric($grade)) {
			$where = array('grade' => (int) $grade);
			$this->where($where);
		}

		if (is_numeric($status)) {
			$where = array('status' => (int) $status);
			$this->where($where);
		}

		$this->order_by('siteid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$root_domain = get_root_domain(post('siteurl'));
		$user = dr('admin/user.get_one_username', post('username'));
		$data = array('uid' => $user['uid'], 'sitename' => post('sitename'), 'siteurl' => post('siteurl'), 'pertainurl' => $root_domain, 'siteinfo' => post('siteinfo'), 'sitetype' => post('sitetype'), 'beian' => post('beian'), 'grade' => post('grade'), 'dayip' => post('dayip'), 'addtime' => DATETIMES, 'status' => 3);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$data = array('sitename' => post('sitename'), 'siteinfo' => post('siteinfo'), 'sitetype' => post('sitetype'), 'beian' => post('beian'), 'grade' => post('grade'), 'dayip' => post('dayip'), 'denyinfo' => post('denyinfo'), 'pertainurl' => post('pertainurl'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$data = array('status' => '3', 'denyinfo' => post('log_text'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$data = array('status' => '2', 'denyinfo' => post('log_text'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($siteid)
	{
		$where = array('siteid' => (int) $siteid);
		$this->where($where);
		$data = $this->delete();
	}

	public function updata_alexa_pr($siteid, $alexa, $pr)
	{
		$where = array('siteid' => (int) $siteid);
		$data = array('alexa' => $alexa, 'pr' => $pr);
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_list_ok($uid)
	{
		$this->where(array('status' => 3, 'uid' => (int) $uid));
		$data = $this->get();
		return $data;
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
