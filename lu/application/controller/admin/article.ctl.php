<?php

APP::load_file('admin/admin', 'ctl');
class article_ctl extends admin_ctl
{
	final public function get_list()
	{
		$type = request('type');
		$status = request('status');
		$search = request('search');
		$searchtype = request('searchtype');
		$article = dr('admin/article.get_list', $type, $search, $searchtype, $status);
		$page = APP::adapter('pager', 'default');
		$params = array('searchtype' => $searchtype, 'search' => $search, 'type ' => $type, 'status' => $status);
		$page->params_url = $params;
		TPL::assign('article', $article);
		TPL::assign('type', $type);
		TPL::assign('page', $page);
		TPL::assign('status', $status);
		TPL::assign('searchtype', $searchtype);
		TPL::assign('search', $search);
		TPL::display('article');
	}

	final public function add()
	{
		TPL::display('article_add');
	}

	final public function add_post()
	{
		if (is_post()) {
			$q = dr('admin/article.add_post');
			$_SESSION['succ'] = true;
			redirect('admin/article.get_list');
		}
	}

	final public function edit()
	{
		$a = dr('admin/article.get_one', (int) get('articleid'));
		TPL::assign('a', $a);
		TPL::display('article_add');
	}

	final public function update_post()
	{
		if (is_post()) {
			$q = dr('admin/article.update_post', (int) post('articleid'));
			$_SESSION['succ'] = true;
			redirect('admin/article.edit?articleid=' . post('articleid'));
		}
	}

	final public function unlock()
	{
		if (is_post()) {
			$articleid = explode(',', post('articleid'));

			foreach ($articleid as $id) {
				$q = dr('admin/article.unlock', (int) $id);
			}
		}
	}

	final public function lock()
	{
		if (is_post()) {
			$articleid = explode(',', post('articleid'));

			foreach ($articleid as $id) {
				$q = dr('admin/article.lock', (int) $id);
			}
		}
	}

	final public function del()
	{
		if (is_post()) {
			$articleid = explode(',', post('articleid'));

			foreach ($articleid as $id) {
				$q = dr('admin/article.del', (int) $id);
			}
		}
	}
}

?>
