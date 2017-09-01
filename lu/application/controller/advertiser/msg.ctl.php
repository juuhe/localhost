<?php

APP::load_file('main/main', 'ctl');
class msg_ctl extends main_ctl
{
	final public function get_list()
	{
		$msg = dr('main/msg.get_list', $GLOBALS['userinfo']['type']);

		foreach ((array) $msg as $key => $m) {
			$rid = 'rid' . substr($GLOBALS['userinfo']['uid'], -1);
			$rd = @explode(',', trim($m[$rid], ','));

			if (!in_array($GLOBALS['userinfo']['uid'], (array) $rd)) {
				$msg[$key]['read'] = 'n';
			}
		}

		TPL::assign('msg', $msg);
		TPL::display('msg_getlist');
	}

	final public function read()
	{
		dr('main/msg.read', (int) get('msgid'));
	}

	final public function get_msg_content()
	{
		$msgid = dr('main/msg.get_msg_content', (int) get('msgid'));
	}
}

?>
