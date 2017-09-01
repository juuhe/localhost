<?php

APP::load_file('main/main', 'ctl');
class code_ctl extends main_ctl
{
	final public function get_custom()
	{
		$planid = (int) get('planid');

		if ($planid) {
			$p = dr('affiliate/plan.get_one', $planid);
		}

		$plan = dr('affiliate/plan.get_zlink_on', $planid);
		TPL::assign('planid', $planid);
		TPL::assign('plan', $plan);
		TPL::assign('p', $p);
		TPL::display('get_custom');
	}
}

?>
