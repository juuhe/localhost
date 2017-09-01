<?php

class app_models
{
	public $echo_page = '';
	public $ar_select = array();
	public $ar_distinct = false;
	public $ar_from = array();
	public $ar_join = array();
	public $ar_where = array();
	public $ar_like = array();
	public $ar_or_like = array();
	public $ar_groupby = array();
	public $ar_having = array();
	public $ar_keys = array();
	public $ar_limit = false;
	public $ar_offset = false;
	public $ar_order = false;
	public $ar_orderby = array();
	public $ar_set = array();
	public $ar_wherein = array();
	public $result_ojb = '';
	public $prefix = 'zyads_';
	public $count = false;
	public $total_count = false;
	public $db = false;

	public function __construct()
	{
		$this->db = APP::adapter('db', 'mysql');
	}

	public function find_one($table = '')
	{
		return $this->get($table, 1, NULL, true);
	}

	public function get($table = '', $limit = NULL, $offset = NULL, $one = false)
	{
		if ($table != '') {
			$this->from($table);
		}

		if (!is_null($limit)) {
			$this->limit($limit, $offset);
		}

		$sql = $this->_make_sql();
		$this->ar_where = array();
		$this->ar_from = array();
		$this->ar_select = array();
		$this->ar_groupby = array();
		$this->ar_limit = array();
		$result = $this->db->query($sql);

		if ($one) {
			if ($data = $this->db->fetch_array($result)) {
				return $data;
			}
		}
		else {
			$results = NULL;

			while ($data = $this->db->fetch_array($result)) {
				if ($data) {
					$results[] = $data;
				}
			}

			return $results;
		}
	}

	public function order_by($orderby, $direction = 'DESC')
	{
		if (strtolower($direction) == 'rand') {
			$orderby = '';
			$direction = 'RAND()';
		}
		else if (trim($direction) != '') {
			$direction = (in_array(strtoupper(trim($direction)), array('ASC', 'DESC'), true) ? ' ' . $direction : ' ');
		}

		if (strpos($orderby, ',') !== false) {
			$temp = array();

			foreach (explode(',', $orderby) as $part) {
				$part = trim($part);
				$temp[] = $part;
			}

			$orderby = implode(', ', $temp);
		}

		$orderby_statement = $orderby . $direction;
		$this->ar_orderby = array();
		$this->ar_orderby[] = $orderby_statement;
		return $this;
	}

	public function _limit($sql, $limit, $offset)
	{
		if ($offset == 0) {
			$offset = '';
		}
		else {
			$offset .= ', ';
		}

		return $sql . 'LIMIT ' . $offset . $limit;
	}

	public function limit($value, $offset = '')
	{
		$this->ar_limit = (int) $value;

		if ($offset != '') {
			$this->ar_offset = (int) $offset;
		}

		return $this;
	}

	public function from($from)
	{
		foreach ((array) $from as $val) {
			if (strpos($val, ',') !== false) {
				foreach (explode(',', $val) as $v) {
					$v = trim($v);

					if (in_array($this->prefix . $from, $this->ar_from)) {
						continue;
					}

					$this->ar_from[] = $this->prefix . $v;
				}
			}

			if (in_array($this->prefix . $from, $this->ar_from)) {
				continue;
			}

			$this->ar_from[] = $this->prefix . $from;
		}

		return $this;
	}

	public function join($table, $cond, $type = '')
	{
		if ($type != '') {
			$type = strtoupper(trim($type));

			if (!in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'))) {
				$type = '';
			}
			else {
				$type .= ' ';
			}
		}

		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else {
			$table = $this->prefix . $table;
		}

		$join = $type . 'JOIN ' . $table . ' ON ' . $cond;
		$this->ar_join = array();
		$this->ar_join[] = $join;
		return $this;
	}

	public function select($select = '*')
	{
		if (is_string($select)) {
			$select = explode(',', $select);
		}

		foreach ($select as $val) {
			$val = trim($val);

			if ($val != '') {
				$this->ar_select[] = $val;
			}
		}

		return $this;
	}

	public function _from_tables($tables)
	{
		if (!is_array($tables)) {
			$tables = array($tables);
		}

		return '(' . implode(', ', $tables) . ')';
	}

	public function find_count($table = '')
	{
		if ($table != '') {
			$this->from($table);
		}

		$this->count = true;
		$count = 'COUNT(*)';
		$groupby = $this->ar_groupby;

		if ($groupby) {
			$groupby = implode(',', $groupby);
			$count = 'count(distinct ' . $groupby . ')';
		}

		$sql = $this->_make_sql('SELECT ' . $count . ' AS count_rows ');
		$this->count = false;
		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);
		return $this->total_count = (int) $row['count_rows'];
	}

	protected function _make_sql($sql_prefix = false)
	{
		$sql = (!$this->ar_distinct ? 'SELECT ' : 'SELECT DISTINCT ');

		if ($sql_prefix !== false) {
			$sql = $sql_prefix;
		}
		else if (count($this->ar_select) == 0) {
			$sql .= '*';
		}
		else {
			foreach ($this->ar_select as $key => $val) {
				$no_escape = (isset($this->ar_no_escape[$key]) ? $this->ar_no_escape[$key] : NULL);
				$this->ar_select[$key] = $val;
			}

			$sql .= implode(', ', $this->ar_select);
		}

		if (0 < count($this->ar_from)) {
			$sql .= "\nFROM ";
			$sql .= $this->_from_tables($this->ar_from);
		}
		else {
			$sql .= "\nFROM " . $this->prefix . $this->default_from;
		}

		if (0 < count($this->ar_join)) {
			$sql .= "\n";
			$sql .= implode("\n", $this->ar_join);
		}

		if ((0 < count($this->ar_where)) || (0 < count($this->ar_like))) {
			$sql .= "\nWHERE ";
		}

		$sql .= implode("\n", $this->ar_where);

		if (0 < count($this->ar_like)) {
			if (0 < count($this->ar_where)) {
				$sql .= "\nAND ";
			}

			$sql .= implode("\n", $this->ar_like);
		}

		if (0 < count($this->ar_or_like)) {
			if (0 < count($this->ar_where)) {
				$sql .= "\nOR";
			}
			else {
				$sql .= "\nWHERE";
			}

			$sql .= implode("\n", $this->ar_or_like);
		}

		if ((0 < count($this->ar_groupby)) && ($this->count === false)) {
			$sql .= "\nGROUP BY ";
			$sql .= implode(', ', $this->ar_groupby);
		}

		if (0 < count($this->ar_having)) {
			$sql .= "\nHAVING ";
			$sql .= implode("\n", $this->ar_having);
		}

		if ((0 < count($this->ar_orderby)) && ($this->count === false)) {
			$sql .= "\nORDER BY ";
			$sql .= implode(', ', $this->ar_orderby);

			if ($this->ar_order !== false) {
				$sql .= ($this->ar_order == 'desc' ? ' DESC' : ' ASC');
			}
		}

		if (is_numeric($this->ar_limit) && ($this->count === false)) {
			$sql .= "\n";
			$sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
		}

		return $sql;
	}

	public function _has_operator($str)
	{
		$str = trim($str);

		if (!preg_match('/(\\s|<|>|!|=|is null|is not null)/i', $str)) {
			return false;
		}

		return true;
	}

	public function fields_parse($field)
	{
		$field = preg_replace('/[^a-z0-9_.%,\'\\"-|()|<|>|!=|is null|is not null]/i', '', $field);
		return $field;
	}

	protected function parse_value($value)
	{
		if (is_string($value)) {
			$value = '\'' . $this->db->escape($value) . '\'';
		}
		else if (is_array($value)) {
			$value = array_map(array($this->db, 'escape'), $value);
		}
		else if (is_bool($value)) {
			$value = ($value ? '1' : '0');
		}
		else if (is_null($value)) {
			$value = 'null';
		}

		return $value;
	}

	protected function where($key, $value = NULL, $type = 'AND', $escape = true)
	{
		if (!is_array($key)) {
			$key = array($key => $value);
		}

		foreach ($key as $k => $v) {
			$prefix = (count($this->ar_where) == 0 ? '' : ' ' . $type . ' ');
			$k = $this->fields_parse($k);
			if (is_null($v) && !$this->_has_operator($k)) {
				$k .= ' IS NULL';
			}

			if (!is_null($v)) {
				if ($escape === true) {
					$v = ' ' . $this->parse_value($v);
				}

				if (!$this->_has_operator($k)) {
					$k .= ' = ';
				}
			}

			$this->ar_where[] = $prefix . $k . $v;
		}

		return $this;
	}

	public function where_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values);
	}

	public function or_where_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, false, 'OR ');
	}

	public function where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, true);
	}

	protected function _where_in($key = NULL, $values = NULL, $not = false, $type = 'AND ')
	{
		if (($key === NULL) || ($values === NULL)) {
			return NULL;
		}

		if (!is_array($values)) {
			$values = array($values);
		}

		$not = ($not ? ' NOT' : '');

		foreach ($values as $value) {
			$this->ar_wherein[] = $this->parse_value($value);
		}

		$prefix = (count($this->ar_where) == 0 ? '' : $type);
		$where_in = $prefix . $key . $not . ' IN (' . implode(', ', $this->ar_wherein) . ') ';
		$this->ar_where[] = $where_in;
		$this->ar_wherein = array();
		return $this;
	}

	public function or_not_like($field, $value = '', $side = 'both')
	{
		return $this->_like($field, $value, 'OR ', $side, 'NOT');
	}

	public function not_like($field, $value = '', $side = 'both')
	{
		return $this->_like($field, $value, 'AND ', $side, 'NOT');
	}

	public function or_like($field, $value = '', $side = 'both')
	{
		return $this->_like($field, $value, 'OR ', $side);
	}

	public function like($field, $value = '', $side = 'both')
	{
		return $this->_like($field, $value, 'AND ', $side);
	}

	protected function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
	{
		if (!is_array($field)) {
			$field = array($field => $match);
		}

		foreach ($field as $k => $v) {
			$k = $this->fields_parse($k);

			if ($type == 'OR ') {
				$prefix = (count($this->ar_or_like) == 0 ? '' : 'OR ');
			}
			else {
				$prefix = (count($this->ar_like) == 0 ? '' : 'AND ');
			}

			$v = $this->db->escape($v);

			if ($side == 'none') {
				$like_statement = $prefix . ' ' . $k . ' ' . $not . ' LIKE \'' . $v . '\'';
			}
			else if ($side == 'before') {
				$like_statement = $prefix . ' ' . $k . ' ' . $not . ' LIKE \'%' . $v . '\'';
			}
			else if ($side == 'after') {
				$like_statement = $prefix . ' ' . $k . ' ' . $not . ' LIKE \'' . $v . '%\'';
			}
			else {
				$like_statement = $prefix . ' ' . $k . ' ' . $not . ' LIKE \'%' . $v . '%\'';
			}

			if ($type == 'OR ') {
				$this->ar_or_like[] = $like_statement;
			}
			else {
				$this->ar_like[] = $like_statement;
			}
		}

		return $this;
	}

	public function group_by($by)
	{
		if (is_string($by)) {
			$by = explode(',', $by);
		}

		foreach ($by as $val) {
			$val = trim($val);

			if ($val != '') {
				$this->ar_groupby[] = $val;
			}
		}

		return $this;
	}

	public function having($key, $value = '', $escape = true)
	{
		return $this->_having($key, $value, 'AND ', $escape);
	}

	public function _having($key, $value = '', $type = 'AND ', $escape = true)
	{
		if (!is_array($key)) {
			$key = array($key => $value);
		}

		foreach ($key as $k => $v) {
			$prefix = (count($this->ar_having) == 0 ? '' : $type);

			if ($escape === true) {
				$v = ' ' . $this->parse_value($v);
			}
		}

		$this->ar_having[] = $prefix . $k . $v;
		return $this;
	}

	public function pager($params = NULL)
	{
		$p = APP::adapter('pager', 'default');
		$p->sql_count = $this->find_count();
		$start_limit = ($p->page_size - 1) * $p->total_count;
		$this->limit($p->total_count, $start_limit);
	}

	public function set($key, $value = '', $escape = true)
	{
		if (!is_array($key)) {
			$key = array($key => $value);
		}

		foreach ($key as $k => $v) {
			if ($escape === false) {
				$this->ar_set[$this->fields_parse($k)] = $v;
			}
			else {
				$this->ar_set[$this->fields_parse($k)] = $this->parse_value($v);
			}
		}

		return $this;
	}

	public function replace($table = '', $set = NULL)
	{
		return $this->_insert($table, $set, 'REPLACE');
	}

	public function insert($table = '', $set = NULL)
	{
		return $this->_insert($table, $set);
	}

	private function _insert($table = '', $set = NULL, $type = 'INSERT')
	{
		if (!is_null($set)) {
			$this->set($set);
		}

		if (!in_array($type, array('INSERT', 'REPLACE'))) {
			throw new Exception('insert not REPLACE');
			return false;
		}

		if (count($this->ar_set) == 0) {
			throw new Exception('insert not value');
			return false;
		}

		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else {
			$table = $this->prefix . $table;
		}

		$sql = $this->db->$type($table, array_keys($this->ar_set), array_values($this->ar_set));
		$this->ar_where = array();
		$this->ar_set = array();
		return $this->db->query($sql);
	}

	public function get_insert_id()
	{
		return $this->db->insert_id();
	}

	public function delete($table = '', $where = '', $limit = NULL)
	{
		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else if (is_array($table)) {
			foreach ($table as $from) {
				$this->delete($from, $where, $limit);
			}

			return NULL;
		}

		if ($where != '') {
			$this->where($where);
		}

		if ($limit != NULL) {
			$this->limit($limit);
		}

		if (count($this->ar_where) == 0) {
			throw new Exception('delete not where');
			return false;
		}

		$sql = $this->db->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);
		$this->ar_where = array();
		$this->ar_set = array();
		return $this->db->query($sql);
	}

	public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
	{
		if (!is_null($set)) {
			$this->set($set);
		}

		if (count($this->ar_set) == 0) {
			throw new Exception('update not set');
			return false;
		}

		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else if (is_array($table)) {
			foreach ((array) $table as $val) {
				$table_arr[] = $this->prefix . $val;
			}

			$table = implode(', ', $table_arr);
		}
		else {
			$table = $this->prefix . $table;
		}

		if ($where != NULL) {
			$this->where($where);
		}

		if ($limit != NULL) {
			$this->limit($limit);
		}

		$sql = $this->db->_update($table, $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
		$this->ar_where = array();
		$this->ar_set = array();
		return $this->db->query($sql);
	}

	public function affected_rows()
	{
		return $this->db->affected_rows();
	}

	public function insert_update($table, $data, $update = array())
	{
		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else {
			$table = $this->prefix . $table;
		}

		foreach ($data as $k => $v) {
			$ar[$k] = '\'' . $this->db->escape($v) . '\'';
		}

		$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($ar)) . ') VALUES (' . implode(', ', array_values($ar)) . ')';

		if ($update) {
			foreach ($update as $k => $v) {
				$au[] = $k . '=' . $k . '+' . $this->db->escape($v);
			}

			$sql .= 'ON DUPLICATE KEY UPDATE ' . implode(', ', $au);
		}

		return $this->db->query($sql);
	}

	public function truncate($table = '')
	{
		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else {
			$table = $this->prefix . $table;
		}

		$sql = 'TRUNCATE TABLE ' . $table;
		return $this->db->query($sql);
	}

	public function repair($table = '')
	{
		if ($table == '') {
			$table = $this->prefix . $this->default_from;
		}
		else {
			$table = $this->prefix . $table;
		}

		$sql = 'REPAIR TABLE ' . $table;
		return $this->db->query($sql);
	}
}


?>
