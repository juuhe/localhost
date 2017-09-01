<?php

class PHPExcel_Shared_XMLWriter
{
	const STORAGE_MEMORY = 1;
	const STORAGE_DISK = 2;

	/**
	 * Internal XMLWriter
	 *
	 * @var XMLWriter
	 */
	private $_xmlWriter;
	/**
	 * Temporary filename
	 *
	 * @var string
	 */
	private $_tempFileName = '';

	public function __construct($pTemporaryStorage = self::STORAGE_MEMORY, $pTemporaryStorageFolder = './')
	{
		$this->_xmlWriter = new XMLWriter();

		if ($pTemporaryStorage == self::STORAGE_MEMORY) {
			$this->_xmlWriter->openMemory();
		}
		else {
			$this->_tempFileName = @tempnam($pTemporaryStorageFolder, 'xml');

			if ($this->_xmlWriter->openUri($this->_tempFileName) === false) {
				$this->_xmlWriter->openMemory();
			}
		}

		$this->_xmlWriter->setIndent(true);
	}

	public function __destruct()
	{
		unset($this->_xmlWriter);

		if ($this->_tempFileName != '') {
			@unlink($this->_tempFileName);
		}
	}

	public function getData()
	{
		if ($this->_tempFileName == '') {
			return $this->_xmlWriter->outputMemory(true);
		}
		else {
			$this->_xmlWriter->flush();
			return file_get_contents($this->_tempFileName);
		}
	}

	public function __call($function, $args)
	{
		try {
			@call_user_func_array(array($this->_xmlWriter, $function), $args);
		}
		catch (Exception $ex) {
		}
	}

	public function writeRaw($text)
	{
		if (isset($this->_xmlWriter) && is_object($this->_xmlWriter) && method_exists($this->_xmlWriter, 'writeRaw')) {
			return $this->_xmlWriter->writeRaw(htmlspecialchars($text));
		}

		return $this->text($text);
	}
}

if (!defined('DATE_W3C')) {
	define('DATE_W3C', 'Y-m-d\\TH:i:sP');
}

?>
