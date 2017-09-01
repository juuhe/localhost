<?php

class elFinderVolumeLocalFileSystem extends elFinderVolumeDriver
{
	/**
	 * Driver id
	 * Must be started from letter and contains [a-z0-9]
	 * Used as part of volume id
	 *
	 * @var string
	 **/
	protected $driverId = "l";
	/**
	 * Required to count total archive files size
	 *
	 * @var int
	 **/
	protected $archiveSize = 0;

	public function __construct()
	{
		$this->options["alias"] = "";
		$this->options["dirMode"] = 493;
		$this->options["fileMode"] = 420;
		$this->options["quarantine"] = ".quarantine";
		$this->options["maxArcFilesSize"] = 0;
	}

	protected function configure()
	{
		if ($this->options["tmbPath"]) {
			$this->options["tmbPath"] = (strpos($this->options["tmbPath"], DIRECTORY_SEPARATOR) === false ? $this->root . DIRECTORY_SEPARATOR . $this->options["tmbPath"] : $this->_normpath($this->options["tmbPath"]));
		}

		parent::configure();
		if ($this->attr($this->root, "read") && !$this->tmbURL && $this->URL) {
			if (strpos($this->tmbPath, $this->root) === 0) {
				$this->tmbURL = $this->URL . str_replace(DIRECTORY_SEPARATOR, "/", substr($this->tmbPath, strlen($this->root) + 1));

				if (preg_match("|[^/?&=]$|", $this->tmbURL)) {
					this->tmbURL .= "/";
				}
			}
		}

		$this->aroot = realpath($this->root);

		if (!empty($this->options["quarantine"])) {
			$this->quarantine = $this->root . DIRECTORY_SEPARATOR . $this->options["quarantine"];
			if ((!is_dir($this->quarantine) && !$this->_mkdir($this->root, $this->options["quarantine"])) || !is_writable($this->quarantine)) {
				$this->archivers["extract"] = array();
				$this->disabled[] = "extract";
			}
		}
		else {
			$this->archivers["extract"] = array();
			$this->disabled[] = "extract";
		}
	}

	protected function _dirname($path)
	{
		return dirname($path);
	}

	protected function _basename($path)
	{
		return basename($path);
	}

	protected function _joinPath($dir, $name)
	{
		return $dir . DIRECTORY_SEPARATOR . $name;
	}

	protected function _normpath($path)
	{
		if (empty($path)) {
			return ".";
		}

		if (strpos($path, "/") === 0) {
			$initial_slashes = true;
		}
		else {
			$initial_slashes = false;
		}

		if ($initial_slashes && (strpos($path, "//") === 0) && (strpos($path, "///") === false)) {
			$initial_slashes = 2;
		}

		$initial_slashes = (int) $initial_slashes;
		$comps = explode("/", $path);
		$new_comps = array();

		foreach ($comps as $comp ) {
			if (in_array($comp, array("", "."))) {
				continue;
			}

			if (($comp != "..") || (!$initial_slashes && !$new_comps) || ($new_comps && (end($new_comps) == ".."))) {
				array_push($new_comps, $comp);
			}
			else if ($new_comps) {
				array_pop($new_comps);
			}
		}

		$comps = $new_comps;
		$path = implode("/", $comps);

		if ($initial_slashes) {
			$path = str_repeat("/", $initial_slashes) . $path;
		}

		return $path ? $path : ".";
	}

	protected function _relpath($path)
	{
		return $path == $this->root ? "" : substr($path, strlen($this->root) + 1);
	}

	protected function _abspath($path)
	{
		return $path == DIRECTORY_SEPARATOR ? $this->root : $this->root . DIRECTORY_SEPARATOR . $path;
	}

	protected function _path($path)
	{
		return $this->rootName . ($path == $this->root ? "" : $this->separator . $this->_relpath($path));
	}

	protected function _inpath($path, $parent)
	{
		return ($path == $parent) || (strpos($path, $parent . DIRECTORY_SEPARATOR) === 0);
	}

	protected function _fileExists($path)
	{
		return file_exists($path);
	}

	protected function _isDir($path)
	{
		return is_dir($path);
	}

	protected function _isFile($path)
	{
		return is_file($path);
	}

	protected function _isLink($path)
	{
		return is_link($path);
	}

	protected function _isReadable($path)
	{
		return is_readable($path);
	}

	protected function _isWritable($path)
	{
		return is_writable($path);
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
		return @filesize($path);
	}

	protected function _filemtime($path)
	{
		return @filemtime($path);
	}

	protected function _subdirs($path)
	{
		if (is_dir($path) && is_readable($path)) {
			$dir = dir($path);

			while (($entry = $dir->read()) !== false) {
				$p = $dir->path . DIRECTORY_SEPARATOR . $entry;
				if (($entry != ".") && ($entry != "..") && is_dir($p) && !$this->attr($p, "hidden")) {
					$dir->close();
					return true;
				}
			}

			$dir->close();
		}

		return false;
	}

	protected function _dimensions($path, $mime)
	{
		clearstatcache();
		return (strpos($mime, "image") === 0) && (($s = @getimagesize($path)) !== false) ? $s[0] . "x" . $s[1] : false;
	}

	protected function _lstat($path)
	{
		return lstat($path);
	}

	protected function _readlink($path)
	{
		if (!$target = @readlink($path)) {
			return false;
		}

		if (substr($target, 0, 1) != DIRECTORY_SEPARATOR) {
			$target = dirname($path) . DIRECTORY_SEPARATOR . $target;
		}

		$atarget = realpath($target);

		if (!$atarget) {
			return false;
		}

		$root = $this->root;
		$aroot = $this->aroot;

		if ($this->_inpath($atarget, $this->aroot)) {
			return $this->_normpath($this->root . DIRECTORY_SEPARATOR . substr($atarget, strlen($this->aroot) + 1));
		}

		return false;
	}

	protected function _scandir($path)
	{
		$files = array();

		foreach (scandir($path) as $name ) {
			if (($name != ".") && ($name != "..")) {
				$files[] = $path . DIRECTORY_SEPARATOR . $name;
			}
		}

		return $files;
	}

	protected function _fopen($path, $mode = "rb")
	{
		return @fopen($path, $mode);
	}

	protected function _fclose($fp, $path = "")
	{
		return @fclose($fp);
	}

	protected function _mkdir($path, $name)
	{
		$path = $path . DIRECTORY_SEPARATOR . $name;

		if (@mkdir($path)) {
			@chmod($path, $this->options["dirMode"]);
			return true;
		}

		return false;
	}

	protected function _mkfile($path, $name)
	{
		$path = $path . DIRECTORY_SEPARATOR . $name;

		if ($fp = @fopen($path, "w")) {
			@fclose($fp);
			@chmod($path, $this->options["fileMode"]);
			return true;
		}

		return false;
	}

	protected function _symlink($target, $path, $name = "")
	{
		if (!$name) {
			$name = basename($path);
		}

		return @symlink("." . DIRECTORY_SEPARATOR . $this->_relpath($target), $path . DIRECTORY_SEPARATOR . $name);
	}

	protected function _copy($source, $targetDir, $name = "")
	{
		$target = $targetDir . DIRECTORY_SEPARATOR . ($name ? $name : basename($source));
		return copy($source, $target);
	}

	protected function _move($source, $targetDir, $name = "")
	{
		$target = $targetDir . DIRECTORY_SEPARATOR . ($name ? $name : basename($source));
		return @rename($source, $target);
	}

	protected function _unlink($path)
	{
		return @unlink($path);
	}

	protected function _rmdir($path)
	{
		return @rmdir($path);
	}

	protected function _save($fp, $dir, $name, $mime, $w, $h)
	{
		$path = $dir . DIRECTORY_SEPARATOR . $name;

		if (!$target = @fopen($path, "wb")) {
			return false;
		}

		while (!feof($fp)) {
			fwrite($target, fread($fp, 8192));
		}

		fclose($target);
		@chmod($path, $this->options["fileMode"]);
		clearstatcache();
		return $path;
	}

	protected function _getContents($path)
	{
		return file_get_contents($path);
	}

	protected function _filePutContents($path, $content)
	{
		if (@file_put_contents($path, $content, LOCK_EX) !== false) {
			clearstatcache();
			return true;
		}

		return false;
	}

	protected function _checkArchivers()
	{
		if (!function_exists("exec")) {
			$this->options["archivers"] = $this->options["archive"] = array();
			return NULL;
		}

		$arcs = array(
			"create"  => array(),
			"extract" => array()
			);
		$this->procExec("tar --version", $o, $ctar);

		if ($ctar == 0) {
			$arcs["create"]["application/x-tar"] = array("cmd" => "tar", "argc" => "-cf", "ext" => "tar");
			$arcs["extract"]["application/x-tar"] = array("cmd" => "tar", "argc" => "-xf", "ext" => "tar");
			$test = $this->procExec("gzip --version", $o, $c);

			if ($c == 0) {
				$arcs["create"]["application/x-gzip"] = array("cmd" => "tar", "argc" => "-czf", "ext" => "tgz");
				$arcs["extract"]["application/x-gzip"] = array("cmd" => "tar", "argc" => "-xzf", "ext" => "tgz");
			}

			$test = $this->procExec("bzip2 --version", $o, $c);

			if ($c == 0) {
				$arcs["create"]["application/x-bzip2"] = array("cmd" => "tar", "argc" => "-cjf", "ext" => "tbz");
				$arcs["extract"]["application/x-bzip2"] = array("cmd" => "tar", "argc" => "-xjf", "ext" => "tbz");
			}
		}

		$this->procExec("zip --version", $o, $c);

		if ($c == 0) {
			$arcs["create"]["application/zip"] = array("cmd" => "zip", "argc" => "-r9", "ext" => "zip");
		}

		$this->procExec("unzip --help", $o, $c);

		if ($c == 0) {
			$arcs["extract"]["application/zip"] = array("cmd" => "unzip", "argc" => "", "ext" => "zip");
		}

		$this->procExec("rar --version", $o, $c);
		if (($c == 0) || ($c == 7)) {
			$arcs["create"]["application/x-rar"] = array("cmd" => "rar", "argc" => "a -inul", "ext" => "rar");
			$arcs["extract"]["application/x-rar"] = array("cmd" => "rar", "argc" => "x -y", "ext" => "rar");
		}
		else {
			$test = $this->procExec("unrar", $o, $c);
			if (($c == 0) || ($c == 7)) {
				$arcs["extract"]["application/x-rar"] = array("cmd" => "unrar", "argc" => "x -y", "ext" => "rar");
			}
		}

		$this->procExec("7za --help", $o, $c);

		if ($c == 0) {
			$arcs["create"]["application/x-7z-compressed"] = array("cmd" => "7za", "argc" => "a", "ext" => "7z");
			$arcs["extract"]["application/x-7z-compressed"] = array("cmd" => "7za", "argc" => "e -y", "ext" => "7z");

			if (empty($arcs["create"]["application/x-gzip"])) {
				$arcs["create"]["application/x-gzip"] = array("cmd" => "7za", "argc" => "a -tgzip", "ext" => "tar.gz");
			}

			if (empty($arcs["extract"]["application/x-gzip"])) {
				$arcs["extract"]["application/x-gzip"] = array("cmd" => "7za", "argc" => "e -tgzip -y", "ext" => "tar.gz");
			}

			if (empty($arcs["create"]["application/x-bzip2"])) {
				$arcs["create"]["application/x-bzip2"] = array("cmd" => "7za", "argc" => "a -tbzip2", "ext" => "tar.bz");
			}

			if (empty($arcs["extract"]["application/x-bzip2"])) {
				$arcs["extract"]["application/x-bzip2"] = array("cmd" => "7za", "argc" => "a -tbzip2 -y", "ext" => "tar.bz");
			}

			if (empty($arcs["create"]["application/zip"])) {
				$arcs["create"]["application/zip"] = array("cmd" => "7za", "argc" => "a -tzip -l", "ext" => "zip");
			}

			if (empty($arcs["extract"]["application/zip"])) {
				$arcs["extract"]["application/zip"] = array("cmd" => "7za", "argc" => "e -tzip -y", "ext" => "zip");
			}

			if (empty($arcs["create"]["application/x-tar"])) {
				$arcs["create"]["application/x-tar"] = array("cmd" => "7za", "argc" => "a -ttar -l", "ext" => "tar");
			}

			if (empty($arcs["extract"]["application/x-tar"])) {
				$arcs["extract"]["application/x-tar"] = array("cmd" => "7za", "argc" => "e -ttar -y", "ext" => "tar");
			}
		}

		$this->archivers = $arcs;
	}

	protected function _unpack($path, $arc)
	{
		$cwd = getcwd();
		$dir = $this->_dirname($path);
		chdir($dir);
		$cmd = $arc["cmd"] . " " . $arc["argc"] . " " . escapeshellarg($this->_basename($path));
		$this->procExec($cmd, $o, $c);
		chdir($cwd);
	}

	protected function _findSymlinks($path)
	{
		if (is_link($path)) {
			return true;
		}

		if (is_dir($path)) {
			foreach (scandir($path) as $name ) {
				if (($name != ".") && ($name != "..")) {
					$p = $path . DIRECTORY_SEPARATOR . $name;

					if (is_link($p)) {
						return true;
					}

					if (is_dir($p) && $this->_findSymlinks($p)) {
						return true;
					}
					else if (is_file($p)) {
						this->archiveSize += filesize($p);
					}
				}
			}
		}
		else {
			this->archiveSize += filesize($path);
		}

		return false;
	}

	protected function _extract($path, $arc)
	{
		if ($this->quarantine) {
			$dir = $this->quarantine . DIRECTORY_SEPARATOR . str_replace(" ", "_", microtime()) . basename($path);
			$archive = $dir . DIRECTORY_SEPARATOR . basename($path);

			if (!@mkdir($dir)) {
				return false;
			}

			chmod($dir, 511);

			if (!copy($path, $archive)) {
				return false;
			}

			$this->_unpack($archive, $arc);
			@unlink($archive);
			$ls = array();

			foreach (scandir($dir) as $i => $name ) {
				if (($name != ".") && ($name != "..")) {
					$ls[] = $name;
				}
			}

			if (empty($ls)) {
				return false;
			}

			$this->archiveSize = 0;
			$symlinks = $this->_findSymlinks($dir);
			$this->remove($dir);

			if ($symlinks) {
				return $this->setError(elFinder::ERROR_ARC_SYMLINKS);
			}

			if ((0 < $this->options["maxArcFilesSize"]) && ($this->options["maxArcFilesSize"] < $this->archiveSize)) {
				return $this->setError(elFinder::ERROR_ARC_MAXSIZE);
			}

			if (count($ls) == 1) {
				$this->_unpack($path, $arc);
				$result = dirname($path) . DIRECTORY_SEPARATOR . $ls[0];
			}
			else {
				$name = basename($path);

				if (preg_match("/\.((tar\.(gz|bz|bz2|z|lzo))|cpio\.gz|ps\.gz|xcf\.(gz|bz2)|[a-z0-9]{1,4})$/i", $name, $m)) {
					$name = substr($name, 0, strlen($name) - strlen($m[0]));
				}

				$test = dirname($path) . DIRECTORY_SEPARATOR . $name;
				if (file_exists($test) || is_link($test)) {
					$name = $this->uniqueName(dirname($path), $name, "-", false);
				}

				$result = dirname($path) . DIRECTORY_SEPARATOR . $name;
				$archive = $result . DIRECTORY_SEPARATOR . basename($path);
				if (!$this->_mkdir(dirname($path), $name) || !copy($path, $archive)) {
					return false;
				}

				$this->_unpack($archive, $arc);
				@unlink($archive);
			}

			return file_exists($result) ? $result : false;
		}
	}

	protected function _archive($dir, $files, $name, $arc)
	{
		$cwd = getcwd();
		chdir($dir);
		$files = array_map("escapeshellarg", $files);
		$cmd = $arc["cmd"] . " " . $arc["argc"] . " " . escapeshellarg($name) . " " . implode(" ", $files);
		$this->procExec($cmd, $o, $c);
		chdir($cwd);
		$path = $dir . DIRECTORY_SEPARATOR . $name;
		return file_exists($path) ? $path : false;
		$this->checkArchivers();
		$dir = $this->decode($args["current"]);
		$targets = $args["targets"];
		$files = array();
		$argc = "";

		foreach ($targets as $target ) {
			$f = $this->file($target);
			$argc .= escapeshellarg($f["name"]) . " ";
			$files[] = $f;
		}

		$arc = $this->options["archivers"]["create"][$args["type"]];

		if ($arc) {
			$name = (count($files) == 1 ? basename($files[0]) : $args["name"]) . "." . $arc["ext"];
			$name = $this->uniqueName($dir, $name, "-", false);
			$cwd = getcwd();
			chdir($dir);
			$cmd = $arc["cmd"] . " " . $arc["argc"] . " " . escapeshellarg($name) . " " . $argc;
			$this->procExec($cmd, $o, $c);
			chdir($cwd);

			if ($c == 0) {
				$finfo = $this->stat($dir . $this->options["separator"] . $name);
				return array($finfo);
			}

			return false;
		}

		return false;
	}
}


?>
