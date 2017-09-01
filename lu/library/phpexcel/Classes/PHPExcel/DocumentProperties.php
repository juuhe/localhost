<?php

class PHPExcel_DocumentProperties
{
	/**
	 * Creator
	 *
	 * @var string
	 */
	private $_creator;
	/**
	 * LastModifiedBy
	 *
	 * @var string
	 */
	private $_lastModifiedBy;
	/**
	 * Created
	 *
	 * @var datetime
	 */
	private $_created;
	/**
	 * Modified
	 *
	 * @var datetime
	 */
	private $_modified;
	/**
	 * Title
	 *
	 * @var string
	 */
	private $_title;
	/**
	 * Description
	 *
	 * @var string
	 */
	private $_description;
	/**
	 * Subject
	 *
	 * @var string
	 */
	private $_subject;
	/**
	 * Keywords
	 *
	 * @var string
	 */
	private $_keywords;
	/**
	 * Category
	 *
	 * @var string
	 */
	private $_category;
	/**
	 * Company
	 * 
	 * @var string
	 */
	private $_company;

	public function __construct()
	{
		$this->_creator = 'Unknown Creator';
		$this->_lastModifiedBy = $this->_creator;
		$this->_created = time();
		$this->_modified = time();
		$this->_title = 'Untitled Spreadsheet';
		$this->_subject = '';
		$this->_description = '';
		$this->_keywords = '';
		$this->_category = '';
		$this->_company = 'Microsoft Corporation';
	}

	public function getCreator()
	{
		return $this->_creator;
	}

	public function setCreator($pValue = '')
	{
		$this->_creator = $pValue;
		return $this;
	}

	public function getLastModifiedBy()
	{
		return $this->_lastModifiedBy;
	}

	public function setLastModifiedBy($pValue = '')
	{
		$this->_lastModifiedBy = $pValue;
		return $this;
	}

	public function getCreated()
	{
		return $this->_created;
	}

	public function setCreated($pValue = NULL)
	{
		if (is_null($pValue)) {
			$pValue = time();
		}

		$this->_created = $pValue;
		return $this;
	}

	public function getModified()
	{
		return $this->_modified;
	}

	public function setModified($pValue = NULL)
	{
		if (is_null($pValue)) {
			$pValue = time();
		}

		$this->_modified = $pValue;
		return $this;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($pValue = '')
	{
		$this->_title = $pValue;
		return $this;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function setDescription($pValue = '')
	{
		$this->_description = $pValue;
		return $this;
	}

	public function getSubject()
	{
		return $this->_subject;
	}

	public function setSubject($pValue = '')
	{
		$this->_subject = $pValue;
		return $this;
	}

	public function getKeywords()
	{
		return $this->_keywords;
	}

	public function setKeywords($pValue = '')
	{
		$this->_keywords = $pValue;
		return $this;
	}

	public function getCategory()
	{
		return $this->_category;
	}

	public function setCategory($pValue = '')
	{
		$this->_category = $pValue;
		return $this;
	}

	public function getCompany()
	{
		return $this->_company;
	}

	public function setCompany($pValue = '')
	{
		$this->_company = $pValue;
		return $this;
	}

	public function __clone()
	{
		$vars = get_object_vars($this);

		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
			else {
				$this->$key = $value;
			}
		}
	}
}


?>
