<?php

APP::load_file('admin/admin', 'ctl');
class gift_ctl extends admin_ctl
{
	final public function get_list()
	{
		$status = request('status');
		$gift = dr('admin/gift.get_list');
		$page = APP::adapter('pager', 'default');
		$searchtype = request('searchtype');
		$search = request('search');
		$status = request('status');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('gift', $gift);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('gift');
	}

	final public function add()
	{
		TPL::display('gift_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$imageurl = $this->_upload();

			if ($imageurl === false) {
				$this->msg();
			}

			$q = dr('admin/gift.add_post', $imageurl);
			$_SESSION['succ'] = true;
			redirect('admin/gift.get_list');
		}
	}

	final public function edit()
	{
		$g = dr('admin/gift.get_one', (int) get('id'));
		TPL::assign('g', $g);
		TPL::display('gift_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$imageurl = $this->_upload();
			$q = dr('admin/gift.update_post', (int) post('id'), $imageurl);
			$_SESSION['succ'] = true;
			redirect('admin/gift.edit?id=' . post('id'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$giftid = explode(',', post('id'));

			foreach ($giftid as $id) {
				$q = dr('admin/gift.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$giftid = explode(',', post('id'));

			foreach ($giftid as $id) {
				$q = dr('admin/gift.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$giftid = explode(',', post('id'));

			foreach ($giftid as $id) {
				$q = dr('admin/gift.del', (int) $id);
			}
		}
	}
}

?>
