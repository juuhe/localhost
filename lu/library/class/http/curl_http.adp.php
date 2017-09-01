<?php

APP::load_file('http', 'cls');
class curl_http extends http
{
	public function __construct()
	{
		$this->useCurl(true);
	}
}

?>
