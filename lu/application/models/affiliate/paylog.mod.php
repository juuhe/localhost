<?php

class paylog_mod extends app_models
{
	public $default_from = 'paylog';

	public function get_list()
	{
		$this->where(array('uid' => $_SESSION['affiliate']['uid']));
		$this->order_by('addtime');
		$this->pager();
		$data = $this->get();
		return $data;
	}
	public function get_sunpay()
	{
		$this->where(array('uid' => $_SESSION['affiliate']['uid'], 'status'=> '1' ));
		$this->select("sum(money) AS sunpay");
		$data = $this->find_one();
		return $data;
	}

}

?>
