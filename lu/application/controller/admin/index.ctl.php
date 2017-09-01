<?php

APP::load_file('admin/admin', 'ctl');
class index_ctl extends admin_ctl
{
	public function get_list()
	{
		TPL::display('index');
	}
}

?>
