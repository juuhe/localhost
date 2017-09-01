<?php

abstract class elFinderVolumeDriver
{
	/**
	 * Driver id
	 * Must be started from letter and contains [a-z0-9]
	 * Used as part of volume id
	 *
	 * @var string
	 **/
	protected $driverId = "a";
	/**
	 * Volume id - used as prefix for files hashes
	 *
	 * @var string
	 **/
	protected $id = "";
	/**
	 * Flag - volume "mounted" and available
	 *
	 * @var bool
	 **/
	protected $mounted = false;
	/**
	 * Root directory path
	 *
	 * @var string
	 **/
	protected $root = "";
	/**
	 * Root basename | alias
	 *
	 * @var string
	 **/
	protected $rootName = "";
	/**
	 * Default directory to open
	 *
	 * @var string
	 **/
	protected $startPath = "";
	/**
	 * Base URL
	 *
	 * @var string
	 **/
	protected $URL = "";
	/**
	 * Thumbnails dir path
	 *
	 * @var string
	 **/
	protected $tmbPath = "";
	/**
	 * Is thumbnails dir writable
	 *
	 * @var bool
	 **/
	protected $tmbPathWritable = false;
	/**
	 * Thumbnails base URL
	 *
	 * @var string
	 **/
	protected $tmbURL = "";
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $tmbSize = 48;
	/**
	 * Image manipulation lib name
	 * auto|imagick|mogtify|gd
	 *
	 * @var string
	 **/
	protected $imgLib = "auto";
	/**
	 * Library to crypt files name
	 *
	 * @var string
	 **/
	protected $cryptLib = "";
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $archivers = array(
		"create"  => array(),
		"extract" => array()
		);
	/**
	 * How many subdirs levels return for tree
	 *
	 * @var int
	 **/
	protected $treeDeep = 1;
	/**
	 * Errors from last failed action
	 *
	 * @var array
	 **/
	protected $error = array();
	/**
	 * Today 24:00 timestamp
	 *
	 * @var int
	 **/
	protected $today = 0;
	/**
	 * Yesterday 24:00 timestamp
	 *
	 * @var int
	 **/
	protected $yesterday = 0;
	/**
	 * Object configuration
	 *
	 * @var array
	 **/
	protected $options = array(
		"id"                => "",
		"path"              => "",
		"startPath"         => "",
		"treeDeep"          => 1,
		"URL"               => "",
		"separator"         => DIRECTORY_SEPARATOR,
		"cryptLib"          => "",
		"mimeDetect"        => "auto",
		"mimefile"          => "",
		"tmbPath"           => ".tmb",
		"tmbPathMode"       => 511,
		"tmbURL"            => "",
		"tmbSize"           => 48,
		"tmbCrop"           => true,
		"tmbBgColor"        => "#ffffff",
		"imgLib"            => "auto",
		"tmbCleanProb"      => 0,
		"copyOverwrite"     => true,
		"copyJoin"          => true,
		"uploadOverwrite"   => true,
		"uploadAllow"       => array("all"),
		"uploadDeny"        => array(),
		"uploadOrder"       => "deny,allow",
		"uploadMaxSize"     => 0,
		"dateFormat"        => "j M Y H:i",
		"timeFormat"        => "H:i",
		"copyFrom"          => true,
		"copyTo"            => true,
		"disabled"          => array(),
		"acceptedName"      => "/^\w[\w\s\.\%\-\(\)\[\]]*$/u",
		"accessControl"     => NULL,
		"accessControlData" => NULL,
		"defaults"          => array("read" => true, "write" => true),
		"attributes"        => array(),
		"archiveMimes"      => array(),
		"archivers"         => array(),
		"utf8fix"           => false,
		"utf8patterns"      => array("\u0438\u0306", "\u0435\u0308", "\u0418\u0306", "\u0415\u0308", "\u00d8A", "\u030a"),
		"utf8replace"       => array("\u0439", "\u0451", "\u0419", "\u0401", "\u00d8", "\u00c5")
		);
	/**
	 * Defaults permissions
	 *
	 * @var array
	 **/
	protected $defaults = array("read" => true, "write" => true, "locked" => false, "hidden" => false);
	/**
	 * Access control function/class
	 *
	 * @var mixed
	 **/
	protected $attributes = array();
	/**
	 * Access control function/class
	 *
	 * @var mixed
	 **/
	protected $access;
	/**
	 * Mime types allowed to upload
	 *
	 * @var array
	 **/
	protected $uploadAllow = array();
	/**
	 * Mime types denied to upload
	 *
	 * @var array
	 **/
	protected $uploadDeny = array();
	/**
	 * Order to validate uploadAllow and uploadDeny
	 *
	 * @var array
	 **/
	protected $uploadOrder = array();
	/**
	 * Maximum allowed upload file size.
	 * Set as number or string with unit - "10M", "500K", "1G"
	 *
	 * @var int|string
	 **/
	protected $uploadMaxSize = 0;
	/**
	 * Mimetype detect method
	 *
	 * @var string
	 **/
	protected $mimeDetect = "auto";
	/**
	 * Flag - mimetypes from externail file was loaded
	 *
	 * @var bool
	 **/
	static 	private $mimetypesLoaded = false;
	/**
	 * Finfo object for mimeDetect == 'finfo'
	 *
	 * @var object
	 **/
	protected $finfo;
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $diabled = array();
	/**
	 * default extensions/mimetypes for mimeDetect == 'internal' 
	 *
	 * @var array
	 **/
	static 	protected $mimetypes = array("ai" => "image/vnd.adobe.photoshop", "eps" => "application/postscript", "exe" => "application/x-executable", "doc" => "application/vnd.ms-word", "xls" => "application/vnd.ms-excel", "ppt" => "application/vnd.ms-powerpoint", "pps" => "application/vnd.ms-powerpoint", "pdf" => "application/pdf", "xml" => "text/xml", "odt" => "application/vnd.oasis.opendocument.text", "swf" => "application/x-shockwave-flash", "torrent" => "application/x-bittorrent", "jar" => "application/x-jar", "gz" => "application/x-gzip", "tgz" => "application/x-gzip", "bz" => "application/x-bzip2", "bz2" => "application/x-bzip2", "tbz" => "application/x-bzip2", "zip" => "application/zip", "rar" => "application/x-rar", "tar" => "application/x-tar", "7z" => "application/x-7z-compressed", "txt" => "text/plain", "php" => "text/x-php", "html" => "text/html", "htm" => "text/html", "js" => "text/javascript", "css" => "text/css", "rtf" => "text/rtf", "rtfd" => "text/rtfd", "py" => "text/x-python", "java" => "text/x-java-source", "rb" => "text/x-ruby", "sh" => "text/x-shellscript", "pl" => "text/x-perl", "sql" => "text/x-sql", "c" => "text/x-csrc", "h" => "text/x-chdr", "cpp" => "text/x-c++src", "hh" => "text/x-c++hdr", "log" => "text/plain", "csv" => "text/x-comma-separated-values", "bmp" => "image/x-ms-bmp", "jpg" => "image/jpeg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "tif" => "image/tiff", "tiff" => "image/tiff", "tga" => "image/x-targa", "psd" => "image/vnd.adobe.photoshop", "xbm" => "image/xbm", "pxm" => "image/pxm", "mp3" => "audio/mpeg", "mid" => "audio/midi", "ogg" => "audio/ogg", "oga" => "audio/ogg", "m4a" => "audio/x-m4a", "wav" => "audio/wav", "wma" => "audio/x-ms-wma", "avi" => "video/x-msvideo", "dv" => "video/x-dv", "mp4" => "video/mp4", "mpeg" => "video/mpeg", "mpg" => "video/mpeg", "mov" => "video/quicktime", "wm" => "video/x-ms-wmv", "flv" => "video/x-flv", "mkv" => "video/x-matroska", "webm" => "video/webm", "ogv" => "video/ogg", "ogm" => "video/ogg");
	/**
	 * Directory separator - required by client
	 *
	 * @var string
	 **/
	protected $separator = DIRECTORY_SEPARATOR;
	/**
	 * Mimetypes allowed to display
	 *
	 * @var array
	 **/
	protected $onlyMimes = array();
	/**
	 * Store files moved or overwrited files info
	 *
	 * @var array
	 **/
	protected $removed = array();

	protected function init()
	{
		return true;
	}

	protected function configure()
	{
		$path = $this->options["tmbPath"];

		if ($path) {
			if (!file_exists($path)) {
				if (@mkdir($path)) {
					chmod($path, $this->options["tmbPathMode"]);
				}
				else {
					$path = "";
				}
			}

			if (is_dir($path) && is_readable($path)) {
				$this->tmbPath = $path;
				$this->tmbPathWritable = is_writable($path);
			}
		}

		$type = (preg_match("/^(imagick|gd|auto)$/i", $this->options["imgLib"]) ? strtolower($this->options["imgLib"]) : "auto");
		if ((($type == "imagick") || ($type == "auto")) && extension_loaded("imagick")) {
			$this->imgLib = "imagick";
		}
		else {
			$this->imgLib = function_exists("gd_info") ? "gd" : "";
		}

		if ($this->tmbPath) {
			srand((double) microtime() * 1000000);

			if (rand(1, 200) <= $this->options["tmbCleanProb"]) {
				$ls = scandir($this->tmbPath);
				$i = 0;

				for ($s = count($ls); $i < $s; $i++) {
					$pinfo = pathinfo($ls[$i]);

					if (strtolower($pinfo["extension"]) == "png") {
						@unlink($this->tmbPath . DIRECTORY_SEPARATOR . $ls[$i]);
					}
				}
			}
		}
	}

	public function driverId()
	{
		return $this->driverId;
	}

	public function id()
	{
		return $this->id;
	}

	public function debug()
	{
		return array("id" => $this->id(), "name" => strtolower(substr(get_class($this), strlen("elfinderdriver"))), "mimeDetect" => $this->mimeDetect, "imgLib" => $this->imgLib);
	}

	public function mount($opts)
	{
		if (empty($opts["path"])) {
			return false;
		}

		$this->options = array_merge($this->options, $opts);
		$this->id = $this->driverId . (!empty($this->options["id"]) ? $this->options["id"] : elFinder::$volumesCnt++) . "_";
		$this->root = $this->_normpath($this->options["path"]);
		$this->separator = isset($this->options["separator"]) ? $this->options["separator"] : DIRECTORY_SEPARATOR;
		$this->defaults = array("read" => isset($this->options["defaults"]["read"]) ? !!$this->options["defaults"]["read"] : true, "write" => isset($this->options["defaults"]["write"]) ? !!$this->options["defaults"]["write"] : true, "locked" => false, "hidden" => false);
		$this->attributes[] = array("pattern" => "~^" . preg_quote(DIRECTORY_SEPARATOR) . "$~", "locked" => true, "hidden" => false);
		if (!empty($this->options["attributes"]) && is_array($this->options["attributes"])) {
			foreach ($this->options["attributes"] as $a ) {
				if (!empty($a["pattern"]) || (1 < count($a))) {
					$this->attributes[] = $a;
				}
			}
		}

		if (!empty($this->options["accessControl"])) {
			if (is_string($this->options["accessControl"]) && function_exists($this->options["accessControl"])) {
				$this->access = $this->options["accessControl"];
			}
			else {
				if (is_array($this->options["accessControl"]) && (1 < count($this->options["accessControl"])) && is_object($this->options["accessControl"][0]) && method_exists($this->options["accessControl"][0], $this->options["accessControl"][1])) {
					$this->access = array($this->options["accessControl"][0], $this->options["accessControl"][1]);
				}
			}
		}

		if (!$this->init()) {
			return false;
		}

		$this->today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$this->yesterday = $this->today - 86400;
		$this->uploadAllow = isset($this->options["uploadAllow"]) && is_array($this->options["uploadAllow"]) ? $this->options["uploadAllow"] : array();
		$this->uploadDeny = isset($this->options["uploadDeny"]) && is_array($this->options["uploadDeny"]) ? $this->options["uploadDeny"] : array();
		$parts = explode(",", isset($this->options["uploadOrder"]) ? $this->options["uploadOrder"] : "deny,allow");
		$this->uploadOrder = array(trim($parts[0]), trim($parts[1]));

		if (!empty($this->options["uploadMaxSize"])) {
			$size = "" . $this->options["uploadMaxSize"];
			$unit = strtolower(substr($size, strlen($size) - 1));
			$n = 1;

			switch ($unit) {
			case "k":
				$n = 1024;
				break;

			case "m":
				$n = 1048576;
				break;

			case "g":
				$n = 1073741824;
			}

			$this->uploadMaxSize = intval($size) * $n;
		}

		$this->disabled = isset($this->options["disabled"]) && is_array($this->options["disabled"]) ? $this->options["disabled"] : array();
		$this->cryptLib = $this->options["cryptLib"];
		$this->mimeDetect = $this->options["mimeDetect"];
		$type = strtolower($this->options["mimeDetect"]);
		$type = (preg_match("/^(finfo|mime_content_type|internal|auto)$/i", $type) ? $type : "auto");
		$regexp = "/text\/x\-(php|c\+\+)/";
		if ((($type == "finfo") || ($type == "auto")) && class_exists("finfo") && preg_match($regexp, array_shift(explode(";", @finfo_file(finfo_open(FILEINFO_MIME), __FILE__))))) {
			$type = "finfo";
		}
		else {
			if ((($type == "mime_content_type") || ($type == "auto")) && function_exists("mime_content_type") && preg_match($regexp, array_shift(explode(";", mime_content_type(__FILE__))))) {
				$type = "mime_content_type";
			}
			else {
				$type = "internal";
			}
		}

		$this->mimeDetect = $type;
		if (($this->mimeDetect == "internal") && !self::$mimetypesLoaded) {
			self::$mimetypesLoaded = true;
			$this->mimeDetect = "internal";
			$file = false;
			if (!empty($this->options["mimefile"]) && file_exists($this->options["mimefile"])) {
				$file = $this->options["mimefile"];
			}
			else if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . "mime.types")) {
				$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "mime.types";
			}
			else if (file_exists(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "mime.types")) {
				$file = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "mime.types";
			}

			if ($file && file_exists($file)) {
				$mimecf = file($file);

				foreach ($mimecf as $line_num => $line ) {
					if (!preg_match("/^\s*#/", $line)) {
						$mime = preg_split("/\s+/", $line, -1, PREG_SPLIT_NO_EMPTY);
						$i = 1;

						for ($size = count($mime); $i < $size; $i++) {
							if (!isset(self::$mimetypes[$mime[$i]])) {
								self::$mimetypes[$mime[$i]] = $mime[0];
							}
						}
					}
				}
			}
		}

		if (!$this->_isDir($this->root)) {
			return false;
		}

		$read = $this->attr($this->root, "read");
		if (!$read && !$this->attr($this->root, "write")) {
			return false;
		}

		if ($read) {
			if ($this->options["startPath"]) {
				$path = $this->_normpath($this->options["startPath"]);
				if ($this->_isDir($path) && $this->attr($path, "read") && !$this->attr($path, "hidden") && $this->_inpath($path, $this->root)) {
					$this->startPath = $path;
				}
			}
		}
		else {
			$this->options["URL"] = "";
			$this->options["tmbURL"] = "";
			$this->options["tmbPath"] = "";
			array_unshift($this->attributes, array("pattern" => "/.*/", "read" => false));
		}

		$this->rootName = empty($this->options["alias"]) ? $this->_basename($this->root) : $this->options["alias"];
		$this->treeDeep = 0 < $this->options["treeDeep"] ? (int) $this->options["treeDeep"] : 1;
		$this->tmbSize = 0 < $this->options["tmbSize"] ? (int) $this->options["tmbSize"] : 48;
		$this->URL = $this->options["URL"];
		if ($this->URL && preg_match("|[^/?&=]$|", $this->URL)) {
			this->URL .= "/";
		}

		$this->tmbURL = !empty($this->options["tmbURL"]) ? $this->options["tmbURL"] : "";
		if ($this->tmbURL && preg_match("|[^/?&=]$|", $this->tmbURL)) {
			this->tmbURL .= "/";
		}

		$this->nameValidator = is_string($this->options["acceptedName"]) && !empty($this->options["acceptedName"]) ? $this->options["acceptedName"] : "";
		$this->_checkArchivers();
		if (!empty($this->options["archiveMimes"]) && is_array($this->options["archiveMimes"])) {
			foreach ($this->archivers["create"] as $mime => $v ) {
				if (!in_array($mime, $this->options["archiveMimes"])) {
					unset($this->archivers["create"][$mime]);
				}
			}
		}

		if (!empty($this->options["archivers"]["create"]) && is_array($this->options["archivers"]["create"])) {
			foreach ($this->options["archivers"]["create"] as $mime => $conf ) {
				if ((strpos($mime, "application/") === 0) && !empty($conf["cmd"]) && isset($conf["argc"]) && !empty($conf["ext"]) && !isset($this->archivers["create"][$mime])) {
					$this->archivers["create"][$mime] = $conf;
				}
			}
		}

		if (!empty($this->options["archivers"]["extract"]) && is_array($this->options["archivers"]["extract"])) {
			foreach ($this->options["archivers"]["extract"] as $mime => $conf ) {
				if ((substr($mime, "application/") === 0) && !empty($cons["cmd"]) && isset($conf["argc"]) && !empty($conf["ext"]) && !isset($this->archivers["extract"][$mime])) {
					$this->archivers["extract"][$mime] = $conf;
				}
			}
		}

		$this->configure();
		return $this->mounted = true;
	}

	public function umount()
	{
	}

	public function error()
	{
		return $this->error;
	}

	public function setMimesFilter($mimes)
	{
		if (is_array($mimes)) {
			$this->onlyMimes = $mimes;
		}
	}

	public function root()
	{
		return $this->encode($this->root);
	}

	public function defaultPath()
	{
		return $this->encode($this->startPath ? $this->startPath : $this->root);
	}

	public function options($hash)
	{
		return array(
	"path"          => $this->_path($this->decode($hash)),
	"url"           => $this->URL,
	"tmbUrl"        => $this->tmbURL,
	"disabled"      => $this->disabled,
	"separator"     => $this->separator,
	"copyOverwrite" => intval($this->options["copyOverwrite"]),
	"archivers"     => array("create" => array_keys($this->archivers["create"]), "extract" => array_keys($this->archivers["extract"]))
	);
	}

	public function commandDisabled($cmd)
	{
		return in_array($cmd, $this->disabled);
	}

	public function mimeAccepted($mime, $mimes = NULL)
	{
		$mimes = (is_array($mimes) ? $mimes : $this->onlyMimes);
		return empty($mimes) || ($mime == "directory") || in_array($mime, $mimes) || in_array(substr($mime, 0, strpos($mime, "/")), $mimes);
	}

	public function isReadable()
	{
		return $this->attr($this->root, "read");
	}

	public function copyFromAllowed()
	{
		return !!$this->options["copyFrom"];
	}

	public function path($hash)
	{
		return $this->_path($this->decode($hash));
	}

	public function realpath($hash)
	{
		$path = $this->decode($hash);
		return $this->_fileExists($path) ? $path : false;
	}

	public function removed()
	{
		return $this->removed;
	}

	public function resetRemoved()
	{
		$this->removed = array();
	}

	public function closest($hash, $attr, $val)
	{
		return $path = $this->closestByAttr($this->decode($hash), $attr, $val) ? $this->encode($path) : false;
	}

	public function file($hash, $realpath = false)
	{
		$path = $this->decode($hash);
		if ((($file = $this->stat($path)) != false) && empty($file["hidden"])) {
			if ($realpath) {
				$file["realpath"] = $path;
			}

			return $file;
		}

		return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
	}

	public function dir($hash, $resolveLink = false)
	{
		if (($dir = $this->file($hash)) == false) {
			return $this->setError(elFinder::ERROR_DIR_NOT_FOUND);
		}

		if ($resolveLink && !empty($dir["alias"]) && ((($target = $this->_readlink($this->decode($hash))) == false) || (($dir = $this->stat($target)) == false) || !empty($dir["hidden"]))) {
			return $this->setError(elFinder::ERROR_DIR_NOT_FOUND);
		}

		if ($dir["mime"] != "directory") {
			return $this->setError(elFinder::ERROR_NOT_DIR);
		}

		return $dir;
	}

	public function scandir($hash)
	{
		if (($dir = $this->dir($hash)) == false) {
			return false;
		}

		return $dir["read"] ? $this->getScandir($this->decode($hash)) : $this->setError(elFinder::ERROR_PERM_DENIED);
	}

	public function ls($hash)
	{
		if ((($dir = $this->dir($hash)) == false) || !$dir["read"]) {
			return false;
		}

		$list = array();

		foreach ($this->_scandir($this->decode($hash)) as $p ) {
			if (!$this->attr($p, "hidden") && $this->mimeAccepted($this->mimetype($p))) {
				$list[] = $this->_basename($p);
			}
		}

		return $list;
	}

	public function tree($hash = "", $deep = 0, $exclude = "")
	{
		$path = ($hash ? $this->decode($hash) : $this->root);
		if ((($dir = $this->stat($path)) == false) || ($dir["mime"] != "directory")) {
			return false;
		}

		$dirs = $this->gettree($path, 0 < $deep ? $deep - 1 : $this->treeDeep - 1, $this->decode($exclude));
		array_unshift($dirs, $dir);
		return $dirs;
	}

	public function parents($hash)
	{
		if (($current = $this->dir($hash)) == false) {
			return false;
		}

		$path = $this->decode($hash);
		$tree = array();

		while ($path != $this->root) {
			$path = $this->_dirname($path);
			if ($this->attr($path, "hidden") || !$this->attr($path, "read")) {
				return false;
			}

			array_unshift($tree, $this->stat($path));

			if ($path != $this->root) {
				foreach ($this->gettree($path, 0) as $dir ) {
					if (!in_array($dir, $tree)) {
						$tree[] = $dir;
					}
				}
			}
		}

		return $tree ? $tree : array($current);
	}

	public function tmb($hash)
	{
		if ($path = $this->decode($hash)) {
			return $tmb = $this->gettmb($path) ? $tmb : $this->createTmb($path);
		}

		return false;
	}

	public function size($hash)
	{
		return $this->countSize($this->decode($hash));
	}

	public function open($hash)
	{
		if ((($file = $this->file($hash)) == false) || ($file["mime"] == "directory")) {
			return false;
		}

		return $this->_fopen($this->decode($hash), "rb");
	}

	public function close($fp, $hash)
	{
		$this->_fclose($fp, $this->decode($hash));
	}

	public function mkdir($dst, $name)
	{
		if ($this->commandDisabled("mkdir")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (!$this->nameAccepted($name)) {
			return $this->setError(elFinder::ERROR_INVALID_NAME);
		}

		if (($dir = $this->dir($dst)) == false) {
			return $this->setError(elFinder::ERROR_TRGDIR_NOT_FOUND, "#" . $dst);
		}

		if (!$dir["write"]) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		$path = $this->decode($dst);
		$dst = $this->_joinPath($path, $name);

		if ($this->_fileExists($dst)) {
			return $this->setError(elFinder::ERROR_EXISTS, $name);
		}

		return $this->_mkdir($path, $name) ? $this->stat($this->_joinPath($path, $name)) : false;
	}

	public function mkfile($dst, $name)
	{
		if ($this->commandDisabled("mkfile")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (!$this->nameAccepted($name)) {
			return $this->setError(elFinder::ERROR_INVALID_NAME);
		}

		if (($dir = $this->dir($dst)) == false) {
			return $this->setError(elFinder::ERROR_TRGDIR_NOT_FOUND, "#" . $dst);
		}

		if (!$dir["write"]) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		$path = $this->decode($dst);

		if ($this->_fileExists($this->_joinPath($path, $name))) {
			return $this->setError(elFinder::ERROR_EXISTS, $name);
		}

		return $this->_mkfile($path, $name) ? $this->stat($this->_joinPath($path, $name)) : false;
	}

	public function rename($hash, $name)
	{
		if ($this->commandDisabled("rename")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (!$this->nameAccepted($name)) {
			return $this->setError(elFinder::ERROR_INVALID_NAME, $name);
		}

		if (!$file = $this->file($hash)) {
			return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
		}

		if ($name == $file["name"]) {
			return $file;
		}

		$path = $this->decode($hash);

		if ($this->attr($path, "locked")) {
			return $this->setError(elFinder::ERROR_LOCKED, $file["name"]);
		}

		$dir = $this->_dirname($path);

		if ($this->_fileExists($this->_joinPath($dir, $name))) {
			return $this->setError(elFinder::ERROR_EXISTS, $name);
		}

		if (!$this->_move($path, $dir, $name)) {
			return false;
		}

		$this->rmTmb($path);
		$path = $this->_joinPath($dir, $name);
		$this->rmTmb($path);
		return $this->stat($path);
	}

	public function duplicate($hash, $suffix = "copy")
	{
		if ($this->commandDisabled("duplicate")) {
			return $this->setError(elFinder::ERROR_COPY, "#" . $hash, elFinder::ERROR_PERM_DENIED);
		}

		if (($file = $this->file($hash)) == false) {
			return $this->setError(elFinder::ERROR_COPY, elFinder::ERROR_FILE_NOT_FOUND);
		}

		$path = $this->decode($hash);
		$dir = $this->_dirname($path);
		return ($path = $this->copy($path, $dir, $this->uniqueName($dir, $this->_basename($path), " " . $suffix . " "))) == false ? false : $this->stat($path);
	}

	public function upload($fp, $dst, $name, $tmpname)
	{
		if ($this->commandDisabled("upload")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (($dir = $this->dir($dst)) == false) {
			return $this->setError(elFinder::ERROR_TRGDIR_NOT_FOUND, "#" . $dst);
		}

		if (!$dir["write"]) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (!$this->nameAccepted($name)) {
			return $this->setError(elFinder::ERROR_INVALID_NAME);
		}

		$mime = $this->mimetype($this->mimeDetect == "internal" ? $name : $tmpname);
		if (($mime == "unknown") && ($this->mimeDetect == "internal")) {
			$mime = elFinderVolumeDriver::mimetypeInternalDetect($name);
		}

		$allow = in_array("all", $this->uploadAllow) || $this->mimeAccepted($mime, $this->uploadAllow);
		$deny = in_array("all", $this->uploadDeny) || $this->mimeAccepted($mime, $this->uploadDeny);
		if (!$this->uploadOrder[0] == "allow" ? $allow && !$deny : $allow || !$deny) {
			return $this->setError(elFinder::ERROR_UPLOAD_FILE_MIME);
		}

		if ((0 < $this->uploadMaxSize) && ($this->uploadMaxSize < filesize($tmpPath))) {
			return $this->setError(elFinder::ERROR_UPLOAD_FILE_SIZE);
		}

		$dstpath = $this->decode($dst);
		$test = $this->_joinPath($dstpath, $name);

		if ($this->_fileExists($test)) {
			if ($this->options["uploadOverwrite"]) {
				if (!$this->attr($test, "write")) {
					return $this->setError(elFinder::ERROR_PERM_DENIED);
				}
				else if ($this->_isDir($test)) {
					return $this->setError(elFinder::ERROR_NOT_REPLACE, $name);
				}

				$removed = $this->stat($test);
				$removed["realpath"] = $test;
			}
			else {
				$name = $this->uniqueName($dstpath, $name, "-", false);
			}
		}

		$w = $h = 0;
		if ((strpos($mime, "image") === 0) && ($s = getimagesize($tmpname))) {
			$w = $s[0];
			$h = $s[1];
		}

		if (($path = $this->_save($fp, $dstpath, $name, $mime, $w, $h)) == false) {
			return false;
		}

		$this->rmTmb($path);

		if (isset($removed)) {
			$this->removed[] = $removed;
		}

		return $this->stat($path);
	}

	public function paste($volume, $src, $dst, $rmSrc = false)
	{
		$err = ($rmSrc ? elFinder::ERROR_MOVE : elFinder::ERROR_COPY);

		if ($this->commandDisabled("paste")) {
			return $this->setError($err, "#" . $src, elFinder::ERROR_PERM_DENIED);
		}

		if (($file = $volume->file($src, $rmSrc)) == false) {
			return $this->setError($err, "#" . $src, elFinder::ERROR_FILE_NOT_FOUND);
		}

		$name = $file["name"];
		$errpath = $volume->path($src);

		if (($dir = $this->dir($dst)) == false) {
			return $this->setError($err, $errpath, elFinder::ERROR_TRGDIR_NOT_FOUND, "#" . $dst);
		}

		if (!$dir["write"] || !$file["read"]) {
			return $this->setError($err, $errpath, elFinder::ERROR_PERM_DENIED);
		}

		$destination = $this->decode($dst);
		if ($test = $volume->closest($src, $rmSrc ? "locked" : "read", $rmSrc)) {
			return $rmSrc ? $this->setError($err, $errpath, elFinder::ERROR_LOCKED, $volume->path($test)) : $this->setError($err, $errpath, elFinder::ERROR_PERM_DENIED);
		}

		$test = $this->_joinPath($destination, $name);

		if ($this->_fileExists($test)) {
			if ($this->options["copyOverwrite"]) {
				if (!$this->isSameType($file["mime"], $this->mimetype($test))) {
					return $this->setError(elFinder::ERROR_NOT_REPLACE, $this->_path($test));
				}

				if (!$this->attr($test, "write")) {
					return $this->setError($err, $errpath, elFinder::ERROR_PERM_DENIED);
				}

				if ($locked = $this->closestByAttr($test, "locked", true)) {
					return $this->setError(elFinder::ERROR_LOCKED, $this->_path($locked));
				}

				if (!$this->remove($test)) {
					return $this->setError(elFinder::ERROR_REPLACE, $this->_path($test));
				}
			}
			else {
				$name = $this->uniqueName($destination, $name, " ", false);
			}
		}

		if ($volume == $this) {
			$source = $this->decode($src);

			if ($this->_inpath($destination, $source)) {
				return $this->setError(elFinder::ERROR_COPY_INTO_ITSELF, $path);
			}

			$method = ($rmSrc ? "move" : "copy");
			return $path = $this->{$method}($source, $destination, $name) ? $this->stat($path) : false;
		}

		if (!$this->options["copyTo"] || !$volume->copyFromAllowed()) {
			return $this->setError(elFinder::ERROR_COPY, $errpath, elFinder::ERROR_PERM_DENIED);
		}

		if (($path = $this->copyFrom($volume, $src, $destination, $name)) == false) {
			return false;
		}

		if ($rmSrc) {
			if ($volume->rm($src)) {
				$this->removed[] = $file;
			}
			else {
				return $this->setError(elFinder::ERROR_MOVE, $errpath, elFinder::ERROR_RM_SRC);
			}
		}

		return $this->stat($path);
	}

	public function getContents($hash)
	{
		$file = $this->file($hash);

		if (!$file) {
			return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
		}

		if ($file["mime"] == "directory") {
			return $this->setError(elFinder::ERROR_NOT_FILE);
		}

		if (!$file["read"]) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		return $this->_getContents($this->decode($hash));
	}

	public function putContents($hash, $content)
	{
		if ($this->commandDisabled("edit")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		$path = $this->decode($hash);

		if (!$file = $this->file($hash)) {
			return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
		}

		if (!$file["write"]) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		return $this->_filePutContents($path, $content) ? $this->stat($path) : false;
	}

	public function extract($hash)
	{
		if ($this->commandDisabled("extract")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (($file = $this->file($hash)) == false) {
			return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
		}

		$archiver = (isset($this->archivers["extract"][$file["mime"]]) ? $this->archivers["extract"][$file["mime"]] : false);

		if (!$archiver) {
			return $this->setError(elFinder::ERROR_NOT_ARCHIVE);
		}

		$path = $this->decode($hash);
		if (!$file["read"] || !$this->attr($this->_dirname($path), "write")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		return $path = $this->_extract($path, $archiver) ? $this->stat($path) : false;
	}

	public function archive($hashes, $mime)
	{
		if ($this->commandDisabled("archive")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		$archiver = (isset($this->archivers["create"][$mime]) ? $this->archivers["create"][$mime] : false);

		if (!$archiver) {
			return $this->setError(elFinder::ERROR_ARCHIVE_TYPE);
		}

		$files = array();

		foreach ($hashes as $hash ) {
			if (($file = $this->file($hash)) == false) {
				return $this->error(elFinder::ERROR_FILE_NOT_FOUND, "#" + $hash);
			}

			if (!$file["read"]) {
				return $this->error(elFinder::ERROR_PERM_DENIED);
			}

			$path = $this->decode($hash);

			if (!isset($dir)) {
				$dir = $this->_dirname($path);

				if (!$this->attr($dir, "write")) {
					return $this->error(elFinder::ERROR_PERM_DENIED);
				}
			}

			$files[] = $this->_basename($path);
		}

		$name = (count($files) == 1 ? $files[0] : "Archive") . "." . $archiver["ext"];
		$name = $this->uniqueName($dir, $name, "");
		return $path = $this->_archive($dir, $files, $name, $archiver) ? $this->stat($path) : false;
	}

	public function resize($hash, $width, $height, $x, $y, $mode = "resize")
	{
		if ($this->commandDisabled("resize")) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (($file = $this->file($hash)) == false) {
			return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
		}

		if (!$file["write"] || !$file["read"]) {
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		$path = $this->decode($hash);

		if (!$this->canResize($path, $file["mime"])) {
			return $this->setError(elFinder::ERROR_UNSUPPORT_TYPE);
		}

		switch ($mode) {
		case "propresize":
			return $this->imgResize($path, $width, $height, true, true, $this->imgLib) ? $this->stat($path) : false;
			break;

		case "crop":
			return $this->imgCrop($path, $width, $height, $x, $y, $this->imgLib) ? $this->stat($path) : false;
			break;

		case "fitsquare":
			return $this->imgSquareFit($path, $width, $height, "center", "middle", $this->options["tmbBgColor"], $this->imgLib) ? $this->stat($path) : false;
			break;

		default:
			return $this->imgResize($path, $width, $height, false, true, $this->imgLib) ? $this->stat($path) : false;
			break;
		}

		return false;
	}

	public function rm($hash)
	{
		if ($this->commandDisabled("rm")) {
			return array(elFinder::ERROR_ACCESS_DENIED);
		}

		return $this->remove($this->decode($hash));
	}

	public function search($q, $mimes)
	{
		return $this->doSearch($this->root, $q, $mimes);
	}

	public function dimensions($hash)
	{
		if (($file = $this->file($hash)) == false) {
			return false;
		}

		return $this->_dimensions($this->decode($hash), $file["mime"]);
	}

	protected function setError($error)
	{
		$this->error = array();

		foreach (func_get_args() as $err ) {
			if (is_array($err)) {
				$this->error = array_merge($this->error, $err);
			}
			else {
				$this->error[] = $err;
			}
		}

		return false;
	}

	protected function encode($path)
	{
		if ($path) {
			$p = $this->_relpath($path);

			if ($p === "") {
				$p = DIRECTORY_SEPARATOR;
			}

			$hash = $this->crypt($p);
			$hash = strtr(base64_encode($hash), "+/=", "-_.");
			$hash = rtrim($hash, ".");
			return $this->id . $hash;
		}
	}

	protected function decode($hash)
	{
		if (strpos($hash, $this->id) === 0) {
			$h = substr($hash, strlen($this->id));
			$h = base64_decode(strtr($h, "-_.", "+/="));
			$path = $this->uncrypt($h);
			return $this->_abspath($path);
		}
	}

	protected function crypt($path)
	{
		return $path;
	}

	protected function uncrypt($hash)
	{
		return $hash;
	}

	protected function nameAccepted($name)
	{
		if ($this->nameValidator) {
			if (function_exists($this->nameValidator)) {
				$f = $this->nameValidator;
				return $f($name);
			}

			return preg_match($this->nameValidator, $name);
		}

		return true;
	}

	public function uniqueName($dir, $name, $suffix = " copy", $checkNum = true)
	{
		$ext = "";

		if (!$this->_isDir($this->_joinPath($dir, $name))) {
			if (preg_match("/\.((tar\.(gz|bz|bz2|z|lzo))|cpio\.gz|ps\.gz|xcf\.(gz|bz2)|[a-z0-9]{1,4})$/i", $name, $m)) {
				$ext = "." . $m[1];
				$name = substr($name, 0, strlen($name) - strlen($m[0]));
			}
		}

		if ($checkNum && preg_match("/(" . $suffix . ")(\d*)$/i", $name, $m)) {
			$i = (int) $m[2];
			$name = substr($name, 0, strlen($name) - strlen($m[2]));
		}
		else {
			$i = 1;
			$name .= $suffix;
		}

		$max = $i + 100000;

		while ($i <= $max) {
			$n = $name . (0 < $i ? $i : "") . $ext;

			if (!$this->_fileExists($this->_joinPath($dir, $n))) {
				return $n;
			}

			$i++;
		}

		return $name . md5($dir) . $ext;
	}

	protected function attr($path, $name)
	{
		if (!isset($this->defaults[$name])) {
			return false;
		}

		$defaults = $perm1 = $perm2 = $perm3 = $this->defaults[$name];

		switch ($name) {
		case "read":
			$perm1 = $this->_isReadable($path);
			break;

		case "write":
			$perm1 = $this->_isWritable($path);
			break;

		case "locked":
			$perm1 = $this->_isLocked($path);
			break;

		case "hidden":
			$perm1 = $this->_isHidden($path);
			break;
		}

		$path = $this->separator . $this->_relpath($path);

		if ($this->access) {
			if (is_array($this->access)) {
				$obj = $this->access[0];
				$method = $this->access[1];
				$perm2 = $obj->{$method}($name, $path, $this->options["accessControlData"], $this);
			}
			else {
				$func = $this->access;
				$perm2 = $func($name, $path, $this->options["accessControlData"], $this);
			}
		}

		$i = 0;

		for ($c = count($this->attributes); $i < $c; $i++) {
			$attrs = $this->attributes[$i];
			if (isset($attrs[$name]) && isset($attrs["pattern"]) && preg_match($attrs["pattern"], $path)) {
				$perm3 = $attrs[$name];
				break;
			}
		}

		$ret = (($name == "read") || ($name == "write") ? $defaults & $perm1 & $perm2 & $perm3 : $defaults ^ $perm1 ^ $perm2 ^ $perm3);
		return $ret;
	}

	protected function stat($path)
	{
		$root = $path == $this->root;
		$link = !$root && $this->_isLink($path);
		if (!$path || (!$this->_fileExists($path) && !$link)) {
			return false;
		}

		$dir = $this->_isDir($path);
		$file = array("hash" => $this->encode($path), "phash" => $root ? "" : $this->encode(dirname($path)), "name" => $root ? $this->rootName : $this->_basename($path));

		if ($link) {
			$stat = $this->_lstat($path);
			$file["date"] = $this->formatDate($stat["mtime"]);

			if (($target = $this->_readlink($path)) != false) {
				$file["mime"] = $this->mimetype($target);
				$file["alias"] = $this->_path($target);
				$file["read"] = (int) $this->attr($path, "read");
				$file["write"] = (int) $this->attr($path, "write");
				$file["size"] = $stat["size"];
			}
			else {
				$file["mime"] = "symlink-broken";
				$file["read"] = 0;
				$file["write"] = 0;
			}
		}
		else {
			$file["mime"] = ($dir ? "directory" : $this->mimetype($path));
			$ts = $this->_filemtime($path);
			$file["date"] = $this->formatDate($ts);
			$file["ts"] = $ts;
			$file["read"] = (int) $this->attr($path, "read");
			$file["write"] = (int) $this->attr($path, "write");

			if (!$file["read"]) {
				$file["size"] = "unknown";
			}
			else if ($dir) {
				$file["size"] = 0;
			}
			else {
				$file["size"] = $this->_filesize($path);
			}
		}

		if ($this->attr($path, "locked")) {
			$file["locked"] = 1;
		}

		if ($this->attr($path, "hidden") || !$this->mimeAccepted($file["mime"])) {
			$file["hidden"] = 1;
		}

		if ($this->options["utf8fix"]) {
			$file["name"] = json_decode(str_replace($this->options["utf8patterns"], $this->options["utf8replace"], json_encode($file["name"])));
		}

		if ($root) {
			$file["volumeid"] = $this->id;
		}

		if ($file["read"] && !isset($file["hidden"])) {
			if ($dir) {
				if (!$link && $this->_subdirs($path)) {
					$file["dirs"] = 1;
				}
			}
			else if (($tmb = $this->gettmb($path)) != false) {
				$file["tmb"] = $tmb;
			}
			else if ($this->canCreateTmb($path, $file["mime"])) {
				$file["tmb"] = 1;
			}
		}

		return $file;
	}

	protected function mimetype($path)
	{
		$type = "";

		if ($this->_isDir($path)) {
			return "directory";
		}

		if ($this->mimeDetect == "finfo") {
			if (empty($this->finfo)) {
				$this->finfo = finfo_open(FILEINFO_MIME);
			}

			$type = @finfo_file($this->finfo, $path);
		}
		else if ($type == "mime_content_type") {
			$type = mime_content_type($path);
		}
		else {
			$type = elFinderVolumeDriver::mimetypeInternalDetect($path);
		}

		$type = explode(";", $type);
		$type = trim($type[0]);

		if ($type == "unknown") {
			if ($this->_isLink($path)) {
				$target = $this->_readlink($path);
				$type = ($target ? $this->mimetype($target) : "symlink-broken");
			}
			else {
				if (($this->_filesize($path) == 0) || preg_match("/\.(ini|conf)$/i", $path)) {
					$type = "text/plain";
				}
			}
		}
		else if ($type == "application/x-empty") {
			$type = "text/plain";
		}
		else if ($type == "application/x-zip") {
			$type = "application/zip";
		}

		if (($type == "unknown") && ($this->mimeDetect != "internal")) {
			$type = elFinderVolumeDriver::mimetypeInternalDetect($path);
		}

		return $type;
	}

	static protected function mimetypeInternalDetect($path)
	{
		$pinfo = pathinfo($path);
		$ext = (isset($pinfo["extension"]) ? strtolower($pinfo["extension"]) : "");
		return isset(elFinderVolumeDriver::$mimetypes[$ext]) ? elFinderVolumeDriver::$mimetypes[$ext] : "unknown";
	}

	protected function countSize($path)
	{
		if ($this->_fileExists($path) && $this->attr($path, "read") && !$this->attr($path, "hidden") && $this->mimeAccepted($this->mimetype($path))) {
			if ($this->_isLink($path)) {
				$lstat = $this->_lstat($path);
				return $lstat["size"];
			}

			if ($this->_isFile($path)) {
				return $this->_filesize($path);
			}

			$size = 0;

			foreach ($this->_scandir($path) as $p ) {
				$name = $this->_basename($p);
				if (($name != ".") && ($p != "..")) {
					$size += $this->countSize($p);
				}
			}

			return $size;
		}

		return 0;
	}

	protected function isSameType($mime1, $mime2)
	{
		return (($mime1 == "directory") && ($mime1 == $mime2)) || (($mime1 != "directory") && ($mime2 != "directory"));
	}

	protected function closestByAttr($path, $attr, $val)
	{
		if ($this->attr($path, $attr) == $val) {
			return $path;
		}

		return $this->_isDir($path) ? $this->childsByAttr($path, $attr, $val) : false;
	}

	protected function childsByAttr($path, $attr, $val)
	{
		foreach ($this->_scandir($path) as $p ) {
			if (($_p = $this->closestByAttr($p, $attr, $val)) != false) {
				return $_p;
			}
		}

		return false;
	}

	protected function getScandir($path)
	{
		$files = array();

		foreach ($this->_scandir($path) as $p ) {
			if (!$this->attr($p, "hidden") && $this->mimeAccepted($this->mimetype($p)) && (($file = $this->stat($p)) != false)) {
				$files[] = $file;
			}
		}

		return $files;
	}

	protected function gettree($path, $deep, $exclude = "")
	{
		$dirs = array();

		foreach ($this->_scandir($path) as $p ) {
			if (($path != $exclude) && !$this->attr($p, "hidden") && $this->_isDir($p) && ($dir = $this->stat($p)) && ($dir["mime"] != "symlink-broken")) {
				$dirs[] = $dir;
				if ((0 < $deep) && isset($dir["dirs"])) {
					$dirs = array_merge($dirs, $this->gettree($p, $deep - 1));
				}
			}
		}

		return $dirs;
	}

	protected function doSearch($path, $q, $mimes)
	{
		$result = array();

		foreach ($this->_scandir($path) as $p ) {
			$mime = $this->mimetype($p);
			if ($this->attr($p, "hidden") || !$this->mimeAccepted($mime)) {
				continue;
			}

			$name = $this->_basename($p);

			if ($this->stripos($name, $q) !== false) {
				$stat = $this->stat($p);
				$stat["path"] = $this->_path($p);

				if ($this->URL) {
					$stat["url"] = $this->URL . str_replace($this->separator, "/", substr($p, strlen($this->root) + 1));
				}

				$result[] = $stat;
			}

			if (($mime == "directory") && $this->attr($p, "read") && !$this->_isLink($p)) {
				$result = array_merge($result, $this->doSearch($p, $q, $mimes));
			}
		}

		return $result;
	}

	protected function copy($src, $dst, $name)
	{
		if ($this->_isLink($src)) {
			return (($link = $this->_readlink($src)) != false) && $this->_symlink($link, $dst, $name) ? $this->_joinPath($dst, $name) : $this->setError(elFinder::ERROR_COPY, $this->_path($src));
		}

		if ($this->_isDir($src)) {
			if (!$this->_isDir($this->_joinPath($dst, $name)) && !$this->_mkdir($dst, $name)) {
				return $this->setError(elFinder::ERROR_COPY, $this->_path($src));
			}

			$dst = $this->_joinPath($dst, $name);
			$ls = $this->_scandir($src);

			foreach ($this->_scandir($src) as $path ) {
				$name = $this->_basename($path);
				if (($name != ".") && ($name != "..") && !$this->attr($path, "hidden")) {
					if (!$this->copy($path, $dst, $name)) {
						return false;
					}
				}
			}

			return $dst;
		}

		return $this->_copy($src, $dst, $name) ? $this->_joinPath($dst, $name) : $this->setError(elFinder::ERROR_COPY, $this->_path($src));
	}

	protected function move($src, $dst, $name)
	{
		$stat = $this->stat($src);
		$stat["realpath"] = $src;

		if ($this->_move($src, $dst, $name)) {
			$this->removed[] = $stat;
			return $this->_joinPath($dst, $name);
		}

		return $this->setError(elFinder::ERROR_MOVE, $this->_path($src));
	}

	protected function copyFrom($volume, $src, $destination, $name)
	{
		if (($source = $volume->file($src)) == false) {
			return $this->setError(elFinder::ERROR_COPY, "#" . $src, $volume->error());
		}

		$errpath = $volume->path($src);

		if (!$this->nameAccepted($source["name"])) {
			return $this->setError(elFinder::ERROR_COPY, $errpath, elFinder::ERROR_INVALID_NAME);
		}

		if (!$source["read"]) {
			return $this->setError(elFinder::ERROR_COPY, $errpath, elFinder::ERROR_PERM_DENIED);
		}

		if ($source["mime"] == "directory") {
			if (!$this->_isDir($this->_joinPath($destination, $name)) && !$this->_mkdir($destination, $name)) {
				return $this->setError(elFinder::ERROR_COPY, $errpath);
			}

			$path = $this->_joinPath($destination, $name);

			foreach ($volume->scandir($src) as $entr ) {
				if (!$this->copyFrom($volume, $entr["hash"], $path, $entr["name"])) {
					return false;
				}
			}
		}
		else {
			$mime = $source["mime"];
			$w = $h = 0;
			if ((strpos($mime, "image") === 0) && ($dim = $volume->dimensions($src))) {
				$s = explode("x", $dim);
				$w = $s[0];
				$h = $s[1];
			}

			if ((($fp = $volume->open($src)) == false) || (($path = $this->_save($fp, $destination, $name, $mime, $w, $h)) == false)) {
				$fp && $volume->close($fp, $src);
				return $this->setError(elFinder::ERROR_COPY, $errpath);
			}

			$volume->close($fp, $src);
		}

		return $path;
	}

	protected function remove($path, $force = false)
	{
		$this->rmTmb($path);
		$stat = $this->stat($path);
		$stat["realpath"] = $path;

		if ($this->_isLink($path)) {
			return $this->_unlink($path) ? true : $this->setError(elFinder::ERROR_RM, $this->_path($path));
		}

		if (!$this->_fileExists($path)) {
			return $this->setError(elFinder::ERROR_RM, $this->_path($path), elFinder::ERROR_FILE_NOT_FOUND);
		}

		$dirname = $this->_dirname($path);
		$name = $this->_basename($path);
		if (!$force && $this->attr($path, "locked")) {
			return $this->setError(elFinder::ERROR_LOCKED, $this->_path($path));
		}

		if ($this->_isFile($path)) {
			if (!$this->_unlink($path)) {
				return $this->setError(elFinder::ERROR_RM, $this->_path($path));
			}
		}
		else if ($this->_isDir($path)) {
			foreach ($this->_scandir($path) as $p ) {
				$name = $this->_basename($p);
				if (($name != ".") && ($name != "..") && !$this->remove($p)) {
					return false;
				}
			}

			if (!$this->_rmdir($path)) {
				return $this->setError(elFinder::ERROR_RM, $this->_path($path));
			}
		}

		$this->removed[] = $stat;
		return true;
	}

	protected function tmbname($path)
	{
		if ($this->_isLink($path) && ($link = $this->_readlink($path))) {
			$path = $link;
		}

		return md5($path) . ".png";
	}

	protected function gettmb($path)
	{
		if ($this->tmbURL && $this->tmbPath) {
			if (strpos($path, $this->tmbPath) === 0) {
				return basename($path);
			}

			$name = $this->tmbname($path);

			if (file_exists($this->tmbPath . DIRECTORY_SEPARATOR . $name)) {
				return $name;
			}
		}

		return false;
	}

	protected function canCreateTmb($path, $mime)
	{
		return $this->tmbPathWritable && (strpos($path, $this->tmbPath) === false) && $this->imgLib && (strpos($mime, "image") === 0) && ($this->imgLib == "gd" ? ($mime == "image/jpeg") || ($mime == "image/png") || ($mime == "image/gif") : true);
	}

	protected function canResize($path, $mime)
	{
		return $this->canCreateTmb($path, $mime);
	}

	protected function createTmb($path)
	{
		if (!$this->canCreateTmb($path, $this->mimetype($path))) {
			return false;
		}

		$name = $this->tmbName($path);
		$tmb = $this->tmbPath . DIRECTORY_SEPARATOR . $name;

		if (($src = $this->_fopen($path, "rb")) == false) {
			return false;
		}

		if (($trg = @fopen($tmb, "wb")) == false) {
			$this->_fclose($src, $path);
			return false;
		}

		while (!feof($src)) {
			fwrite($trg, fread($src, 8192));
		}

		$this->_fclose($src, $path);
		fclose($trg);
		$result = false;
		$tmbSize = $this->tmbSize;

		if (($s = @getimagesize($tmb)) == false) {
			return false;
		}

		if (($s[0] <= $tmbSize) && ($s[1] <= $tmbSize)) {
			$result = $this->imgSquareFit($tmb, $tmbSize, $tmbSize, "center", "middle", $this->options["tmbBgColor"], $this->imgLib, "png");
		}
		else if ($this->options["tmbCrop"]) {
			$result = true;
			if (!(($tmbSize < $s[0]) && ($s[1] <= $tmbSize)) || (($s[0] <= $tmbSize) && ($tmbSize < $s[1])) || (($tmbSize < $s[0]) && ($tmbSize < $s[1]))) {
				$result = $this->imgResize($tmb, $tmbSize, $tmbSize, true, false, $this->imgLib, "png");
			}

			$result = $this->imgCrop($tmb, $tmbSize, $tmbSize, $x, $y, $this->imgLib, "png");
		}
		else {
			$result = $this->imgResize($tmb, $tmbSize, $tmbSize, true, true, $this->imgLib, "png");
			$result &= $this->imgSquareFit($tmb, $tmbSize, $tmbSize, "center", "middle", $this->options["tmbBgColor"], $this->imgLib, "png");
		}

		if (!$result) {
			unlink($tmb);
			return false;
		}

		return $name;
	}

	protected function imgResize($path, $width, $height, $keepProportions = false, $resizeByBiggerSide = true, $imgLib = "imagick", $destformat = NULL)
	{
		if (($s = @getimagesize($path)) == false) {
			return false;
		}

		$result = false;
		list($size_w, $size_h) = array($width, $height);

		if ($keepProportions == true) {
			list($orig_w, $orig_h, $new_w, $new_h) = array($s[0], $s[1], $width, $height);
			$xscale = $orig_w / $new_w;
			$yscale = $orig_h / $new_h;

			if ($resizeByBiggerSide) {
				if ($orig_h < $orig_w) {
					$size_h = ($orig_h * $width) / $orig_w;
					$size_w = $width;
				}
				else {
					$size_w = ($orig_w * $height) / $orig_h;
					$size_h = $height;
				}
			}
			else if ($orig_h < $orig_w) {
				$size_w = ($orig_w * $height) / $orig_h;
				$size_h = $height;
			}
			else {
				$size_h = ($orig_h * $width) / $orig_w;
				$size_w = $width;
			}
		}

		switch ($imgLib) {
		case "imagick":
			try {
				$img = new imagick($path);
			}
			catch (Exception $e) {
				return false;
			}

			($size_w, $size_h, Imagick::FILTER_LANCZOS, true);
			$result = $img->writeImage($path);
			return $result ? $path : false;
			break;

		case "gd":
			if ($s["mime"] == "image/jpeg") {
				$img = imagecreatefromjpeg($path);
			}
			else if ($s["mime"] == "image/png") {
				$img = imagecreatefrompng($path);
			}
			else if ($s["mime"] == "image/gif") {
				$img = imagecreatefromgif($path);
			}
			else if ($s["mime"] == "image/xbm") {
				$img = imagecreatefromxbm($path);
			}

			if ($img && (false != $tmp = imagecreatetruecolor($size_w, $size_h))) {
				if (!imagecopyresampled($tmp, $img, 0, 0, 0, 0, $size_w, $size_h, $s[0], $s[1])) {
					return false;
				}

				if (($destformat == "jpg") || (($destformat == NULL) && ($s["mime"] == "image/jpeg"))) {
					$result = imagejpeg($tmp, $path, 100);
				}
				else {
					if (($destformat == "gif") || (($destformat == NULL) && ($s["mime"] == "image/gif"))) {
						$result = imagegif($tmp, $path, 7);
					}
					else {
						$result = imagepng($tmp, $path, 7);
					}
				}

				imagedestroy($img);
				imagedestroy($tmp);
				return $result ? $path : false;
			}

			break;
		}

		return false;
	}

	protected function imgCrop($path, $width, $height, $x, $y, $imgLib = "imagick", $destformat = NULL)
	{
		if (($s = @getimagesize($path)) == false) {
			return false;
		}

		$result = false;

		switch ($imgLib) {
		case "imagick":
			try {
				$img = new imagick($path);
			}
			catch (Exception $e) {
				return false;
			}

			($width, $height, $x, $y);
			$result = $img->writeImage($path);
			return $result ? $path : false;
			break;

		case "gd":
			if ($s["mime"] == "image/jpeg") {
				$img = imagecreatefromjpeg($path);
			}
			else if ($s["mime"] == "image/png") {
				$img = imagecreatefrompng($path);
			}
			else if ($s["mime"] == "image/gif") {
				$img = imagecreatefromgif($path);
			}
			else if ($s["mime"] == "image/xbm") {
				$img = imagecreatefromxbm($path);
			}

			if ($img && (false != $tmp = imagecreatetruecolor($width, $height))) {
				if (!imagecopy($tmp, $img, 0, 0, $x, $y, $width, $height)) {
					return false;
				}

				if (($destformat == "jpg") || (($destformat == NULL) && ($s["mime"] == "image/jpeg"))) {
					$result = imagejpeg($tmp, $path, 100);
				}
				else {
					if (($destformat == "gif") || (($destformat == NULL) && ($s["mime"] == "image/gif"))) {
						$result = imagegif($tmp, $path, 7);
					}
					else {
						$result = imagepng($tmp, $path, 7);
					}
				}

				imagedestroy($img);
				imagedestroy($tmp);
				return $result ? $path : false;
			}

			break;
		}

		return false;
	}

	protected function imgSquareFit($path, $width, $height, $align = "center", $valign = "middle", $bgcolor = "#0000ff", $imgLib = "imagick", $destformat = NULL)
	{
		if (($s = @getimagesize($path)) == false) {
			return false;
		}

		$result = false;
		$y = ceil(abs($height - $s[1]) / 2);
		$x = ceil(abs($width - $s[0]) / 2);

		switch ($imgLib) {
		case "imagick":
			try {
				$img = new imagick($path);
			}
			catch (Exception $e) {
				return false;
			}

			$img1 = new ();
			$img1->newImage($width, $height, new ImagickPixel($bgcolor));
			$img1->setImageColorspace($img->getImageColorspace());
			$img1->setImageFormat($destformat != NULL ? $destformat : $img->getFormat());
			$img1->compositeImage($img, imagick::COMPOSITE_OVER, $x, $y);
			$result = $img1->writeImage($path);
			return $result ? $path : false;
			break;

		case "gd":
			if ($s["mime"] == "image/jpeg") {
				$img = imagecreatefromjpeg($path);
			}
			else if ($s["mime"] == "image/png") {
				$img = imagecreatefrompng($path);
			}
			else if ($s["mime"] == "image/gif") {
				$img = imagecreatefromgif($path);
			}
			else if ($s["mime"] == "image/xbm") {
				$img = imagecreatefromxbm($path);
			}

			if ($img && (false != $tmp = imagecreatetruecolor($width, $height))) {
				if ($bgcolor == "transparent") {
					list($r, $g, $b) = array(0, 0, 255);
				}
				else {
					list($r, $g, $b) = sscanf($bgcolor, "#%02x%02x%02x");
				}

				$bgcolor1 = imagecolorallocate($tmp, $r, $g, $b);

				if ($bgcolor == "transparent") {
					$bgcolor1 = imagecolortransparent($tmp, $bgcolor1);
				}

				imagefill($tmp, 0, 0, $bgcolor1);

				if (!imagecopy($tmp, $img, $x, $y, 0, 0, $s[0], $s[1])) {
					return false;
				}

				if (($destformat == "jpg") || (($destformat == NULL) && ($s["mime"] == "image/jpeg"))) {
					$result = imagejpeg($tmp, $path, 100);
				}
				else {
					if (($destformat == "gif") || (($destformat == NULL) && ($s["mime"] == "image/gif"))) {
						$result = imagegif($tmp, $path, 7);
					}
					else {
						$result = imagepng($tmp, $path, 7);
					}
				}

				imagedestroy($img);
				imagedestroy($tmp);
				return $result ? $path : false;
			}

			break;
		}

		return false;
	}

	protected function procExec($command, &$output = NULL, &$return_var = -1, &$error_output = NULL)
	{
		$descriptorspec = array(
			array("pipe", "r"),
			array("pipe", "w"),
			array("pipe", "w")
			);
		$process = proc_open($command, $descriptorspec, $pipes, NULL, NULL);

		if (is_resource($process)) {
			fclose($pipes[0]);
			$tmpout = "";
			$tmperr = "";

			if (!feof($pipes[1])) {
				$output[] = fgets($pipes[1], 1024);
			}

			if (!feof($pipes[2])) {
				$error_output[] = fgets($pipes[2], 1024);
			}

			fclose($pipes[1]);
			fclose($pipes[2]);
			$return_var = proc_close($process);
		}

		return $return_var;
	}

	protected function rmTmb($path)
	{
		$tmb = $this->tmbPath . DIRECTORY_SEPARATOR . $this->tmbName($path);
		file_exists($tmb) && @unlink($tmb);
		clearstatcache();
	}

	protected function formatDate($ts)
	{
		if ($this->today < $ts) {
			return "Today " . date($this->options["timeFormat"], $ts);
		}

		if ($this->yesterday < $ts) {
			return "Yesterday " . date($this->options["timeFormat"], $ts);
		}

		return date($this->options["dateFormat"], $ts);
	}

	protected function stripos($haystack, $needle, $offset = 0)
	{
		if (function_exists("mb_stripos")) {
			return mb_stripos($haystack, $needle, $offset);
		}
		else {
			if (function_exists("mb_strtolower") && function_exists("mb_strpos")) {
				return mb_strpos(mb_strtolower($haystack), mb_strtolower($needle), $offset);
			}
		}

		return stripos($haystack, $needle, $offset);
	}

	abstract protected function _dirname($path);

	abstract protected function _basename($path);

	abstract protected function _joinPath($dir, $name);

	abstract protected function _normpath($path);

	abstract protected function _relpath($path);

	abstract protected function _abspath($path);

	abstract protected function _path($path);

	abstract protected function _inpath($path, $parent);

	abstract protected function _fileExists($path);

	abstract protected function _isDir($path);

	abstract protected function _isFile($path);

	abstract protected function _isLink($path);

	abstract protected function _isReadable($path);

	abstract protected function _isWritable($path);

	abstract protected function _isLocked($path);

	abstract protected function _isHidden($path);

	abstract protected function _filesize($path);

	abstract protected function _filemtime($path);

	abstract protected function _subdirs($path);

	abstract protected function _dimensions($path, $mime);

	abstract protected function _lstat($path);

	abstract protected function _readlink($path);

	abstract protected function _scandir($path);

	abstract protected function _fopen($path, $mode);

	abstract protected function _fclose($fp, $path);

	abstract protected function _mkdir($path, $name);

	abstract protected function _mkfile($path, $name);

	abstract protected function _symlink($target, $path, $name);

	abstract protected function _copy($source, $targetDir, $name);

	abstract protected function _move($source, $targetDir, $name);

	abstract protected function _unlink($path);

	abstract protected function _rmdir($path);

	abstract protected function _save($fp, $dir, $name, $mime, $w, $h);

	abstract protected function _getContents($path);

	abstract protected function _filePutContents($path, $content);

	abstract protected function _extract($path, $arc);

	abstract protected function _archive($dir, $files, $name, $arc);

	abstract protected function _checkArchivers();
}


?>
