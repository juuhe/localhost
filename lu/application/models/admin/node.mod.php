<?php

class node_mod extends app_models
{
	public $default_from = 'node';

	public function get_controller()
	{
		$where = array('type' => 'c');
		$this->order_by('sort');
		$this->where($where);
		$data = $this->get();
		return $data;
	}
}

?>
