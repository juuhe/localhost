<?php

class report_mod extends app_models
{
	public $default_from = 'stats';

	public function get_list($timerange, $type = false, $to_id_type = false, $id = false, $pager = true)
	{
		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		if ($type != 'all') {
			if ($id) {
				$this->where($to_id_type, $id);
			}

			if ($pager) {
				$this->group_by('day,' . $to_id_type);
			}
			else {
				$this->group_by('day');
			}

			$this->select("sum(views) As views,\r\n\t\t\t\tsum(num) As num,\r\n\t\t\t\tsum(sumpay) As sumpay  ,\r\n\t\t\t\tplantype,day," . $to_id_type . ", \r\n\t\t\t\t");
			$this->order_by('day');
		}
		else {
			$this->group_by('day');
			$this->select("group_concat(views) As views,\r\n\t\t\t\tgroup_concat(num) As num,\r\n\t\t\t\tgroup_concat(sumpay) As sumpay  ,\r\n\t\t\t\t group_concat(plantype) As plantype,day\r\n\t\t\t\t");
			$this->order_by('day');
		}

		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$this->where('(num>0 OR views>0)');

		if ($pager) {
			$this->pager();
		}
		else {
			$this->ar_limit = array();
		}

		$data = $this->get();
		return $data;
	}

	public function get_index_stats()
	{
		$this->where('day', DAYS);
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$this->select('sum(views) AS views,sum(num) AS num,sum(sumpay) AS sumpay,plantype');
		$this->group_by('plantype');
		$data = $this->get();
		return $data;
	}

	public function get_24hour()
	{
		$timerange = request('timerange');
		$this->from('log_hour');
		$this->select('sum(hour0) AS hour0,sum(hour1) AS hour1,sum(hour2) AS hour2,sum(hour3) AS hour3,sum(hour4) AS hour4,sum(hour5) AS hour5,sum(hour6) AS hour6,sum(hour7) AS hour7,sum(hour8) AS hour8,sum(hour9) AS hour9,sum(hour10) AS hour10,sum(hour11) AS hour11,sum(hour12) AS hour12,sum(hour13) AS hour13,sum(hour14) AS hour14,sum(hour15) AS hour15,sum(hour16) AS hour16,sum(hour17) AS hour17,sum(hour18) AS hour18,sum(hour19) AS hour19,sum(hour20) AS hour20,sum(hour21) AS hour21,sum(hour22) AS hour22,sum(hour23) AS hour23');

		if ($timerange) {
			$d = @explode('_', $timerange);
			$time_begin = strtotime($d[0]);
			$time_end = strtotime($d[1]);
			$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		}

		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$this->group_by('day,uid');
		$data = $this->get();
		return $data;
	}

	public function get_day_sunpay()
	{
		$this->select('sum(sumpay) As sumpay');
		$this->where('day', DAYS);
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$data = $this->find_one();
		return $data;
	}
	public function get_clearing()
	{
		$this->select('planid');
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$data = $this->find_one();
		$pid=$data['planid'];
		$clear=dr('affiliate/plan.get_clearing',$pid);
        $da =$clear['clearing'];
		return $da;
	}
	public function get_week_sunpay()
	{
		$this->select('sum(sumpay) As sumpay');
		$time_begin =strtotime(date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))));
		$time_end = strtotime(DAYS);
		$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$data = $this->find_one();
		return $data;
	}

	public function get_yesterday_sunpay()
	{
		$this->select('sum(sumpay) As sumpay');
		$date = date('Y-n-d', TIMES - 86400);
		$this->where('day', $date);
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$data = $this->find_one();
		return $data;
	}
    public function get_last_month_sunpay(){
		$this->select('sum(sumpay) As sumpay');
		$month=(int)(date("m")-1);
		$year=date("Y");
		if($month==0){
			 $month = 12;
			  $year=(int)(date("Y")-1);
		} 
		if($month==1||$month==3||$month==5||$month==7||$month==8||$month==10||$month==12){
			$day = 31;
		} elseif($month==2){
			if($year%100==0&&$year%400==0){
                $day = 29;
			} elseif($year%100!=0&&$year%4==0){
				$day = 29;
			} else{
				$day = 28;
			}
		} else{
			$day = 30;
		}
		$time_begin = strtotime(date("Y-m-d",mktime(0, 0 , 0,date("m")-1,1,date("Y"))));
		$time_end = strtotime(date("Y-m-d",mktime(0, 0 , 0,$month,$day,$year)));
		$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$data = $this->find_one();
		return $data;

	}
	public function nopaid_sunpay(){
		$where = array('pay.status' =>0, 'pay.uid'=> (int) $_SESSION['affiliate']['uid']);
		$this->where($where);
		$this->select('sum(sumpay) As sumpay');
		$this->from('stats AS s');
		$this->join('paylog AS pay', 'pay.uid = s.uid ', 'left');
		$this->pager();
		$data = $this->get();
		return $data;	
	}
	public function nopaid_stats(){
		$where = array('pay.status' =>0, 'pay.uid'=> (int) $_SESSION['affiliate']['uid']);
		$this->where($where);
		$this->select("sum(s.views) As views,\r\n\t\t\t\tsum(s.num) As num,\r\n\t\t\t\tsum(s.sumpay) As sumpay  ,\r\n\t\t\t\ts.plantype,s.day," . $to_id_type . ", \r\n\t\t\t\t");
		$this->from('stats AS s');
		$this->join('paylog AS pay', 'pay.uid = s.uid ', 'left');
		$this->order_by('day');
		$this->pager();
		$data = $this->get();
		return $data;	
	}
	public function get_month_sunpay()
	{
		$this->select('sum(sumpay) As sumpay');
		$time_begin = strtotime(date('Y-n-1', TIMES));
		$time_end = strtotime(DAYS);
		$this->where(array('day >=' => date('YmdHis', $time_begin), 'day <=' => date('YmdHis', $time_end)));
		$this->where('uid', (int) $_SESSION['affiliate']['uid']);
		$data = $this->find_one();
		return $data;
	}
}

?>
