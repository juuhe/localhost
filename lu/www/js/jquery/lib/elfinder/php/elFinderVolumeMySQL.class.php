<?php

class elFinderVolumeMySQL extends elFinderVolumeDriver
{
	/**
	 * Driver id
	 * Must be started from letter and contains [a-z0-9]
	 * Used as part of volume id
	 *
	 * @var string
	 **/
	protected $driverId = "m";
	/**
	 * Database object
	 *
	 * @var mysqli
	 **/
	protected $db;
	/**
	 * Tables to store files
	 *
	 * @var string
	 **/
	protected $tbf = "";
	/**
	 * Function or object and method to test files permissions
	 *
	 * @var string|array
	 **/
	protected $accessControl;
	/**
	 * Directory for tmp files
	 * If not set driver will try to use tmbDir as tmpDir
	 *
	 * @var string
	 **/
	protected $tmpPath = "";
	/**
	 * Files info cache
	 *
	 * @var array
	 **/
	protected $cache = array();
	/**
	 * Numbers of sql requests (for debug)
	 *
	 * @var int
	 **/
	protected $sqlCnt = 0;
	/**
	 * Last db error message
	 *
	 * @var string
	 **/
	protected $dbError = "";

	public function __construct()
	{
		$opts = array("host" => "localhost", "user" => "", "pass" => "", "db" => "", "port" => NULL, "socket" => NULL, "files_table" => "elfinder_file", "tmbPath" => "", "tmpPath" => "");
		$this->options = array_merge($this->options, $opts);
	}

	protected function init()
	{
		if (!$this->options["host"] || !$this->options["user"] || !$this->options["pass"] || !$this->options["db"] || !$this->options["path"]) {
			return false;
		}

		$this->db = new mysqli($this->options["host"], $this->options["user"], $this->options["pass"], $this->options["db"], $this->options["port"], $this->options["socket"]);
		if ($this->db->connect_error || @mysqli_connect_error()) {
			return false;
		}

		$this->db->set_charset("utf8");

		if ($res = $this->db->query("SHOW TABLES")) {
			while ($row = $res->fetch_array()) {
				if ($row[0] == $this->options["files_table"]) {
					$this->tbf = $this->options["files_table"];
					break;
				}
			}
		}

		if (!$this->tbf) {
			return false;
		}

		$this->options["alias"] = "";
		return true;
	}

	protected function configure()
	{
		parent::configure();
		$tmp = $this->options["tmpPath"];

		if ($tmp) {
			if (!file_exists($tmp)) {
				if (@mkdir($tmp)) {
					@chmod($tmp, $this->options["tmbPathMode"]);
				}
			}

			$this->tmpPath = is_dir($tmp) && is_writable($tmp);
		}

		if (!$this->tmpPath && $this->tmbPath && $this->tmbPathWritable) {
			$this->tmpPath = $this->tmbPath;
		}

		$this->mimeDetect = "internal";
	}

	public function umount()
	{
		$this->db->close();
	}

	public function debug()
	{
		$debug = parent::debug();
		$debug["sqlCount"] = $this->sqlCnt;

		if ($this->dbError) {
			$debug["dbError"] = $this->dbError;
		}

		return $debug;
	}

	protected function query($sql)
	{
		->sqlCnt++;
		$res = $this->db->query($sql);

		if (!$res) {
			$this->dbError = $this->db->error;
		}

		return $res;
	}

	protected function fetch($id)
	{
		$sql = "SELECT f.id, f.parent_id, f.name, f.size, f.mtime, f.mime, f.width, f.height, ch.id AS dirs\n\t\t\t\tFROM " . $this->tbf . " AS f \n\t\t\t\tLEFT JOIN " . $this->tbf . " AS p ON p.id=f.parent_id\n\t\t\t\tLEFT JOIN " . $this->tbf . " AS ch ON ch.parent_id=f.id AND ch.mime=\"directory\"\n\t\t\t\tWHERE f.id=\"" . $id . "\"\n\t\t\t\tGROUP BY f.id";
		$res = $this->query($sql);
		return $res ? $res->fetch_assoc() : false;
	}

	protected function fetchChilds($id)
	{
		$sql = "SELECT f.id, f.parent_id, f.name, f.size, f.mtime, f.mime, f.width, f.height, ch.id AS dirs \n\t\t\t\tFROM " . $this->tbf . " AS f \n\t\t\t\tLEFT JOIN " . $this->tbf . " AS ch ON ch.parent_id=f.id AND ch.mime=\"directory\"\n\t\t\t\tWHERE f.parent_id=\"" . $id . "\"\n\t\t\t\tGROUP BY f.id";
		$res = $this->query($sql);

		if ($res) {
			$result = array();

			while ($row = $res->fetch_assoc()) {
				$result[] = $row;
			}

			return $result;
		}

		return false;
	}

	protected function updateCache($data)
	{
		if (!is_array($data) || empty($data["id"])) {
			return false;
		}

		$id = $data["id"];
		$mime = $data["mime"];
		$file = array("id" => $data["id"], "parent_id" => $data["parent_id"], "hash" => $this->encode($data["id"]), "phash" => $data["parent_id"] ? $this->encode($data["parent_id"]) : "", "name" => $data["name"], "mime" => $mime, "size" => $data["size"], "ts" => $data["mtime"], "date" => $this->formatDate($data["mtime"]));

		if (!$data["parent_id"]) {
			$file["volumeid"] = $this->id;
		}

		if ($mime == "directory") {
			if ($data["dirs"]) {
				$file["dirs"] = 1;
			}
		}
		else {
			if ($data["width"] && $data["height"]) {
				$file["dim"] = $data["width"] . "x" . $data["height"];
			}

			if (($tmb = $this->gettmb($id)) != false) {
				$file["tmb"] = $tmb;
			}
			else if ($this->canCreateTmb($id, $mime)) {
				$file["tmb"] = 1;
			}
		}

		$this->cache[$id] = $file;
		$file["read"] = $this->attr($id, "read");
		$file["write"] = $this->attr($id, "write");

		if ($this->attr($id, "locked")) {
			$file["locked"] = 1;
		}

		if ($this->attr($id, "hidden")) {
			$file["hidden"] = 1;
		}

		$this->cache[$id] = $file;
		return true;
	}

	protected function stat($path, $raw = false)
	{
		if (!isset($this->cache[$path])) {
			if (!$this->updateCache($this->fetch($path))) {
				$this->cache[$path] = false;
			}
		}

		$file = $this->cache[$path];

		if (empty($file)) {
			return false;
		}

		if (!$raw) {
			unset($file["id"]);
			unset($file["parent_id"]);
		}

		return $file;
	}

	protected function clearstat()
	{
		$this->cache = array();
		$this->paths = array();
	}

	protected function getParents($id)
	{
		$parents = array();

		while (0 < $id) {
			$file = $this->stat($id, true);

			if (!$file) {
				return array();
			}

			$id = $file["parent_id"];

			if (0 < $id) {
				if (!$this->attr($id, "read") || $this->attr($id, "hidden")) {
					return array();
				}

				array_unshift($parents, $id);
			}
		}

		return $parents;
	}

	protected function mimetype($path)
	{
		return $file = $this->stat($path) ? $file["mime"] : "unknown";
	}

	protected function tmpname($path)
	{
		return $this->tmpPath . DIRECTORY_SEPARATOR . md5($path);
	}

	protected function make($path, $name, $mime)
	{
		if ($this->_isDir($path)) {
			$this->clearstat();
			$sql = "INSERT INTO %s (parent_id, name, size, mtime, mime) VALUES (\"%s\", \"%s\", 0, %d, \"%s\")";
			$sql = sprintf($sql, $this->tbf, $path, $this->db->real_escape_string($name), time(), $mime);
			return $this->query($sql) && (0 < $this->db->affected_rows);
		}

		return false;
	}

	protected function _dirname($path)
	{
		return $file = $this->stat($path, true) ? $file["parent_id"] : false;
	}

	protected function _joinPath($dir, $name)
	{
		$sql = "SELECT id FROM " . $this->tbf . " WHERE parent_id=\"" . $dir . "\" AND name=\"" . $this->db->real_escape_string($name) . "\"";
		if (($res = $this->query($sql)) && ($r = $res->fetch_assoc())) {
			return $r["id"];
		}

		return -1;
	}

	protected function _basename($path)
	{
		return $file = $this->stat($path) ? $file["name"] : "";
	}

	protected function _normpath($path)
	{
		return $path;
	}

	protected function _relpath($path)
	{
		return $path;
	}

	protected function _abspath($path)
	{
		return $path;
	}

	protected function _path($path)
	{
		if (($file = $this->stat($path)) == false) {
			return "";
		}

		$parentsIds = $this->getParents($path);
		$path = "";

		foreach ($parentsIds as $id ) {
			$dir = $this->stat($id);
			$path .= $dir["name"] . $this->separator;
		}

		return $path . $file["name"];
	}

	protected function _inpath($path, $parent)
	{
		return $path == $parent ? true : in_array($parent, $this->getParents($path));
	}

	protected function _fileExists($path)
	{
		return !!$this->stat($path);
	}

	protected function _isDir($path)
	{
		return $file = $this->stat($path) ? $file["mime"] == "directory" : false;
	}

	protected function _isFile($path)
	{
		return $file = $this->stat($path) ? $file["mime"] != "directory" : false;
	}

	protected function _isLink($path)
	{
		return false;
	}

	protected function _isReadable($path)
	{
		return true;
	}

	protected function _isWritable($path)
	{
		return true;
	}

	protected function _isLocked($path)
	{
		return false;
	}

	protected function _isHidden($path)
	{
		return false;
	}

	protected function _filesize($path)
	{
		return $file = $this->stat($path) ? $file["size"] : false;
	}

	protected function _filemtime($path)
	{
		return $file = $this->stat($path) ? $file["mtime"] : false;
	}

	protected function _subdirs($path)
	{
		return $file = $this->stat($path) ? $file["dirs"] : false;
	}

	protected function _dimensions($path, $mime)
	{
		return $file = $this->stat($path) ? $file["dim"] : false;
	}

	protected function _lstat($path)
	{
		return false;
	}

	protected function _readlink($path)
	{
		return false;
	}

	protected function _scandir($id)
	{
		$files = array();
		$raw = $this->fetchChilds($id);

		if (is_array($raw)) {
			foreach ($raw as $data ) {
				$id = $data["id"];
				$this->updateCache($data);

				if ($this->stat($id)) {
					$files[] = $id;
				}
			}
		}

		return $files;
	}

	protected function _fopen($path, $mode = "rb")
	{
		$fp = ($this->tmbPathWritable ? @fopen($this->tmpname($path), "w+") : tmpfile());

		if ($fp) {
			if (($res = $this->query("SELECT content FROM " . $this->tbf . " WHERE id=\"" . $path . "\"")) && ($r = $res->fetch_assoc())) {
				fwrite($fp, $r["content"]);
				rewind($fp);
				return $fp;
			}
			else {
				$this->_fclose($fp, $path);
			}
		}

		return false;
	}

	protected function _fclose($fp, $path = "")
	{
		@fclose($fp);

		if ($path) {
			$path = $this->tmpPath . DIRECTORY_SEPARATOR . md5($path);
			is_file($path) && @unlink($path);
		}
	}

	protected function _mkdir($path, $name)
	{
		return $this->make($path, $name, "directory");
	}

	protected function _mkfile($path, $name)
	{
		return $this->make($path, $name, "text/plain");
	}

	protected function _symlink($target, $path, $name = "")
	{
		return false;
	}

	protected function _copy($source, $target, $name = "")
	{
		$this->clearstat();
		$name = (empty($name) ? $this->_basename($source) : $name);
		$id = $this->_joinPath($target, $name);
		$sql = (0 < $id ? sprintf("REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) (SELECT %d, %d, name, content, size, mtime, mime, width, height FROM %s WHERE id=%d)", $this->tbf, $id, $this->_dirname($id), $this->tbf, $source) : sprintf("INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) SELECT %d, \"%s\", content, size, %d, mime, width, height FROM %s WHERE id=%d", $this->tbf, $target, $this->db->real_escape_string($name), time(), $this->tbf, $source));
		$this->clearstat();
		return $this->query($sql);
	}

	protected function _move($source, $targetDir, $name = "")
	{
		if (!$name) {
			$name = $this->_basename($source);
		}

		$this->clearstat();
		$sql = "UPDATE " . $this->tbf . " SET parent_id=\"" . $this->_dirname($source) . "\", name=\"" . $this->db->real_escape_string($name) . "\", mtime=\"" . time() . "\" WHERE id=\"" . intval($source) . "\"";
		return $this->query($sql) && $this->db->affected_rows;
	}

	protected function _unlink($path)
	{
		$this->clearstat();
		$sql = "DELETE FROM " . $this->tbf . " WHERE id=\"" . intval($path) . "\" AND mime!=\"directory\" LIMIT 1";
		return $this->query($sql) && (0 < $this->db->affected_rows);
	}

	protected function _rmdir($path)
	{
		$this->clearstat();
		$sql = "SELECT COUNT(f.id) AS num FROM " . $this->tbf . " WHERE parent_id=\"" . intval($path) . "\" GROUP BY f.parent_id";

		if ($res = $this->query($sql)) {
			if ($r = $res->fetch_assoc()) {
				if (0 < $r["num"]) {
					return false;
				}
			}
		}

		$sql = "DELETE FROM " . $this->tbf . " WHERE id=\"" . intval($path) . "\" AND mime=\"directory\" LIMIT 1";
		return $this->query($sql) ? 0 < $this->db->affected_rows : false;
	}

	protected function _save($fp, $dir, $name, $mime, $w, $h)
	{
		$this->clearstat();
		$id = $this->_joinPath($dir, $name);
		$this->rmTmb($id);
		$tmp = $this->tmpPath . DIRECTORY_SEPARATOR . md5(rand());
		if ($this->tmbPathWritable && (($target = @fopen($tmp, "wb")) != false)) {
			while (!feof($fp)) {
				fwrite($target, fread($fp, 8192));
			}

			@fclose($target);
			$sql = (0 < $id ? "REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) VALUES (" . $id . ", %d, \"%s\", LOAD_FILE(\"%s\"), %d, %d, \"%s\", %d, %d)" : "INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) VALUES (%d, \"%s\", LOAD_FILE(\"%s\"), %d, %d, \"%s\", %d, %d)");
			$sql = sprintf($sql, $this->tbf, $dir, $this->db->real_escape_string($name), realpath($tmp), filesize($tmp), time(), $mime, $w, $h);
			$res = $this->query($sql);
			@unlink($tmp);

			if ($res) {
				if (0 < $id) {
					return $id;
				}
				else if ($this->db->insert_id) {
					return $this->db->insert_id;
				}
			}
		}

		$stat = fstat($fp);
		$size = $stat["size"];
		$content = "";
		rewind($fp);

		while (!feof($fp)) {
			$content .= fread($fp, 8192);
		}

		$content = $this->db->real_escape_string($content);
		$sql = (0 < $id ? "REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) VALUES (" . $id . ", %d, \"%s\", \"%s\", %d, %d, \"%s\", %d, %d)" : "INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) VALUES (%d, \"%s\", \"%s\", %d, %d, \"%s\", %d, %d)");
		$sql = sprintf($sql, $this->tbf, $dir, $this->db->real_escape_string($name), $content, $size, time(), $mime, $w, $h);
		unset($content);

		if ($this->query($sql)) {
			return 0 < $id ? $id : $this->db->insert_id;
		}

		return false;
	}

	protected function _getContents($path)
	{
		$sql = sprintf("SELECT content FROM %s WHERE id=%d", $this->tbf, $path);
		if (($res = $this->query($sql)) && ($r = $res->fetch_assoc())) {
			return $r["content"];
		}

		return false;
	}

	protected function _filePutContents($path, $content)
	{
		$this->clearstat();
		$sql = sprintf("UPDATE %s SET content=\"%s\", size=%d, mtime=%d WHERE id=%d", $this->tbf, $this->db->real_escape_string($content), strlen($content), time(), $path);
		return $this->query($sql);
	}

	protected function _extract($path, $arc)
	{
		return false;
	}

	protected function _archive($dir, $files, $name, $arc)
	{
		return false;
	}

	protected function _checkArchivers()
	{
		return array();
	}
}


?>
