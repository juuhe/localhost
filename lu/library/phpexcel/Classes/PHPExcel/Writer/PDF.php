<?php

$k_path_url = dirname(__FILE__) . '/PDF';
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/PDF/tcpdf.php';
class PHPExcel_Writer_PDF extends PHPExcel_Writer_HTML implements PHPExcel_Writer_IWriter
{
	/**
	 * Temporary storage directory
	 *
	 * @var string
	 */
	private $_tempDir = '';
	/**
	 * Font
	 *
	 * @var string
	 */
	private $_font = 'freesans';
	/**
	 * Orientation (Over-ride)
	 *
	 * @var string
	 */
	private $_orientation;
	/**
	 * Paper size (Over-ride)
	 *
	 * @var int
	 */
	private $_paperSize;
	/**
	 * Paper Sizes xRef List
	 *
	 * @var array
	 */
	static private $_paperSizes = array(
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER                        => 'LETTER',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_SMALL                  => 'LETTER',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_TABLOID                       => array(792, 1224),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEDGER                        => array(1224, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL                         => 'LEGAL',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_STATEMENT                     => array(396, 612),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_EXECUTIVE                     => 'EXECUTIVE',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3                            => 'A3',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4                            => 'A4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_SMALL                      => 'A4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5                            => 'A5',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_B4                            => 'B4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5                            => 'B5',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO                         => 'FOLIO',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_QUARTO                        => array(609.45000000000005, 779.52999999999997),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_1                    => array(720, 1008),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_2                    => array(792, 1224),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_NOTE                          => 'LETTER',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO9_ENVELOPE                  => array(279, 639),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO10_ENVELOPE                 => array(297, 684),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO11_ENVELOPE                 => array(324, 747),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO12_ENVELOPE                 => array(342, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO14_ENVELOPE                 => array(360, 828),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_C                             => array(1224, 1584),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_D                             => array(1584, 2448),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_E                             => array(2448, 3168),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_DL_ENVELOPE                   => array(311.81, 623.62),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_C5_ENVELOPE                   => 'C5',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_C3_ENVELOPE                   => 'C3',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_C4_ENVELOPE                   => 'C4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_C6_ENVELOPE                   => 'C6',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_C65_ENVELOPE                  => array(323.14999999999998, 649.13),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_B4_ENVELOPE                   => 'B4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5_ENVELOPE                   => 'B5',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_B6_ENVELOPE                   => array(498.89999999999998, 354.32999999999998),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_ITALY_ENVELOPE                => array(311.81, 651.97000000000003),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_MONARCH_ENVELOPE              => array(279, 540),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_6_3_4_ENVELOPE                => array(261, 468),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_US_STANDARD_FANFOLD           => array(1071, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_GERMAN_STANDARD_FANFOLD       => array(612, 864),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_GERMAN_LEGAL_FANFOLD          => 'FOLIO',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B4                        => 'B4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_JAPANESE_DOUBLE_POSTCARD      => array(566.92999999999995, 419.52999999999997),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_1              => array(648, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_2              => array(720, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_3              => array(1080, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_INVITE_ENVELOPE               => array(623.62, 623.62),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_EXTRA_PAPER            => array(667.79999999999995, 864),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL_EXTRA_PAPER             => array(667.79999999999995, 1080),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_TABLOID_EXTRA_PAPER           => array(841.67999999999995, 1296),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_EXTRA_PAPER                => array(668.98000000000002, 912.75999999999999),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_TRANSVERSE_PAPER       => array(595.79999999999995, 792),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER           => 'A4',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_EXTRA_TRANSVERSE_PAPER => array(667.79999999999995, 864),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_SUPERA_SUPERA_A4_PAPER        => array(643.46000000000004, 1009.13),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_SUPERB_SUPERB_A3_PAPER        => array(864.57000000000005, 1380.47),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_PLUS_PAPER             => array(612, 913.67999999999995),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_PLUS_PAPER                 => array(595.27999999999997, 935.42999999999995),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5_TRANSVERSE_PAPER           => 'A5',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_JIS_B5_TRANSVERSE_PAPER       => array(515.90999999999997, 728.5),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_PAPER                => array(912.75999999999999, 1261.4200000000001),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5_EXTRA_PAPER                => array(493.23000000000002, 666.13999999999999),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B5_EXTRA_PAPER            => array(569.75999999999999, 782.36000000000001),
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A2_PAPER                      => 'A2',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_TRANSVERSE_PAPER           => 'A3',
		PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER     => array(912.75999999999999, 1261.4200000000001)
		);

	public function __construct(PHPExcel $phpExcel)
	{
		parent::__construct($phpExcel);
		$this->setUseInlineCss(true);
		$this->_tempDir = PHPExcel_Shared_File::sys_get_temp_dir();
	}

	public function setFont($fontName)
	{
		$this->_font = $fontName;
		return $this;
	}

	public function getPaperSize()
	{
		return $this->_paperSize;
	}

	public function setPaperSize($pValue = PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER)
	{
		$this->_paperSize = $pValue;
		return $this;
	}

	public function getOrientation()
	{
		return $this->_orientation;
	}

	public function setOrientation($pValue = PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT)
	{
		$this->_orientation = $pValue;
		return $this;
	}

	public function save($pFilename = NULL)
	{
		$this->_phpExcel->garbageCollect();
		$saveArrayReturnType = PHPExcel_Calculation::getArrayReturnType();
		PHPExcel_Calculation::setArrayReturnType(PHPExcel_Calculation::RETURN_ARRAY_AS_VALUE);
		$fileHandle = fopen($pFilename, 'w');

		if ($fileHandle === false) {
			throw new Exception('Could not open file ' . $pFilename . ' for writing.');
		}

		$this->_isPdf = true;
		$this->buildCSS(true);
		$html = '';
		$html .= $this->generateSheetData();
		$paperSize = 'LETTER';

		if (is_null($this->getSheetIndex())) {
			$orientation = ($this->_phpExcel->getSheet(0)->getPageSetup()->getOrientation() == PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE ? 'L' : 'P');
			$printPaperSize = $this->_phpExcel->getSheet(0)->getPageSetup()->getPaperSize();
		}
		else {
			$orientation = ($this->_phpExcel->getSheet($this->getSheetIndex())->getPageSetup()->getOrientation() == PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE ? 'L' : 'P');
			$printPaperSize = $this->_phpExcel->getSheet($this->getSheetIndex())->getPageSetup()->getPaperSize();
		}

		if (!is_null($this->_orientation)) {
			$orientation = ($this->_orientation == PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE ? 'L' : 'P');
		}

		if (!is_null($this->_paperSize)) {
			$printPaperSize = $this->_paperSize;
		}

		if (isset(self::$_paperSizes[$printPaperSize])) {
			$paperSize = self::$_paperSizes[$printPaperSize];
		}

		$pdf = new TCPDF($orientation, 'pt', $paperSize);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AddPage();
		$pdf->SetFont($this->_font);
		$pdf->writeHTML($html);
		$pdf->SetTitle($this->_phpExcel->getProperties()->getTitle());
		$pdf->SetAuthor($this->_phpExcel->getProperties()->getCreator());
		$pdf->SetSubject($this->_phpExcel->getProperties()->getSubject());
		$pdf->SetKeywords($this->_phpExcel->getProperties()->getKeywords());
		$pdf->SetCreator($this->_phpExcel->getProperties()->getCreator());
		fwrite($fileHandle, $pdf->output($pFilename, 'S'));
		fclose($fileHandle);
		PHPExcel_Calculation::setArrayReturnType($saveArrayReturnType);
	}

	public function getTempDir()
	{
		return $this->_tempDir;
	}

	public function setTempDir($pValue = '')
	{
		if (is_dir($pValue)) {
			$this->_tempDir = $pValue;
		}
		else {
			throw new Exception('Directory does not exist: ' . $pValue);
		}

		return $this;
	}
}

?>
