<?php

class PHPExcel_Writer_Excel5 implements PHPExcel_Writer_IWriter
{
	/**
	 * Pre-calculate formulas
	 *
	 * @var boolean
	 */
	private $_preCalculateFormulas;
	/**
	 * PHPExcel object
	 *
	 * @var PHPExcel
	 */
	private $_phpExcel;
	/**
	 * The BIFF version of the written Excel file, BIFF5 = 0x0500, BIFF8 = 0x0600
	 *
	 * @var integer
	 */
	private $_BIFF_version;
	/**
	 * Total number of shared strings in workbook
	 *
	 * @var int
	 */
	private $_str_total;
	/**
	 * Number of unique shared strings in workbook
	 *
	 * @var int
	 */
	private $_str_unique;
	/**
	 * Array of unique shared strings in workbook
	 *
	 * @var array
	 */
	private $_str_table;
	/**
	 * Color cache. Mapping between RGB value and color index.
	 *
	 * @var array
	 */
	private $_colors;
	/**
	 * Formula parser
	 *
	 * @var PHPExcel_Writer_Excel5_Parser
	 */
	private $_parser;

	public function __construct(PHPExcel $phpExcel)
	{
		$this->_preCalculateFormulas = true;
		$this->_phpExcel = $phpExcel;
		$this->_BIFF_version = 1536;
		$this->_str_total = 0;
		$this->_str_unique = 0;
		$this->_str_table = array();
		$this->_parser = new PHPExcel_Writer_Excel5_Parser($this->_BIFF_version);
	}

	public function save($pFilename = NULL)
	{
		$this->_phpExcel->garbageCollect();
		$saveDateReturnType = PHPExcel_Calculation_Functions::getReturnDateType();
		PHPExcel_Calculation_Functions::setReturnDateType(PHPExcel_Calculation_Functions::RETURNDATE_EXCEL);
		$this->_colors = array();
		$this->_writerWorkbook = new PHPExcel_Writer_Excel5_Workbook($this->_phpExcel, $this->_BIFF_version, $this->_str_total, $this->_str_unique, $this->_str_table, $this->_colors, $this->_parser);
		$cellXfCollection = $this->_phpExcel->getCellXfCollection();

		for ($i = 0; $i < 15; ++$i) {
			$this->_writerWorkbook->addXfWriter($cellXfCollection[0], true);
		}

		foreach ($this->_phpExcel->getCellXfCollection() as $style) {
			$this->_writerWorkbook->addXfWriter($style, false);
		}

		$workbookStreamName = ($this->_BIFF_version == 1536 ? 'Workbook' : 'Book');
		$OLE = new PHPExcel_Shared_OLE_PPS_File(PHPExcel_Shared_OLE::Asc2Ucs($workbookStreamName));
		$countSheets = $this->_phpExcel->getSheetCount();
		$worksheetSizes = array();

		for ($i = 0; $i < $countSheets; ++$i) {
			$this->_writerWorksheets[$i] = new PHPExcel_Writer_Excel5_Worksheet($this->_BIFF_version, $this->_str_total, $this->_str_unique, $this->_str_table, $this->_colors, $this->_parser, $this->_preCalculateFormulas, $this->_phpExcel->getSheet($i));
			$this->_writerWorksheets[$i]->close();
			$worksheetSizes[] = $this->_writerWorksheets[$i]->_datasize;
		}

		$OLE->append($this->_writerWorkbook->writeWorkbook($worksheetSizes));

		for ($i = 0; $i < $countSheets; ++$i) {
			$OLE->append($this->_writerWorksheets[$i]->getData());
		}

		$root = new PHPExcel_Shared_OLE_PPS_Root(time(), time(), array($OLE));
		$res = $root->save($pFilename);
		PHPExcel_Calculation_Functions::setReturnDateType($saveDateReturnType);
	}

	public function setTempDir($pValue = '')
	{
		return $this;
	}

	public function getPreCalculateFormulas()
	{
		return $this->_preCalculateFormulas;
	}

	public function setPreCalculateFormulas($pValue = true)
	{
		$this->_preCalculateFormulas = $pValue;
	}
}

?>
