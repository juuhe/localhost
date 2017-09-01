<?php

class apply_ctl extends main_ctl
{
	final public function post_apply()
	{
		$ap = dr('affiliate/apply.get_apply_status', (int) $_SESSION['affiliate']['uid'], (int) post('planid'));

		if ($ap) {
			exit();
		}

		$apply = dr('affiliate/apply.post_apply');
	}
}
APP::load_file('main/main', 'ctl');

?>
