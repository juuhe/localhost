<?php

class paylog_mod extends app_models
{
	public $default_from = 'onlinepay';

	public function get_list()
	{
		$this->where(array('username' => $_SESSION['advertiser']['username']));
		$this->order_by('onlineid');
		$this->pager();
		$data = $this->get();
		return $data;
	}
}

?>
