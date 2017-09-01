<?php

class PHPExcel_Writer_Excel2007 implements PHPExcel_Writer_IWriter
{
	/**
	 * Pre-calculate formulas
	 *
	 * @var boolean
	 */
	private $_preCalculateFormulas = true;
	/**
	 * Office2003 compatibility
	 *
	 * @var boolean
	 */
	private $_office2003compatibility = false;
	/**
	 * Private writer parts
	 *
	 * @var PHPExcel_Writer_Excel2007_WriterPart[]
	 */
	private $_writerParts;
	/**
	 * Private PHPExcel
	 *
	 * @var PHPExcel
	 */
	private $_spreadSheet;
	/**
	 * Private string table
	 *
	 * @var string[]
	 */
	private $_stringTable;
	/**
	 * Private unique PHPExcel_Style_Conditional HashTable
	 *
	 * @var PHPExcel_HashTable
	 */
	private $_stylesConditionalHashTable;
	/**
	 * Private unique PHPExcel_Style_Fill HashTable
	 *
	 * @var PHPExcel_HashTable
	 */
	private $_fillHashTable;
	/**
	 * Private unique PHPExcel_Style_Font HashTable
	 *
	 * @var PHPExcel_HashTable
	 */
	private $_fontHashTable;
	/**
	 * Private unique PHPExcel_Style_Borders HashTable
	 *
	 * @var PHPExcel_HashTable
	 */
	private $_bordersHashTable;
	/**
	 * Private unique PHPExcel_Style_NumberFormat HashTable
	 *
	 * @var PHPExcel_HashTable
	 */
	private $_numFmtHashTable;
	/**
	 * Private unique PHPExcel_Worksheet_BaseDrawing HashTable
	 *
	 * @var PHPExcel_HashTable
	 */
	private $_drawingHashTable;
	/**
	 * Use disk caching where possible?
	 *
	 * @var boolean
	 */
	private $_useDiskCaching = false;
	/**
	 * Disk caching directory
	 *
	 * @var string
	 */
	private $_diskCachingDirectory;

	public function __construct(PHPExcel $pPHPExcel = NULL)
	{
		$this->setPHPExcel($pPHPExcel);
		$this->_diskCachingDirectory = './';
		$this->_writerParts['stringtable'] = new PHPExcel_Writer_Excel2007_StringTable();
		$this->_writerParts['contenttypes'] = new PHPExcel_Writer_Excel2007_ContentTypes();
		$this->_writerParts['docprops'] = new PHPExcel_Writer_Excel2007_DocProps();
		$this->_writerParts['rels'] = new PHPExcel_Writer_Excel2007_Rels();
		$this->_writerParts['theme'] = new PHPExcel_Writer_Excel2007_Theme();
		$this->_writerParts['style'] = new PHPExcel_Writer_Excel2007_Style();
		$this->_writerParts['workbook'] = new PHPExcel_Writer_Excel2007_Workbook();
		$this->_writerParts['worksheet'] = new PHPExcel_Writer_Excel2007_Worksheet();
		$this->_writerParts['drawing'] = new PHPExcel_Writer_Excel2007_Drawing();
		$this->_writerParts['comments'] = new PHPExcel_Writer_Excel2007_Comments();

		foreach ($this->_writerParts as $writer) {
			$writer->setParentWriter($this);
		}

		$this->_stringTable = array();
		$this->_stylesConditionalHashTable = new PHPExcel_HashTable();
		$this->_fillHashTable = new PHPExcel_HashTable();
		$this->_fontHashTable = new PHPExcel_HashTable();
		$this->_bordersHashTable = new PHPExcel_HashTable();
		$this->_numFmtHashTable = new PHPExcel_HashTable();
		$this->_drawingHashTable = new PHPExcel_HashTable();
	}

	public function getWriterPart($pPartName = '')
	{
		if (($pPartName != '') && isset($this->_writerParts[strtolower($pPartName)])) {
			return $this->_writerParts[strtolower($pPartName)];
		}
		else {
			return NULL;
		}
	}

	public function save($pFilename = NULL)
	{
		if (!is_null($this->_spreadSheet)) {
			$this->_spreadSheet->garbageCollect();
			$originalFilename = $pFilename;
			if ((strtolower($pFilename) == 'php://output') || (strtolower($pFilename) == 'php://stdout')) {
				$pFilename = @tempnam('./', 'phpxltmp');

				if ($pFilename == '') {
					$pFilename = $originalFilename;
				}
			}

			$saveDateReturnType = PHPExcel_Calculation_Functions::getReturnDateType();
			PHPExcel_Calculation_Functions::setReturnDateType(PHPExcel_Calculation_Functions::RETURNDATE_EXCEL);
			$this->_stringTable = array();

			for ($i = 0; $i < $this->_spreadSheet->getSheetCount(); ++$i) {
				$this->_stringTable = $this->getWriterPart('StringTable')->createStringTable($this->_spreadSheet->getSheet($i), $this->_stringTable);
			}

			$this->_stylesConditionalHashTable->addFromSource($this->getWriterPart('Style')->allConditionalStyles($this->_spreadSheet));
			$this->_fillHashTable->addFromSource($this->getWriterPart('Style')->allFills($this->_spreadSheet));
			$this->_fontHashTable->addFromSource($this->getWriterPart('Style')->allFonts($this->_spreadSheet));
			$this->_bordersHashTable->addFromSource($this->getWriterPart('Style')->allBorders($this->_spreadSheet));
			$this->_numFmtHashTable->addFromSource($this->getWriterPart('Style')->allNumberFormats($this->_spreadSheet));
			$this->_drawingHashTable->addFromSource($this->getWriterPart('Drawing')->allDrawings($this->_spreadSheet));
			$objZip = new ZipArchive();

			if ($objZip->open($pFilename, ZIPARCHIVE::OVERWRITE) !== true) {
				if ($objZip->open($pFilename, ZIPARCHIVE::CREATE) !== true) {
					throw new Exception('Could not open ' . $pFilename . ' for writing.');
				}
			}

			$objZip->addFromString('[Content_Types].xml', $this->getWriterPart('ContentTypes')->writeContentTypes($this->_spreadSheet));
			$objZip->addFromString('_rels/.rels', $this->getWriterPart('Rels')->writeRelationships($this->_spreadSheet));
			$objZip->addFromString('xl/_rels/workbook.xml.rels', $this->getWriterPart('Rels')->writeWorkbookRelationships($this->_spreadSheet));
			$objZip->addFromString('docProps/app.xml', $this->getWriterPart('DocProps')->writeDocPropsApp($this->_spreadSheet));
			$objZip->addFromString('docProps/core.xml', $this->getWriterPart('DocProps')->writeDocPropsCore($this->_spreadSheet));
			$objZip->addFromString('xl/theme/theme1.xml', $this->getWriterPart('Theme')->writeTheme($this->_spreadSheet));
			$objZip->addFromString('xl/sharedStrings.xml', $this->getWriterPart('StringTable')->writeStringTable($this->_stringTable));
			$objZip->addFromString('xl/styles.xml', $this->getWriterPart('Style')->writeStyles($this->_spreadSheet));
			$objZip->addFromString('xl/workbook.xml', $this->getWriterPart('Workbook')->writeWorkbook($this->_spreadSheet));

			for ($i = 0; $i < $this->_spreadSheet->getSheetCount(); ++$i) {
				$objZip->addFromString('xl/worksheets/sheet' . ($i + 1) . '.xml', $this->getWriterPart('Worksheet')->writeWorksheet($this->_spreadSheet->getSheet($i), $this->_stringTable));
			}

			for ($i = 0; $i < $this->_spreadSheet->getSheetCount(); ++$i) {
				$objZip->addFromString('xl/worksheets/_rels/sheet' . ($i + 1) . '.xml.rels', $this->getWriterPart('Rels')->writeWorksheetRelationships($this->_spreadSheet->getSheet($i), $i + 1));

				if (0 < $this->_spreadSheet->getSheet($i)->getDrawingCollection()->count()) {
					$objZip->addFromString('xl/drawings/_rels/drawing' . ($i + 1) . '.xml.rels', $this->getWriterPart('Rels')->writeDrawingRelationships($this->_spreadSheet->getSheet($i)));
					$objZip->addFromString('xl/drawings/drawing' . ($i + 1) . '.xml', $this->getWriterPart('Drawing')->writeDrawings($this->_spreadSheet->getSheet($i)));
				}

				if (0 < count($this->_spreadSheet->getSheet($i)->getComments())) {
					$objZip->addFromString('xl/drawings/vmlDrawing' . ($i + 1) . '.vml', $this->getWriterPart('Comments')->writeVMLComments($this->_spreadSheet->getSheet($i)));
					$objZip->addFromString('xl/comments' . ($i + 1) . '.xml', $this->getWriterPart('Comments')->writeComments($this->_spreadSheet->getSheet($i)));
				}

				if (0 < count($this->_spreadSheet->getSheet($i)->getHeaderFooter()->getImages())) {
					$objZip->addFromString('xl/drawings/vmlDrawingHF' . ($i + 1) . '.vml', $this->getWriterPart('Drawing')->writeVMLHeaderFooterImages($this->_spreadSheet->getSheet($i)));
					$objZip->addFromString('xl/drawings/_rels/vmlDrawingHF' . ($i + 1) . '.vml.rels', $this->getWriterPart('Rels')->writeHeaderFooterDrawingRelationships($this->_spreadSheet->getSheet($i)));

					foreach ($this->_spreadSheet->getSheet($i)->getHeaderFooter()->getImages() as $image) {
						$objZip->addFromString('xl/media/' . $image->getIndexedFilename(), file_get_contents($image->getPath()));
					}
				}
			}

			for ($i = 0; $i < $this->getDrawingHashTable()->count(); ++$i) {
				if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPExcel_Worksheet_Drawing) {
					$imageContents = NULL;
					$imagePath = $this->getDrawingHashTable()->getByIndex($i)->getPath();

					if (strpos($imagePath, 'zip://') !== false) {
						$imagePath = substr($imagePath, 6);
						$imagePathSplitted = explode('#', $imagePath);
						$imageZip = new ZipArchive();
						$imageZip->open($imagePathSplitted[0]);
						$imageContents = $imageZip->getFromName($imagePathSplitted[1]);
						$imageZip->close();
						unset($imageZip);
					}
					else {
						$imageContents = file_get_contents($imagePath);
					}

					$objZip->addFromString('xl/media/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
				}
				else if ($this->getDrawingHashTable()->getByIndex($i) instanceof PHPExcel_Worksheet_MemoryDrawing) {
					ob_start();
					call_user_func($this->getDrawingHashTable()->getByIndex($i)->getRenderingFunction(), $this->getDrawingHashTable()->getByIndex($i)->getImageResource());
					$imageContents = ob_get_contents();
					ob_end_clean();
					$objZip->addFromString('xl/media/' . str_replace(' ', '_', $this->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()), $imageContents);
				}
			}

			PHPExcel_Calculation_Functions::setReturnDateType($saveDateReturnType);

			if ($objZip->close() === false) {
				throw new Exception('Could not close zip file ' . $pFilename . '.');
			}

			if ($originalFilename != $pFilename) {
				if (copy($pFilename, $originalFilename) === false) {
					throw new Exception('Could not copy temporary zip file ' . $pFilename . ' to ' . $originalFilename . '.');
				}

				@unlink($pFilename);
			}
		}
		else {
			throw new Exception('PHPExcel object unassigned.');
		}
	}

	public function getPHPExcel()
	{
		if (!is_null($this->_spreadSheet)) {
			return $this->_spreadSheet;
		}
		else {
			throw new Exception('No PHPExcel assigned.');
		}
	}

	public function setPHPExcel(PHPExcel $pPHPExcel = NULL)
	{
		$this->_spreadSheet = $pPHPExcel;
		return $this;
	}

	public function getStringTable()
	{
		return $this->_stringTable;
	}

	public function getStylesConditionalHashTable()
	{
		return $this->_stylesConditionalHashTable;
	}

	public function getFillHashTable()
	{
		return $this->_fillHashTable;
	}

	public function getFontHashTable()
	{
		return $this->_fontHashTable;
	}

	public function getBordersHashTable()
	{
		return $this->_bordersHashTable;
	}

	public function getNumFmtHashTable()
	{
		return $this->_numFmtHashTable;
	}

	public function getDrawingHashTable()
	{
		return $this->_drawingHashTable;
	}

	public function getPreCalculateFormulas()
	{
		return $this->_preCalculateFormulas;
	}

	public function setPreCalculateFormulas($pValue = true)
	{
		$this->_preCalculateFormulas = $pValue;
	}

	public function getOffice2003Compatibility()
	{
		return $this->_office2003compatibility;
	}

	public function setOffice2003Compatibility($pValue = false)
	{
		$this->_office2003compatibility = $pValue;
		return $this;
	}

	public function getUseDiskCaching()
	{
		return $this->_useDiskCaching;
	}

	public function setUseDiskCaching($pValue = false, $pDirectory = NULL)
	{
		$this->_useDiskCaching = $pValue;

		if (!is_null($pDirectory)) {
			if (is_dir($pDirectory)) {
				$this->_diskCachingDirectory = $pDirectory;
			}
			else {
				throw new Exception('Directory does not exist: ' . $pDirectory);
			}
		}

		return $this;
	}

	public function getDiskCachingDirectory()
	{
		return $this->_diskCachingDirectory;
	}
}

?>
