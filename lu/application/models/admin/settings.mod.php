<?php

class settings_mod extends app_models
{
	public $default_from = 'settings';

	public function update_post()
	{
		$null = array('opne_affiliate_register', 'opne_advertiser_register', 'tomail');

		foreach ($null as $n) {
			if (empty($_POST[$n])) {
				$_POST[$n] = '';
			}
		}

		foreach (post() as $key => $val) {
			if (is_array($val)) {
				$val = @implode(',', $val);
			}

			$this->replace('', array('title' => $key, 'value' => $val));
		}
	}

	public function get_list()
	{
		$data = $this->get();
		return $data;
	}

	public function get_sync_setting()
	{
		$this->select('value');
		$this->where('title', 'sync_setting');
		$data = $this->find_one();
		return $data;
	}
}

?>
