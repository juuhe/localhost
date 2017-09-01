<?php

class gift_mod extends app_models
{
	public $default_from = 'gift';

	public function get_list($type = false, $gift = false)
	{
		$this->where('status', 'y');

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

		$this->order_by('id');
		$this->pager();
		$data = $this->get();
		return $data;
	}

	public function exchange_post()
	{
		$gift = dr('affiliate/gift.get_one', (int) post('id'));
		$data = array('giftid' => post('id'), 'integral' => $gift['integral'], 'contact' => post('contact'), 'tel' => post('tel'), 'address' => post('address'), 'username' => $_SESSION['affiliate']['username'], 'addtime' => DATETIMES);
		$this->set($data);
		$this->insert('giftlog');
		dr('main/account.update_integral', $_SESSION['affiliate']['username'], '-', $gift['integral']);
		return true;
	}

	public function get_one($id)
	{
		$where = array('id' => (int) $id);
		$this->where($where);
		$data = $this->find_one();
		return $data;
	}
}

?>
