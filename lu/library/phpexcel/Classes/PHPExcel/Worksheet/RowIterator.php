<?php

class PHPExcel_Worksheet_RowIterator extends CachingIterator
{
	/**
	 * PHPExcel_Worksheet to iterate
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_subject;
	/**
	 * Current iterator position
	 *
	 * @var int
	 */
	private $_position = 1;

	public function __construct(PHPExcel_Worksheet $subject = NULL)
	{
		$this->_subject = $subject;
	}

	public function __destruct()
	{
		unset($this->_subject);
	}

	public function rewind()
	{
		$this->_position = 1;
	}

	public function current()
	{
		return new PHPExcel_Worksheet_Row($this->_subject, $this->_position);
	}

	public function key()
	{
		return $this->_position;
	}

	public function next()
	{
		++$this->_position;
	}

	public function valid()
	{
		return $this->_position <= $this->_subject->getHighestRow();
	}
}

?>
