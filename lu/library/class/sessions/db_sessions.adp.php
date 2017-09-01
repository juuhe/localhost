<?php

class db_sessions extends app_models
{
	public $lifeTime = '';

	public function db_sessions()
	{
		parent::__construct();
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'gc'));
	}

	public function open($savePath, $sessName)
	{
		$this->lifeTime = 3600;
		return true;
	}

	public function close()
	{
		$this->gc(ini_get('session.gc_maxlifetime'));
		return true;
	}

	public function read($sessID)
	{
		$sql = 'SELECT session_data AS d FROM ' . $this->prefix . 'sessions WHERE session_id = \'' . $sessID . '\'   AND session_expires >' . TIMES;
		$row = $this->db->get_one($sql);

		if ($row) {
			$data = $row['d'];
			return $data;
		}

		return false;
	}

	public function write($sessID, $sessData)
	{
		if (!$sessData) {
			return false;
		}

		$newExp = TIMES + $this->lifeTime;
		$sql = 'SELECT * FROM ' . $this->prefix . 'sessions WHERE session_id = \'' . $sessID . '\'';
		$num = $this->db->get_num($sql);

		if ($num) {
			$this->db->query('UPDATE ' . $this->prefix . 'sessions  SET session_expires = \'' . $newExp . '\', session_data = \'' . $sessData . '\' WHERE session_id = \'' . $sessID . '\'');

			if ($this->db->affected_rows()) {
				return true;
			}
		}
		else {
			$this->db->query('INSERT INTO ' . $this->prefix . 'sessions (  session_id, session_expires, session_data)  VALUES( \'' . $sessID . '\', \'' . $newExp . '\',  \'' . $sessData . '\')');

			if ($this->db->affected_rows()) {
				return true;
			}
		}

		return false;
	}

	public function destroy($sessID)
	{
		$this->db->query('DELETE FROM ' . $this->prefix . 'sessions WHERE session_id = \'' . $sessID . '\'');

		if ($this->db->affected_rows()) {
			return true;
		}

		return false;
	}

	public function gc($sessMaxLifeTime)
	{
		$this->db->query('DELETE FROM ' . $this->prefix . 'sessions WHERE session_expires < ' . TIMES);
		return true;
	}

	public function __destruct()
	{
		session_write_close();
	}
}

?>
