<?php

class default_pager
{
	public $total_count = 20;
	public $sql_count = 0;
	public $page_size = 0;
	public $params_url = false;

	public function __construct()
	{
		$page_size = max(1, intval(get('page')));
		$this->page_size = $page_size;
	}

	public function echoPage()
	{
		$sqlnum = $this->sql_count;
		$totalcount = $this->total_count;
		$pagesize = $this->page_size;
		$echopage = '';
		$route = RUN_CONTROLLER . '.' . RUN_ACTION;
		$add_url = $this->params_url;

		if (is_array($add_url)) {
			$add_url = http_build_query($add_url);
		}

		$add_url .= '&page=';

		if ($totalcount < $sqlnum) {
			$page = 10;
			$offset = 2;
			$realpages = @ceil($sqlnum / $totalcount);
			$pages = $realpages;

			if ($pages < $page) {
				$from = 1;
				$to = $pages;
			}
			else {
				$from = $pagesize - $offset;
				$to = ($from + $page) - 1;

				if ($from < 1) {
					$to = ($pagesize + 1) - $from;
					$from = 1;

					if (($to - $from) < $page) {
						$to = $page;
					}
				}
				else if ($pages < $to) {
					$from = ($pages - $page) + 1;
					$to = $pages;
				}
			}

			$echopage = '<div class="zpage_str"><div>第 ' . $pagesize . '页 总共 ' . $realpages . '页  ' . $sqlnum . '条</div></div>';
			$echopage .= '<div class="zpage"><div class="data"><ul>';
			$echopage .= ((1 < ($pagesize - $offset)) && ($page < $pages) ? '<li class="prev disabled"><a class="p_redirect" href="' . url($route, $add_url . '1') . '">首页</a></li>' : '') . '<li' . ((1 < ($pagesize - $offset)) && ($page < $pages) ? '' : ' class="prev disabled"') . ' ><a ' . (1 < $pagesize ? 'href=' . url($route, $add_url . ($pagesize - 1)) : ' style="color: #999;"') . '>上一页</a></li>';

			for ($i = $from; $i <= $to; $i++) {
				$echopage .= ($i == $pagesize ? '<li class="active"><a>' . $i . '</a></li>' : '<li><a href="' . url($route, $add_url . $i) . '" class="p_num">' . $i . '</a></li>');
			}

			$echopage .= '<li  class="next ' . ($pages <= $to ? 'last' : '') . '"><a ' . ($pagesize < $pages ? 'href=' . url($route, $add_url . ($pagesize + 1)) : ' style="color: #999;"') . ">下一页</a></li>\r\n\t\t\t\t\t" . ($to < $pages ? '<li class="last"><a class="p_redirect" href="' . url($route, $add_url . $pages) . '">尾页</a></li>' : '') . "\r\n\t\t\t</ul></div></div>";
		}

		return $echopage;
	}
}


?>
