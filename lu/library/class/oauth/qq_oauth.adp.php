<?php

class qq_oauth
{
	public $appid;
	public $appkey;
	public $redirect_uri;
	public $access_token;
	public $openid;

	public function __construct()
	{
		$this->appid = $GLOBALS['C_ZYIIS']['oauth_qq_app_id'];
		$this->appkey = $GLOBALS['C_ZYIIS']['oauth_qq_app_key'];
		$this->redirect_uri = 'http://' . $GLOBALS['C_ZYIIS']['authorized_url'] . WEB_URL . DEFAULT_ENTRANCE_FILE . '?e=oauth/qq.userlogin';
		if (!$this->appid || $this->appke) {
			exit('Appid is empty Settings in the Settings');
		}
	}

	public function get_access_token()
	{
		if ($_GET['state'] != $_SESSION['state']) {
			exit('state error');
		}

		$to_url = 'https://graph.qq.com/oauth2.0/token?';
		$code = $_GET['code'];
		$url = 'grant_type=authorization_code&client_id=' . $this->appid . '&client_secret=' . $this->appkey . '&code=' . $code . '&redirect_uri=' . urlencode($this->redirect_uri) . '';
		$msg = get_url($to_url . $url);
		$params = array();
		parse_str($msg, $params);

		if (!$params['access_token']) {
			exit('get_access_token error');
		}

		$this->access_token = $params['access_token'];
		return $this->access_token;
	}

	public function get_openid()
	{
		if (!$this->access_token) {
			$this->get_access_token();
		}

		$to_url = 'https://graph.qq.com/oauth2.0/me?access_token=' . $this->access_token;
		$msg = get_url($to_url);
		$lpos = strpos($msg, '(');
		$rpos = strrpos($msg, ')');
		$msg = substr($msg, $lpos + 1, $rpos - $lpos - 1);
		$msg = json_decode($msg, true);

		if (!$msg['openid']) {
			exit('get_openid error');
		}

		$this->openid = $msg['openid'];
		return $this->openid;
	}

	public function get_user_info()
	{
		if (!$this->openid) {
			$this->get_openid();
		}

		$to_url = 'https://graph.qq.com/user/get_user_info?';
		$url = 'access_token=' . $this->access_token . '&oauth_consumer_key=' . $this->appid . '&openid=' . $this->openid . '';
		$msg = get_url($to_url . $url);
		$msg = json_decode($msg, true);
		return $msg;
	}

	public function login()
	{
		$to_url = 'https://graph.qq.com/oauth2.0/authorize?';
		$state = md5(uniqid(rand(), true));
		$_SESSION['state'] = $state;
		$keysArr = array('response_type' => 'code', 'client_id' => $this->appid, 'redirect_uri' => $this->redirect_uri, 'scope' => 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr', 'state' => $state);
		return $to_url . http_build_query($keysArr);
	}
}


?>
