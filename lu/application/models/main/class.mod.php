<?php

class class_mod extends app_models
{
	public $default_from = 'class';

	public function get_one($classid)
	{
		$where = array('classid' => (int) $classid);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}

	public function get_all($type = false)
	{
		if ($type) {
			$this->where('type', (int) $type);
		}

		$this->order_by('classid');
		$data = $this->get();
		return $data;
	}
}

?>
