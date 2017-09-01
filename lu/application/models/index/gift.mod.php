<?php

class gift_mod extends app_models
{
	public $default_from = 'gift';

	public function get_top_gift($type = false, $gift, $limit = false)
	{
		$this->where('status', 'y');

		if ($limit) {
			$this->limit($limit);
		}

		if (is_numeric($type)) {
			$this->where('type', (int) $type);
		}

		if ($gift == 1) {
			$addsql = ' AND  integral<500';
			$this->where('integral <', 500);
		}
		else if ($gift == 2) {
			$this->where('integral >=', 500);
			$this->where('integral <', 2000);
		}
		else if ($gift == 3) {
			$this->where('integral >=', 2000);
			$this->where('integral <', 5000);
		}
		else if ($gift == 4) {
			$this->where('integral >=', 5000);
			$this->where('integral <', 10000);
		}
		else if ($gift == 5) {
			$this->where('integral >=', 10000);
			$this->where('integral <', 50000);
		}
		else if ($gift == 6) {
			$this->where('integral >=', 50000);
			$this->where('integral <', 100000);
		}
		else if ($gift == 7) {
			$this->where('integral >', 100000);
		}

		$this->order_by('top,id');
		$data = $this->get();
		return $data;
	}
}

?>
