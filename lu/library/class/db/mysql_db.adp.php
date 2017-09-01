<?php

class mysql_db
{
	public $lists = array();
	public $config;
	public $conn;
	public $error = false;

	public function __construct($param = array())
	{
		$this->config = $GLOBALS['mysql'];
	}

	public function Connect($mode = 'write', $slaves = 0, $resource = false)
	{
		static $connect;
		static $count_reconnect = 0;
		static $error_connect = 0;
		$mode = (in_array($mode, array('write', 'read')) ? strtolower($mode) : 'write');
		if (!isset($connect[$mode]) || ($resource !== false)) {
			if (($mode == 'read') && isset($this->config['slaves']) && !empty($this->config['slaves'])) {
				$count = count($this->config['slaves']);

				if ($slaves < (int) 0) {
					$slaves = rand(0, $count - 1);
				}

				$c = $this->config['slaves'][$slaves];
			}
			else {
				$c = $this->config;
			}

			if (empty($c['dbhost']) || empty($c['dbuser'])) {
				$this->errorLog('dbHost Or dbUser is null');
			}

			$connect[$mode] = @mysql_connect($c['dbhost'] . ':' . $c['dbport'], $c['dbuser'], $c['dbpwd']);

			if (!$connect[$mode]) {
				if ($mode == 'read') {
					++$error_connect;

					if ($count <= $error_connect) {
						return $this->Connect();
					}

					if ($count < ++$slaves) {
						$slaves = 0;
					}

					return $this->Connect('read', $slaves);
				}
				else {
					$this->errorLog('Can\'t connect to MySQL server');
				}
			}

			$c['charset'] = isset($c['charset']) ? $c['charset'] : 'UTF8';

			if (!mysql_select_db($c['dbname'], $connect[$mode])) {
				$this->errorLog('Can\'t use database: ' . $c['dbhost'] . '');
			}

			mysql_query('SET NAMES \'' . $c['charset'] . '\'', $connect[$mode]);

			if ($c['sql_mode']) {
				mysql_query('SET sql_mode=\'\'', $connect[$mode]);
			}
		}

		$this->conn = $connect[$mode];
		return $connect[$mode];
	}

	public function push_sql($sql)
	{
		array_push($this->lists, $sql);
		return $this->last_sql = $sql;
	}

	public function query($sql, $mode = 'write')
	{
		$sql = $this->push_sql($sql);
		$dbConn = $this->Connect();

		if (!$dbConn) {
			exit();
		}

		if ($this->error) {
			exit();
		}

		if (!($query = mysql_query($sql, $dbConn))) {
			$this->errorLog('MySQL query sql error ', $sql);
		}

		return $query;
	}

	public function _update($table, $values, $where, $orderby = array(), $limit = false)
	{
		foreach ($values as $key => $val) {
			$valstr[] = $key . ' = ' . $val;
		}

		$limit = (!$limit ? '' : ' LIMIT ' . $limit);
		$orderby = (1 <= count($orderby) ? ' ORDER BY ' . implode(', ', $orderby) : '');
		$sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $valstr);
		$sql .= (($where != '') && (1 <= count($where)) ? ' WHERE ' . implode(' ', $where) : '');
		$sql .= $orderby . $limit;
		return $sql;
	}

	public function _replace($table, $keys, $values)
	{
		return 'REPLACE INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')';
	}

	public function replace($table, $keys, $values)
	{
		return $this->_replace($table, $keys, $values);
	}

	public function insert($table, $keys, $values)
	{
		return 'INSERT INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')';
	}

	public function _delete($table, $where = array(), $like = array(), $limit = false)
	{
		$conditions = '';
		if ((0 < count($where)) || (0 < count($like))) {
			$conditions = "\nWHERE ";
			$conditions .= implode("\n", $where);
			if ((0 < count($where)) && (0 < count($like))) {
				$conditions .= ' AND ';
			}

			$conditions .= implode("\n", $like);
		}

		$limit = (!$limit ? '' : ' LIMIT ' . $limit);
		return 'DELETE FROM ' . $table . $conditions . $limit;
	}

	public function fetch_array($query, $result_type = MYSQL_ASSOC)
	{
		return mysql_fetch_array($query, MYSQL_ASSOC);
	}

	public function get_tables($db_name = '')
	{
		if (!empty($db_name)) {
			$sql = 'SHOW TABLES FROM ' . $db_name;
		}
		else {
			$sql = 'SHOW TABLES ';
		}

		$result = $this->query($sql);
		$info = array();

		foreach ($result as $key => $val) {
			$info[$key] = current($val);
		}

		return $info;
	}

	public function get_fields($table_name)
	{
		$result = $this->query('SHOW COLUMNS FROM ' . $this->parseKey($table_name));
		$info = array();

		if ($result) {
			foreach ($result as $key => $val) {
				$info[$val['Field']] = array('name' => $val['Field'], 'type' => $val['Type']);
			}
		}

		return $info;
	}

	public function escape($str)
	{
		if (is_array($str)) {
			foreach ($str as $key => $val) {
				$str[$key] = $this->escape($val);
			}

			return $str;
		}

		if ($this->conn) {
			$str = mysql_real_escape_string($str, $this->conn);
		}
		else {
			$dbConn = $this->Connect();
			$str = mysql_real_escape_string($str, $dbConn);
		}

		return $str;
	}

	public function num_rows($query)
	{
		$query = mysql_num_rows($query);
		return $query;
	}

	public function get_one($sql)
	{
		$sql = $sql . ' limit 0,1';
		$query = $this->query($sql);

		if ($results = $this->fetch_array($query)) {
			return $results;
		}

		return false;
	}

	public function get_all($sql)
	{
		$query = $this->query($sql);

		while ($data = $this->fetch_array($query)) {
			$results[] = $data;
		}

		return $results;
	}

	public function get_num($sql)
	{
		$query = $this->query($sql);
		$num = $this->num_rows($query);
		return $num;
	}

	public function insert_id()
	{
		if (0 < ($lastId = mysql_insert_id())) {
			return $lastId;
		}
	}

	public function affected_rows()
	{
		return mysql_affected_rows();
	}

	public function errorLog($msg, $sql = false)
	{
		echo $msg;
		$this->error = true;
		$timestamp = time();
		$errmsg = '';
		$dberrortime = WWW_DIR . '/var/log/dberrortime.log';
		$dberrorfile = WWW_DIR . '/var/log/dberrorlog.php';
		$dberror = mysql_error();
		$dberrno = mysql_errno();
		$errmsg .= 'ZYADS: ' . $msg . "\n";
		$errmsg .= 'Time: ' . gmdate('Y-n-j g:ia', time()) . "\n";
		$errmsg .= 'Error:  ' . $dberror . "\n";
		$errmsg .= 'Errno:  ' . $dberrno . "\n";
		$errmsg .= 'Script: ' . $_SERVER['PHP_SELF'] . "\n";

		if ($sql) {
			$errmsg .= 'SQL: ' . $sql . "\n";
		}

		$errlog = array();

		if ($fp = fopen($dberrortime, 'r')) {
			while (count($errlog) < 20) {
				$log = explode('	', fgets($fp, 50));

				if (($timestamp - $log[0]) < 86400) {
					$errlog[$log[0]] = $log[1];
				}
			}

			@fclose($fp);
		}

		if (!in_array($dberrno, $errlog)) {
			$errlog[$timestamp] = $dberrno;
			$fp = fopen($dberrortime, 'w');
			@flock($fp, 2);

			foreach (array_unique($errlog) as $dateline => $errno) {
				@fwrite($fp, $dateline . '	' . $errno);
			}

			@fclose($fp);
			$fp = fopen($dberrorfile, 'a');
			@fwrite($fp, "\n<?PHP exit('------------------Www.Zyiis.Com------------------'); ?>\n" . trim($errmsg) . "\n");
			@fclose($fp);
		}

		exit();
	}
}


?>
