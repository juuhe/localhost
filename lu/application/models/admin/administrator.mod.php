<?php

class administrator_mod extends app_models
{
	public $default_from = 'administrator';

	public function get_list()
	{
		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$password = md5(md5(post('password') . post('username') . '123987'));
		$data = array('username' => post('username'), 'password' => $password, 'userinfo' => post('userinfo'), 'rolesid' => (int) post('rolesid'), 'status	' => 1, 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($id)
	{
		$where = array('id' => (int) $id);

		if (post('password')) {
			$password = md5(md5(post('password') . post('username') . '123987'));
			$this->set('password', $password);
		}

		$data = array('userinfo' => post('userinfo'), 'rolesid' => (int) post('rolesid'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($id)
	{
		$where = array('id' => $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_username_one($username)
	{
		$where = array('username' => $username);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 'y');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($id)
	{
		$where = array('id' => (int) $id);
		$data = array('status' => 'n');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($id)
	{
		$count = $this->find_count();

		if ($count == 1) {
			return 'err_count';
		}

		if ($_SESSION['adminstratorid'] == post('id')) {
			return 'err_session';
		}

		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->delete();
	}

	public function count_roles($rolesid)
	{
		$where = array('rolesid' => (int) $rolesid);
		$this->where($where);
		$data = $this->find_count();
		$this->ar_where = array();
		return $data;
	}

	public function update_login_ip_time($username, $ip, $time)
	{
		$where = array('username' => $username);
		$data = array('loginip' => $ip, 'logintime' => $time);
		$this->where($where);
		$this->set('loginnum', 'loginnum+1', false);
		$this->set($data);
		$data = $this->update();
	}
}

?>
