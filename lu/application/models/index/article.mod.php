<?php

class article_mod extends app_models
{
	public $default_from = 'article';

	public function get_type_article($type = false, $limit = false, $page = false)
	{
		$this->where('status', 'y');

		if ($limit) {
			$this->limit($limit);
		}

		if ($type) {
			$this->where_in('type', $type);
		}

		$this->order_by('top Desc,articleid');

		if ($page) {
			$this->pager();
		}

		$data = $this->get();
		return $data;
	}

	public function get_one($articleid)
	{
		$this->where('articleid', (int) $articleid);
		$data = $this->find_one();
		return $data;
	}
}

?>
