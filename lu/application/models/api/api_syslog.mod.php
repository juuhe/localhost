<?php

class api_syslog_mod extends app_models
{
	public $default_from = 'syslog';

	public function add_data($username, $controller, $action, $content)
	{
		$data = array('username' => $username, 'controller' => $controller, 'action' => $action, 'content' => var_export($content, true), 'ip' => get_ip(), 'time' => DATETIMES);
		$this->set($data);
		$this->insert();
		return true;
	}
}

?>
