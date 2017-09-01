<?php

class article_mod extends app_models
{
	public $default_from = 'article';

	public function get_list($type = false, $search = false, $searchtype = false, $status = false)
	{
		if ($search) {
			switch ($searchtype) {
			case 'title':
				$this->like('title', $search);
				break;
			}
		}

		if (is_numeric($type)) {
			$where = array('type' => (int) $type);
			$this->where($where);
		}

		if ($status) {
			$where = array('status' => $status);
			$this->where($where);
		}

		$this->order_by('articleid');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function add_post()
	{
		$data = array('type' => (int) post('type'), 'color' => post('color'), 'content' => post('content', '', false), 'title' => post('title'), 'status' => 1, 'top' => (int) post('top'), 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}

	public function update_post($articleid)
	{
		$where = array('articleid' => (int) $articleid);
		$data = array('type' => (int) post('type'), 'color' => post('color'), 'content' => post('content', '', false), 'title' => post('title'), 'top' => (int) post('top'));
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function get_one($articleid)
	{
		$where = array('articleid' => (int) $articleid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function unlock($articleid)
	{
		$where = array('articleid' => (int) $articleid);
		$data = array('status' => 'y');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function lock($articleid)
	{
		$where = array('articleid' => (int) $articleid);
		$data = array('status' => 'n');
		$this->where($where);
		$this->set($data);
		$data = $this->update();
	}

	public function del($articleid)
	{
		$where = array('articleid' => (int) $articleid);
		$this->where($where);
		$data = $this->delete();
	}
}

?>
